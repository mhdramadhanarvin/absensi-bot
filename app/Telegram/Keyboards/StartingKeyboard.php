<?php

namespace App\Telegram\Keyboards;

use Telegram\Bot\Keyboard\Keyboard;

class StartingKeyboard
{
    public $keyboard;

    public function __construct()
    {
        $this->keyboard = $this->keyboard();
    }


    public function keyboard()
    {
        $keyboard = [
            ['⏰ Info Alarm', '⏺️ Set Alarm'],
            ['🟢 Check In', '🔴 Check Out'],
            ['⌛ History In & Out'],
        ];

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);

        return $reply_markup;
    }
}
