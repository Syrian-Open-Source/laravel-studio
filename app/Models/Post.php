<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Post
 *
 * @author karam mustafa
 * @package App\Models
 */
class Post extends BaseModel
{
    use HasFactory;

    /**
     *
     * @author karam mustafa
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'user_id',
    ];

    /**
     * user relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author karam mustafa
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
