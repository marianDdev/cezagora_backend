<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConnectionRequestResource;
use App\Http\Resources\ConnectionResource;
use App\Services\NetworkingService;
use Exception;
use Illuminate\Http\JsonResponse;

class ConnectionRequestController extends Controller
{
    public function create(NetworkingService $service, int $receiverId): ConnectionRequestResource|JsonResponse
    {
        try {
            return $service->createConnectionRequest($receiverId);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 401);
        }
    }

    public function acceptRequest(NetworkingService $service, int $id): ConnectionResource|JsonResponse
    {
        return $service->acceptConnectionRequest($id);
    }
}
