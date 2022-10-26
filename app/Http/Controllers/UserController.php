<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Services\OrganizationService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getAutUserData(AuthService $service, OrganizationService $organizationService)
    {
        /** @var User $user */
        $user = Auth::user();

        dd($user);

        $organizationTypModel = $organizationService->getOrganizationByAuthUser();

        return $service->responseData(
            $user,
            $user->organization,
            $organizationTypModel,
            $user->currentAccessToken()->token
        );
    }
}
