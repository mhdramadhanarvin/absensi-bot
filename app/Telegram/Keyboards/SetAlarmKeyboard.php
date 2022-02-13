<?php

namespace App\Telegram\Keyboards;

use Telegram\Bot\Actions;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class SetAlarmKeyboard
{
    public function __construct()
    {
        $webhook = Telegram::getWebhookUpdates();
        $to = $webhook->message->chat->id;

        $keyboard = [
            ['⏰ Info Alarm', '⌛ History In & Out']
        ];

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);

        $response = Telegram::sendMessage([
            'chat_id' => $to,
            'text' => 'Choose the one',
            'reply_markup' => $reply_markup
        ]);
    }
}
