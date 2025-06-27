<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DevelopmentDataSeeder extends Seeder
{
    /**
     * Seed the application's database for development.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            OrganizationSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            GroupSeeder::class,
            TagSeeder::class,
            DatasetSeeder::class,
            EnhancedDatasetSeeder::class,
            ApiUsageSeeder::class,
            SearchLogSeeder::class,
        ]);

        $this->command->info('Development data seeded successfully!');
        $this->command->info('You can now login with:');
        $this->command->info('Email: admin@gorontalokab.go.id');
        $this->command->info('Password: admin123');
    }
}