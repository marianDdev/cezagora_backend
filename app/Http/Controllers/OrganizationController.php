<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateOrganizationRequest;
use App\Http\Requests\UploadListRequest;
use App\Http\Resources\OrganizationResource;
use App\Services\OrganizationService;

class OrganizationController extends Controller
{
    public function update(
        UpdateOrganizationRequest $request,
        OrganizationService $service
    ): OrganizationResource
    {
        $authOrg = $service->getAuthOrganization();
        $service->update($authOrg, $request->validated());

        return new OrganizationResource($authOrg);
    }
}
