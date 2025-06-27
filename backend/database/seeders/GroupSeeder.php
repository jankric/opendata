<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            [
                'name' => 'Data Prioritas',
                'description' => 'Dataset yang menjadi prioritas utama pemerintah daerah',
                'is_active' => true,
            ],
            [
                'name' => 'Data Real-time',
                'description' => 'Dataset yang diperbarui secara real-time atau berkala',
                'is_active' => true,
            ],
            [
                'name' => 'Data Geospasial',
                'description' => 'Dataset yang mengandung informasi geografis dan pemetaan',
                'is_active' => true,
            ],
            [
                'name' => 'Data Statistik',
                'description' => 'Dataset berisi data statistik dan analisis',
                'is_active' => true,
            ],
            [
                'name' => 'Data Keuangan',
                'description' => 'Dataset terkait anggaran dan keuangan daerah',
                'is_active' => true,
            ],
            [
                'name' => 'Data Pelayanan Publik',
                'description' => 'Dataset terkait layanan publik kepada masyarakat',
                'is_active' => true,
            ],
        ];

        foreach ($groups as $group) {
            Group::create($group);
        }
    }
}