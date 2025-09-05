<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function PHPUnit\Framework\assertJson;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = auth('api')->login($this->user);
    }

    /** @test */
    public function guests_cannot_access_posts()
    {
        $this->getJson('/api/posts')->assertUnauthorized();
    }

    /** @test */
    public function it_lists_posts()
    {

        Post::factory()->count(3)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
            'Accept'        => 'application/json',
        ])->getJson('/api/posts');

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_creates_a_post()
    {
        // $this->actingAs($this->user);

        $payload = [
            'title_data' => 'My First Post',
            'content_data'  => 'This is a test body',
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
            'Accept'        => 'application/json',
        ])->postJson('/api/posts', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'My First Post')
            ->assertJson([
                'success' => true,
                'message' => 'Post created successfully',
            ]);

        $this->assertDatabaseHas('posts', [
            'title'   => 'My First Post',
            'content'    => 'This is a test body',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_shows_a_post()
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
            'Accept'        => 'application/json',
        ]);

        // $this->actingAs($this->user);

        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response->getJson("/api/posts/{$post->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $post->id);
    }

    /** @test */
    public function it_updates_a_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $payload = ['title_data' => 'Updated Title', 'content_data' => 'Updated body'];

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
            'Accept'        => 'application/json',
        ])->putJson("/api/posts/{$post->id}", $payload);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Post updated successfully',
            ]);

        $this->assertDatabaseHas('posts', [
            'id'    => $post->id,
            'title' => 'Updated Title',
        ]);
    }

    /** @test */
    public function it_deletes_a_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
            'Accept'        => 'application/json',
        ])->deleteJson("/api/posts/{$post->id}");

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => "Post with ID {$post->id} deleted successfully"
            ]);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
