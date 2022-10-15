<?php

namespace App\Http\Controllers;


use App\Models\Organization;
use App\Services\NetworkingService;

class FollowingController extends Controller
{
    public function create(NetworkingService $service, int $organizationId)
    {
        $organization = Organization::find($organizationId);

        $service->createFollowing($organization);
    }
}
