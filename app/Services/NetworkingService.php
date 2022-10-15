<?php

namespace App\Services;

use App\Http\Resources\ConnectionRequestResource;
use App\Http\Resources\ConnectionResource;
use App\Http\Resources\DistributorResource;
use App\Models\Connection;
use App\Models\ConnectionRequest;
use App\Models\Organization;
use App\Models\User;
use App\Notifications\ConnectionRequestAccepted;
use App\Notifications\ConnectionRequestReceived;
use Illuminate\Support\Facades\Auth;

class NetworkingService
{
    private OrganizationService $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    public function createConnectionRequest(array $validated): ConnectionRequestResource
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $authOrganization = $this->organizationService->getOrganizationByAuthUser();

        $data = array_merge(
            [
                'user_id' => $authUser->id,
                'auth_organization' => $authOrganization->name
            ], $validated
        );
        $newConnectionRequest = ConnectionRequest::create($data);

        $authUser->notify(new ConnectionRequestReceived($data));

        return new ConnectionRequestResource($newConnectionRequest);
    }

    /**
     * once the connection request is accepted and the networking connection is established
     * the connection request will be deleted
     *
     * @param int $id
     *
     * @return ConnectionResource
     */
    public function acceptConnectionRequest(int $id): ConnectionResource
    {
        $connectionRequest = ConnectionRequest::find($id);
        $organizationDetailes =  $this->extractOrganizationDetails($connectionRequest);
        $connection = $this->createConnection($organizationDetailes);

        $connectedOrganizationUser = $this->extractUserToNotify($organizationDetailes['organization_id']);

        $emailData = $this->composeEmailData($connection);
        $connectedOrganizationUser->notify(new ConnectionRequestAccepted($emailData));

        $connectionRequest->delete();

        return $connection;
    }

    private function extractOrganizationDetails(ConnectionRequest $connectionRequest): array
    {
        $organization = Organization::find($connectionRequest->organization_id);
        $model = $this->organizationService->getOrganizationTypeModel($organization);

        return [
            'user_id' => Auth::user()->id,
            'organization_id' => $connectionRequest->organization_id,
            'organization_type' => $connectionRequest->organization_type,
            'name' => $connectionRequest->name,
            'continent' => $model->continent,
            'country' => $model->country,
            'city' => $model->city,
        ];
    }

    private function createConnection(array $data): ConnectionResource
    {
        return new ConnectionResource(Connection::create($data));
    }

    private function extractUserToNotify(int $organizationId): User
    {
        /** @var User $user */
        $user = Organization::find($organizationId)->user;

        return $user;
    }

    private function composeEmailData(ConnectionResource $connection): array
    {
        $authOrganization = $this->organizationService->getOrganizationByAuthUser();

        return [
          'receiver' => $connection->name,
          'organization_type' => $connection->organization_type,
          'auth_organization_name' => $authOrganization->name
        ];
    }
}
