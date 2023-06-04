<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Company;
use App\Models\Thread;
use Illuminate\Support\Collection;

class ChatService
{
    public function createThread(int $authCompanyId, int $otherCompanyId): Thread
    {
        $thread = $this->getThreadByOtherCompanyId($authCompanyId, $otherCompanyId);

        if (is_null($thread)) {
            $thread = Thread::create(['first_company_id' => $authCompanyId, 'second_company_id' => $otherCompanyId]);
        }

        return $thread;
    }

    public function createMessage(int $authCompanyId, Thread $thread,  string $message): Message
    {
        return Message::create(
            [
                'author_id' => $authCompanyId,
                'thread_id' => $thread->id,
                'body' => $message,
            ]
        );
    }

    public function getThreadByOtherCompanyId(int $authCompanyId, int $otherCompanyId): ?Thread
    {
        return Thread::where(
            function ($query) use ($authCompanyId, $otherCompanyId) {
                $query->where(
                    function ($q) use ($authCompanyId, $otherCompanyId) {
                        $q->where('first_company_id', $authCompanyId)
                          ->where('second_company_id', $otherCompanyId);
                    }
                )
                      ->orWhere(
                          function ($q) use ($authCompanyId, $otherCompanyId) {
                              $q->where('first_company_id', $otherCompanyId)
                                ->where('second_company_id', $authCompanyId);
                          }
                      );
            }
        )->first();
    }

    public function getMyThreads(Company $authOrg): Collection
    {
        $myThreads = Thread::where('first_company_id', $authOrg->id)
              ->orWhere('second_company_id', $authOrg->id)
              ->get();

        $this->mapExtraFieldsToThread($myThreads, $authOrg);

        return $myThreads;
    }

    private function mapExtraFieldsToThread(Collection $myThreads, Company $authOrg): void
    {
        $myThreads->map(function ($thread) use ($authOrg) {
            $myCompany = Company::find($thread->first_company_id);
            $interlocutor = Company::find($thread->second_company_id);

            if ($thread->second_company_id === $authOrg->id) {
                $myCompany = Company::find($thread->second_company_id);
                $interlocutor = Company::find($thread->first_company_id);
            }

            $myAvatarUrl = $myCompany->getFirstMediaUrl('profile_picture');
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
