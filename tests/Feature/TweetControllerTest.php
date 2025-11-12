<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tweet;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TweetControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_create_a_tweet()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
                         ->postJson('/api/tweets', ['body' => 'My first tweet!']);

        $response->assertStatus(201)
                 ->assertJsonStructure(['data' => ['id', 'body', 'user']]);

        $this->assertDatabaseHas('tweets', ['body' => 'My first tweet!']);
    }

    /** @test */
    public function authenticated_user_can_view_tweets()
    {
        $user = User::factory()->create();
        Tweet::factory()->for($user)->count(3)->create();

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
                         ->getJson('/api/tweets');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data']);
    }

    /** @test */
    public function authenticated_user_can_update_their_tweet()
    {
        $user = User::factory()->create();
        $tweet = Tweet::factory()->for($user)->create(['body' => 'Old body']);
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
                         ->putJson("/api/tweets/{$tweet->id}", ['body' => 'Updated body']);

        $response->assertStatus(200)
                 ->assertJsonFragment(['body' => 'Updated body']);
    }

    /** @test */
    public function authenticated_user_can_delete_their_tweet()
    {
        $user = User::factory()->create();
        $tweet = Tweet::factory()->for($user)->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
                         ->deleteJson("/api/tweets/{$tweet->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Deleted']);

        $this->assertDatabaseMissing('tweets', ['id' => $tweet->id]);
    }
}
