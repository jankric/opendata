<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'manage users',
            'create users',
            'edit users',
            'delete users',
            'view users',

            // Organization management
            'manage organizations',
            'create organizations',
            'edit organizations',
            'delete organizations',
            'view organizations',

            // Dataset management
            'manage datasets',
            'create datasets',
            'edit datasets',
            'delete datasets',
            'view datasets',
            'publish datasets',
            'approve datasets',
            'feature datasets',

            // Resource management
            'manage resources',
            'create resources',
            'edit resources',
            'delete resources',
            'view resources',
            'download resources',

            // Category management
            'manage categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view categories',

            // Group management
            'manage groups',
            'create groups',
            'edit groups',
            'delete groups',
            'view groups',

            // Analytics
            'view analytics',
            'view detailed analytics',

            // System settings
            'manage settings',
            'view system logs',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $organizationAdmin = Role::create(['name' => 'organization-admin']);
        $organizationAdmin->givePermissionTo([
            'view users',
            'create users',
            'edit users',
            'view organizations',
            'edit organizations',
            'manage datasets',
            'create datasets',
            'edit datasets',
            'delete datasets',
            'view datasets',
            'publish datasets',
            'approve datasets',
            'manage resources',
            'create resources',
            'edit resources',
            'delete resources',
            'view resources',
            'download resources',
            'view categories',
            'view groups',
            'view analytics',
        ]);

        $publisher = Role::create(['name' => 'publisher']);
        $publisher->givePermissionTo([
            'create datasets',
            'edit datasets',
            'view datasets',
            'create resources',
            'edit resources',
            'view resources',
            'download resources',
            'view categories',
            'view groups',
        ]);

        $reviewer = Role::create(['name' => 'reviewer']);
        $reviewer->givePermissionTo([
            'view datasets',
            'approve datasets',
            'view resources',
            'download resources',
            'view categories',
            'view groups',
            'view analytics',
        ]);

        $viewer = Role::create(['name' => 'viewer']);
        $viewer->givePermissionTo([
            'view datasets',
            'view resources',
            'download resources',
            'view categories',
            'view groups',
        ]);
    }
}