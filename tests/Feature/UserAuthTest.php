<?php

namespace Tests\Feature;

use App\Enums\UserRoles;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        // Additional setup if needed
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_user_registration()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);

        $response2 = $this->getJson('/api/v1/user');
        $response2->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'roles',
                ],
            ]);

    }

    public function test_user_login()
    {
        $password = 'password123';
        $email = $this->faker->unique()->safeEmail;
        $user = \App\Models\User::factory()->create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        $user->assignRole(UserRoles::ADMIN->value);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(200);
    }

    public function test_user_logout()
    {
        $password = 'password123';
        $email = $this->faker->unique()->safeEmail;
        $user = \App\Models\User::factory()->create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        $user->assignRole(UserRoles::ADMIN->value);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $email,
            'password' => $password,
        ]);
        $response->assertStatus(200);

        $response2 = $this->postJson('/api/v1/auth/logout', []);
        $response2->assertStatus(204);
    }
}
