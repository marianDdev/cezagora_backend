<?php

namespace App\Http\Requests;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCosmeticsEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        //todo create admin dashboard (admin.cezagora.com) and admin users for adding events, blog posts etc
        /** @var User $user */
        $user = Auth::user();

        return $user->isAdmin();
    }

    public function rules(): array
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        return [
            'is_live' => ['nullable', 'boolean'],
            'title' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'start_at' => ['nullable', 'string', 'date_format:Y-m-d H:i:s', 'after_or_equal:'. $now],
            'end_at' => ['nullable', 'string', 'date_format:Y-m-d H:i:s', 'after_or_equal:start_at'],
            'country' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'host'=> ['nullable', 'string'],
            'link'=> ['nullable', 'url'],
            'credit' => ['nullable', 'string'],
            'event_media' => ['nullable', 'file'],
        ];
    }
}
