<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcceptConnectionRequest;
use App\Http\Requests\StoreConnectionInvitationRequest;
use App\Http\Resources\ConnectionRequestResource;
use App\Http\Resources\ConnectionRequestResourceCollection;
use App\Http\Resources\ConnectionResource;
use App\Models\ConnectionRequest;
use App\Models\Organization;
use App\Services\NetworkingService;
use App\Services\OrganizationService;
use Exception;
use Illuminate\Http\JsonResponse;

class ConnectionRequestController extends Controller
{
    public function list(OrganizationService $organizationService): ConnectionRequestResourceCollection
    {
        $authOrg = $organizationService->getAuthOrganization();
        $requests = $authOrg->connectionRequestsReceived->sortByDesc('created_at');

        return new ConnectionRequestResourceCollection($requests);
    }

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

    public function acceptRequest(
        AcceptConnectionRequest $request,
        NetworkingService $service,
        OrganizationService $organizationService
    ): JsonResponse
    {
        $validated = $request->validated();
        $authOrg = $organizationService->getAuthOrganization();
        $connectionRequest = ConnectionRequest::find($validated['request_id']);

        if (is_null($connectionRequest)) {
            return response()->json('Connection request not found.', 404);
        }

        $requesterOrganization = Organization::find($connectionRequest->requester_organization_id);

        if (is_null($requesterOrganization)) {
            return response()->json('Requester organization not found.', 404);
        }

        if ($connectionRequest->receiver_organization_id !== $authOrg->id) {
            return response()->json('You are not allowed to accept this connection request.', 401);
        }

        $data = [
            'auth_organization' => $authOrg,
            'requester_organization' => $requesterOrganization,
            'connection_request' => $connectionRequest
        ];

        $service->acceptConnectionRequest($data);

        return response()->json('Connection request accepted');
    }
}
