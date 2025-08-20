<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'first_name'   => 'System',
            'last_name'    => 'Admin',
            'employee_id'  => 110000, // since your column is bigint (no dash allowed)
            'section_id'   => 1, // must correspond to an actual section
            'user_type'    => 'assessment',
            'position'     => 'Administrator',
            'password'     => Hash::make('password123'),
        ]);
    }
}
