<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ApiUsageSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating API usage logs...');

        $endpoints = [
            '/api/v1/datasets',
            '/api/v1/categories',
            '/api/v1/organizations',
            '/api/v1/stats',
            '/api/v1/datasets/search',
            '/api/v1/datasets/popular',
            '/api/v1/datasets/recent',
        ];

        $methods = ['GET', 'POST', 'PUT', 'DELETE'];
        $statusCodes = [200, 201, 400, 401, 403, 404, 422, 500];
        $userIds = User::pluck('id')->toArray();

        // Generate 1000 API usage logs
        for ($i = 0; $i < 1000; $i++) {
            DB::table('api_usage_logs')->insert([
                'endpoint' => $this->faker()->randomElement($endpoints),
                'method' => $this->faker()->randomElement($methods),
                'status_code' => $this->faker()->randomElement($statusCodes),
                'response_time' => $this->faker()->randomFloat(3, 0.1, 5.0),
                'user_id' => $this->faker()->optional(0.4)->randomElement($userIds),
                'ip_address' => $this->faker()->ipv4(),
                'user_agent' => $this->faker()->userAgent(),
                'requested_at' => $this->faker()->dateTimeBetween('-3 months', 'now'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('API usage logs created successfully!');
    }

    private function faker(): \Faker\Generator
    {
        return \Faker\Factory::create();
    }
}