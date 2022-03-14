<?php

namespace Tests\Feature;



use App\Models\Post;
use App\Models\User;

/**
 * Class PostTest
 *
 * @author karam mustafa
 * @package Tests\Feature
 */
class PostTest extends BaseFeatureTest
{

    /**
     *
     */
    const POST_API = '/api/posts';

    /**
     * test get all posts.
     *
     * @author karam mustafa
     */
    public function test_obtain_posts()
    {
        $this->get(static::POST_API)->assertStatus(static::$SUCCESS_RESPONSE);
    }

    /**
     * test get validation error
     *
     * @author karam mustafa
     */
    public function test_create_new_post_and_get_validation_error()
    {
        $this->postJson(static::POST_API)
            ->assertStatus(static::$VALIDATION_ERROR);
    }

    /**
     * test create a new post and get validation error, with testing for message.
     *
     * @author karam mustafa
     */
    public function test_create_new_post_and_get_validation_error_and_test_message()
    {
        $this->post(static::POST_API)
            ->assertJson("message", ["The title field is required."])
            ->assertStatus(static::$VALIDATION_ERROR);
    }

    /**
     * test create a new post.
     *
     * @author karam mustafa
     */
    public function test_create_new_post()
    {
        // create a new user.
        $user = User::factory()->create();

        // post new request to create a post.
        $this->postJson(static::POST_API,[
            'user_id' => $user->id,
            'description' => 'dummy description',
            'title' => 'dummy title',
        ])->assertStatus(static::$SUCCESS_RESPONSE);

        // check database records.
        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'description' => 'dummy description',
            'title' => 'dummy title',
        ]);
    }

    /**
     * test create a new post.
     *
     * @author karam mustafa
     */
    public function test_edit_custom_post()
    {
        // create new post
        $user = User::factory()->create();
        $this->postJson(static::POST_API,[
            'user_id' => $user->id,
            'description' => 'dummy description',
            'title' => 'dummy title',
        ])->assertStatus(static::$SUCCESS_RESPONSE);

        // check database records.
        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'description' => 'dummy description',
            'title' => 'dummy title',
        ]);

        $post = Post::first();

        //edit post
        $this->putJson(static::POST_API."/{$post->id}",[
            'user_id' => $user->id,
            'description' => 'dummy description updated',
            'title' => 'dummy title updated',
        ])->assertStatus(static::$SUCCESS_RESPONSE);

        // check if post was edited successfully
        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'description' => 'dummy description updated',
            'title' => 'dummy title updated',
        ]);
    }

    public function test_delete_post(){

        $this->postJson(static::POST_API,[
            'user_id' => $this->create_user()->id,
            'description' => 'dummy description',
            'title' => 'dummy title',
        ])->assertStatus(static::$SUCCESS_RESPONSE);

        // check database records.
        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'description' => 'dummy description',
            'title' => 'dummy title',
        ]);

        $post = Post::first();

        $this->deleteJson(static::POST_API."/{$post->id}",[])->assertStatus(static::$SUCCESS_RESPONSE);
    }

    public function create_user(){
        return User::factory()->create();
    }
}
