<?php

namespace App\Http\Controllers;

use App\Http\Requests\FollowRequest;
use App\Http\Resources\FollowerResourceCollection;
use App\Http\Resources\FollowingResourceCollection;
use App\Services\NetworkingService;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;

class FollowerController extends Controller
{
    public function followingList(CompanyService $companyService): FollowingResourceCollection
    {
        $authCompany = $companyService->getAuthCompany();
        $followings = $authCompany->followings;

        return new FollowingResourceCollection($followings);
    }

    public function followersList(CompanyService $companyService): FollowerResourceCollection
    {
        $authCompany = $companyService->getAuthCompany();
        $followers = $authCompany->followers;

        return new FollowerResourceCollection($followers);
    }

    public function follow(
        FollowRequest $request,
        CompanyService $companyService,
        NetworkingService $networkingService,
    ): JsonResponse
    {
        $validated = $request->validated();
        $authCompanyid = $companyService->getAuthCompany()->id;
        $existingFollow = $networkingService->getFollowing($validated['company_id']);

        if ($validated['company_id'] === $authCompanyid) {
            return response()->json('You are trying to follow your own company.', 401);
        }

        if (!is_null($existingFollow)) {
            return response()->json('You are already following this company.', 401);
        }


        return response()->json('Successfully following.');
    }

    public function unFollow(NetworkingService $networkingService, int $followedCompanyId): JsonResponse
    {
        $existingFollowing = $networkingService->getFollowing($followedCompanyId);

        if (is_null($existingFollowing)) {
            return response()->json("This following doesn't exist.", 404);
        }

        $existingFollowing->delete();

        return response()->json("Successfully unfollowed.");
    }
}
