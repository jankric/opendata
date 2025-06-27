<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductionDataSeeder extends Seeder
{
    /**
     * Seed the application's database for production.
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
        ]);

        $this->command->info('Production data seeded successfully!');
        $this->command->warn('Remember to:');
        $this->command->warn('1. Change default passwords');
        $this->command->warn('2. Update organization information');
        $this->command->warn('3. Configure email settings');
        $this->command->warn('4. Set up file storage');
    }
}