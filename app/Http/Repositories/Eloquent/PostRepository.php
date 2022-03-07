<?php


namespace App\Http\Repositories\Eloquent;


use App\Http\Repositories\Interfaces\IPostRepository;
use App\Models\Post;

class PostRepository extends BaseRepository implements IPostRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Post::class;
    }
}
