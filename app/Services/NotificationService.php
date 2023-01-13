<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use App\Notifications\ConnectionRequestAccepted;
use App\Notifications\ConnectionRequestReceived;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    private OrganizationService $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    public function sendConnectionRequestReceivedEmail(Organization $receiver): void
    {
        $authOrganization = $this->organizationService->getAuthOrganization();

        $email = [
            'receiver'           => $receiver->name,
            'requester_org_type' => $authOrganization->type ?? '',
            'requester_org_name' => $authOrganization->name,
        ];

        $receiver->user->notify(new ConnectionRequestReceived($email));
    }

    public function sendConnectionRequestAcceptedEmail(
        string $requesterOrganizationName,
        string $authOrganizationName,
        string $authOrganizationType = null
    ): void
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        $authUser->notify(new ConnectionRequestAccepted($requesterOrganizationName, $authOrganizationName, $authOrganizationType));
    }
}
