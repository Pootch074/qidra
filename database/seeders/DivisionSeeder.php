<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Division::insert([
            [
                'id'  => 1,   // make sure division with ID 1 exists
                'division_name' => 'Protective Services Division',
                'dswd_id' => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],

        ]);
    }
}
