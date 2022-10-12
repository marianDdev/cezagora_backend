<?php

namespace App\Http\Requests;

use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            [
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
            ]
        ];
    }
}
