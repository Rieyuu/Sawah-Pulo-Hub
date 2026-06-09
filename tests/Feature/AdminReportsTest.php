<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReportsTest extends TestCase
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
    public function admin_can_retrieve_dashboard_reports()
    {
        // Buat beberapa data transaksi sukses
        TicketOrder::create([
            'user_id' => $this->user->id,
            'ticket_id' => $this->ticket->id,
            'quantity' => 2,
            'total_price' => 30000,
            'ticket_code' => 'SWP-REP01',
            'status' => 'success',
            'is_used' => false,
            'expired_at' => now()->addDays(7)
        ]);

        TicketOrder::create([
            'user_id' => $this->user->id,
            'ticket_id' => $this->ticket->id,
            'quantity' => 3,
            'total_price' => 45000,
            'ticket_code' => 'SWP-REP02',
            'status' => 'success',
            'is_used' => true,
            'used_at' => now(),
            'expired_at' => now()->addDays(7)
        ]);

        // Buat order pending (tidak boleh masuk hitungan keuangan/wisatawan unik)
        TicketOrder::create([
            'user_id' => $this->user->id,
            'ticket_id' => $this->ticket->id,
            'quantity' => 1,
            'total_price' => 15000,
            'ticket_code' => 'SWP-REP03',
            'status' => 'pending',
            'is_used' => false,
            'expired_at' => null
        ]);

        $response = $this->getJson('/api/admin/reports/dashboard', [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Statistik dashboard berhasil diambil.',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'total_revenue',
                    'tickets_sold',
                    'total_visitors',
                    'chart_data' => [
                        '*' => ['label', 'raw_date', 'revenue', 'tickets']
                    ],
                    'popular_tickets' => [
                        '*' => ['title', 'sold']
                    ],
                    'recent_orders' => [
                        '*' => ['id', 'ticket_code', 'user_name', 'ticket_title', 'quantity', 'total_price', 'status', 'created_at']
                    ]
                ]
            ]);

        $this->assertEquals(75000, $response->json('data.total_revenue'));
        $this->assertEquals(5, $response->json('data.tickets_sold'));
        $this->assertEquals(1, $response->json('data.total_visitors')); // Hanya 1 user unik
    }

    /** @test */
    public function tourist_cannot_retrieve_dashboard_reports()
    {
        $response = $this->getJson('/api/admin/reports/dashboard', [
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
