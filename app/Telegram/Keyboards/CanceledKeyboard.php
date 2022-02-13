<?php

namespace App\Telegram\Keyboards;

use Telegram\Bot\Actions;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Laravel\Facades\Telegram;

class CanceledKeyboard
{
    public function __construct($to)
    {
        $keyboard = [
            ['⏰ Info Alarm', '⏺️ Set Alarm'],
            ['🟢 Check IN', '🔴 Check OUT'],
            ['⌛ History In & Out'],
        ];

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);
        Cache::forget($to);
        Telegram::sendChatAction([
            'chat_id'   => $to,
            'action'    => Actions::TYPING
        ]);
        $response = Telegram::sendMessage([
            'chat_id' => $to,
            'text' => 'Yahh kenapa dibatalin sih 😔',
            'reply_markup' => (new StartingKeyboard)->keyboard
        ]);
    }
}
