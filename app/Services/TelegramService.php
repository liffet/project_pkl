<?php

namespace App\Services;

use GuzzleHttp\Client;

class TelegramService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(config('services.guzzle'));
    }

    public function sendMessage($message)
    {
        return $this->client->post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [
            'form_params' => [
                'chat_id' => env('TELEGRAM_CHAT_ID'),
                'text' => $message,
            ]
        ]);
    }
}
