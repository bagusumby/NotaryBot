<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmployeeSchedule;
use App\Models\User;

class EmployeeScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get staff and admin users
        $employees = User::whereIn('role', ['staff', 'admin'])->get();
        
        if ($employees->isEmpty()) {
            $this->command->warn('No staff or admin users found. Please create users first.');
            return;
        }
        
        // Days of week
        $weekDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        
        foreach ($employees as $employee) {
            foreach ($weekDays as $day) {
                EmployeeSchedule::create([
                    'user_id' => $employee->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00',
                    'end_time' => '17:00',
                    'is_active' => true
                ]);
            }
            
            $this->command->info("Created schedules for {$employee->name} (Mon-Fri, 09:00-17:00)");
        }
    }
}
