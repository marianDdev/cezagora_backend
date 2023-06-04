<?php

namespace App\Http\Requests;

use App\Models\ConnectionRequest;
use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AcceptConnectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'request_id' => ['required', 'integer', Rule::exists(ConnectionRequest::class, 'id')]
        ];
    }
}
