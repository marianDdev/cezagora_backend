<?php

namespace App\Services;

use App\Http\Resources\ConnectionRequestResource;
use App\Http\Resources\ConnectionResource;
use App\Models\Connection;
use App\Models\ConnectionRequest;
use App\Models\Follower;
use App\Models\Company;
use App\Models\User;
use App\Notifications\ConnectionRequestAccepted;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NetworkingService
{
    private CompanyService $companyService;
    private NotificationService $notificationService;

    public function __construct(CompanyService $companyService, NotificationService $notificationService)
    {
        $this->companyService = $companyService;
        $this->notificationService = $notificationService;
    }

    public function createConnectionRequest(Company $authOrg, Company $receiver): void
    {
        $data = [
            'receiver_company_id'  => $receiver->id,
            'requester_company_id' => $authOrg->id,
        ];

        ConnectionRequest::create($data);

        $this->follow($authOrg->id, $receiver->id);

        $this->notificationService->sendConnectionRequestReceivedEmail($receiver);
    }

    public function acceptConnectionRequest(array $data): void
    {
        $authOrg               = $data['auth_company'];
        $connectionRequest     = $data['connection_request'];
        $requesterCompany = $data['requester_company'];


        //todo check if the connection already exists
        $this->createConnection(
            [
                'company_id'           => $authOrg->id,
                'connected_company_id' => $requesterCompany->id,
            ]
        );

        $this->notificationService->sendConnectionRequestAcceptedEmail(
            $requesterCompany->name,
            $authOrg->name,
            $authOrg->type
        );

        $connectionRequest->delete();
    }

    public function getFollowing(int $followedCompanyId)
    {
        $followerCompanyid = $this->companyService->getAuthCompany()->id;

        return Follower::where('follower_company_id', $followerCompanyid)
                       ->where('followed_company_id', $followedCompanyId)
                       ->first();
    }

    public function follow(int $authCompanyid, int $followedCompanyId): void
    {
        Follower::create(
            [
                'follower_company_id' => $authCompanyid,
                'followed_company_id' => $followedCompanyId,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function getNetworkingStatusByCompanyId(int $companyId): array
    {
        if (Company::find($companyId) === null) {
            throw new Exception('This is not a valid company.');
        }

        $authCompanyId = $this->companyService->getAuthCompany()->id;

        return [
            'connection_requested' => $this->isConnectionRequested($authCompanyId, $companyId),
            'connected'            => $this->isConnected($authCompanyId, $companyId),
            'followed'             => $this->isFollowed($authCompanyId, $companyId),
        ];

    }

    /**
     * @throws Exception
     */
    public function getNetworkingStatsByCompanyId(int $companyId)
    {
        if (Company::find($companyId) === null) {
            throw new Exception('This is not a valid company.');
        }
    }

    private function isConnectionRequested(int $requesterCompanyId, int $receiverCompanyId): bool
    {
        return !is_null(
            ConnectionRequest::where('requester_company_id', $requesterCompanyId)
                             ->where('receiver_company_id', $receiverCompanyId)
                             ->first()
        );
    }

    private function isConnected(int $companyId, int $connectedCompanyId): bool
    {
        return
            !is_null(
                Connection::where('company_id', $companyId)
                          ->where('connected_company_id', $connectedCompanyId)
                          ->first()
            ) ||
            !is_null(
                Connection::where('company_id', $connectedCompanyId)
                          ->where('connected_company_id', $companyId)
                          ->first()
            );
    }

    private function isFollowed(int $followerCompanyId, int $followedCompanyId): bool
    {
        return !is_null(
            Follower::where('follower_company_id', $followerCompanyId)
                    ->where('followed_company_id', $followedCompanyId)
                    ->first()
        );
    }

    private function createConnection(array $data): ConnectionResource
    {
        return new ConnectionResource(Connection::create($data));
    }
}
