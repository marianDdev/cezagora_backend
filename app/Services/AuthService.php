<?php

namespace App\Services;

use App\Http\Resources\AuthResponseResource;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    private CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function register(array $validated): int
    {
        $user = User::create($validated);

        return $user->id;
    }

    public function responseData(User $user, Company $company, string $token): array
    {
        $authData = [
            'company' => $company,
            'user'         => $user,
            'profile_picture' => $company->getFirstMediaUrl('profile_picture'),
            'background_picture' => $company->getFirstMediaUrl('background_picture'),
            'connections_count' => $company->connections->count(),
            'followers_count' => $company->followers->count(),
            'followings_count' => $company->followings->count(),
        ];

        return [
            'token' => $token,
            'data'  => new AuthResponseResource($authData),
        ];
    }
}
