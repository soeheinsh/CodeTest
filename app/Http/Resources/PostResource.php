<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'author_id' => $this->author_id,
            'like_count' => optional($this->likes)->count(),

            'tags' => optional($this->tags)->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'value' => $tag->value,
                    'created_at' => $tag->created_at,
                    'updated_at' => $tag->updated_at,
                ];
            })->all(),

            'likes' => optional($this->likes)->map(function ($like) {
                return [
                    'id' => $like->id,
                    'post_id' => $like->post_id,
                    'user_id' => $like->user_id,
                ];
            })->all(),
        ];
    }
}
