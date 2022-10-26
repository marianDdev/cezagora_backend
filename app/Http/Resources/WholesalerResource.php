<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WholesalerResource extends JsonResource
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
            'organization_id'     => $this->organization_id,
            'name'                => $this->name,
            'email'               => $this->email,
            'phone'               => $this->phone,
            'continent'           => $this->continent,
            'country'             => $this->country,
            'city'                => $this->city,
            'address'             => $this->address,
            'products_categories' => $this->products_categories,
            'has_list_uploaded'  => $this->has_list_uploaded,
        ];
    }
}
