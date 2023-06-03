<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResponseResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request): array {
        $validated = $request->validated();
        $user      = User::create($validated);
        event(new Registered($user));

        Auth::login($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'token' => $token,
            'user'  => $user,
        ];
    }

    public function login(
        Request     $request,
        AuthService $authService,
    ): array|JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                                        'message' => 'Invalid login details',
                                    ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token            = $user->createToken('auth_token')->plainTextToken;
        $userCompany = $user->company;

        return $authService->responseData($user, $userCompany, $token);
    }

    public function adminLogin(Request $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                                        'message' => 'Invalid login details',
                                    ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        if (!$user->isAdmin()) {
            return response()->json('Only admin users are allowed', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }
}
