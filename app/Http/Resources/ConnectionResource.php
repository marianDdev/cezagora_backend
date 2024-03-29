<?php

namespace App\Http\Resources;

use App\Models\Company;
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
        $connection = Company::find($this->connected_company_id);

        return [
            'name' => $connection->name,
            'type' => $connection->type,
            'avatar_url' => $connection->getFirstMediaUrl('profile_picture'),
            'background_url' => $connection->getFirstMediaUrl('background_picture'),
        ];
    }
}
