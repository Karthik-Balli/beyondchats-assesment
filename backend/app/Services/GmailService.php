<?php

namespace App\Services;

use Google\Client;
use Google\Service\Gmail;

class GmailService
{
    public function getClient($accessToken)
    {
        $client = new Client();

        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));

        $client->setAccessToken($accessToken);

        return $client;
    }

    public function getThreads($accessToken)
    {
        $client = $this->getClient($accessToken);

        $gmail = new Gmail($client);

        return $gmail->users_threads->listUsersThreads('me');
    }

    public function getThread($accessToken, $threadId)
    {
        $client = $this->getClient($accessToken);

        $gmail = new \Google\Service\Gmail($client);

        return $gmail->users_threads->get('me', $threadId);
    }
}