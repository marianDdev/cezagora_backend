<?php

namespace App\Services;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
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
            $authOrg->addMediaFromRequest('list')
                    ->toMediaCollection('lists');

            $authOrg->has_list_uploaded = true;
            $authOrg->save();

        } else {
            throw new Exception('Your list was not uploaded.');
        }

        return response()->json(
            [
                'message' => 'Successfully uploaded.',
            ]
        );
    }

    public function uploadProfilePicture(Request $request): JsonResponse
    {
        $authOrg = $this->organizationService->getAuthOrganization();
        if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            if (!is_null($authOrg->getMedia('profile_picture'))) {
                /** @var Media $media */
                foreach ($authOrg->getMedia('profile_picture') as $media) {
                    $media->delete();
                }
            }
            $authOrg->addMediaFromRequest('profile_picture')
                    ->toMediaCollection('profile_picture');

            $authOrg->save();

        } else {
            throw new Exception('Your profile picture was not uploaded.');
        }

        return response()->json(
            [
                'message' => 'Successfully uploaded.',
            ]
        );
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     * @throws Exception
     */
    public function uploadBackgroundPicture(Request $request): JsonResponse
    {
        $authOrg = $this->organizationService->getAuthOrganization();
        if ($request->hasFile('background_picture') && $request->file('background_picture')->isValid()) {
            if (!is_null($authOrg->getMedia('background_picture'))) {
                /** @var Media $media */
                foreach ($authOrg->getMedia('background_picture') as $media) {
                    $media->delete();
                }
            }
            $authOrg->addMediaFromRequest('background_picture')
                    ->toMediaCollection('background_picture');

            $authOrg->save();

        } else {
            throw new Exception('Your profile picture was not uploaded.');
        }

        return response()->json(
            [
                'message' => 'Successfully uploaded.',
            ]
        );
    }
}
