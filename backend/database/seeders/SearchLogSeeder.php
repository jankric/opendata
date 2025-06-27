<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SearchLogSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating search logs...');

        $searchQueries = [
            'penduduk',
            'ekonomi',
            'kesehatan',
            'pendidikan',
            'infrastruktur',
            'data penduduk gorontalo',
            'pdrb 2024',
            'rumah sakit',
            'sekolah dasar',
            'jalan kabupaten',
            'statistik',
            'demografi',
            'inflasi',
            'tenaga kerja',
            'lingkungan',
            'air bersih',
            'transportasi',
            'keamanan',
            'anggaran',
            'investasi',
        ];

        $userIds = User::pluck('id')->toArray();

        // Generate 500 search logs
        for ($i = 0; $i < 500; $i++) {
            DB::table('search_logs')->insert([
                'query' => $this->faker()->randomElement($searchQueries),
                'result_count' => $this->faker()->numberBetween(0, 50),
                'user_id' => $this->faker()->optional(0.3)->randomElement($userIds),
                'ip_address' => $this->faker()->ipv4(),
                'user_agent' => $this->faker()->userAgent(),
                'searched_at' => $this->faker()->dateTimeBetween('-6 months', 'now'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Search logs created successfully!');
    }

    private function faker(): \Faker\Generator
    {
        return \Faker\Factory::create();
    }
}