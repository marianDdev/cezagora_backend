<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Thread;

class ChatService
{
    public function createThread(int $authOrganizationId, int $otherOrganizationId): Thread
    {
        $thread = $this->getThreadByOtherOrganizationId($authOrganizationId, $otherOrganizationId);

        if (is_null($thread)) {
            $thread = Thread::create(['first_organization_id' => $authOrganizationId, 'second_organization_id' => $otherOrganizationId]);
        }

        return $thread;
    }

    public function createMessage(int $authOrganizationId, Thread $thread,  string $message): Message
    {
        return Message::create(
            [
                'author_id' => $authOrganizationId,
                'thread_id' => $thread->id,
                'body' => $message,
            ]
        );
    }

    public function getThreadByOtherOrganizationId(int $authOrganizationId, int $otherOrganizationId): ?Thread
    {
        return Thread::where(
            function ($query) use ($authOrganizationId, $otherOrganizationId) {
                $query->where(
                    function ($q) use ($authOrganizationId, $otherOrganizationId) {
                        $q->where('first_organization_id', $authOrganizationId)
                          ->where('second_organization_id', $otherOrganizationId);
                    }
                )
                      ->orWhere(
                          function ($q) use ($authOrganizationId, $otherOrganizationId) {
                              $q->where('first_organization_id', $otherOrganizationId)
                                ->where('second_organization_id', $authOrganizationId);
                          }
                      );
            }
        )->first();
    }
}
