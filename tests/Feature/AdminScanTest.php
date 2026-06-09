<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminScanTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $adminToken;
    protected $user;
    protected $userToken;
    protected $ticket;

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

        // Create tourist user
        $this->user = User::create([
            'name' => 'Tourist User',
            'email' => 'tourist@example.com',
            'whatsapp' => '082222222222',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);
        $this->user->roles()->attach($userRole);

        // Create a ticket
        $this->ticket = Ticket::create([
            'title' => 'Tiket Masuk Reguler',
            'description' => 'Akses seluruh kawasan',
            'price' => 15000,
            'stock' => 10,
            'is_active' => true
        ]);

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
    public function admin_can_scan_valid_ticket()
    {
        // Buat pesanan tiket yang sudah sukses/aktif & belum kadaluwarsa
        $order = TicketOrder::create([
            'user_id' => $this->user->id,
            'ticket_id' => $this->ticket->id,
            'quantity' => 2,
            'total_price' => 30000,
            'ticket_code' => 'SWP-SUCCESS123',
            'status' => 'success',
            'is_used' => false,
            'expired_at' => now()->addDays(7)
        ]);

        $response = $this->postJson('/api/admin/tickets/scan', [
            'ticket_code' => 'SWP-SUCCESS123'
        ], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Tiket berhasil discan. Selamat menikmati kunjungan Anda!',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'ticket_code',
                    'is_used',
                    'used_at',
                    'user' => ['id', 'name', 'email', 'whatsapp'],
                    'ticket' => ['id', 'title', 'price']
                ]
            ]);

        // Pastikan terupdate di database
        $this->assertTrue($order->fresh()->is_used);
        $this->assertNotNull($order->fresh()->used_at);
    }

    /** @test */
    public function admin_cannot_scan_unpaid_ticket()
    {
        // Status pending_payment
        TicketOrder::create([
            'user_id' => $this->user->id,
            'ticket_id' => $this->ticket->id,
            'quantity' => 2,
            'total_price' => 30000,
            'ticket_code' => 'SWP-UNPAID123',
            'status' => 'pending_payment',
            'is_used' => false,
            'expired_at' => null
        ]);

        $response = $this->postJson('/api/admin/tickets/scan', [
            'ticket_code' => 'SWP-UNPAID123'
        ], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 400,
                'message' => 'Tiket belum dibayar.',
                'data' => null
            ]);
    }

    /** @test */
    public function admin_cannot_scan_pending_verification_ticket()
    {
        // Status pending (menunggu verifikasi admin)
        TicketOrder::create([
            'user_id' => $this->user->id,
            'ticket_id' => $this->ticket->id,
            'quantity' => 2,
            'total_price' => 30000,
            'ticket_code' => 'SWP-PENDING123',
            'status' => 'pending',
            'is_used' => false,
            'expired_at' => null
        ]);

        $response = $this->postJson('/api/admin/tickets/scan', [
            'ticket_code' => 'SWP-PENDING123'
        ], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 400,
                'message' => 'Tiket sedang menunggu verifikasi pembayaran oleh admin.',
                'data' => null
            ]);
    }

    /** @test */
    public function admin_cannot_scan_failed_ticket()
    {
        // Status failed
        TicketOrder::create([
            'user_id' => $this->user->id,
            'ticket_id' => $this->ticket->id,
            'quantity' => 2,
            'total_price' => 30000,
            'ticket_code' => 'SWP-FAILED123',
            'status' => 'failed',
            'is_used' => false,
            'expired_at' => null
        ]);

        $response = $this->postJson('/api/admin/tickets/scan', [
            'ticket_code' => 'SWP-FAILED123'
        ], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 400,
                'message' => 'Tiket tidak dapat digunakan karena pembayaran gagal atau kedaluwarsa.',
                'data' => null
            ]);
    }

    /** @test */
    public function admin_cannot_scan_expired_ticket()
    {
        // Status success tapi expired_at sudah lampau
        TicketOrder::create([
            'user_id' => $this->user->id,
            'ticket_id' => $this->ticket->id,
            'quantity' => 2,
            'total_price' => 30000,
            'ticket_code' => 'SWP-EXPIRED123',
            'status' => 'success',
            'is_used' => false,
            'expired_at' => now()->subDay()
        ]);

        $response = $this->postJson('/api/admin/tickets/scan', [
            'ticket_code' => 'SWP-EXPIRED123'
        ], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'status',
                'message',
                'data'
            ]);

        $this->assertStringContainsString('Tiket sudah kedaluwarsa', $response->json('message'));
    }

    /** @test */
    public function admin_cannot_scan_already_used_ticket()
    {
        // Status success, is_used = true
        TicketOrder::create([
            'user_id' => $this->user->id,
            'ticket_id' => $this->ticket->id,
            'quantity' => 2,
            'total_price' => 30000,
            'ticket_code' => 'SWP-USED123',
            'status' => 'success',
            'is_used' => true,
            'used_at' => now()->subHours(2),
            'expired_at' => now()->addDays(7)
        ]);

        $response = $this->postJson('/api/admin/tickets/scan', [
            'ticket_code' => 'SWP-USED123'
        ], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'status',
                'message',
                'data'
            ]);

        $this->assertStringContainsString('Tiket sudah pernah digunakan', $response->json('message'));
    }

    /** @test */
    public function admin_cannot_scan_non_existent_ticket()
    {
        $response = $this->postJson('/api/admin/tickets/scan', [
            'ticket_code' => 'SWP-NONEXIST123'
        ], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'status' => 404,
                'message' => 'Tiket tidak terdaftar/ditemukan.',
                'data' => null
            ]);
    }

    /** @test */
    public function tourist_cannot_scan_ticket()
    {
        $response = $this->postJson('/api/admin/tickets/scan', [
            'ticket_code' => 'SWP-ANY123'
        ], [
            'Authorization' => "Bearer {$this->userToken}"
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'status' => 403,
                'message' => 'Forbidden - Access denied',
                'data' => null
            ]);
    }
}
