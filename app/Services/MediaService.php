<?php

namespace App\Services;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            $organizationTypeModel->addMediaFromRequest('list')
                                  ->toMediaCollection('lists');

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
}
