<?php

namespace App\Http\Controllers;

use App\Services\NetworkingService;
use App\Services\OrganizationService;
use Illuminate\Http\JsonResponse;

class FollowerController extends Controller
{
    public function follow(
        OrganizationService $organizationService,
        NetworkingService $networkingService,
        int $followedOrganizationId
    ): JsonResponse
    {
        $authOrganizationid = $organizationService->getAuthOrganization()->id;
        $existingFollow = $networkingService->getFollowing($followedOrganizationId);

        if ($followedOrganizationId === $authOrganizationid) {
            return response()->json('You are trying to follow your own company.', 401);
        }

        if (!is_null($existingFollow)) {
            return response()->json('You are already following this company.', 401);
        }


        return response()->json('Successfully following.');
    }

    public function unFollow(NetworkingService $networkingService, int $followedOrganizationId): JsonResponse
    {
        $existingFollowing = $networkingService->getFollowing($followedOrganizationId);

        if (is_null($existingFollowing)) {
            return response()->json("This following doesn't exist.", 404);
        }

        $existingFollowing->delete();

        return response()->json("Successfully unfollowed.");
    }
}
