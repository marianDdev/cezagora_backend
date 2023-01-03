<?php

namespace App\Services;

use App\Http\Resources\AuthResponseResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    private OrganizationService $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    public function register(array $validated): int
    {
        $organization = Organization::create(
            [
                'number_of_users'       => 1,
                'name'                  => $validated['company_name'],
                'email'                 => $validated['email'],
                'has_list_uploaded'     => false,
                'has_details_completed' => false,
                'company_types' => [],
                'selling_methods' => []
            ]
        );

        $user = User::create(
            [
                'organization_id' => $organization->id,
                'company_name'    => $organization->name,
                'email'           => $validated['email'],
                'password'        => Hash::make($validated['password']),
            ]
        );

        return $user->id;
    }

    public function responseData(User $user, Organization $organization, string $token): array
    {
        $authData = [
            'organization' => $organization,
            'user'         => $user,
            'profile_picture' => $organization->getFirstMediaUrl('profile_picture'),
            'background_picture' => $organization->getFirstMediaUrl('background_picture'),
            'connections_count' => $organization->connections->count(),
            'followers_count' => $organization->followers->count(),
            'followings_count' => $organization->followings->count(),
        ];

        return [
            'token' => $token,
            'data'  => new AuthResponseResource($authData),
        ];
    }
}
