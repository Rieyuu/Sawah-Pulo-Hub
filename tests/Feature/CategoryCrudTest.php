<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected $adminToken;

    protected $user;

    protected $userToken;

    protected function setUp(): void
    {
        parent::setUp();

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
            'name' => 'Tourist User',
            'email' => 'tourist@example.com',
            'whatsapp' => '082222222222',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);
        $this->user->roles()->attach($userRole);

        // Get tokens
        $adminLogin = $this->postJson('/api/login', [
            'identifier' => 'admin@example.com',
            'password' => 'password123',
        ]);
        $this->adminToken = $adminLogin->json('data.access_token');

        $userLogin = $this->postJson('/api/login', [
            'identifier' => 'tourist@example.com',
            'password' => 'password123',
        ]);
        $this->userToken = $userLogin->json('data.access_token');
    }

    /** @test */
    public function admin_can_list_categories()
    {
        Category::create(['name' => 'Pertanian', 'slug' => 'pertanian']);
        Category::create(['name' => 'Peternakan', 'slug' => 'peternakan']);

        $response = $this->getJson('/api/admin/categories', [
            'Authorization' => "Bearer {$this->adminToken}",
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function regular_user_cannot_list_or_modify_categories()
    {
        // List check
        $response = $this->getJson('/api/admin/categories', [
            'Authorization' => "Bearer {$this->userToken}",
        ]);
        $response->assertStatus(403);

        // Create check
        $response = $this->postJson('/api/admin/categories', ['name' => 'Illegal'], [
            'Authorization' => "Bearer {$this->userToken}",
        ]);
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_create_category()
    {
        $response = $this->postJson('/api/admin/categories', [
            'name' => 'Perkebunan Modern',
        ], [
            'Authorization' => "Bearer {$this->adminToken}",
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Perkebunan Modern')
            ->assertJsonPath('data.slug', 'perkebunan-modern');

        $this->assertDatabaseHas('categories', [
            'name' => 'Perkebunan Modern',
            'slug' => 'perkebunan-modern',
        ]);
    }

    /** @test */
    public function admin_can_update_category()
    {
        $category = Category::create(['name' => 'Pertanian', 'slug' => 'pertanian']);

        $response = $this->putJson("/api/admin/categories/{$category->id}", [
            'name' => 'Pertanian Hidroponik',
        ], [
            'Authorization' => "Bearer {$this->adminToken}",
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Pertanian Hidroponik')
            ->assertJsonPath('data.slug', 'pertanian-hidroponik');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Pertanian Hidroponik',
            'slug' => 'pertanian-hidroponik',
        ]);
    }

    /** @test */
    public function admin_can_delete_category()
    {
        $category = Category::create(['name' => 'Peternakan Kelinci', 'slug' => 'peternakan-kelinci']);

        $response = $this->deleteJson("/api/admin/categories/{$category->id}", [], [
            'Authorization' => "Bearer {$this->adminToken}",
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
