<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Show the form for editing the settings.
     */
    public function edit()
    {
        // Ambil setting pertama, jika tidak ada, buat default
        $setting = Setting::first() ?? new Setting([
            'operational_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'start_time' => '09:00',
            'end_time' => '17:00',
            'session_duration' => 60,
        ]);

        return view('settings.edit', compact('setting'));
    }

    /**
     * Update the settings in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'operational_days' => 'required|array',
            'operational_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'session_duration' => 'required|integer|min:15|max:240',
        ]);

        // Add seconds to time format for database storage
        $validated['start_time'] = $validated['start_time'] . ':00';
        $validated['end_time'] = $validated['end_time'] . ':00';

        // Update atau create setting pertama
        $setting = Setting::first();
        if ($setting) {
            $setting->update($validated);
        } else {
            Setting::create($validated);
        }

        return redirect()->route('settings.edit')->with('success', 'Settings updated successfully!');
    }
}
