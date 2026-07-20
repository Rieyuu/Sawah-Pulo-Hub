<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\SiteSetting;
use App\Models\User;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSettingTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected $adminToken;

    protected $user;

    protected $userToken;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        // Seed roles
        $adminRole = Role::create(['name' => 'Administrator', 'slug' => 'admin']);
        $userRole = Role::create(['name' => 'User / Visitor', 'slug' => 'user']);

        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'whatsapp' => '081111111111',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);
        $this->admin->roles()->attach($adminRole);

        // Create regular user
        $this->user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'whatsapp' => '082222222222',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);
        $this->user->roles()->attach($userRole);

        // Seed settings
        $this->seed(SiteSettingSeeder::class);

        // Get admin token
        $adminLogin = $this->postJson('/api/login', [
            'identifier' => 'admin@example.com',
            'password' => 'password123',
        ]);
        $this->adminToken = $adminLogin->json('data.access_token');

        // Get regular user token
        $userLogin = $this->postJson('/api/login', [
            'identifier' => 'user@example.com',
            'password' => 'password123',
        ]);
        $this->userToken = $userLogin->json('data.access_token');
    }

    /** @test */
    public function admin_can_retrieve_settings()
    {
        $response = $this->getJson('/api/admin/settings', [
            'Authorization' => "Bearer {$this->adminToken}",
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'about_history' => ['value', 'type', 'updated_by', 'updated_at'],
                    'operating_days' => ['value', 'type', 'updated_by', 'updated_at'],
                ],
            ]);
    }

    /** @test */
    public function regular_user_cannot_retrieve_settings()
    {
        $response = $this->getJson('/api/admin/settings', [
            'Authorization' => "Bearer {$this->userToken}",
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_update_text_and_image_settings()
    {
        $file = UploadedFile::fake()->image('site_plan_new.jpg');

        $response = $this->postJson('/api/admin/settings', [
            'operating_days' => 'Senin - Sabtu',
            'operating_hours' => '09:00 - 16:00 WIB',
            'site_plan_image' => $file,
        ], [
            'Authorization' => "Bearer {$this->adminToken}",
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Successfully updated 3 settings',
            ]);

        // Assert text values are updated
        $this->assertEquals('Senin - Sabtu', SiteSetting::getValue('operating_days'));
        $this->assertEquals('09:00 - 16:00 WIB', SiteSetting::getValue('operating_hours'));

        // Assert image was saved in storage and path updated
        $imageSetting = SiteSetting::where('key', 'site_plan_image')->first();
        $this->assertEquals('image', $imageSetting->type);
        $this->assertStringContainsString('/storage/settings/', $imageSetting->value);
        $storedName = str_replace('/storage/', '', $imageSetting->value);
        Storage::disk('public')->assertExists($storedName);

        // Assert observer recorded admin ID
        $this->assertEquals($this->admin->id, $imageSetting->user_id);
    }
}
