<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Division;
use App\Models\Section;
use App\Models\Step;
use App\Models\User;
use App\Models\Window;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Resolve references once (not inside loop)
        $division = Division::where('division_name', 'PROTECTIVE SERVICES DIVISION')->first();
        $section = Section::where('section_name', 'CRISIS INTERVENTION SECTION')->first();

        $preassessment = Step::where('step_name', 'Pre-assessment')
            ->where('section_id', $section?->id)
            ->first();

        $regular = Category::where('category_name', 'regular')
            ->where('step_id', $preassessment?->id)
            ->first();

        $window2 = Window::where('window_number', 2)
            ->where('category_id', $regular?->id)
            ->first();

        $users = [
            [
                'division_id' => $division?->id,
                'section_id' => $section?->id,
                'first_name' => 'Blad CIS',
                'last_name' => 'Admin',
                'email' => 'baybalio@dswd.gov.ph',
                'position' => 'Project Development Officer II',
                'user_type' => User::TYPE_ADMIN,
                // 'category_id' => $regular?->id,
                // 'step_id' => $preassessment?->id,
                // 'window_id' => $window3?->id,
                'status' => User::STATUS_ACTIVE,
                'email_is_verified' => true,
            ],
            [
                'division_id' => $division?->id,
                'section_id' => $section?->id,
                'first_name' => 'Blad CIS',
                'last_name' => 'PACD',
                'email' => 'bladsparrow74@gmail.com',
                'position' => 'Project Development Officer II',
                'user_type' => User::TYPE_PACD,
                // 'category_id' => $regular?->id,
                // 'step_id' => $preassessment?->id,
                // 'window_id' => $window3?->id,
                'status' => User::STATUS_ACTIVE,
                'email_is_verified' => true,
            ],
            [
                'division_id' => $division?->id,
                'section_id' => $section?->id,
                'first_name' => 'Blad CIS',
                'last_name' => 'Regu Preassess',
                'email' => 'ybaliobladymer@gmail.com',
                'position' => 'Project Development Officer II',
                'user_type' => User::TYPE_USER,
                'category_id' => $regular?->id,
                'step_id' => $preassessment?->id,
                'window_id' => $window2?->id,
                'status' => User::STATUS_ACTIVE,
                'email_is_verified' => true,
            ],
            [
                'division_id' => $division?->id,
                'section_id' => $section?->id,
                'first_name' => 'Yhena',
                'last_name' => 'Villamil',
                'email' => 'yvvillamil@dswd.gov.ph',
                'position' => 'Computer Programmer III',
                'user_type' => User::TYPE_ADMIN,
                'status' => User::STATUS_ACTIVE,
                'email_is_verified' => true,
            ],
            // [
            //     'division_id' => $division?->id,
            //     'section_id' => $section?->id,
            //     'first_name' => 'Yhena',
            //     'last_name' => 'Villamil',
            //     'email' => 'villamil.yhena@gmail.com',
            //     'position' => 'Administrative Assistant I',
            //     'user_type' => User::TYPE_USER,
            //     'category_id' => 'both',
            //     'step' => 'Assessment',
            //     'window' => 3,
            //     'status' => User::STATUS_ACTIVE,
            //     'email_is_verified' => true,
            //     'password' => 'Password@123',
            // ],
            // [
            //     'division_id' => $division?->id,
            //     'section_id' => $section?->id,
            //     'first_name' => 'Francis',
            //     'last_name' => 'Sale',
            //     'email' => 'fosale@dswd.gov.ph',
            //     'position' => 'Administrative Aide II',
            //     'user_type' => User::TYPE_USER,
            //     'category_id' => 'regular',
            //     'step' => 'Pre-assessment',
            //     'window' => 1, 'status' => User::STATUS_INACTIVE,
            //     'email_is_verified' => true,
            //     'password' => 'Password@123',
            // ],
            // [
            //     'division_id' => $division?->id,
            //     'section_id' => $section?->id,
            //     'first_name' => 'Kim',
            //     'last_name' => 'Juanico',
            //     'email' => 'krajuanico@dswd.gov.ph',
            //     'position' => 'Administrative Aide I',
            //     'user_type' => User::TYPE_USER,
            //     'category_id' => 'priority',
            //     'step' => 'Pre-assessment',
            //     'window' => 1, 'status' => User::STATUS_INACTIVE,
            //     'email_is_verified' => true,
            //     'password' => 'Password@123',
            // ],
            // [
            //     'division_id' => $division?->id,
            //     'section_id' => $section?->id,
            //     'first_name' => 'Jay',
            //     'last_name' => 'Villas',
            //     'email' => 'fjlvillas@dswd.gov.ph',
            //     'position' => 'Administrative Aide IV',
            //     'user_type' => User::TYPE_USER,
            //     'category_id' => 'regular',
            //     'step' => 'Encode', 'window' => 1,
            //     'status' => User::STATUS_INACTIVE,
            //     'email_is_verified' => true,
            //     'password' => 'Password@123',
            // ],
            // [
            //     'division_id' => $division?->id,
            //     'section_id' => $section?->id,
            //     'first_name' => 'Dan',
            //     'last_name' => 'Umbay',
            //     'email' => 'jdcumbay@dswd.gov.ph',
            //     'position' => 'Administrative Aide I',
            //     'user_type' => User::TYPE_USER,
            //     'category_id' => 'priority',
            //     'step' => 'Encode', 'window' => 1,
            //     'status' => User::STATUS_INACTIVE,
            //     'email_is_verified' => true,
            //     'password' => 'Password@123',
            // ],
            // [
            //     'division_id' => $division?->id,
            //     'section_id' => $section?->id,
            //     'first_name' => 'Chard',
            //     'last_name' => 'Tams',
            //     'email' => 'rgtamala@dswd.gov.ph',
            //     'position' => 'Administrative Aide I',
            //     'user_type' => User::TYPE_USER,
            //     'category_id' => 'both',
            //     'step' => 'Release',
            //     'window' => 1,
            //     'status' => User::STATUS_INACTIVE,
            //     'email_is_verified' => true,
            //     'password' => 'Password@123',
            // ],
            // [
            //     'division_id' => $division?->id,
            //     'section_id' => $section?->id,
            //     'first_name' => 'Juan',
            //     'last_name' => 'Dela Cruz',
            //     'email' => 'jdcruz@dswd.gov.ph',
            //     'position' => 'Social Welfare Officer II',
            //     'user_type' => User::TYPE_USER,
            //     'category_id' => 'regular',
            //     'status' => User::STATUS_INACTIVE,
            //     'email_is_verified' => true,
            //     'password' => 'Password@123',
            // ],
        ];

        foreach ($users as $user) {
            User::create(array_merge($user, [
                'password' => 'Password@123',
            ]));
        }
    }
}
