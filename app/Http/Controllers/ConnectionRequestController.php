<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConnectionInvitationRequest;
use App\Http\Resources\ConnectionRequestResource;
use App\Services\NetworkingService;

class ConnectionRequestController extends Controller
{
    public function create(StoreConnectionInvitationRequest $request, NetworkingService $service): ConnectionRequestResource
    {
        return $service->createConnectionRequest($request->validated());
    }

    public function acceptRequest(NetworkingService $service, int $id)
    {
        return $service->acceptConnectionRequest($id);
    }
}
