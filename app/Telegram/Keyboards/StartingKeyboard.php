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
            ['β° Info Alarm', 'βΊοΈ Set Alarm'],
            ['π’ Check In', 'π΄ Check Out'],
            ['β History In & Out'],
        ];

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);

        return $reply_markup;
    }
}
