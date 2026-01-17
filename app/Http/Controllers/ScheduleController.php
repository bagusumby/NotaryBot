<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Start with base query
        $query = Appointment::query()->with('employee');

        // Filter based on role - staff can only see their own appointments
        if ($user->isStaff()) {
            $query->where('employee_id', $user->id);
        }
        // Admin and superadmin can see all appointments

        // Get all appointments and format for FullCalendar
        $appointments = $query->get()->map(function ($appointment) {
            try {
                if (!str_contains($appointment->booking_time ?? '', '-')) {
                    throw new \Exception("Invalid time format");
                }

                // Parse booking date
                $bookingDate = Carbon::parse($appointment->booking_date);

                // Parse start and end times
                [$startTime, $endTime] = array_map('trim', explode('-', $appointment->booking_time));

                // Try to parse with different formats
                try {
                    // Try format with AM/PM first (09:00 AM)
                    $startDateTime = Carbon::createFromFormat('h:i A', $startTime)
                        ->setDate($bookingDate->year, $bookingDate->month, $bookingDate->day);
                    $endDateTime = Carbon::createFromFormat('h:i A', $endTime)
                        ->setDate($bookingDate->year, $bookingDate->month, $bookingDate->day);
                } catch (\Exception $e) {
                    // If failed, try 24-hour format (09:00)
                    $startDateTime = Carbon::createFromFormat('H:i', $startTime)
                        ->setDate($bookingDate->year, $bookingDate->month, $bookingDate->day);
                    $endDateTime = Carbon::createFromFormat('H:i', $endTime)
                        ->setDate($bookingDate->year, $bookingDate->month, $bookingDate->day);
                }

                // Handle overnight appointments (if end time is next day)
                if ($endDateTime->lt($startDateTime)) {
                    $endDateTime->addDay();
                }

                return [
                    'id' => $appointment->id,
                    'title' => $appointment->name,
                    'start' => $startDateTime->toIso8601String(),
                    'end' => $endDateTime->toIso8601String(),
                    'description' => $appointment->notes,
                    'email' => $appointment->email,
                    'phone' => $appointment->phone,
                    'status' => $appointment->status,
                    'color' => $this->getStatusColor($appointment->status),
                    'name' => $appointment->name,
                    'notes' => $appointment->notes,
                    'employee' => $appointment->employee ? $appointment->employee->name : 'Not assigned',
                ];
            } catch (\Exception $e) {
                \Log::error("Format error for appointment {$appointment->id}: {$e->getMessage()}");
                return null;
            }
        })->filter()->values()->toArray();

        return view('schedule-management', compact('appointments'));
    }

    // Helper function to get color based on status
    private function getStatusColor($status)
    {
        $colors = [
            'Pending' => '#f39c12',
            'Confirmed' => '#2ecc71',
            'Cancelled' => '#ff0000',
            'Completed' => '#008000',
        ];

        return $colors[$status] ?? '#7f8c8d';
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'status' => 'required|in:Pending,Confirmed,Cancelled,Completed'
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);
        $appointment->update(['status' => $request->status]);

        return redirect()->route('schedule-management')
            ->with('success', 'Appointment status updated successfully!');
    }
}
