<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\ThreadResourceCollection;
use App\Models\Message;
use App\Models\Organization;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getMyThreads(ChatService $service): ThreadResourceCollection
    {
        $authOrg = $this->authOrganization();
        $myThreads = $service->getMyThreads($authOrg);

        return new ThreadResourceCollection($myThreads);
    }

    public function getMessagesByOtherOrganizationId(int $otherOrganizationId, ChatService $chatService): array
    {
        $interlocutor = Organization::find($otherOrganizationId);
        $interlocutorAvatar = $interlocutor->getFirstMediaUrl('profile_picture');
        $authOrganizationId = $this->authOrganization()->id;
        $thread             = $chatService->getThreadByOtherOrganizationId($authOrganizationId, $otherOrganizationId);

        $messagesArray           = [];

        if (!is_null($thread)) {
            $messages = Message::where('thread_id', $thread->id)->get();
            $messages->map(function ($message) use ($interlocutorAvatar) {
                $message->interlocutor_avatar = $interlocutorAvatar;
            });
            $messagesArray = $messages->toArray();
        }

        return $messagesArray;
    }

    public function sendMessage(StoreMessageRequest $request, ChatService $chatService): JsonResponse
    {
        $validated = $request->validated();

        $message             = $validated['message'];
        $otherOrganizationId = $validated['interlocutor_id'];
        $authOrganizationId  = $this->authOrganization()->id;

        event(new MessageEvent($otherOrganizationId, $message));

        $thread = $chatService->createThread($authOrganizationId, $otherOrganizationId);

        $message = $chatService->createMessage($authOrganizationId, $thread, $message);

        return response()->json(
            [
                'status'  => 'Message Sent.',
                'message' => $message,
            ]
        );
    }

    private function authOrganization(): Organization
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        return $authUser->organization;
    }
}
