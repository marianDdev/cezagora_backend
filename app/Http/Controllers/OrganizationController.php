<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateOrganizationRequest;
use App\Http\Requests\UploadListRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Services\MediaService;
use App\Services\NetworkingService;
use App\Services\OrganizationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function update(
        UpdateOrganizationRequest $request,
        OrganizationService       $service
    ): OrganizationResource
    {
        $authOrg = $service->getAuthOrganization();
        $service->update($authOrg, $request->validated());

        return new OrganizationResource($authOrg);
    }

    /**
     * @throws Exception
     */
    public function getDataByOrganizationId(NetworkingService $networkingService, int $organizationId): array
    {
        $organization     = Organization::find($organizationId);
        $networkingStatus = $networkingService->getNetworkingStatusByOrganizationId($organization->id);
        $lists            = $organization->getMedia('lists');

        return [
            'organization'      => $organization,
            'networking_status' => $networkingStatus,
            'lists'             => $lists,
        ];
    }

    /**
     * @throws Exception
     */
    public function uploadProfilePicture(Request $request, MediaService $mediaService): JsonResponse
    {
        $mediaService->uploadProfilePicture($request);

        return response()->json(
            [
                'message' => 'Successfully uploaded.',
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function uploadBackgroundPicture(Request $request, MediaService $mediaService): JsonResponse
    {
        $mediaService->uploadBackgroundPicture($request);

        return response()->json(
            [
                'message' => 'Successfully uploaded.',
            ]
        );
    }
}
