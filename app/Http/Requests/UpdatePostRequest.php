<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title'                  => ['nullable', 'string', 'max:128'],
            'text'                   => ['nullable', 'string', 'max:256'],
        ];
    }
}
