<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConnectionInvitationRequest;
use App\Http\Resources\ConnectionRequestResource;
use App\Http\Resources\ConnectionResource;
use App\Models\ConnectionRequest;
use App\Models\Organization;
use App\Services\NetworkingService;
use App\Services\OrganizationService;
use Exception;
use Illuminate\Http\JsonResponse;

class ConnectionRequestController extends Controller
{
    public function create(
        StoreConnectionInvitationRequest $request,
        NetworkingService                $service,
        OrganizationService              $organizationService
    ): ConnectionRequestResource|JsonResponse
    {
        $validated = $request->validated();
        $receiver  = Organization::find($validated['receiver_id']);
        $authOrg   = $organizationService->getAuthOrganization();

        $existingrequest = ConnectionRequest::where('receiver_organization_id', $receiver->id)
                                            ->where('requester_organization_id', $authOrg->id)
                                            ->first();
        if (!is_null($existingrequest)) {
            return response()->json('You already invited this company to your network.', 401);
        }

        if (is_null($receiver)) {
            return response()->json('Company not found.', 404);
        }

        try {
            return new ConnectionRequestResource($service->createConnectionRequest($authOrg, $receiver));
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 401);
        }
    }

    public function acceptRequest(NetworkingService $service, int $connectionRequestid): ConnectionResource|JsonResponse
    {
        return $service->acceptConnectionRequest($connectionRequestid);
    }
}
