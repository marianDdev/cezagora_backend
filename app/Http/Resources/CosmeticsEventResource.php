<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CosmeticsEventResource extends JsonResource
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
            'is_live' => $this->is_live ? 'live' : 'online',
            'title' => $this->title,
            'description' => $this->description,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'country' => $this->country,
            'city' => $this->city,
            'address' => $this->address,
            'host' => $this->host,
            'link' => $this->link,
            'credit' => $this->credit,
            'event_media_url' => $this->getFirstMediaurl('event_media'),
        ];
    }
}
