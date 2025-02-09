<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define your permissions.
        $permissions = [
            'upload files',
            'delete files',
            'add admins',
            'delete admins',
            'add roles',
            'delete roles',
        ];

        // Create permissions.
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles.
        $superAdminRole = Role::firstOrCreate(['name' => 'super admin']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Assign permissions to roles.
        // Super admin gets all permissions.
        $superAdminRole->syncPermissions(Permission::all());

        // Editor can only upload files.
        $editorRole->syncPermissions(['upload files','delete files']);

        // Admin can upload files and add admins.
        $adminRole->syncPermissions(['upload files','delete files', 'add admins']);
    }
}
