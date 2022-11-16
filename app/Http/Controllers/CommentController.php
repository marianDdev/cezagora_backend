<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentResourceCollection;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Services\OrganizationService;
use Illuminate\Http\JsonResponse;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class CommentController extends Controller
{
    public function getOne(int $id): CommentResource|JsonResponse
    {
        $comment = Comment::find($id);

        if (is_null($comment)) {
            return response()->json('Post not found.', 404);
        }

        return new CommentResource($comment);
    }

    public function list(int $postId): CommentResourceCollection
    {
        return new CommentResourceCollection(Comment::where('post_id', $postId)->get());
    }

    public function create(StoreCommentRequest $commentRequest, OrganizationService $organizationService): CommentResource
    {
        $author     = $organizationService->getAuthOrganization();
        $data       = array_merge($commentRequest->validated(), ['author_organization_id' => $author->id]);
        $newComment = Comment::create($data);

        if (
            $commentRequest->hasFile('comment_media')
            && $commentRequest->file('comment_media')->isValid()
        ) {
            $newComment->addMediaFromRequest('comment_media')
                       ->toMediaCollection('comment_media');
        }

        return new CommentResource($newComment);
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function update(
        UpdateCommentRequest $commentRequest,
        OrganizationService  $organizationService,
        int                  $id
    ): CommentResource|JsonResponse
    {
        $authOrg = $organizationService->getAuthOrganization();

        /** @var Comment $comment */
        $comment = Comment::find($id);

        if (is_null($comment)) {
            return response()->json('Comment not found.', 404);
        } else {
            if ($comment->author->id !== $authOrg->id) {
                return response()->json('You should update only your comments.', 401);
            }
        }

        foreach ($commentRequest->validated() as $column => $value) {
            if ($comment->hasAttribute($column)) {
                $comment->$column = $value;
            }
        }

        if (
            $commentRequest->hasFile('comment_media')
            && $commentRequest->file('comment_media')->isValid()
        ) {
            foreach ($comment->getMedia('comment_media') as $mediItem) {
                $mediItem->delete();
            }

            $comment->addMediaFromRequest('comment_media')
                    ->toMediaCollection('comment_media');
        }

        $comment->save();

        return new CommentResource($comment);
    }

    public function delete(OrganizationService $organizationService, int $id): JsonResponse
    {
        $authOrg = $organizationService->getAuthOrganization();
        $comment = Comment::find($id);

        if (!is_null($comment)) {
            if ($comment->author->id !== $authOrg->id) {
                return response()->json('You should update only your comments.', 401);
            }
        }

        foreach ($comment->getMedia('comment_media') as $mediItem) {
            $mediItem->delete();
        }

        $comment->delete();

        return response()->json('Successfully deleted');
    }
}
