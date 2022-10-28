<?php

namespace App\Services;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaService
{
    private OrganizationService $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    /**
     * @throws Exception
     */
    public function uploadList(Request $request): JsonResponse
    {
        $organizationTypeModel = $this->organizationService->getOrganizationByAuthUser();

        if ($request->hasFile('list') && $request->file('list')->isValid()) {
            $organizationTypeModel->addMediaFromRequest('list')->toMediaCollection('lists');
            $organizationTypeModel->has_list_uploaded = true;
            $organizationTypeModel->save();
        } else {
            throw new Exception('Your list was not uploaded.');
        }

        return response()->json(
          [
              'message' => 'Successfully uploaded.',
          ]
        );
    }

    public function replaceList(Request $request, int $fileId): JsonResponse
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
