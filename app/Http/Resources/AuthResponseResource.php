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
        $type     = $this['organization']->type;
        $resource = 'App\Http\Resources\\' . ucfirst($type) . 'Resource';

        return [
            'organization'      => new OrganizationResource($this['organization']),
            'user'              => new UserResource($this['user']),
            'organization_type' => new $resource($this['organization_type']),
        ];
    }
}
