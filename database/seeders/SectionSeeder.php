<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Section::insert([
            [
                'id'  => 1,
                'section_name' => 'Crisis Intervention Section',
                'division_id' => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]
        ]);
    }
}
