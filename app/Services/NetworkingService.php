<?php

namespace App\Services;

use App\Http\Resources\ConnectionRequestResource;
use App\Http\Resources\ConnectionResource;
use App\Models\Connection;
use App\Models\ConnectionRequest;
use App\Models\Follower;
use App\Models\Following;
use App\Models\Organization;
use App\Models\User;
use App\Notifications\ConnectionRequestAccepted;
use App\Notifications\ConnectionRequestReceived;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NetworkingService
{
    private OrganizationService $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    /**
     * @throws Exception
     */
    public function createConnectionRequest(int $receiverId): ConnectionRequestResource
    {
        /** @var User $authUser */
        $authUser         = Auth::user();
        $authOrganizationModel = $this->organizationService->getOrganizationByAuthUser();
        $hasLists = $authOrganizationModel->getMedia('lists') !== null;

        if (!$authOrganizationModel->organization->has_details_completed || !$hasLists) {
            throw new Exception("you can't make connection request if you didn't add your compnay details or didn't upload stock lists." );
        }

        $receiverOrganization = Organization::find($receiverId);
        $receiverOrganizationModel = $this->organizationService->getOrganizationTypeModel($receiverOrganization);

        $data = [
            'receiver_id'                => $receiverId,
            'requester_id'      => $authUser->organization->id,
            'requester_type' => $authUser->organization->type,
            'name' => $authOrganizationModel->name
        ];

        $email = [
            'receiver' => $receiverOrganizationModel->name,
            'requester_org_type' => $authUser->organization->type,
            'requester_org_name' => $authOrganizationModel->name,
        ];

        $newConnectionRequest = ConnectionRequest::create($data);

        $this->createFollowing($receiverOrganization);

        $receiverOrganization->user->notify(new ConnectionRequestReceived($email));

        return new ConnectionRequestResource($newConnectionRequest);
    }

    /**
     * once the connection request is accepted and the networking connection is established
     * the connection request will be deleted
     *
     * @param int $id
     *
     * @return ConnectionResource|JsonResponse
     */
    public function acceptConnectionRequest(int $id): ConnectionResource|JsonResponse
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        $connectionRequest = ConnectionRequest::find($id);
        $requesterOrganization      = Organization::find($connectionRequest->organization_id);

        if ($connectionRequest->user_id !== $authUser->id) {
            return response()->json(['You are now allowed to accept this connection request.'], 401);
        }

        $requesterOrganizationDetailes = array_merge(['user_id' => $authUser->id], $this->extractOrganizationDetails($requesterOrganization));
        $connection           = $this->createConnection($requesterOrganizationDetailes);

        $authOrganizationModel = $this->organizationService->getOrganizationByAuthUser();

        $email = [
                'receiver' => $connection['name'],
                'organization_type' => $authUser->organization->type,
                'organization_name' => $authOrganizationModel->name
        ];


        $authUser->notify(new ConnectionRequestAccepted($email));
        $connectionRequest->delete();

        return $connection;
    }

    public function createFollowing(Organization $organization): void
    {
        $followingData = array_merge(['user_id' => Auth::user()->id], $this->extractOrganizationDetails($organization));
        Following::create($followingData);

        $authOrganizationModel = $this->organizationService->getOrganizationByAuthUser();
        $followerData          = array_merge(['user_id' => $organization->user->id], $this->extractOrganizationDetails($authOrganizationModel->organization));
        Follower::create($followerData);
    }

    private function extractOrganizationDetails(Organization $organization): array
    {
        $model = $this->organizationService->getOrganizationTypeModel($organization);

        return [
            'organization_id'   => $organization->id,
            'organization_type' => $organization->type,
            'name'              => $model->name,
            'continent'         => $model->continent,
            'country'           => $model->country,
            'city'              => $model->city,
        ];
    }

    private function createConnection(array $data): ConnectionResource
    {
        return new ConnectionResource(Connection::create($data));
    }
}
