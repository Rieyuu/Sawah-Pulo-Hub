<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TicketOrderTransactionTest extends TestCase
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
    public function tourist_can_create_ticket_order()
    {
        $response = $this->postJson('/api/orders', [
            'ticket_id' => $this->ticket->id,
            'quantity' => 2
        ], [
            'Authorization' => "Bearer {$this->userToken}"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['id', 'ticket_code', 'total_price', 'status']
            ]);

        $order = TicketOrder::first();
        $this->assertEquals('pending_payment', $order->status);
        $this->assertEquals(30000, $order->total_price);
        $this->assertNotNull($order->ticket_code);
        $this->assertStringStartsWith('SWP-', $order->ticket_code);
    }

    /** @test */
    public function tourist_cannot_order_exceeding_stock()
    {
        $response = $this->postJson('/api/orders', [
            'ticket_id' => $this->ticket->id,
            'quantity' => 12 // Stock is only 10
        ], [
            'Authorization' => "Bearer {$this->userToken}"
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 400,
                'message' => 'Insufficient stock. Only 10 tickets available.'
            ]);
    }

    /** @test */
    public function tourist_can_upload_payment_proof()
    {
        $order = TicketOrder::create([
            'user_id' => $this->user->id,
            'ticket_id' => $this->ticket->id,
            'quantity' => 2,
            'total_price' => 30000,
            'ticket_code' => 'SWP-TESTING',
            'status' => 'pending_payment'
        ]);

        $file = UploadedFile::fake()->image('proof.jpg');

        $response = $this->postJson("/api/orders/{$order->id}/upload-payment", [
            'proof_of_payment' => $file
        ], [
            'Authorization' => "Bearer {$this->userToken}"
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Payment proof uploaded successfully. Waiting for admin verification.'
            ]);

        $order->refresh();
        $this->assertEquals('pending', $order->status);
        $this->assertNotNull($order->proof_of_payment);

        $storedName = str_replace('/storage/', '', $order->proof_of_payment);
        Storage::disk('public')->assertExists($storedName);
    }

    /** @test */
    public function admin_can_approve_payment_which_decrements_stock_and_sets_expiration()
    {
        $order = TicketOrder::create([
            'user_id' => $this->user->id,
            'ticket_id' => $this->ticket->id,
            'quantity' => 3,
            'total_price' => 45000,
            'ticket_code' => 'SWP-APPROVE',
            'status' => 'pending',
            'proof_of_payment' => '/storage/payments/proof.jpg'
        ]);

        $response = $this->postJson("/api/admin/orders/{$order->id}/approve", [], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(200);
        
        $order->refresh();
        $this->ticket->refresh();

        $this->assertEquals('success', $order->status);
        $this->assertEquals(7, $this->ticket->stock); // 10 - 3 = 7
        $this->assertNotNull($order->expired_at);
        $this->assertTrue($order->expired_at->isAfter(now()->addDays(6)));
    }

    /** @test */
    public function admin_can_reject_payment()
    {
        $order = TicketOrder::create([
            'user_id' => $this->user->id,
            'ticket_id' => $this->ticket->id,
            'quantity' => 1,
            'total_price' => 15000,
            'ticket_code' => 'SWP-REJECT',
            'status' => 'pending',
            'proof_of_payment' => '/storage/payments/proof.jpg'
        ]);

        $response = $this->postJson("/api/admin/orders/{$order->id}/reject", [], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(200);
        
        $order->refresh();
        $this->assertEquals('failed', $order->status);
    }

    /** @test */
    public function tourist_cannot_access_admin_orders_index_or_approval()
    {
        $order = TicketOrder::create([
            'user_id' => $this->user->id,
            'ticket_id' => $this->ticket->id,
            'quantity' => 1,
            'total_price' => 15000,
            'ticket_code' => 'SWP-REJECT',
            'status' => 'pending'
        ]);

        // Access index
        $indexResponse = $this->getJson('/api/admin/orders', [
            'Authorization' => "Bearer {$this->userToken}"
        ]);
        $indexResponse->assertStatus(403);

        // Access approve
        $approveResponse = $this->postJson("/api/admin/orders/{$order->id}/approve", [], [
            'Authorization' => "Bearer {$this->userToken}"
        ]);
        $approveResponse->assertStatus(403);
    }

    /** @test */
    public function order_cancelled_after_payment_timeout()
    {
        \App\Models\SiteSetting::setValue('payment_timeout_hours', '2');

        $order = TicketOrder::create([
            'user_id' => $this->user->id,
            'ticket_id' => $this->ticket->id,
            'quantity' => 1,
            'total_price' => 15000,
            'ticket_code' => 'SWP-EXPIRED-TEST',
            'status' => 'pending_payment',
        ]);
        // Force update created_at to bypass timestamps auto-set
        $order->created_at = now()->subHours(3);
        $order->save();

        $response = $this->getJson('/api/orders/history', [
            'Authorization' => "Bearer {$this->userToken}"
        ]);

        $response->assertStatus(200);
        $order->refresh();
        $this->assertEquals('failed', $order->status);
    }

    /** @test */
    public function tourist_cannot_upload_proof_after_payment_timeout()
    {
        \App\Models\SiteSetting::setValue('payment_timeout_hours', '2');

        $order = TicketOrder::create([
            'user_id' => $this->user->id,
            'ticket_id' => $this->ticket->id,
            'quantity' => 1,
            'total_price' => 15000,
            'ticket_code' => 'SWP-EXPIRED-UPLOAD',
            'status' => 'pending_payment',
        ]);
        $order->created_at = now()->subHours(3);
        $order->save();

        $file = UploadedFile::fake()->image('proof.jpg');

        $response = $this->postJson("/api/orders/{$order->id}/upload-payment", [
            'proof_of_payment' => $file
        ], [
            'Authorization' => "Bearer {$this->userToken}"
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 400,
                'message' => 'The payment window for this ticket order has expired. Please place a new order.'
            ]);

        $order->refresh();
        $this->assertEquals('failed', $order->status);
    }
}
