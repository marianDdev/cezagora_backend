<?php

namespace App\Http\Resources;

use App\Models\Company;
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
        $company = Company::find($this->requester_company_id);
        $avatarUrl = $company->getFirstMediaUrl('profile_picture') ?? null;
        $name = $company->name;

        return [
            'id' => $this->id,
            'name' => $name,
            'avatar' => $avatarUrl
        ];
    }
}
