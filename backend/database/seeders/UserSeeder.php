<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $diskominfo = Organization::where('name', 'Dinas Komunikasi dan Informatika')->first();
        $bappeda = Organization::where('name', 'Badan Perencanaan Pembangunan Daerah')->first();
        $dinkes = Organization::where('name', 'Dinas Kesehatan')->first();

        // Super Admin
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@gorontalokab.go.id',
            'password' => Hash::make('admin123'),
            'phone' => '081234567890',
            'organization_id' => $diskominfo->id,
            'position' => 'Administrator Sistem',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('super-admin');

        // Organization Admin - Diskominfo
        $orgAdmin = User::create([
            'name' => 'Ahmad Wijaya',
            'email' => 'ahmad.wijaya@gorontalokab.go.id',
            'password' => Hash::make('password123'),
            'phone' => '081234567891',
            'organization_id' => $diskominfo->id,
            'position' => 'Kepala Dinas Komunikasi dan Informatika',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $orgAdmin->assignRole('organization-admin');

        // Publisher - Bappeda
        $publisher = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@gorontalokab.go.id',
            'password' => Hash::make('password123'),
            'phone' => '081234567892',
            'organization_id' => $bappeda->id,
            'position' => 'Staff Data dan Informasi',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $publisher->assignRole('publisher');

        // Reviewer
        $reviewer = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@gorontalokab.go.id',
            'password' => Hash::make('password123'),
            'phone' => '081234567893',
            'organization_id' => $diskominfo->id,
            'position' => 'Data Analyst',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $reviewer->assignRole('reviewer');

        // Publisher - Dinkes
        $publisherDinkes = User::create([
            'name' => 'Dr. Andi Pratama',
            'email' => 'andi.pratama@gorontalokab.go.id',
            'password' => Hash::make('password123'),
            'phone' => '081234567894',
            'organization_id' => $dinkes->id,
            'position' => 'Kepala Bidang Data Kesehatan',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $publisherDinkes->assignRole('publisher');

        // Viewer
        $viewer = User::create([
            'name' => 'Maya Sari',
            'email' => 'maya.sari@gorontalokab.go.id',
            'password' => Hash::make('password123'),
            'phone' => '081234567895',
            'organization_id' => $bappeda->id,
            'position' => 'Staff Monitoring',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $viewer->assignRole('viewer');
    }
}