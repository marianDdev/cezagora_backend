<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Services\OrganizationService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getAutUserData(AuthService $service, OrganizationService $organizationService): array
    {
        /** @var User $user */
        $user = Auth::user();

        $organizationTypeModel = $organizationService->getOrganizationByAuthUser();

        return $service->responseData(
            $user,
            $user->organization,
            $organizationTypeModel,
            $user->currentAccessToken()->token
        );
    }
}
