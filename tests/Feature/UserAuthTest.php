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
        $response = $this->postJson('/auth/register', [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);

        $response2 = $this->getJson('/user');
        $this->debugResponse($response2);
        $response2->assertStatus(201);

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

        $response = $this->postJson('/auth/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->debugResponse($response);
        $response->assertStatus(200);
    }

    public function test_user_login_invalid_credentials()
    {
        $response = $this->postJson('/auth/login', [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->debugResponse($response);
        $response->assertStatus(422);
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

        $response = $this->postJson('/auth/login', [
            'email' => $email,
            'password' => $password,
        ]);
        $response->assertStatus(200);

        $response2 = $this->postJson('/auth/logout', []);
        $response2->assertStatus(204);
    }
}
