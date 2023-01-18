<?php

namespace App\Http\Controllers;

use App\Http\Requests\FollowRequest;
use App\Http\Resources\FollowerResourceCollection;
use App\Http\Resources\FollowingResourceCollection;
use App\Services\NetworkingService;
use App\Services\OrganizationService;
use Illuminate\Http\JsonResponse;

class FollowerController extends Controller
{
    public function followingList(OrganizationService $organizationService): FollowingResourceCollection
    {
        $authOrganization = $organizationService->getAuthOrganization();
        $followings = $authOrganization->followings;

        return new FollowingResourceCollection($followings);
    }

    public function followersList(OrganizationService $organizationService): FollowerResourceCollection
    {
        $authOrganization = $organizationService->getAuthOrganization();
        $followers = $authOrganization->followers;

        return new FollowerResourceCollection($followers);
    }

    public function follow(
        FollowRequest $request,
        OrganizationService $organizationService,
        NetworkingService $networkingService,
    ): JsonResponse
    {
        $validated = $request->validated();
        $authOrganizationid = $organizationService->getAuthOrganization()->id;
        $existingFollow = $networkingService->getFollowing($validated['organization_id']);

        if ($validated['organization_id'] === $authOrganizationid) {
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
