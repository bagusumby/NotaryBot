<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmployeeSchedule;
use Illuminate\Http\Request;

class EmployeeScheduleController extends Controller
{
    /**
     * Display a listing of employee schedules.
     */
    public function index()
    {
        $users = User::with('schedules')->get();
        return view('employee_schedules.index', compact('users'));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create()
    {
        $users = User::all();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        return view('employee_schedules.create', compact('users', 'days'));
    }

    /**
     * Store a newly created schedule in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'days' => 'required|array|min:1',
            'days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_active' => 'boolean',
        ], [
            'days.required' => 'Pilih minimal 1 hari.',
            'days.min' => 'Pilih minimal 1 hari.',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Create schedule for each selected day
        $createdCount = 0;
        foreach ($validated['days'] as $day) {
            // Check if schedule already exists for this user and day
            $existing = EmployeeSchedule::where('user_id', $validated['user_id'])
                ->where('day_of_week', $day)
                ->first();

            if (!$existing) {
                EmployeeSchedule::create([
                    'user_id' => $validated['user_id'],
                    'day_of_week' => $day,
                    'start_time' => $validated['start_time'],
                    'end_time' => $validated['end_time'],
                    'is_active' => $validated['is_active'],
                ]);
                $createdCount++;
            }
        }

        $message = $createdCount > 0
            ? "Berhasil menambahkan $createdCount jadwal!"
            : "Jadwal untuk hari yang dipilih sudah ada.";

        return redirect()->route('employee-schedules.index')->with('success', $message);
    }

    /**
     * Show the form for editing the specified schedule.
     */
    public function edit(EmployeeSchedule $employeeSchedule)
    {
        $users = User::all();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        return view('employee_schedules.edit', compact('employeeSchedule', 'users', 'days'));
    }

    /**
     * Update the specified schedule in storage.
     */
    public function update(Request $request, EmployeeSchedule $employeeSchedule)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $employeeSchedule->update($validated);

        return redirect()->route('employee-schedules.index')->with('success', 'Jadwal berhasil diperbarui!');
    }

    /**
     * Remove the specified schedule from storage.
     */
    public function destroy(EmployeeSchedule $employeeSchedule)
    {
        $employeeSchedule->delete();
        return redirect()->route('employee-schedules.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}
