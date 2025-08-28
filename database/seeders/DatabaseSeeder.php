<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Example default Admin account
        User::create([
            'first_name'  => 'Admin',
            'last_name'   => 'User',
            'employee_id' => 111234,
            'user_type'   => 'admin',
            'window_id'   => null,
            'position'    => 'System Administrator',
            'password'    => Hash::make('password'),
        ]);

        User::create([
            'first_name'  => 'Preassess',
            'last_name'   => 'User',
            'employee_id' => 111235,
            'user_type'   => 'preasses',
            'window_id'   => null,
            'position'    => 'SWOII',
            'password'    => Hash::make('password'),
        ]);


        // You can also add more seed users here if needed
    }
}
