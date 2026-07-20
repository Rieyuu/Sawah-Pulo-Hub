<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected $adminToken;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $adminRole = Role::create(['name' => 'Administrator', 'slug' => 'admin']);

        // Create admin user using default password 'password'
        $this->admin = User::create([
            'name' => 'Admin Test User',
            'email' => 'admin.test@example.com',
            'whatsapp' => '089999999999',
            'password' => Hash::make('password'), // default password
            'is_active' => true,
        ]);
        $this->admin->roles()->attach($adminRole);

        // Get token
        $loginRes = $this->postJson('/api/login', [
            'identifier' => 'admin.test@example.com',
            'password' => 'password',
        ]);
        $this->adminToken = $loginRes->json('data.access_token');
    }

    /** @test */
    public function admin_shows_default_password_warning_flag()
    {
        // 1. Ambil profil, pastikan is_using_default_password = true
        $profileRes = $this->getJson('/api/profile', [
            'Authorization' => "Bearer {$this->adminToken}",
        ]);

        $profileRes->assertStatus(200);
        $this->assertTrue($profileRes->json('data.user.is_using_default_password'));

        // 2. Ubah password admin ke 'newsecurepassword'
        $updateRes = $this->putJson('/api/profile', [
            'name' => 'Admin Updated Name',
            'email' => 'admin.test@example.com',
            'whatsapp' => '089999999999',
            'current_password' => 'password',
            'password' => 'newsecurepassword',
            'password_confirmation' => 'newsecurepassword',
        ], [
            'Authorization' => "Bearer {$this->adminToken}",
        ]);

        $updateRes->assertStatus(200);

        // Flag is_using_default_password harus berubah jadi false karena password sudah diganti dari 'password'
        $this->assertFalse($updateRes->json('data.user.is_using_default_password'));

        // 3. Verifikasi dengan memanggil ulang profil
        $profileRes2 = $this->getJson('/api/profile', [
            'Authorization' => "Bearer {$this->adminToken}",
        ]);
        $this->assertFalse($profileRes2->json('data.user.is_using_default_password'));
    }

    /** @test */
    public function admin_can_update_profile_and_password()
    {
        // Hubungi API update profile
        $response = $this->putJson('/api/profile', [
            'name' => 'Super Administrator',
            'email' => 'superadmin@example.com',
            'whatsapp' => '089988887777',
        ], [
            'Authorization' => "Bearer {$this->adminToken}",
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'whatsapp'],
                ],
            ]);

        // Pastikan tersimpan di DB
        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'name' => 'Super Administrator',
            'email' => 'superadmin@example.com',
            'whatsapp' => '089988887777',
        ]);
    }
}
