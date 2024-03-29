<?php

namespace App\Http\Requests;

use App\Models\ConnectionRequest;
use App\Models\Company;
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
            'receiver_id' => ['required', 'integer', Rule::exists(Company::class, 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.not_in' => 'You have already invited this company to your connections network.'
        ];
    }
}
