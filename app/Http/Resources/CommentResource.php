<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'text' => $this['text'],
            'media' => $this->getMedia('comment_media'),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d')
        ];
    }
}
