<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateOrganizationRequest;
use App\Http\Requests\UploadListRequest;
use App\Services\MediaService;
use App\Services\OrganizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class OrganizationController extends Controller
{
    public function update(UpdateOrganizationRequest $request, OrganizationService $service)
    {
        $organizationTypeModel = $service->getOrganizationByAuthUser();

        if (!is_null($organizationTypeModel)) {
            return $service->update($organizationTypeModel, $request->validated());
        }

        return response()->json(
            ['You are not allowed to update this organization'],
            401
        );
    }

    public function getList(string $uuid): Media
    {
        return Media::findByUuid($uuid);
    }

    public function getLists(OrganizationService $service)
    {
        $organizationTypeModel = $service->getOrganizationByAuthUser();

        if (!is_null($organizationTypeModel)) {
           return $organizationTypeModel->getMedia('lists');
        }

        return response()->json(
            ['You are not allowed to update this organization'],
            401
        );
    }

    public function uploadNewList(Request $request, MediaService $mediaService): JsonResponse
    {
        $mediaService->uploadList($request);

        return response()->json(
            [
                'message' => 'Successfully uploaded.',
            ]
        );
    }

//    public function replaceList(Request $request, MediaService $mediaService, int $listId)
//    {
//        $mediaService->replaceList($request, $listId);
//    }

    public function deletList(string $uuid): JsonResponse
    {
        $list = Media::findByUuid($uuid);

        if (!is_null($list)) {
            $list->delete();
            return response()->json(['successfully deleted']);
        }

        return response()->json(["you can't delete this file."], 401);
    }
}
