<?php

namespace App\Services;

use App\Models\Company;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

class ListsService
{
    private CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function getListsByCompanyId(int $companyId): MediaCollection
    {
        $company = Company::find($companyId);

        return $company->getMedia('lists');
    }
}
