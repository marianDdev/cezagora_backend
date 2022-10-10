<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    public function register(array $validated): array
    {
        $organization = Organization::create(
            [
                'type' => $validated['organization_type'],
            ]
        );

        $user = User::create(
            [
                'organization_id' => $organization->id,
                'first_name'      => $validated['first_name'],
                'last_name'       => $validated['last_name'],
                'email'           => $validated['user_email'],
                'phone'           => $validated['user_phone'],
                'position'        => $validated['position'],
                'password'        => Hash::make($validated['password']),
            ]
        );

        $organizationType = 'App\Models\\' . ucfirst($validated['organization_type']);
        $type             = $organizationType::create(
            [
                'organization_id' => $organization->id,
                'name'  => $validated['organization_name'],
                'email' => $validated['organization_email'],
                'phone' => $validated['organization_phone'],
            ]
        );

        return [
            'organization'      => $organization,
            'user'              => $user,
            'organization_type' => $type,
        ];
    }
}
