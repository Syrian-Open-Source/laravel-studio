<?php


namespace Tests\Feature;


use App\Helpers\Classes\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BaseFeatureTest extends TestCase
{
    use RefreshDatabase, Response;

    public function test_base_test_pass_to_ignore_warning()
    {
        $this->assertTrue(true);
    }
}
