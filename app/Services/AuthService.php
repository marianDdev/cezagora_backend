<?php

namespace App\Services;

use App\Http\Resources\AuthResponseResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $validated): AuthResponseResource
    {
        $organization = Organization::create(['type' => $validated['organization_type']]);

        $user = User::create(
            [
                'organization_id' => $organization->id,
                'first_name'      => $validated['first_name'],
                'last_name'       => $validated['last_name'],
                'email'           => $validated['user_email'],
                'phone'           => $validated['user_phone'],
                'position'        => $validated['position'] ?? null,
                'password'        => Hash::make($validated['password']),
            ]
        );

        $organizationModel = $this->getOrganizationModel($organization);
        $organizationType  = $organizationModel::create(
            [
                'organization_id' => $organization->id,
                'name'            => $validated['organization_name'],
                'email'           => $validated['organization_email'],
                'phone'           => $validated['organization_phone'],
            ]
        );

        return $this->responseData($user, $organization, $organizationType);
    }

    public function responseData(
        User         $user,
        Organization $organization,
        Model        $organizationType,
        string        $token = ''
    ): AuthResponseResource
    {
        $authData = [
            'token' => $token,
            'organization'      => $organization,
            'user'              => $user,
            'organization_type' => $organizationType,
        ];

        return new AuthResponseResource($authData);
    }

    public function getOrganizationModel(Organization $organization): string
    {
        return 'App\Models\\' . ucfirst($organization->type);
    }
}
