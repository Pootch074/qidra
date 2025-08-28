<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1️⃣ Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $preassessRole = Role::firstOrCreate(['name' => 'preassess']);

        // 2️⃣ Create Admin user
        $admin = User::updateOrCreate(
            ['employee_id' => 111234], // search by employee_id
            [
                'first_name' => 'Admin',
                'last_name'  => 'User',
                'user_type'  => 'admin',
                'window_id'  => null,
                'assigned_category' => 'priority',
                'position'   => 'System Administrator',
                'password'   => Hash::make('password'),
            ]
        );

        // Assign Admin role
        $admin->assignRole($adminRole);

        // 3️⃣ Create Preassess user
        $preassess = User::updateOrCreate(
            ['employee_id' => 111235],
            [
                'first_name' => 'Preassess',
                'last_name'  => 'User',
                'user_type'  => 'preassess',
                'window_id'  => null,
                'assigned_category' => 'priority',
                'position'   => 'SWOII',
                'password'   => Hash::make('password'),
            ]
        );

        // Assign Preassess role
        $preassess->assignRole($preassessRole);
    }
}
