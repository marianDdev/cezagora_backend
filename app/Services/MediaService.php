<?php

namespace App\Services;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            /** @var Media $media */
            $media = $organizationTypeModel->addMediaFromRequest('list')
                                  ->toMediaCollection('lists');

            $organizationTypeModel->has_list_uploaded = true;
            $organizationTypeModel->save();

            Storage::disk('cezagora_react')->put($media->file_name, $media->stream());
        } else {
            throw new Exception('Your list was not uploaded.');
        }

        return response()->json(
          [
              'message' => 'Successfully uploaded.',
          ]
        );
    }
}
