<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use App\Models\User;
use Database\Factories\CommentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\JwtAuthTrait;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase, JwtAuthTrait;
    /**
     * A basic feature test example.
     */
    public function test_user_can_create_a_post(): void
    {
        $user = User::factory()->create();

        $post = Post::factory([
            'author_id' => $user->id
        ])->create();

        $response = $this->jwtAs($user)->postJson(route('posts.store'), $post->toArray());

        $response->assertStatus(201);

        $response->assertSee('title');
    }


    public function test_user_can_list_all_posts(): void
    {
        $user = User::factory()->create();

        Post::factory()->count(4)->create();

        $response = $this->jwtAs($user)->getJson(route('posts.index'));

        $response->assertOk();

        $response->assertSee('title');
    }


    public function test_user_can_show_a_post(): void
    {
        $user = User::factory()->create();

        $post = Post::factory([
            'author_id' => $user->id
        ])->create();

        $response = $this->jwtAs($user)->getJson(route('posts.show', $post));

        $response->assertOk();

        $response->assertSee('content');
    }


    public function test_user_can_delete_his_own__post(): void
    {
        $user = User::factory()->create();

        $post = Post::factory([
            'author_id' => $user->id
        ])->create();

        $response = $this->jwtAs($user)->deleteJson(route('posts.destroy', $post));

        $response->assertOk();
    }

    public function test_user_can_update_his_own__post(): void
    {
        $user = User::factory()->create();

        $post = Post::factory([
            'author_id' => $user->id
        ])->create();

        $response = $this->jwtAs($user)->putJson(route('posts.update', $post), [
            'title' => 'Ruby on rails',
            'content' => 'i love ruby',
            'category_id' => 1
        ]);

        $response->assertStatus(200);

        $this->assertEquals('Ruby on rails', $response->json()['data']['title']);
    }


    public function test_user_can_comment_on_a_post(): void
    {
        $user = User::factory()->create();

        $post = Post::factory()->create();

        $comment = CommentFactory::new()->create([
            'post_id' => $post->id,
            'author_id' => $user->id
        ]);

        $response = $this->jwtAs($user)->postJson(route('comment.store', $comment->toArray()));

        $response->assertStatus(201);

        $response->assertSee('comment');
    }
}
