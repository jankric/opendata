<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kependudukan',
                'description' => 'Data demografis dan statistik penduduk',
                'icon' => 'Users',
                'color' => 'bg-blue-100 text-blue-700',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Ekonomi',
                'description' => 'PDRB, inflasi, dan indikator ekonomi',
                'icon' => 'TrendingUp',
                'color' => 'bg-green-100 text-green-700',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Kesehatan',
                'description' => 'Fasilitas dan layanan kesehatan',
                'icon' => 'Heart',
                'color' => 'bg-red-100 text-red-700',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Pendidikan',
                'description' => 'Sekolah, siswa, dan sarana pendidikan',
                'icon' => 'GraduationCap',
                'color' => 'bg-purple-100 text-purple-700',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Infrastruktur',
                'description' => 'Jalan, jembatan, dan bangunan publik',
                'icon' => 'Building2',
                'color' => 'bg-orange-100 text-orange-700',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Lingkungan',
                'description' => 'Kualitas udara, air, dan konservasi',
                'icon' => 'TreePine',
                'color' => 'bg-emerald-100 text-emerald-700',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Ketenagakerjaan',
                'description' => 'Angkatan kerja dan lapangan kerja',
                'icon' => 'Briefcase',
                'color' => 'bg-indigo-100 text-indigo-700',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Keamanan',
                'description' => 'Ketertiban dan keamanan publik',
                'icon' => 'Shield',
                'color' => 'bg-slate-100 text-slate-700',
                'sort_order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}