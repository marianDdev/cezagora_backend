<?php

namespace App\Http\Resources;

use App\Models\Organization;
use Illuminate\Http\Resources\Json\JsonResource;

class FollowingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $followingOrganization = Organization::find($this->followed_organization_id);

        return [
            'id' => $followingOrganization->id,
            'name' => $followingOrganization->name,
            'type' => $followingOrganization->type,
            'avatar_url' => $followingOrganization->getFirstMediaUrl('profile_picture'),
            'background_url' => $followingOrganization->getFirstMediaUrl('background_picture'),
        ];
    }
}
