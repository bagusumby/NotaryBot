<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Setting;
use App\Models\EmployeeSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Filter based on role
        if ($user->isStaff()) {
            $appointments = Appointment::with('employee')
                ->where('employee_id', $user->id)
                ->latest()
                ->paginate(10);
        } else {
            // Admin and superadmin see all
            $appointments = Appointment::with('employee')->latest()->paginate(10);
        }
        
        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = User::whereIn('role', ['staff', 'admin'])->get();
        return view('appointments.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string',
            'booking_date' => 'required|date',
            'booking_time' => 'required|string',
            'status' => 'required|in:Pending,Confirmed,Cancelled,Completed'
        ]);

        // Auto-assign employee if not provided or empty
        if (empty($validated['employee_id'])) {
            $date = $validated['booking_date'];
            $time = $validated['booking_time'];
            $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
            
            // Get available employees for this day
            $availableUserIds = EmployeeSchedule::where('day_of_week', $dayOfWeek)
                ->where('is_active', true)
                ->pluck('user_id')
                ->toArray();
            
            if (empty($availableUserIds)) {
                if ($request->ajax()) {
                    return response()->json(['errors' => ['booking_time' => ['No employees available for this day.']]], 422);
                }
                return back()->withErrors(['booking_time' => 'No employees available for this day.'])->withInput();
            }
            
            // Get already booked employees for this slot
            $bookedUserIds = Appointment::where('booking_date', $date)
                ->where('booking_time', $time)
                ->whereIn('employee_id', $availableUserIds)
                ->whereNotIn('status', ['Cancelled'])
                ->pluck('employee_id')
                ->toArray();
            
            // Find free employees
            $freeUserIds = array_diff($availableUserIds, $bookedUserIds);
            
            if (empty($freeUserIds)) {
                if ($request->ajax()) {
                    return response()->json(['errors' => ['booking_time' => ['This time slot is fully booked. Please select another time.']]], 422);
                }
                return back()->withErrors(['booking_time' => 'This time slot is fully booked. Please select another time.'])->withInput();
            }
            
            // Randomly assign to one of the free employees
            $validated['employee_id'] = $freeUserIds[array_rand($freeUserIds)];
        }

        $appointment = Appointment::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment created successfully.',
                'appointment' => $appointment
            ]);
        }

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        // Check permission
        if (auth()->user()->isStaff() && $appointment->employee_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        // Check permission
        if (auth()->user()->isStaff() && $appointment->employee_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $employees = User::whereIn('role', ['staff', 'admin'])->get();
        return view('appointments.edit', compact('appointment', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Check permission
        if (auth()->user()->isStaff() && $appointment->employee_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string',
            'booking_date' => 'required|date',
            'booking_time' => 'required|string',
            'status' => 'required|in:Pending,Confirmed,Cancelled,Completed'
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        // Check permission
        if (auth()->user()->isStaff() && $appointment->employee_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Public booking page
     */
    public function booking()
    {
        return view('booking');
    }

    /**
     * Get available time slots based on settings and bookings
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'employee_id' => 'nullable|exists:users,id'
        ]);

        $date = $request->input('date');
        $employeeId = $request->input('employee_id');
        
        $carbonDate = Carbon::parse($date);
        $dayOfWeek = strtolower($carbonDate->format('l'));
        
        // Get system settings
        $setting = Setting::first();
        
        if (!$setting) {
            return response()->json([
                'slots' => [],
                'message' => 'System settings not configured'
            ]);
        }
        
        // Check if day is operational
        if (!in_array($dayOfWeek, $setting->operational_days)) {
            return response()->json([
                'slots' => [],
                'message' => 'Not an operational day'
            ]);
        }
        
        // Get available employees for this day
        $availableSchedules = EmployeeSchedule::where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->when($employeeId, function($query) use ($employeeId) {
                return $query->where('user_id', $employeeId);
            })
            ->with('user')
            ->get();
        
        if ($availableSchedules->isEmpty()) {
            return response()->json([
                'slots' => [],
                'message' => 'No employees available on this day'
            ]);
        }
        
        // Generate time slots
        $slots = [];
        $startTime = Carbon::parse($setting->start_time);
        $endTime = Carbon::parse($setting->end_time);
        $duration = $setting->session_duration;
        
        while ($startTime->copy()->addMinutes($duration)->lte($endTime)) {
            $slotStart = $startTime->format('H:i');
            $slotEnd = $startTime->copy()->addMinutes($duration)->format('H:i');
            $slotTime = $slotStart . ' - ' . $slotEnd;
            
            // Count available employees for this slot
            $availableCount = 0;
            $availableUserIds = [];
            
            foreach ($availableSchedules as $schedule) {
                $empStart = Carbon::parse($schedule->start_time);
                $empEnd = Carbon::parse($schedule->end_time);
                $slotStartCarbon = Carbon::parse($slotStart);
                $slotEndCarbon = Carbon::parse($slotEnd);
                
                // Check if slot is within employee's working hours
                if ($slotStartCarbon->gte($empStart) && $slotEndCarbon->lte($empEnd)) {
                    $availableCount++;
                    $availableUserIds[] = $schedule->user_id;
                }
            }
            
            // Count existing bookings for this slot
            $bookedCount = Appointment::where('booking_date', $date)
                ->where('booking_time', $slotTime)
                ->whereIn('employee_id', $availableUserIds)
                ->whereNotIn('status', ['Cancelled'])
                ->count();
            
            // Only add slot if there's availability
            $availableSlots = $availableCount - $bookedCount;
            if ($availableSlots > 0) {
                $slots[] = [
                    'time' => $slotTime,
                    'available_slots' => $availableSlots,
                    'user_ids' => $availableUserIds
                ];
            }
            
            $startTime->addMinutes($duration);
        }
        
        return response()->json(['slots' => $slots]);
    }
}
