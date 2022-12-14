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
            ['β° Info Alarm', 'βΊοΈ Set Alarm'],
            ['π’ Check IN', 'π΄ Check OUT'],
            ['β History In & Out'],
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
            'text' => 'Yahh kenapa dibatalin sih π',
            'reply_markup' => (new StartingKeyboard)->keyboard
        ]);
    }
}
