<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConnectionInvitationRequest;
use App\Http\Resources\ConnectionRequestResource;
use App\Http\Resources\ConnectionResource;
use App\Services\NetworkingService;
use Illuminate\Http\JsonResponse;

class ConnectionRequestController extends Controller
{
    public function create(NetworkingService $service, int $organizationId): ConnectionRequestResource
    {
        return $service->createConnectionRequest($organizationId);
    }

    public function acceptRequest(NetworkingService $service, int $id): ConnectionResource|JsonResponse
    {
        return $service->acceptConnectionRequest($id);
    }
}
