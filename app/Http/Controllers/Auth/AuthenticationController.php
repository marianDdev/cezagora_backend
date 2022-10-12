<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResponseResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request, AuthService $service): AuthResponseResource
    {
        $authData = $service->register($request->validated());

        event(new Registered($authData['user']));

        Auth::login($authData['user']);

        return $authData;
    }

    public function login(Request $request, AuthService $service): AuthResponseResource
    {

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                                        'message' => 'Invalid login details',
                                    ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;
        $organizationModel = $service->getOrganizationModel($user->organization);
        $userOrganization = $organizationModel::where('organization_id', $user->organization_id)->first();

        return $service->responseData($user, $user->organization, $userOrganization, $token);

    }
}
