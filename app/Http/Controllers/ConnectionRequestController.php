<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcceptConnectionRequest;
use App\Http\Requests\StoreConnectionInvitationRequest;
use App\Http\Resources\ConnectionRequestResource;
use App\Http\Resources\ConnectionRequestResourceCollection;
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
        $authOrg  = $organizationService->getAuthOrganization();
        $requests = $authOrg->connectionRequestsReceived->sortByDesc('created_at');

        return new ConnectionRequestResourceCollection($requests);
    }

    public function send(
        StoreConnectionInvitationRequest $request,
        NetworkingService                $service,
        OrganizationService              $organizationService
    ): ConnectionRequestResource|JsonResponse
    {
        $validated = $request->validated();
        $receiver  = Organization::find($validated['receiver_id']);
        $authOrg   = $organizationService->getAuthOrganization();

        if (is_null($receiver)) {
            return response()->json('Company not found.', 404);
        }

        $existingrequest = ConnectionRequest::where('receiver_organization_id', $receiver->id)
                                            ->where('requester_organization_id', $authOrg->id)
                                            ->first();

        $hasLists = $authOrg->getMedia('lists') !== null;

        if (!$authOrg->has_details_completed || !$hasLists) {
            return response()->json("you can't make connection request if you didn't add your company details or didn't upload stock lists.");
        }

        if (!is_null($existingrequest)) {
            return response()->json('You already invited this company to your network.', 401);
        }

        $service->createConnectionRequest($authOrg, $receiver);

        return response()->json('Connection request sent.');
    }

    public function accept(
        AcceptConnectionRequest $request,
        NetworkingService       $service,
        OrganizationService     $organizationService
    ): JsonResponse
    {
        $validated         = $request->validated();
        $authOrg           = $organizationService->getAuthOrganization();
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
            'auth_organization'      => $authOrg,
            'requester_organization' => $requesterOrganization,
            'connection_request'     => $connectionRequest,
        ];

        $service->acceptConnectionRequest($data);

        return response()->json('Connection request accepted.');
    }

    public function decline(int $requestId): JsonResponse
    {
        $connectionRequest = ConnectionRequest::find($requestId);

        if (is_null($connectionRequest)) {
            return response()->json('Connection request not found.', 404);
        }

        $connectionRequest->delete();

        return response()->json('Connection request declined.');
    }
}
