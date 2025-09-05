<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostCrudTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void {
         parent::setUp();
         $this->user = User::factory()->create();
    }
    /** @test */
    public function guests_cannot_access_posts() {
        $this->get('/posts')->assertRedirect('/login');
        $this->post('/posts', [])->assertRedirect('/login');
    }

    /** @test */
    public function it_lists_posts_on_index_page(){
        $this->actingAs($this->user);

        $posts = Post::factory()->count(3)->create(['user_id' => $this->user->id]);

        $this->get('/posts')
        ->assertOk()
        ->assertSee('Posts')
        ->assertSee($posts[0]->title)
        ->assertSee($posts[1]->title);
    }

    /** @test */
    public function it_shows_the_create_form(){
        $this->actingAs($this->user);
        $this->get('/posts/create')
        ->assertOk()
        ->assertSee('Create');
    }

        /** @test */
    public function it_validates_inputs_on_store()
    {
        $this->actingAs($this->user);

        $this->from('/posts/create')->post('/posts', [
            'title' => '',                        // missing title
            'content'  => 'Some body',
        ])->assertRedirect('/posts/create')
          ->assertSessionHasErrors(['title']);

        $this->assertDatabaseCount('posts', 0);
    }

        /** @test */
    public function it_creates_a_post()
    {
        $this->actingAs($this->user);

        $payload = ['title' => 'My First Post', 'content' => 'Hello'];

        $response = $this->post('/posts', $payload);

        $post = Post::first();
        $response->assertRedirect(route('posts.show', $post));
        $this->assertDatabaseHas('posts', [
            'id'      => $post->id,
            'user_id' => $this->user->id,
            'title'   => 'My First Post',
            'content'    => 'Hello',
     ]);
    }

        /** @test */
    public function it_shows_a_single_post()
    {
        $this->actingAs($this->user);
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $this->get("/posts/{$post->id}")
            ->assertOk()
            ->assertSee($post->title)
            ->assertSee(e(str($post->content)->limit(50)));
    }

        /** @test */
    public function it_shows_the_edit_form()
    {
        $this->actingAs($this->user);
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $this->get("/posts/{$post->id}/edit")
            ->assertOk()
            ->assertSee('Edit')
            ->assertSee($post->title);
    }

        /** @test */
    public function it_validates_inputs_on_update()
    {
        $this->actingAs($this->user);
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $this->from("/posts/{$post->id}/edit")->put("/posts/{$post->id}", [
            'title' => '',                        // invalid
            'content'  => 'Updated',
        ])->assertRedirect("/posts/{$post->id}/edit")
          ->assertSessionHasErrors(['title']);

        $this->assertDatabaseMissing('posts', ['id' => $post->id, 'content' => 'Updated']);
    }

        /** @test */
    public function it_updates_a_post()
    {
        $this->actingAs($this->user);
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->put("/posts/{$post->id}", [
            'title' => 'Updated title',
            'content'  => 'Updated body',
        ]);

        $response->assertRedirect(route('posts.show', $post));
        $this->assertDatabaseHas('posts', [
            'id'    => $post->id,
            'title' => 'Updated title',
            'content'  => 'Updated body',
        ]);
    }

        /** @test */
    public function it_deletes_a_post()
    {
        $this->actingAs($this->user);
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete("/posts/{$post->id}");

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
