<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DswdSeeder::class,
            DivisionSeeder::class,
            SectionSeeder::class,
            StepSeeder::class,
            WindowSeeder::class,
            UserSeeder::class, // <-- make sure filename & class name match
        ]);
    }
}
