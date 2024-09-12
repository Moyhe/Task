<?php

namespace Tests\Feature\Auth\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\JwtAuthTrait;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase, JwtAuthTrait;

    public function test_admin_can__login(): void
    {

        User::factory()->create([
            'email' => 'admin@gmail.com',
        ]);

        $admin = [
            'email' => 'admin@gmail.com',
            'password' => "password"
        ];

        $response = $this->postJson(route('admin.login'), $admin);

        $response->assertStatus(200);

        $this->assertArrayHasKey('token', $response->json());
    }


    public function test_admin_create_other_admins(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $adminRegister = [

            'name' => 'mohye',
            'email' => 'geni@gmail.com',
            'password' => "123456789",
            'password_confirmation' => "123456789"

        ];

        $response = $this->jwtAs($admin)->postJson(route('admin.create'), $adminRegister);

        $response->assertStatus(200);

        $this->assertArrayHasKey('user', $response->json());
    }
}
