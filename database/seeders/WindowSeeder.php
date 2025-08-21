<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WindowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('windows')->insert([
            [
                'id'   => 1,
                'window_number'   => 1,
                'step_id'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'   => 2,
                'window_number'   => 2,
                'step_id'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],


            [
                'id'   => 3,
                'window_number'   => 1,
                'step_id'   => 2,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'   => 4,
                'window_number'   => 2,
                'step_id'   => 2,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'   => 5,
                'window_number'   => 1,
                'step_id'   => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'   => 6,
                'window_number'   => 2,
                'step_id'   => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'   => 7,
                'window_number'   => 3,
                'step_id'   => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'   => 8,
                'window_number'   => 4,
                'step_id'   => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'   => 9,
                'window_number'   => 1,
                'step_id'   => 4,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'   => 10,
                'window_number'   => 2,
                'step_id'   => 4,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'   => 11,
                'window_number'   => 3,
                'step_id'   => 4,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        ]);
    }
}
