<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QuickResponse;

class QuickResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quickResponses = [
            // Welcome chips
            [
                'title' => 'Layanan Notaris',
                'value' => 'Apa saja layanan notaris yang tersedia?',
                'type' => 'welcome',
                'order' => 1,
                'is_active' => true
            ],
            [
                'title' => 'Buat Janji',
                'value' => 'Bagaimana cara membuat janji temu?',
                'type' => 'welcome',
                'order' => 2,
                'is_active' => true
            ],
            [
                'title' => 'Dokumen yang Diperlukan',
                'value' => 'Dokumen apa saja yang harus saya siapkan?',
                'type' => 'welcome',
                'order' => 3,
                'is_active' => true
            ],
            [
                'title' => 'Biaya Layanan',
                'value' => 'Berapa biaya untuk layanan notaris?',
                'type' => 'welcome',
                'order' => 4,
                'is_active' => true
            ],
            
            // General chips (after bot response)
            [
                'title' => 'Info Lainnya',
                'value' => 'Ada informasi lain yang ingin saya tanyakan',
                'type' => 'general',
                'order' => 1,
                'is_active' => true
            ],
            [
                'title' => 'Lokasi Kantor',
                'value' => 'Dimana lokasi kantor notaris?',
                'type' => 'general',
                'order' => 2,
                'is_active' => true
            ],
            [
                'title' => 'Jam Operasional',
                'value' => 'Kapan jam operasional kantor?',
                'type' => 'general',
                'order' => 3,
                'is_active' => true
            ],
            [
                'title' => 'Hubungi Kami',
                'value' => 'Bagaimana cara menghubungi kantor notaris?',
                'type' => 'general',
                'order' => 4,
                'is_active' => true
            ]
        ];

        foreach ($quickResponses as $qr) {
            QuickResponse::create($qr);
        }
    }
}
