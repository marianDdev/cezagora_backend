<?php

namespace App\Http\Controllers;

use App\Services\NetworkingService;

class FollowerController extends Controller
{
    public function follow(NetworkingService $networkingService, int $followedOrganizationId)
    {
        return $networkingService->follow($followedOrganizationId);
    }
}
