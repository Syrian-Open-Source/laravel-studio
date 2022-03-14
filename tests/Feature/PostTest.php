<?php

namespace Tests\Feature;


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
}
