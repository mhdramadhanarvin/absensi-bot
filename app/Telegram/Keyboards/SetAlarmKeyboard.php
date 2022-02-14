<?php

namespace App\Telegram\Keyboards;

use Telegram\Bot\Actions;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class SetAlarmKeyboard
{
    protected $to;

    public function __construct()
    {
        $webhook = Telegram::getWebhookUpdates();
        $to = $webhook->message->chat->id;

        $keyboard = [
            ['⛔ Batalkan']
        ];

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);

        $response = Telegram::sendMessage([
            'chat_id' => $to,
            'text' => 'Masukkan waktu',
            'reply_markup' => $reply_markup
        ]);
    }
}
