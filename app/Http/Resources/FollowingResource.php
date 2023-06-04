<?php

namespace App\Http\Resources;

use App\Models\Company;
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
        $followingCompany = Company::find($this->followed_company_id);

        return [
            'id' => $followingCompany->id,
            'name' => $followingCompany->name,
            'type' => $followingCompany->type,
            'avatar_url' => $followingCompany->getFirstMediaUrl('profile_picture'),
            'background_url' => $followingCompany->getFirstMediaUrl('background_picture'),
        ];
    }
}
