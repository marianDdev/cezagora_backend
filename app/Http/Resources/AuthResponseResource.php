<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class AuthResponseResource extends JsonResource
{
    public function toArray(Request $request): array|JsonSerializable|Arrayable
    {
        $user = $this['user'];

        return [
            'token'              => $this['token'],
            'user'               => new UserResource($user),
            'company'            => new CompanyResource($user->company) ?? null,
            'profile_picture'    => $user->getFirstMediaUrl('profile_picture'),
            'background_picture' => $user->getFirstMediaUrl('background_picture'),
            'connections_count'  => $user->connections->count(),
            'followers_count'    => $user->followers->count(),
            'followings_count'   => $user->followings->count(),
        ];
    }
}
