<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FacilityCrudTest extends TestCase
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

        $adminRole = Role::create(['name' => 'Administrator', 'slug' => 'admin']);
        $userRole = Role::create(['name' => 'User / Visitor', 'slug' => 'user']);

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'whatsapp' => '081111111111',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);
        $this->admin->roles()->attach($adminRole);

        $this->user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'whatsapp' => '082222222222',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);
        $this->user->roles()->attach($userRole);

        $adminLogin = $this->postJson('/api/login', [
            'identifier' => 'admin@example.com',
            'password' => 'password123',
        ]);
        $this->adminToken = $adminLogin->json('data.access_token');

        $userLogin = $this->postJson('/api/login', [
            'identifier' => 'user@example.com',
            'password' => 'password123',
        ]);
        $this->userToken = $userLogin->json('data.access_token');
    }

    /** @test */
    public function admin_can_list_facilities()
    {
        Facility::create([
            'name' => 'Fasilitas Utama',
            'slug' => 'fasilitas-utama',
            'description' => 'Deskripsi Fasilitas Utama',
            'user_id' => $this->admin->id
        ]);

        $response = $this->getJson('/api/admin/facilities', [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => ['id', 'name', 'slug', 'description', 'user']
                ]
            ])
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function regular_user_cannot_list_facilities()
    {
        $response = $this->getJson('/api/admin/facilities', [
            'Authorization' => "Bearer {$this->userToken}"
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_create_facility_with_image()
    {
        $file = UploadedFile::fake()->image('facility.jpg');

        $response = $this->postJson('/api/admin/facilities', [
            'name' => 'Toilet Bersih',
            'description' => 'Fasilitas sanitasi toilet bersih',
            'image' => $file
        ], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['id', 'name', 'slug', 'image_path', 'user_id']
            ]);

        $facility = Facility::first();
        $this->assertNotNull($facility->image_path);
        
        $storedName = str_replace('/storage/', '', $facility->image_path);
        Storage::disk('public')->assertExists($storedName);

        $this->assertEquals($this->admin->id, $facility->user_id);
        $this->assertStringContainsString('toilet-bersih', $facility->slug);
    }

    /** @test */
    public function admin_can_update_facility()
    {
        $facility = Facility::create([
            'name' => 'Kantin Lama',
            'slug' => 'kantin-lama',
            'description' => 'Deskripsi Lama',
            'user_id' => $this->admin->id
        ]);

        $response = $this->putJson("/api/admin/facilities/{$facility->id}", [
            'name' => 'Kantin Baru',
            'description' => 'Deskripsi Baru',
        ], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(200);
        $facility->refresh();
        $this->assertEquals('Kantin Baru', $facility->name);
        $this->assertStringContainsString('kantin-baru', $facility->slug);
    }

    /** @test */
    public function admin_can_soft_delete_facility()
    {
        $facility = Facility::create([
            'name' => 'Fasilitas Rusak',
            'slug' => 'fasilitas-rusak',
            'description' => 'Deskripsi',
            'user_id' => $this->admin->id
        ]);

        $response = $this->deleteJson("/api/admin/facilities/{$facility->id}", [], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(200);
        $this->assertSoftDeleted('facilities', ['id' => $facility->id]);
    }

    /** @test */
    public function admin_can_restore_facility()
    {
        $facility = Facility::create([
            'name' => 'Fasilitas Hilang',
            'slug' => 'fasilitas-hilang',
            'description' => 'Deskripsi',
            'user_id' => $this->admin->id
        ]);
        $facility->delete();

        $response = $this->postJson("/api/admin/facilities/{$facility->id}/restore", [], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(200);
        $facility->refresh();
        $this->assertNull($facility->deleted_at);
    }
}
