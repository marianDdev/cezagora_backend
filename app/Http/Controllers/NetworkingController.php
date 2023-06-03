<?php

namespace App\Http\Controllers;

use App\Services\NetworkingService;
use Exception;
use Illuminate\Http\JsonResponse;

class NetworkingController extends Controller
{
    public function getStatusByCompanyId(NetworkingService $service, int $companyId): JsonResponse|array
    {
        try {
            return $service->getNetworkingStatusByCompanyId($companyId);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 401);
        }
    }
}
