<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostResourceCollection;
use App\Models\Post;
use App\Services\OrganizationService;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function getOne(int $id): PostResource|JsonResponse
    {
        $post = Post::find($id);

        if (is_null($post)) {
            return response()->json('Post not found.', 404);
        }

        return new PostResource($post);
    }

    public function list(): PostResourceCollection
    {
        return new PostResourceCollection(Post::orderBy('created_at', 'DESC')->get());
    }

    public function myPosts(OrganizationService $organizationService): PostResourceCollection
    {
        $authorg = $organizationService->getAuthOrganization();

        return new PostResourceCollection($authorg->posts);
    }

    public function create(StorePostRequest $postRequest, OrganizationService $organizationService): PostResource
    {
        $author  = $organizationService->getAuthOrganization();
        $data    = array_merge($postRequest->validated(), ['author_organization_id' => $author->id]);
        /** @var Post $newPost */
        $newPost = Post::create($data);

        if (
            $postRequest->hasFile('post_media')
            && $postRequest->file('post_media')->isValid()
        ) {
           $newPost->addMediaFromRequest('post_media')
                             ->toMediaCollection('post_media');
        }

        return new PostResource($newPost);
    }

    public function update(
        UpdatePostRequest $updatePostRequest,
        OrganizationService $organizationService,
        int $id
    ): PostResource|JsonResponse {
        $authOrg = $organizationService->getAuthOrganization();

        /** @var Post $post */
        $post = Post::find($id) ?? abort(404, 'Post not found');

        if (!is_null($post)) {
            if ($post->author->id !== $authOrg->id ) {
                return response()->json('You should update only your posts.', 401);
            }
        }

        foreach ($updatePostRequest->validated() as $column => $value) {
            if ($post->hasAttribute($column)) {
                $post->$column = $value;
            }
        }

        if (
            $updatePostRequest->hasFile('post_media')
            && $updatePostRequest->file('post_media')->isValid()
        ) {
            foreach ($post->getMedia('post_media') as $mediItem) {
                $mediItem->delete();
            }

            $post->addMediaFromRequest('post_media')
                 ->toMediaCollection('post_media');
        }

        $post->save();

        return new PostResource($post);
    }

    public function delete(OrganizationService $organizationService, int $id): JsonResponse
    {
        $authOrg = $organizationService->getAuthOrganization();
        $post = Post::find($id) ?? abort(404, 'Post not found');

        if (!is_null($post)) {
            if ($post->author->id !== $authOrg->id ) {
                return response()->json('You should update only your posts.', 401);
            }
        }

        foreach ($post->getMedia('post_media') as $mediItem) {
            $mediItem->delete();
        }

        $post->delete();

        return response()->json('Successfully deleted');
    }
}
