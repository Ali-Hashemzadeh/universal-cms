<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Define permissions
        $permissions = [
            'view_entries',
            'create_entries',
            'edit_entries',
            'delete_entries',
            'manage_content_types',
            'manage_users',
            'manage_roles',
            'manage_settings',
            'manage_media',
            'manage_menus',
            'manage_forms',
        ];

        // 3. Create permissions (using firstOrCreate and Explicit Guard)
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission, 
                'guard_name' => 'web' // <--- Force the guard
            ]);
        }

        // 4. Create Roles (Explicit Guard)
        $userRole = Role::firstOrCreate([
            'name' => 'user', 
            'guard_name' => 'web'
        ]);
        
        // 5. Assign Permission
        // Pass the guard explicitly or use the object approach if strings fail
        $userRole->givePermissionTo('view_entries');

        $adminRole = Role::firstOrCreate([
            'name' => 'admin', 
            'guard_name' => 'web'
        ]);
        
        $adminRole->givePermissionTo(Permission::all());
    }
}