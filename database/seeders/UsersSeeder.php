<?php

namespace Database\Seeders;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->count(10)
            ->withRoles([UserRoles::PROJECT_MANAGER->value])
            ->create();

        User::factory()
            ->count(10)
            ->withRoles([UserRoles::TEAM_LEAD->value])
            ->create();

        User::factory()
            ->count(10)
            ->withRoles([UserRoles::TEAM_MEMBER->value])
            ->create();
    }
}
