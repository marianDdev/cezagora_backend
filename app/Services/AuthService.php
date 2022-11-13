<?php

namespace App\Services;

use App\Http\Resources\AuthResponseResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
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
                'type'                  => $validated['organization_type'],
                'number_of_users'       => 1,
                'name'                => $validated['organization_name'],
                'email'               => $validated['organization_email'],
                'phone'               => $validated['organization_phone'],
                'products_categories' => [],
                'has_list_uploaded'   => false,
                'has_details_completed' => false,
            ]
        );

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

        return $user->id;
    }

    public function responseData(User $user, Organization $organization, string $token): array
    {
        $authData = [
            'organization'      => $organization,
            'user'              => $user,
        ];

        return [
            'token' => $token,
            'data'  => new AuthResponseResource($authData),
        ];
    }
}
