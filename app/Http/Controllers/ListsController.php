<?php

namespace App\Http\Controllers;

use App\Services\MediaService;
use App\Services\OrganizationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ListsController extends Controller
{
    public function listAll(OrganizationService $service): JsonResponse
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

    /**
     * @throws Exception
     */
    public function upload(Request $request, MediaService $mediaService): JsonResponse
    {
        $mediaService->uploadList($request);

        return response()->json(
            [
                'message' => 'Successfully uploaded.',
            ]
        );
    }

    public function delete(string $uuid): JsonResponse
    {
        $list = Media::findByUuid($uuid);


        if (!is_null($list)) {
            $model = $list->model;

            $list->delete();

            if (count($model->getMedia('lists')) === 0) {
                $model->has_list_uploaded = false;
                $model->save();
            }

            return response()->json(['successfully deleted']);
        }

        return response()->json(["you can't delete this file."], 401);
    }
}
