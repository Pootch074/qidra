<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('steps')->insert([
            [
                'id'   => 1,
                'step_number'   => 1,
                'step_name'   => 'Preasses',
                'section_id' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'   => 2,
                'step_number'   => 2,
                'step_name'   => 'Encoding',
                'section_id' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'   => 3,
                'step_number'   => 3,
                'step_name'   => 'Assesment',
                'section_id' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'   => 4,
                'step_number'   => 4,
                'step_name'   => 'Releasing',
                'section_id' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        ]);
    }
}
