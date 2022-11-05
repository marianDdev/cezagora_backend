<?php

namespace App\Services;

use App\Models\Organization;
use App\Notifications\ConnectionRequestReceived;

class NotificationService
{
    private OrganizationService $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    public function notifyAboutConnectionRequestReceived($authUser, $receiverOrganizationId): void
    {
        $receiverOrganization      = Organization::find($receiverOrganizationId);
        $receiverOrganizationModel = $this->organizationService->getOrganizationTypeModel($receiverOrganization);
        $authOrganizationModel = $this->organizationService->getOrganizationByAuthUser();

        $email = [
            'receiver'           => $receiverOrganizationModel->name,
            'requester_org_type' => $authUser->organization->type,
            'requester_org_name' => $authOrganizationModel->name,
        ];

        $receiverOrganization->user->notify(new ConnectionRequestReceived($email));
    }
}
