<?php

namespace App\Http\Resources;

use App\Models\Organization;
use App\Services\NetworkingService;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DistributorResource extends JsonResource
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
            'id'                  => $this['id'],
            'organization_id'     => $this['organization_id'],
            'name'                => $this['name'],
            'email'               => $this['email'],
            'phone'               => $this['phone'],
            'continent'           => $this['continent'],
            'country'             => $this['country'],
            'city'                => $this['city'],
            'address'             => $this['address'],
            'products_categories' => $this['products_categories'],
            'has_list_uploaded'   => $this['has_list_uploaded'],
            'organization_type'   => Organization::find($this['organization_id'])->type,
            'networking_status'   => $this['networking_status'] ?? null,
            'lists'               => $this['lists'],
            'created_at' => Carbon::parse($this['created_at'])->format('Y-m-d')
        ];
    }
}
