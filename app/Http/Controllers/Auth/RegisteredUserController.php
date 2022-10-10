<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResponseResource;
use App\Models\Organization;
use App\Models\User;
use App\Services\RegisterService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, RegisterService $service): AuthResponseResource
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'string', 'email', 'max:255'],
            'user_phone' => ['required', 'string'],
            'position' => ['nullable', 'string', 'max:255'],
            'organization_name' => ['required', 'string', 'max:255'],
            'organization_email' => ['required', 'string', 'email', 'max:255'],
            'organization_phone' => ['required', 'string'],
            'organization_type' => ['required', 'string', Rule::in(Organization::TYPES)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $authData = $service->register($validated);

        event(new Registered($authData['user']));

        Auth::login($authData['user']);

        return new AuthResponseResource($authData);
    }
}
