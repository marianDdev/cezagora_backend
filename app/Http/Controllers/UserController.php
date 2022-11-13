<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getAutUserData(AuthService $service): array
    {
        /** @var User $user */
        $user = Auth::user();

        return $service->responseData(
            $user,
            $user->organization,
            $user->currentAccessToken()->token
        );
    }
}
