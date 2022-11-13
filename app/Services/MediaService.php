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
        $authOrg = $this->organizationService->getAuthOrganization();
        if ($request->hasFile('list') && $request->file('list')->isValid()) {
            /** @var Media $media */
            $media = $authOrg->addMediaFromRequest('list')
                                  ->toMediaCollection('lists');

            $authOrg->has_list_uploaded = true;
            $authOrg->save();

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
