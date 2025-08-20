<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DswdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dswd')->delete();
        DB::table('dswd')->insert([
            [
                'field_office' => 'DSWD FO XI - Davao City',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]
        ]);
    }
}
