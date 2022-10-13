<?php

namespace App\Services;

use App\Models\Distributor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaService
{
    private OrganizationService $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    public function uploadFile(Request $request): JsonResponse
    {
        $organizationTypeModel = $this->organizationService->getOrganizationByAuthUser();
        $fileType = $request->get('file_type');
        $filesCollection = $fileType . 's';

        if ($request->hasFile($fileType) && $request->file($fileType)->isValid()) {
            $organizationTypeModel->addMediaFromRequest($fileType)->toMediaCollection($filesCollection);
            $organizationTypeModel->has_list_uploaded = true;
            $organizationTypeModel->save();
        }

        return response()->json(
          [
              'message' => 'Successfully uploaded.',
              'code' => 200
          ]
        );
    }

    public function replaceFile(Request $request, int $fileId): JsonResponse
    {
        $organizationTypeModel = $this->organizationService->getOrganizationByAuthUser();
        $file = Media::find($fileId);
        if ($file->model_id  === $organizationTypeModel->id) {
            $file->delete();
            $this->uploadFile($request);
        }

        return response()->json(
          [
              'message' => 'You are not allowed to replace this file.',
              'code' => 401
          ]
        );
    }
}
