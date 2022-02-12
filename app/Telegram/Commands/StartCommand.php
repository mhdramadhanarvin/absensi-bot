<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use App\Models\UsersModel;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "start";

    /**
     * @var string Command Description
     */
    protected $description = "Start Command to get you started";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $fromTelegram = request()->message['chat'];

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

        $response = $this->replyWithMessage([
            'chat_id' => $fromTelegram['id'],
            'text' => 'Hola, selamat datang di Bot, pilih salah satu',
            'reply_markup' => $reply_markup
        ]);
    }
}
