<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use App\Notifications\ConnectionRequestReceived;

class NotificationService
{
    private OrganizationService $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    public function notifyAboutConnectionRequestReceived(int $receiverOrganizationId): void
    {
        $receiverOrganization      = Organization::find($receiverOrganizationId);
        $authOrganization = $this->organizationService->getAuthOrganization();

        $email = [
            'receiver'           => $receiverOrganization->name,
            'requester_org_type' => $authOrganization->type,
            'requester_org_name' => $authOrganization->name,
        ];

        $receiverOrganization->user->notify(new ConnectionRequestReceived($email));
    }
}
