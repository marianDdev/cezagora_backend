<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\ThreadResourceCollection;
use App\Models\Message;
use App\Models\Company;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getMyThreads(ChatService $service): ThreadResourceCollection
    {
        $authOrg = $this->authCompany();
        $myThreads = $service->getMyThreads($authOrg);

        return new ThreadResourceCollection($myThreads);
    }

    public function getMessagesByOtherCompanyId(int $otherCompanyId, ChatService $chatService): array
    {
        $interlocutor = Company::find($otherCompanyId);
        $interlocutorAvatar = $interlocutor->getFirstMediaUrl('profile_picture');
        $authCompanyId = $this->authCompany()->id;
        $thread             = $chatService->getThreadByOtherCompanyId($authCompanyId, $otherCompanyId);

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
        $otherCompanyId = $validated['interlocutor_id'];
        $authCompanyId  = $this->authCompany()->id;

        event(new MessageEvent($otherCompanyId, $message));

        $thread = $chatService->createThread($authCompanyId, $otherCompanyId);

        $message = $chatService->createMessage($authCompanyId, $thread, $message);

        return response()->json(
            [
                'status'  => 'Message Sent.',
                'message' => $message,
            ]
        );
    }

    private function authCompany(): Company
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        return $authUser->company;
    }
}
