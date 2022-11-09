<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateOrganizationRequest;
use App\Http\Requests\UploadListRequest;
use App\Models\Organization;
use App\Services\NetworkingService;
use App\Services\OrganizationService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationController extends Controller
{
    public function update(UpdateOrganizationRequest $request, OrganizationService $service)
    {
        //check if auth user is distributor, retailer, manufacturer or wholesaler
        $organizationTypeModel = $service->getOrganizationByAuthUser();

        if (!is_null($organizationTypeModel)) {
            return $service->update($organizationTypeModel, $request->validated());
        }

        return response()->json(
            ['You are not allowed to update this organization'],
            401
        );
    }

    /**
     * @throws Exception
     */
    public function getOrganizationTypeModelByOrganizationId(
        OrganizationService $service,
        NetworkingService $networkingService,
        int $organizationId
    ): ?JsonResource
    {
        $organization = Organization::find($organizationId);
        $modelResource = $service->getModelResource($organization);

        $model = $service->getOrganizationTypeModel($organization);
        $networkingStatus = $networkingService->getNetworkingStatusByOrganizationId($organization->id);
        $lists = $model->getMedia('lists');

        $data = array_merge($model->toArray(), ['networking_status' => $networkingStatus, 'lists' => $lists]);

        return new $modelResource($data);
    }
}
