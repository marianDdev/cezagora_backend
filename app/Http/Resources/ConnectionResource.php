<?php

namespace App\Http\Resources;

use App\Models\Organization;
use Illuminate\Http\Resources\Json\JsonResource;

class ConnectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $connection = Organization::find($this->connected_organization_id);

        return [
            'name' => $connection->name,
            'type' => $connection->type,
            'avatar_url' => $connection->getFirstMediaUrl('profile_picture')
        ];
    }
}
