<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Clear cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::firstOrCreate(['name' => 'view posts']);
        Permission::firstOrCreate(['name' => 'create posts']);
        Permission::firstOrCreate(['name' => 'edit posts']);
        Permission::firstOrCreate(['name' => 'delete posts']);

        // Create roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $editor = Role::firstOrCreate(['name' => 'editor']);
        $viewer = Role::firstOrCreate(['name' => 'viewer']);
        $preassess = Role::firstOrCreate(['name' => 'preassess']); // new role for your users

        // Assign permissions
        $admin->givePermissionTo(Permission::all());
        $editor->givePermissionTo(['view posts', 'create posts', 'edit posts']);
        $viewer->givePermissionTo(['view posts']);
        // $preassess can be assigned permissions later if needed
    }
}
