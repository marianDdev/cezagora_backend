<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThreadResource extends JsonResource
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
            'my_avatar_url' => $this->myAvatarUrl,
            'interlocutor_id' => $this->interlocutorId,
            'interlocutor_avatar_url' => $this->interlocutorAvatarUrl,
            'interlocutor_name' => $this->interlocutorName,
            'interlocutor_address' => $this->interlocutorAddress,
            'interlocutor_phone' => $this->interlocutorPhone,
            'company_types' => $this->companyTypes,
            'messages' => $this->messages
        ];
    }
}
