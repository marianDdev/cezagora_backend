<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use App\Notifications\ConnectionRequestAccepted;
use App\Notifications\ConnectionRequestReceived;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    private CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function sendConnectionRequestReceivedEmail(Company $receiver): void
    {
        $authCompany = $this->companyService->getAuthCompany();

        $email = [
            'receiver'           => $receiver->name,
            'requester_org_type' => $authCompany->type ?? '',
            'requester_org_name' => $authCompany->name,
        ];

        $receiver->user->notify(new ConnectionRequestReceived($email));
    }

    public function sendConnectionRequestAcceptedEmail(
        string $requesterCompanyName,
        string $authCompanyName,
        string $authCompanyType = null
    ): void
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        $authUser->notify(new ConnectionRequestAccepted($requesterCompanyName, $authCompanyName, $authCompanyType));
    }
}
