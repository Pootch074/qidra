<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks (important for truncating related tables)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate all tables in the correct order
        DB::table('users')->truncate();
        DB::table('windows')->truncate();
        DB::table('steps')->truncate();
        DB::table('sections')->truncate();
        DB::table('divisions')->truncate();
        DB::table('dswd')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            DswdSeeder::class,
            DivisionSeeder::class,
            SectionSeeder::class,
            StepSeeder::class,
            WindowSeeder::class,
            UserSeeder::class,
        ]);
    }
}
