<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Article;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TouristPagesTest extends TestCase
{
    use RefreshDatabase;

    protected $ticket;
    protected $article;

    protected function setUp(): void
    {
        parent::setUp();

        // Create sample user as author
        $user = User::create([
            'name' => 'Author User',
            'email' => 'author@example.com',
            'whatsapp' => '081234567890',
            'password' => bcrypt('password123'),
        ]);

        // Create sample ticket
        $this->ticket = Ticket::create([
            'title' => 'Tiket Masuk Reguler',
            'description' => 'Akses penuh',
            'price' => 15000,
            'stock' => 100,
            'is_active' => true,
        ]);

        // Create sample article category & article
        $category = Category::create([
            'name' => 'Eduwisata',
            'slug' => 'eduwisata',
        ]);

        $this->article = Article::create([
            'title' => 'Menanam Padi Modern',
            'slug' => 'menanam-padi-modern',
            'content' => 'Ini adalah konten edukatif tentang menanam padi.',
            'category_id' => $category->id,
            'author_id' => $user->id,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function public_guest_can_access_profil_wisata_page()
    {
        $response = $this->get('/profil-wisata');
        $response->assertStatus(200)
            ->assertSee('Profil Wisata')
            ->assertSee('Sejarah')
            ->assertSee('Profil');
    }

    /** @test */
    public function public_guest_can_access_facilities_page()
    {
        $response = $this->get('/facilities');
        $response->assertStatus(200)
            ->assertSee('Denah Peta 2D Kawasan')
            ->assertSee('Layout Area');
    }

    /** @test */
    public function public_guest_can_access_tickets_page_with_active_tickets()
    {
        $response = $this->get('/tickets');
        $response->assertStatus(200)
            ->assertSee('Katalog Tiket Wisata')
            ->assertSee('Tiket Masuk Reguler');
    }

    /** @test */
    public function public_guest_can_access_articles_page()
    {
        $response = $this->get('/articles');
        $response->assertStatus(200)
            ->assertSee('Artikel')
            ->assertSee('Berita')
            ->assertSee('Menanam Padi Modern');
    }

    /** @test */
    public function public_guest_can_access_article_details_page()
    {
        $response = $this->get("/articles/{$this->article->id}");
        $response->assertStatus(200)
            ->assertSee('Menanam Padi Modern')
            ->assertSee('Ini adalah konten edukatif tentang menanam padi.');
    }
}
