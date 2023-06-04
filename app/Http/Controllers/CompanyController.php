<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Requests\UploadListRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\MediaService;
use App\Services\NetworkingService;
use App\Services\CompanyService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function update(
        UpdateCompanyRequest $request,
        CompanyService       $service
    ): CompanyResource
    {
        $authOrg = $service->getAuthCompany();
        $service->update($authOrg, $request->validated());

        return new CompanyResource($authOrg);
    }

    /**
     * @throws Exception
     */
    public function getDataByCompanyId(NetworkingService $networkingService, int $companyId): array
    {
        $company     = Company::find($companyId);
        $networkingStatus = $networkingService->getNetworkingStatusByCompanyId($company->id);
        $lists            = $company->getMedia('lists');

        return [
            'company'      => $company,
            'networking_status' => $networkingStatus,
            'lists'             => $lists,
            "avatar" => $company->getFirstMediaUrl('profile_picture') ?? null,
            "background" => $company->getFirstMediaUrl('background_picture') ?? null,
        ];
    }

    public function getProfilePictureUrl(CompanyService $service):? string
    {
        $authorg = $service->getAuthCompany();

        return $authorg->getFirstMediaUrl('profile_picture');
    }

    public function getOtherProfilePictureUrl(int $companyId): string
    {
        $org = Company::find($companyId);

        return $org->getFirstMediaUrl('profile_picture');
    }

    public function getBackgroundPictureUrl(CompanyService $service): ?string
    {
        $authorg = $service->getAuthCompany();

        return $authorg->getFirstMediaUrl('background_picture');
    }

    public function getOtherBackgroundPictureUrl(int $companyId): ?string
    {
        $org = Company::find($companyId);

        return $org->getFirstMediaUrl('background_picture');
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
