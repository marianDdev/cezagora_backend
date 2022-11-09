<?php

namespace App\Services;

use App\Models\Organization;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
        $orgTypeModel = $this->organizationService->getOrganizationTypeModel($organization);

        return $orgTypeModel->getMedia('lists');
    }
}
