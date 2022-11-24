<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
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

    public function getMessagesByOtherOrganizationId(int $otherOrganizationId, ChatService $chatService)
    {
        $authOrganizationId = $this->authOrganization()->id;
        $thread = $chatService->createThread($authOrganizationId, $otherOrganizationId);
    }

    public function sendMessage(Request $request, ChatService $chatService): JsonResponse
    {
        $message = $request->input('message');
        $otherOrganizationId = $request->input('otherOrganizationId');
        $authOrganizationId = $this->authOrganization()->id;

        event(new MessageEvent($message, $otherOrganizationId));

        $thread = $chatService->createThread($authOrganizationId, $otherOrganizationId);

        $message = $chatService->createMessage($authOrganizationId, $thread, $message);

        return response()->json(
            [
                'status' => 'Message Sent.',
                'message' => $message
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
