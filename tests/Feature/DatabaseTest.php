<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected $token;
    /**
     * A basic feature test example.
     */

       protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = auth('api')->login($this->user);
    }

    /**
     * @test
     */

    public function it_can_create_a_post(): void
    {
        
        $payload = [
            'title_data' => 'My First Post-63939t7883f',
            'content_data'  => 'This is a test body-56798hs',
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
            'Accept'        => 'application/json',
        ])->postJson('/api/posts', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'My First Post-63939t7883f')
            ->assertJson([
                'success' => true,
                'message' => 'Post created successfully',
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'My First Post-63939t7883f',
            'content'  => 'This is a test body-56798hs',
            'user_id' => $this->user->id,
        ]);
    }
}
