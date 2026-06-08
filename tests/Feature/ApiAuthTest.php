<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\AccessToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        Role::create(['name' => 'Administrator', 'slug' => 'admin']);
        Role::create(['name' => 'User / Visitor', 'slug' => 'user']);
    }

    /** @test */
    public function a_user_can_register_successfully()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Wisatawan Baru',
            'email' => 'wisatawan.baru@example.com',
            'whatsapp' => '089999999999',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'whatsapp']
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'wisatawan.baru@example.com',
            'whatsapp' => '089999999999',
        ]);

        $user = User::where('email', 'wisatawan.baru@example.com')->first();
        $this->assertTrue($user->hasRole('user'));
    }

    /** @test */
    public function registration_fails_with_validation_errors()
    {
        // First user
        User::create([
            'name' => 'Existing',
            'email' => 'exist@example.com',
            'whatsapp' => '081234567890',
            'password' => Hash::make('password'),
        ]);

        // Try duplicate register
        $response = $this->postJson('/api/register', [
            'name' => 'New Guy',
            'email' => 'exist@example.com', // duplicate
            'whatsapp' => '081234567890', // duplicate
            'password' => 'short', // too short
            'password_confirmation' => 'mismatch', // password confirmation fails
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['status', 'message', 'errors'])
            ->assertJsonValidationErrors(['email', 'whatsapp', 'password']);
    }

    /** @test */
    public function a_user_can_login_successfully()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'whatsapp' => '081234567890',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $user->roles()->attach(Role::where('slug', 'user')->first());

        // Test login with email
        $response = $this->postJson('/api/login', [
            'identifier' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'whatsapp', 'roles'],
                    'access_token',
                    'refresh_token',
                    'expires_in'
                ]
            ]);

        // Check if token was saved in database
        $this->assertDatabaseHas('access_tokens', [
            'user_id' => $user->id,
        ]);
        
        $tokenRecord = AccessToken::where('user_id', $user->id)->first();
        $this->assertNotNull($tokenRecord->token);
        $this->assertNull($tokenRecord->revoked_at);

        // Test login with WhatsApp
        $responseWhatsapp = $this->postJson('/api/login', [
            'identifier' => '081234567890',
            'password' => 'password123',
        ]);

        $responseWhatsapp->assertStatus(200);
    }

    /** @test */
    public function login_fails_with_invalid_credentials()
    {
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'whatsapp' => '081234567890',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/login', [
            'identifier' => 'test@example.com',
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 401,
                'message' => 'Invalid credentials',
            ]);
    }

    /** @test */
    public function login_fails_if_account_is_inactive()
    {
        $user = User::create([
            'name' => 'Inactive User',
            'email' => 'inactive@example.com',
            'whatsapp' => '081234567890',
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        $response = $this->postJson('/api/login', [
            'identifier' => 'inactive@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'status' => 403,
                'message' => 'Account is inactive',
            ]);
    }

    /** @test */
    public function a_user_can_logout_successfully()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'whatsapp' => '081234567890',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $user->roles()->attach(Role::where('slug', 'user')->first());

        $loginResponse = $this->postJson('/api/login', [
            'identifier' => 'test@example.com',
            'password' => 'password123',
        ]);

        $token = $loginResponse->json('data.access_token');

        $response = $this->postJson('/api/logout', [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Logout successful',
            ]);

        // Verify token in DB is revoked
        $tokenRecord = AccessToken::where('user_id', $user->id)->first();
        $this->assertNotNull($tokenRecord->revoked_at);

        // Try accessing a protected route with the logged out token should fail
        $profileResponse = $this->getJson('/api/profile', [
            'Authorization' => "Bearer $token",
        ]);

        $profileResponse->assertStatus(401);
    }
}
