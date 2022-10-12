<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateOrganizationRequest;
use App\Services\OrganizationService;

class OrganizationController extends Controller
{
    public function update(UpdateOrganizationRequest $request, OrganizationService $service)
    {
        $organizationTypeModel = $service->getOrganizationByAuthUser();

        if (!is_null($organizationTypeModel)) {
            return $service->update($organizationTypeModel, $request->validated());
        }

        return response()->json(
            [
                'message' => 'You are not allowed to update this organization',
                'code' => 401
            ]
        );
    }
}
