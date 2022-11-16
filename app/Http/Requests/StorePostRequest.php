<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title'      => ['required', 'string', 'max:128'],
            'text'       => ['required', 'string', 'max:256'],
            'post_media' => ['nullable', 'file'],
        ];
    }
}
