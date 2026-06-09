<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TicketCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $adminToken;
    protected $user;
    protected $userToken;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup storage fake
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
    public function admin_can_list_tickets()
    {
        Ticket::create([
            'title' => 'Tiket A',
            'description' => 'Deskripsi Tiket A',
            'price' => 15000,
            'stock' => 50,
            'is_active' => true,
            'user_id' => $this->admin->id
        ]);

        $response = $this->getJson('/api/admin/tickets', [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => ['id', 'title', 'price', 'stock', 'user']
                ]
            ])
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function regular_user_cannot_list_tickets()
    {
        $response = $this->getJson('/api/admin/tickets', [
            'Authorization' => "Bearer {$this->userToken}"
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_create_ticket_with_image()
    {
        $file = UploadedFile::fake()->image('ticket_image.jpg');

        $response = $this->postJson('/api/admin/tickets', [
            'title' => 'Tiket Baru',
            'description' => 'Deskripsi Tiket Baru',
            'price' => 20000,
            'stock' => 100,
            'is_active' => true,
            'image' => $file
        ], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['id', 'title', 'image_path', 'user_id']
            ]);

        $ticket = Ticket::first();
        $this->assertNotNull($ticket->image_path);
        
        // Assert image was saved in storage
        $storedName = str_replace('/storage/', '', $ticket->image_path);
        Storage::disk('public')->assertExists($storedName);

        // Assert observer logged admin ID
        $this->assertEquals($this->admin->id, $ticket->user_id);
    }

    /** @test */
    public function admin_can_update_ticket()
    {
        $ticket = Ticket::create([
            'title' => 'Tiket Asli',
            'description' => 'Deskripsi Asli',
            'price' => 10000,
            'stock' => 10,
            'is_active' => true,
            'user_id' => $this->admin->id
        ]);

        $response = $this->putJson("/api/admin/tickets/{$ticket->id}", [
            'title' => 'Tiket Diedit',
            'description' => 'Deskripsi Diedit',
            'price' => 12000,
            'stock' => 15,
            'is_active' => false
        ], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(200);
        $ticket->refresh();
        $this->assertEquals('Tiket Diedit', $ticket->title);
        $this->assertEquals(12000, $ticket->price);
        $this->assertFalse($ticket->is_active);
    }

    /** @test */
    public function admin_can_soft_delete_ticket()
    {
        $ticket = Ticket::create([
            'title' => 'Tiket Dihapus',
            'description' => 'Deskripsi Dihapus',
            'price' => 10000,
            'stock' => 10,
            'is_active' => true,
            'user_id' => $this->admin->id
        ]);

        $response = $this->deleteJson("/api/admin/tickets/{$ticket->id}", [], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(200);
        $this->assertSoftDeleted('tickets', ['id' => $ticket->id]);
    }

    /** @test */
    public function admin_can_restore_ticket()
    {
        $ticket = Ticket::create([
            'title' => 'Tiket Terhapus',
            'description' => 'Deskripsi Terhapus',
            'price' => 10000,
            'stock' => 10,
            'is_active' => true,
            'user_id' => $this->admin->id
        ]);
        $ticket->delete();

        $response = $this->postJson("/api/admin/tickets/{$ticket->id}/restore", [], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(200);
        $ticket->refresh();
        $this->assertNull($ticket->deleted_at);
    }
}
