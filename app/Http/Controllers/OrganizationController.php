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

    public function uploadNewFile(Request $request, MediaService $mediaService): void
    {
        $mediaService->uploadFile($request);
    }

    public function replaceFile(Request $request, MediaService $mediaService, int $fileId)
    {
        $mediaService->replaceFile($request, $fileId);
    }

    public function deletFile(int $fileId): JsonResponse
    {
        $file = Media::find($fileId);

        if (!is_null($file)) {
            $file->delete();
            return response()->json(['successfully deleted']);
        }

        return response()->json(["you can't delete this file."], 401);
    }
}
