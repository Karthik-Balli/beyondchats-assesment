<?php

namespace App\Services;

use App\Models\Thread;
use App\Models\Message;

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

        foreach ($threads->getThreads() as $threadData) {

            $threadId = $threadData->getId();

            $thread = Thread::updateOrCreate(
                ['gmail_thread_id' => $threadId],
                [
                    'subject' => 'Email Thread',
                    'last_message_at' => now()
                ]
            );

            // fetch full thread
            $gmailThread = $this->gmailService->getThread($accessToken, $threadId);

            foreach ($gmailThread->getMessages() as $gmailMessage) {

                $headers = $gmailMessage->getPayload()->getHeaders();

                $from = $this->getHeader($headers, 'From');
                $to = $this->getHeader($headers, 'To');
                $body = $this->extractBody($gmailMessage->getPayload());
                $subject = $this->getHeader($headers, 'Subject');
                $date = $this->getHeader($headers, 'Date');

                $message = Message::updateOrCreate(
                    ['gmail_message_id' => $gmailMessage->getId()],
                    [
                        'thread_id' => $thread->id,
                        'sender' => $from,
                        'receiver' => $to,
                        'body_html' => $body,
                        'sent_at' => $date
                    ]
                );
                
                $attachments = $this->extractAttachments(
                    $gmailMessage->getPayload()->getParts()
                );
                foreach ($attachments as $attachment) {

                    Attachment::create([
                        'message_id' => $message->id,
                        'filename' => $attachment['filename'],
                        'mime_type' => $attachment['mime_type']
                    ]);
                }
                
            }
        }
    }

    private function getHeader($headers, $name)
    {
        foreach ($headers as $header) {
            if ($header->getName() === $name) {
                return $header->getValue();
            }
        }

        return null;
    }

    private function extractBody($payload)
    {
        if ($payload->getBody()->getData()) {
            return base64_decode(
                strtr($payload->getBody()->getData(), '-_', '+/')
            );
        }

        if ($payload->getParts()) {

            foreach ($payload->getParts() as $part) {

                if ($part->getMimeType() === 'text/html') {

                    return base64_decode(
                        strtr($part->getBody()->getData(), '-_', '+/')
                    );
                }
            }
        }

        return null;
    }

    private function extractAttachments($parts)
    {
        $attachments = [];

        foreach ($parts as $part) {

            if ($part->getFilename()) {

                $attachments[] = [
                    'filename' => $part->getFilename(),
                    'mime_type' => $part->getMimeType(),
                    'attachment_id' => $part->getBody()->getAttachmentId()
                ];
            }
        }

        return $attachments;
    }
}