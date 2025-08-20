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
        User::create([
            'first_name'   => 'System',
            'last_name'    => 'Admin',
            'employee_id'  => '11-0000', 
            'section_id'   => 1,
            'user_type'    => 'admin',
            'position'     => 'SWOII',
            'password'     => Hash::make('password'),
        ]);
    }
}
