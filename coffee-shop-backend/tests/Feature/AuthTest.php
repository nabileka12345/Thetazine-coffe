<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    /**
     * Test user registration creates user with 'user' role
     */
    public function test_register_creates_user_with_user_role()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.role', 'user');
        
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
            'role' => 'user',
        ]);
    }

    /**
     * Test admin user has 'admin' role
     */
    public function test_admin_user_has_admin_role()
    {
        $admin = User::where('email', 'admin@thetazine.com')->first();
        
        $this->assertNotNull($admin);
        $this->assertEquals('admin', $admin->role);
    }

    /**
     * Test login returns correct role
     */
    public function test_login_returns_user_role()
    {
        // Create a test user
        User::create([
            'name' => 'Login Test User',
            'email' => 'logintest@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'logintest@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.role', 'user');
    }

    /**
     * Test admin login returns admin role
     */
    public function test_admin_login_returns_admin_role()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'admin@thetazine.com',
            'password' => 'admin123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.role', 'admin');
    }
}
