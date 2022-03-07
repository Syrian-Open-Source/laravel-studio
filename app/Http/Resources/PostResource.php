<?php

namespace App\Http\Resources;


class PostResource extends BaseResource implements ResourceInterface
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return mixed
     */
    public function toArray($request)
    {
        $this->mapCollection($this, function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'user_id' => $item->user_id,
            ];
        });

        return $this->resolvePaginationResults($this);
    }

}
