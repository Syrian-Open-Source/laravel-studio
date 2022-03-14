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
     *
     * @author karam mustafa
     * @var \App\Models\User
     */
    private User $user = null;

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
        // post new request to create a post.
        $this->postJson(static::POST_API, [
            'user_id' => $this->create_user()->id,
            'description' => 'dummy description',
            'title' => 'dummy title',
        ])->assertStatus(static::$SUCCESS_RESPONSE);
        // check database records.
        $this->assertDatabaseHas('posts', [
            'user_id' => $this->create_user()->id,
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
        $this->postJson(static::POST_API, [
            'user_id' => $this->create_user()->id,
            'description' => 'dummy description',
            'title' => 'dummy title',
        ])->assertStatus(static::$SUCCESS_RESPONSE);
        // check database records.
        $this->assertDatabaseHas('posts', [
            'user_id' => $this->create_user()->id,
            'description' => 'dummy description',
            'title' => 'dummy title',
        ]);

        $post = Post::first();
        //edit post
        $this->putJson(static::POST_API."/{$post->id}", [
            'user_id' => $this->create_user()->id,
            'description' => 'dummy description updated',
            'title' => 'dummy title updated',
        ])->assertStatus(static::$SUCCESS_RESPONSE);
        // check if post was edited successfully
        $this->assertDatabaseHas('posts', [
            'user_id' => $this->create_user()->id,
            'description' => 'dummy description updated',
            'title' => 'dummy title updated',
        ]);
    }

    /**
     * description
     *
     * @author karam mustafa
     */
    public function test_delete_post()
    {

        $this->postJson(static::POST_API, [
            'user_id' => $this->create_user()->id,
            'description' => 'dummy description',
            'title' => 'dummy title',
        ])->assertStatus(static::$SUCCESS_RESPONSE);

        // check database records.
        $this->assertDatabaseHas('posts', [
            'user_id' => $this->create_user()->id,
            'description' => 'dummy description',
            'title' => 'dummy title',
        ]);

        $post = Post::first();

        $this->deleteJson(static::POST_API."/{$post->id}", [])->assertStatus(static::$SUCCESS_RESPONSE);
    }

    /**
     * create a new user
     *
     * @return \App\Models\User|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     * @author karam mustafa
     */
    public function create_user()
    {
        if (!$this->user) {
            $this->user = User::factory()->create();
        }
        return $this->user;
    }
}
