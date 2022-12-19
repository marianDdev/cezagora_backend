<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Models\Organization;
use App\Models\Thread;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getMyThreads(): Collection
    {
        $authOrg = $this->authOrganization();

        return Thread::where('first_organization_id', $authOrg->id)
                     ->orWhere('second_organization_id', $authOrg->id)
                     ->get();
    }

    public function getMessagesByOtherOrganizationId(int $otherOrganizationId, ChatService $chatService): array
    {
        $authOrganizationId = $this->authOrganization()->id;
        $thread             = $chatService->getThreadByOtherOrganizationId($authOrganizationId, $otherOrganizationId);
        $messages           = [];

        if (!is_null($thread)) {
            $messages = Message::where('thread_id', $thread->id)->get()->toArray();
        }

        return $messages;
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
