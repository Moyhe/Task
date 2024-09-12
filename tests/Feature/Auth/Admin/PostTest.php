<?php

namespace Tests\Feature\Auth\Admin;

use App\Models\Post;
use App\Models\User;
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
    public function test_admin_can_create_a_post(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $post = Post::factory([
            'author_id' => $admin->id
        ])->create();

        $response = $this->jwtAs($admin)->postJson('api/admin/posts', $post->toArray());

        $response->assertStatus(201);

        $response->assertSee('title');
    }


    public function test_admin_can_list_all_posts(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        Post::factory()->count(4)->create();

        $response = $this->jwtAs($admin)->getJson('api/admin/posts');

        $response->assertOk();

        $response->assertSee('title');
    }


    public function test_admin_can_show_a_post(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $post = Post::factory([
            'author_id' => $admin->id
        ])->create();

        $response = $this->jwtAs($admin)->getJson('api/admin/posts/', ['post' => $post->id]);

        $response->assertOk();

        $response->assertSee('content');
    }


    public function test_admin_can_delete_his_own__post(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $post = Post::factory([
            'author_id' => $admin->id
        ])->create();

        $response = $this->jwtAs($admin)->deleteJson('/api/admin/posts/' . $post->id);
        $response->assertOk();
    }

    public function test_admin_can_update_his_own__post(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $post = Post::factory([
            'author_id' => $admin->id
        ])->create();

        $response = $this->jwtAs($admin)->putJson('/api/admin/posts/' . $post->id, [
            'title' => 'Ruby on rails',
            'content' => 'i love ruby',
            'category_id' => 1
        ]);

        $response->assertStatus(200);

        $this->assertEquals('Ruby on rails', $response->json()['data']['title']);
    }
}
