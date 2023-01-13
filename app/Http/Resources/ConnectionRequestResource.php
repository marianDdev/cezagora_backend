<?php

namespace App\Http\Resources;

use App\Models\Organization;
use Illuminate\Http\Resources\Json\JsonResource;

class ConnectionRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $organization = Organization::find($this->requester_organization_id);
        $avatarUrl = $organization->getFirstMediaUrl('profile_picture') ?? null;
        $name = $organization->name;

        return [
            'id' => $this->id,
            'name' => $name,
            'avatar' => $avatarUrl
        ];
    }
}
