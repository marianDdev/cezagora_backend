<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'post_id' => ['required', 'integer', Rule::exists(Post::class, 'id')],
            'text'       => ['required', 'string', 'max:256'],
            'comment_media' => ['nullable', 'file'],
        ];
    }
}
