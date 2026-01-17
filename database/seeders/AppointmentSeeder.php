<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get staff user
        $staff = User::where('email', 'staff@notarybot.com')->first();
        $admin = User::where('email', 'admin@notarybot.com')->first();

        // Create sample appointments for testing
        $appointments = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '081234567890',
                'notes' => 'Document notarization for property transfer',
                'booking_date' => Carbon::today()->addDays(2)->format('Y-m-d'),
                'booking_time' => '09:00 AM - 10:00 AM',
                'status' => 'Confirmed',
                'employee_id' => $staff->id,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '081234567891',
                'notes' => 'Marriage certificate notarization',
                'booking_date' => Carbon::today()->addDays(3)->format('Y-m-d'),
                'booking_time' => '10:30 AM - 11:30 AM',
                'status' => 'Pending',
                'employee_id' => $staff->id,
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael@example.com',
                'phone' => '081234567892',
                'notes' => 'Power of attorney document',
                'booking_date' => Carbon::today()->addDays(1)->format('Y-m-d'),
                'booking_time' => '14:00 - 15:00',
                'status' => 'Confirmed',
                'employee_id' => $admin->id,
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@example.com',
                'phone' => '081234567893',
                'notes' => 'Business contract notarization',
                'booking_date' => Carbon::today()->addDays(5)->format('Y-m-d'),
                'booking_time' => '13:00 PM - 14:00 PM',
                'status' => 'Pending',
                'employee_id' => $admin->id,
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david@example.com',
                'phone' => '081234567894',
                'notes' => 'Affidavit notarization',
                'booking_date' => Carbon::yesterday()->format('Y-m-d'),
                'booking_time' => '11:00 AM - 12:00 PM',
                'status' => 'Completed',
                'employee_id' => $staff->id,
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily@example.com',
                'phone' => '081234567895',
                'notes' => 'Will and testament signing',
                'booking_date' => Carbon::today()->format('Y-m-d'),
                'booking_time' => '15:30 - 16:30',
                'status' => 'Confirmed',
                'employee_id' => $staff->id,
            ],
            [
                'name' => 'Robert Taylor',
                'email' => 'robert@example.com',
                'phone' => '081234567896',
                'notes' => 'Land deed notarization - Client cancelled due to personal reasons',
                'booking_date' => Carbon::today()->addDays(4)->format('Y-m-d'),
                'booking_time' => '09:30 AM - 10:30 AM',
                'status' => 'Cancelled',
                'employee_id' => $admin->id,
            ],
        ];

        foreach ($appointments as $appointment) {
            Appointment::create($appointment);
        }
    }
}
