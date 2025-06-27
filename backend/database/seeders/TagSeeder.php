<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'penduduk', 'description' => 'Data terkait penduduk', 'color' => '#3B82F6'],
            ['name' => 'demografi', 'description' => 'Data demografis', 'color' => '#1E40AF'],
            ['name' => 'statistik', 'description' => 'Data statistik', 'color' => '#1D4ED8'],
            ['name' => 'ekonomi', 'description' => 'Data ekonomi', 'color' => '#10B981'],
            ['name' => 'pdrb', 'description' => 'Produk Domestik Regional Bruto', 'color' => '#059669'],
            ['name' => 'inflasi', 'description' => 'Data inflasi', 'color' => '#047857'],
            ['name' => 'kesehatan', 'description' => 'Data kesehatan', 'color' => '#EF4444'],
            ['name' => 'rumah-sakit', 'description' => 'Data rumah sakit', 'color' => '#DC2626'],
            ['name' => 'puskesmas', 'description' => 'Data puskesmas', 'color' => '#B91C1C'],
            ['name' => 'dokter', 'description' => 'Data tenaga medis', 'color' => '#991B1B'],
            ['name' => 'pendidikan', 'description' => 'Data pendidikan', 'color' => '#8B5CF6'],
            ['name' => 'sekolah', 'description' => 'Data sekolah', 'color' => '#7C3AED'],
            ['name' => 'siswa', 'description' => 'Data siswa', 'color' => '#6D28D9'],
            ['name' => 'guru', 'description' => 'Data guru', 'color' => '#5B21B6'],
            ['name' => 'infrastruktur', 'description' => 'Data infrastruktur', 'color' => '#F59E0B'],
            ['name' => 'jalan', 'description' => 'Data jalan', 'color' => '#D97706'],
            ['name' => 'jembatan', 'description' => 'Data jembatan', 'color' => '#B45309'],
            ['name' => 'transportasi', 'description' => 'Data transportasi', 'color' => '#92400E'],
            ['name' => 'lingkungan', 'description' => 'Data lingkungan', 'color' => '#10B981'],
            ['name' => 'air', 'description' => 'Data kualitas air', 'color' => '#059669'],
            ['name' => 'udara', 'description' => 'Data kualitas udara', 'color' => '#047857'],
            ['name' => 'ketenagakerjaan', 'description' => 'Data ketenagakerjaan', 'color' => '#6366F1'],
            ['name' => 'tenaga-kerja', 'description' => 'Data tenaga kerja', 'color' => '#4F46E5'],
            ['name' => 'pengangguran', 'description' => 'Data pengangguran', 'color' => '#4338CA'],
            ['name' => 'keamanan', 'description' => 'Data keamanan', 'color' => '#6B7280'],
            ['name' => 'ketertiban', 'description' => 'Data ketertiban', 'color' => '#4B5563'],
            ['name' => 'kriminalitas', 'description' => 'Data kriminalitas', 'color' => '#374151'],
            ['name' => 'anggaran', 'description' => 'Data anggaran', 'color' => '#F59E0B'],
            ['name' => 'keuangan', 'description' => 'Data keuangan', 'color' => '#D97706'],
            ['name' => 'apbd', 'description' => 'Anggaran Pendapatan dan Belanja Daerah', 'color' => '#B45309'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}