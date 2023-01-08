<?php

namespace App\Services;

use App\Http\Resources\ConnectionRequestResource;
use App\Http\Resources\ConnectionResource;
use App\Models\Connection;
use App\Models\ConnectionRequest;
use App\Models\Follower;
use App\Models\Organization;
use App\Models\User;
use App\Notifications\ConnectionRequestAccepted;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NetworkingService
{
    private OrganizationService $organizationService;
    private NotificationService $notificationService;

    public function __construct(OrganizationService $organizationService, NotificationService $notificationService)
    {
        $this->organizationService = $organizationService;
        $this->notificationService = $notificationService;
    }

    /**
     * @throws Exception
     */
    public function createConnectionRequest(Organization $authOrg, Organization $receiver): ConnectionRequest
    {
        $hasLists = $authOrg->getMedia('lists') !== null;

        if (!$authOrg->has_details_completed || !$hasLists) {
            throw new Exception("you can't make connection request if you didn't add your company details or didn't upload stock lists.");
        }

        $data = [
            'receiver_organization_id'  => $receiver->id,
            'requester_organization_id' => $authOrg->id,
        ];

        $newConnectionRequest = ConnectionRequest::create($data);

        $this->follow($authOrg->id, $receiver->id);

        $this->notificationService->notifyAboutConnectionRequestReceived($receiver);

        return $newConnectionRequest;
    }

    /**
     * once the connection request is accepted and the networking connection is established
     * the connection request will be deleted
     *
     * @param int $connectionRequestid
     *
     * @return ConnectionResource|JsonResponse
     */
    public function acceptConnectionRequest(int $connectionRequestid): ConnectionResource|JsonResponse
    {
        /** @var User $authUser */
        $authUser              = Auth::user();
        $authOrg               = $this->organizationService->getAuthOrganization();
        $connectionRequest     = ConnectionRequest::find($connectionRequestid);
        $requesterOrganization = Organization::find($connectionRequest->requester_organization_id);

        if ($connectionRequest->receiver_organization_id !== $authOrg->id) {
            return response()->json(['You are now allowed to accept this connection request.'], 401);
        }

        $connection = $this->createConnection(
            [
                'organization_id'           => $authOrg->id,
                'connected_organization_id' => $requesterOrganization->id,
            ]
        );


        $email = [
            'receiver'          => $requesterOrganization->name,
            'organization_type' => $authOrg->type,
            'organization_name' => $authOrg->name,
        ];


        $authUser->notify(new ConnectionRequestAccepted($email));
        $connectionRequest->delete();

        return $connection;
    }

    public function getFollowing(int $followedOrganizationId)
    {
        $followerOrganizationid = $this->organizationService->getAuthOrganization()->id;

        return Follower::where('follower_organization_id', $followerOrganizationid)
                       ->where('followed_organization_id', $followedOrganizationId)
                       ->first();
    }

    public function follow(int $authOrganizationid, int $followedOrganizationId): void
    {
        Follower::create(
            [
                'follower_organization_id' => $authOrganizationid,
                'followed_organization_id' => $followedOrganizationId,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function getNetworkingStatusByOrganizationId(int $organizationId): array
    {
        if (Organization::find($organizationId) === null) {
            throw new Exception('This is not a valid organization.');
        }

        $authOrganizationId = $this->organizationService->getAuthOrganization()->id;

        return [
            'connection_requested' => $this->isConnectionRequested($authOrganizationId, $organizationId),
            'connected'            => $this->isConnected($authOrganizationId, $organizationId),
            'followed'             => $this->isFollowed($authOrganizationId, $organizationId),
        ];

    }

    /**
     * @throws Exception
     */
    public function getNetworkingStatsByOrganizationId(int $organizationId)
    {
        if (Organization::find($organizationId) === null) {
            throw new Exception('This is not a valid organization.');
        }
    }

    private function isConnectionRequested(int $requesterOrganizationId, int $receiverOrganizationId): bool
    {
        return !is_null(
            ConnectionRequest::where('requester_organization_id', $requesterOrganizationId)
                             ->where('receiver_organization_id', $receiverOrganizationId)
                             ->first()
        );
    }

    private function isConnected(int $organizationId, int $connectedOrganizationId): bool
    {
        return
            !is_null(
                Connection::where('organization_id', $organizationId)
                          ->where('connected_organization_id', $connectedOrganizationId)
                          ->first()
            ) ||
            !is_null(
                Connection::where('organization_id', $connectedOrganizationId)
                          ->where('connected_organization_id', $organizationId)
                          ->first()
            );
    }

    private function isFollowed(int $followerOrganizationId, int $followedOrganizationId): bool
    {
        return !is_null(
            Follower::where('follower_organization_id', $followerOrganizationId)
                    ->where('followed_organization_id', $followedOrganizationId)
                    ->first()
        );
    }

    private function createConnection(array $data): ConnectionResource
    {
        return new ConnectionResource(Connection::create($data));
    }
}
