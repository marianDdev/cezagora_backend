<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'company'      => new CompanyResource($this['company']),
            'user'              => new UserResource($this['user']),
            'profile_picture'   => $this['profile_picture'],
            'background_picture'   => $this['background_picture'],
            'connections_count' => $this['connections_count'],
            'followers_count'   => $this['followers_count'],
            'followings_count'  => $this['followings_count'],
        ];
    }
}
