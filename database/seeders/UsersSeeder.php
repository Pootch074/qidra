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

        $window3 = Window::where('window_number', 2)
            ->where('category_id', $regular?->id)
            ->first();

        $users = [
            [
                'division_id' => $division?->id,
                'section_id' => $section?->id,
                'first_name' => 'Blad',
                'last_name' => 'CIS PACD',
                'email' => 'baybalio@dswd.gov.ph',
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
                'first_name' => 'Blad',
                'last_name' => 'Preassess',
                'email' => 'bladsparrow74@gmail.com',
                'position' => 'Project Development Officer II',
                'user_type' => User::TYPE_USER,
                'category_id' => $regular?->id,
                'step_id' => $preassessment?->id,
                'window_id' => $window3?->id,
                'status' => User::STATUS_ACTIVE,
                'email_is_verified' => true,
            ],
        ];

        foreach ($users as $user) {
            User::create(array_merge($user, [
                'password' => 'Password@123',
            ]));
        }
    }
}
