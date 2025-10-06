<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class TelegramHelper
{
    public static function sendMessage($message)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        if (!$token || !$chatId) {
            return false;
        }

        return Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML',
        ]);
    }
}
