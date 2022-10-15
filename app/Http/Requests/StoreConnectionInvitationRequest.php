<?php

namespace App\Http\Requests;

use App\Models\ConnectionRequest;
use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreConnectionInvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'organization_id' => ['required', 'integer', Rule::exists(Organization::class, 'id'), Rule::notIn(ConnectionRequest::all()->pluck('organization_id')->toArray())],
            'organization_type' => ['required', 'string', Rule::in(Organization::TYPES)],
            'name' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'organization_id.not_in' => 'You have already invited this company to your connections network.'
        ];
    }
}
