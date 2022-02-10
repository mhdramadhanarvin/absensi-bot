<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use App\Models\UsersModel;
use Telegram\Bot\Commands\Command;
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
    public function handle($arguments)
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $fromTelegram = request()->message['chat'];
        $user = UsersModel::find($fromTelegram['id']);
        $name = $fromTelegram['username'];

        // $this->replyWithMessage(['text' => "Oke, $name bot sudah siap.."]);

        // $response = 'Petunjuk penggunaan:' . PHP_EOL;
        // $commands = $this->getTelegram()->getCommands();
        // foreach ($commands as $name => $command) {
        //     $response .= sprintf('/%s - %s' . PHP_EOL, $name, $command->getDescription());
        // }
        $keyboard2 = Telegram::KeyboardButton([
            'text'  => "A"
        ]);
        // dd(gettype($keyboard2));

        $keyboard = [
            // ['ℹ️ Info'],
            // ['Check IN', 'Check OUT'],
            [
                'text'  => "A",
                'url'   => 'https://google.com'
            ]
        ];

        $reply_markup = Telegram::ReplyKeyboardMarkup([
            'keyboard' => [$keyboard],
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        $response = Telegram::sendMessage([
            'chat_id' => 928840271,
            'text' => 'Hello World',
            'reply_markup' => $reply_markup
        ]);
    }
}
