<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'author' => $this['author']->name,
            'avatar' => $this['author']->getFirstMediaUrl('profile_picture'),
            'title' => $this['title'],
            'text' => $this['text'],
            'media' => $this->getMedia('post_media') ?? null,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'comments' => new CommentResourceCollection($this->comments)
        ];
    }
}
