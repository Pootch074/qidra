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
            'employee_id' => 111234, // stored as bigint (login will format as 11-1234 in input)
            'user_type'   => 'admin',
            'window_id'   => null,   // can assign later
            'position'    => 'System Administrator',
            'password'    => Hash::make('password123'), // change this later
        ]);

        // You can also add more seed users here if needed
    }
}
