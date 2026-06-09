<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $adminToken;
    protected $user;
    protected $userToken;
    protected $category;

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

        // Create Category
        $this->category = Category::create([
            'name' => 'Event Wisata',
            'slug' => 'event-wisata'
        ]);
    }

    /** @test */
    public function admin_can_list_articles()
    {
        Article::create([
            'title' => 'Artikel Indah',
            'slug' => 'artikel-indah',
            'content' => 'Lorem ipsum dolor sit amet.',
            'category_id' => $this->category->id,
            'author_id' => $this->admin->id
        ]);

        $response = $this->getJson('/api/admin/articles', [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => ['id', 'title', 'slug', 'content', 'category', 'author']
                ]
            ])
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function regular_user_cannot_list_articles()
    {
        $response = $this->getJson('/api/admin/articles', [
            'Authorization' => "Bearer {$this->userToken}"
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_create_article_with_image()
    {
        $file = UploadedFile::fake()->image('article.jpg');

        $response = $this->postJson('/api/admin/articles', [
            'title' => 'Artikel Menarik Baru',
            'content' => 'Isi konten artikel yang sangat mendalam dan informatif.',
            'category_id' => $this->category->id,
            'image' => $file
        ], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['id', 'title', 'slug', 'image_path', 'author_id', 'category_id']
            ]);

        $article = Article::first();
        $this->assertNotNull($article->image_path);
        
        $storedName = str_replace('/storage/', '', $article->image_path);
        Storage::disk('public')->assertExists($storedName);

        $this->assertEquals($this->admin->id, $article->author_id);
        $this->assertStringContainsString('artikel-menarik-baru', $article->slug);
    }

    /** @test */
    public function admin_can_update_article()
    {
        $article = Article::create([
            'title' => 'Artikel Lawas',
            'slug' => 'artikel-lawas',
            'content' => 'Konten lama',
            'category_id' => $this->category->id,
            'author_id' => $this->admin->id
        ]);

        $response = $this->putJson("/api/admin/articles/{$article->id}", [
            'title' => 'Artikel Terkini',
            'content' => 'Konten baru',
            'category_id' => $this->category->id
        ], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(200);
        $article->refresh();
        $this->assertEquals('Artikel Terkini', $article->title);
        $this->assertEquals('Konten baru', $article->content);
        $this->assertStringContainsString('artikel-terkini', $article->slug);
    }

    /** @test */
    public function admin_can_soft_delete_article()
    {
        $article = Article::create([
            'title' => 'Artikel Dihapus',
            'slug' => 'artikel-dihapus',
            'content' => 'Konten',
            'category_id' => $this->category->id,
            'author_id' => $this->admin->id
        ]);

        $response = $this->deleteJson("/api/admin/articles/{$article->id}", [], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(200);
        $this->assertSoftDeleted('articles', ['id' => $article->id]);
    }

    /** @test */
    public function admin_can_restore_article()
    {
        $article = Article::create([
            'title' => 'Artikel Terhapus',
            'slug' => 'artikel-terhapus',
            'content' => 'Konten',
            'category_id' => $this->category->id,
            'author_id' => $this->admin->id
        ]);
        $article->delete();

        $response = $this->postJson("/api/admin/articles/{$article->id}/restore", [], [
            'Authorization' => "Bearer {$this->adminToken}"
        ]);

        $response->assertStatus(200);
        $article->refresh();
        $this->assertNull($article->deleted_at);
    }
}
