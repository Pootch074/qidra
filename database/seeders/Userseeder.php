<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Userseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'first_name'  => 'System',
                'last_name'   => 'Admin',
                'employee_id' => '11-0000',
                'user_type'   => 'admin',
                'window_id'   => null,
                'assigned_category'   => null,
                'position'    => 'SWOII',
                'password'    => Hash::make('password'),
            ],
            [
                'first_name'  => 'Preasses',
                'last_name'   => 'Person',
                'employee_id' => '11-1110',
                'user_type'   => 'preasses',
                'window_id'   => 1,
                'assigned_category'   => 'regular',
                'position'    => 'SWOII',
                'password'    => Hash::make('password'),
            ],
            [
                'first_name'  => 'Encoder',
                'last_name'   => 'Person',
                'employee_id' => '11-1111',
                'user_type'   => 'encoding',
                'window_id'   => 3,
                'assigned_category'   => 'regular',
                'position'    => 'SWOII',
                'password'    => Hash::make('password'),
            ],
        ]);
    }
}
