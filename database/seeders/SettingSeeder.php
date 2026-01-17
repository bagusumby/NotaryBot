<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::firstOrCreate(
            ['id' => 1],
            [
                'operational_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'session_duration' => 60, // 1 hour
            ]
        );
    }
}
