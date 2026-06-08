<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\AccessToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        Role::create(['name' => 'Administrator', 'slug' => 'admin']);
        Role::create(['name' => 'User / Visitor', 'slug' => 'user']);

        // Create user
        $this->user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'whatsapp' => '081234567890',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $this->user->roles()->attach(Role::where('slug', 'user')->first());

        // Log in to get token
        $response = $this->postJson('/api/login', [
            'identifier' => 'john@example.com',
            'password' => 'password123',
        ]);

        $this->token = $response->json('data.access_token');
    }

    /** @test */
    public function authenticated_user_can_view_profile()
    {
        $response = $this->getJson('/api/profile', [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Profile retrieved successfully',
                'data' => [
                    'user' => [
                        'id' => $this->user->id,
                        'name' => 'John Doe',
                        'email' => 'john@example.com',
                        'whatsapp' => '081234567890',
                    ]
                ]
            ]);
    }

    /** @test */
    public function authenticated_user_can_update_profile_without_password()
    {
        $response = $this->putJson('/api/profile', [
            'name' => 'John Updated',
            'email' => 'john.updated@example.com',
            'whatsapp' => '089999999999',
        ], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => [
                        'name' => 'John Updated',
                        'email' => 'john.updated@example.com',
                        'whatsapp' => '089999999999',
                    ]
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'John Updated',
            'email' => 'john.updated@example.com',
            'whatsapp' => '089999999999',
        ]);
    }

    /** @test */
    public function authenticated_user_can_update_password()
    {
        $response = $this->putJson('/api/profile', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'whatsapp' => '081234567890',
            'current_password' => 'password123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Profile updated successfully'
            ]);

        // Verify hash password is updated
        $this->user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->user->password));
    }

    /** @test */
    public function update_password_fails_if_current_password_is_incorrect()
    {
        $response = $this->putJson('/api/profile', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'whatsapp' => '081234567890',
            'current_password' => 'wrong_password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['status', 'message', 'errors'])
            ->assertJsonValidationErrors(['current_password']);
    }

    /** @test */
    public function authenticated_user_can_soft_delete_their_own_account()
    {
        $response = $this->deleteJson('/api/profile', [], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Account deleted successfully'
            ]);

        // Assert user is soft-deleted
        $this->assertSoftDeleted('users', [
            'id' => $this->user->id,
        ]);

        // Assert all access tokens for user are revoked
        $this->assertDatabaseMissing('access_tokens', [
            'user_id' => $this->user->id,
            'revoked_at' => null,
        ]);

        // Assert that request using the token now fails
        $profileResponse = $this->getJson('/api/profile', [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $profileResponse->assertStatus(401);
    }
}
