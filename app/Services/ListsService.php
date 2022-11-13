<?php

namespace App\Services;

use App\Models\Organization;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

class ListsService
{
    private OrganizationService $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    public function getListsByOrganizationId(int $organizationId): MediaCollection
    {
        $organization = Organization::find($organizationId);

        return $organization->getMedia('lists');
    }
}
