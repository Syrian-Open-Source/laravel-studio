<?php

namespace Tests\Feature;



class PostTest extends BaseFeatureTest
{

    const POST_API = '/api/posts';

    /**
     * test get all posts.
     *
     * @author karam mustafa
     */
    public function test_obtain_posts()
    {

        $this->get(static::POST_API)->assertStatus($this->SUCCESS_RESPONSE);

    }
    /**
     * test get validation error
     *
     * @author karam mustafa
     */
    public function test_create_new_post_and_get_validation_error()
    {
        $this->post(static::POST_API)
            ->assertStatus($this->VALIDATION_ERROR);
    }
    /**
     * test create a new post and get validation error, with testing for message.
     *
     * @author karam mustafa
     */
    public function test_create_new_post_and_get_validation_error_and_test_message()
    {
        $this->post(static::POST_API)
            ->assertJson("message" , ["The title field is required."])
            ->assertStatus($this->VALIDATION_ERROR);
    }
}
