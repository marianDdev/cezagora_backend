<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Services\OrganizationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function register(
        RegisterRequest $request,
        AuthService $authService
    ): array
    {
        $authData = $authService->register($request->validated());

        event(new Registered($authData['data']['user']));

        Auth::login($authData['data']['user']);

        return $authData;
    }

    public function login(
        Request $request,
        AuthService $authService,
    ): array|JsonResponse {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                                        'message' => 'Invalid login details',
                                    ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;
        $userOrganization = $user->organization;

        return $authService->responseData($user, $userOrganization, $token);
    }
}
