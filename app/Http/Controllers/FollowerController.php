<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\NetworkingService;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{
    public function follow(NetworkingService $networkingService)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        return $networkingService->follow($authUser->organization->id);
    }
}
