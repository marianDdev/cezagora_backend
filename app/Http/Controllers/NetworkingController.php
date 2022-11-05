<?php

namespace App\Http\Controllers;

use App\Services\NetworkingService;
use Exception;
use Illuminate\Http\JsonResponse;

class NetworkingController extends Controller
{
    public function getStatusByOrganizationId(NetworkingService $service, int $organizationId): JsonResponse|array
    {
        try {
            return $service->getNetworkingStatusByOrganizationId($organizationId);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 401);
        }
    }
}
