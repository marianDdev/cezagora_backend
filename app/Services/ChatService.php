<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Organization;
use App\Models\Thread;
use Illuminate\Support\Collection;

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

    public function getMyThreads(Organization $authOrg): Collection
    {
        $myThreads = Thread::where('first_organization_id', $authOrg->id)
              ->orWhere('second_organization_id', $authOrg->id)
              ->get();

        $this->mapExtraFieldsToThread($myThreads, $authOrg);

        return $myThreads;
    }

    private function mapExtraFieldsToThread(Collection $myThreads, Organization $authOrg): void
    {
        $myThreads->map(function ($thread) use ($authOrg) {
            $myOrganization = Organization::find($thread->first_organization_id);
            $interlocutor = Organization::find($thread->second_organization_id);

            if ($thread->second_organization_id === $authOrg->id) {
                $myOrganization = Organization::find($thread->second_organization_id);
                $interlocutor = Organization::find($thread->first_organization_id);
            }

            $myAvatarUrl = $myOrganization->getFirstMediaUrl('profile_picture');
            $interlocutorAvatarUrl = $interlocutor->getFirstMediaUrl('profile_picture');
            $interlocutorAddress = '';
            if (!is_null($interlocutor->city)) {
                $interlocutorAddress .= $interlocutor->city;
            }
            if (!is_null($interlocutor->country)) {
                $interlocutorAddress .= ', ' . $interlocutor->country;
            }

            $thread->myAvatarUrl = $myAvatarUrl;
            $thread->interlocutorAvatarUrl = $interlocutorAvatarUrl;
            $thread->interlocutorName = $interlocutor->name;
            $thread->interlocutorId = $interlocutor->id;
            $thread->interlocutorAddress = $interlocutorAddress;
            $thread->interlocutorPhone = $interlocutor->phone ?? '';
            $thread->companyTypes = $interlocutor->company_types ? implode(', ', $interlocutor->company_types) : '';
        });
    }
}
