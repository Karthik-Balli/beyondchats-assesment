<?php

namespace App\Services;

use App\Models\Thread;

class EmailSyncService
{
    protected $gmailService;

    public function __construct(GmailService $gmailService)
    {
        $this->gmailService = $gmailService;
    }

    public function syncEmails($accessToken)
    {
        $threads = $this->gmailService->getThreads($accessToken);

        foreach ($threads->getThreads() as $thread) {

            Thread::updateOrCreate(
                ['gmail_thread_id' => $thread->getId()],
                [
                    'subject' => 'Email Thread',
                    'last_message_at' => now()
                ]
            );
        }
    }
}