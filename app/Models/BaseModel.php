<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 * @desc this is an abstract class follow [ Liskov substitution principle ], and used as a template for all classes that
 * extend from this abstract
 * @author karam mustafa
 * @package App
 */
abstract class BaseModel extends Model
{
    /**
     * @author karam mustafa
     * @var string
     */
    public string $imagePath = 'images/public/';

    /**
     * @param $query
     *
     * @return string
     * @author karam mustafa
     */
    public function scopeGetByDesc($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * @param  String  $query
     *
     * @param  Integer  $limit
     *
     * @return string
     * @author karam mustafa
     */
    public function scopeGetByDescAndTake($query, $limit)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }
}
