<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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
        ]);
    }
}