<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseResource extends ResourceCollection
{

    public function resolvePaginationResults(ResourceInterface $instance)
    {
        return [
            'current_page' => $instance->currentPage(),
            'data' => $instance->collection->toArray(),
            'first_page_url' => $instance->url(1),
            'from' => $instance->firstItem(),
            'last_page' => $instance->lastPage() ?? $instance->count(),
            'last_page_url' => $instance->url($instance->lastPage()),
            'next_page_url' => $instance->nextPageUrl(),
            'path' => $instance->path(),
            'per_page' => $instance->perPage(),
            'prev_page_url' => $instance->previousPageUrl(),
            'to' => $instance->lastItem(),
            'total' => $instance->total(),
        ];
    }


    /**
     * description
     *
     * @param  \App\Http\Resources\ResourceInterface  $instance
     * @param  \Closure  $callback
     *
     * @return mixed
     * @author karam mustafa
     */
    public function mapCollection(ResourceInterface $instance, \Closure $callback = null)
    {
        return $instance->collection->map(function ($item) use ($callback) {
            return $callback($item);
        });
    }
}
