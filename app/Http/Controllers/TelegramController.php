<?php

namespace App\Http\Controllers;

use Telegram\Bot\Api;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    private $telegram;

    public function __construct()
    {
        $this->telegram = new Api(config('telegram.bots.mybot.token'));
    }

    public function index()
    {
        // $command = new \App\Telegram\Commands\StartCommand;
        Telegram::addCommands([
            \App\Telegram\Commands\StartCommand::class,
            \Telegram\Bot\Commands\HelpCommand::class
        ]);
    }

    public function webhook(Request $request)
    {
        // try {
        //     $response = Telegram::sendPhoto([
        //         'chat_id' => '928840271',
        //         'photo' => 'https://www.pixelstalk.net/wp-content/uploads/2016/04/Google-images-free-download-620x388.jpg',
        //         'caption' => 'Stream Gaming',
        //         // 'text' => 'Stream Gaming'
        //     ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => $e->getMessage(),
        //         'error' => $e
        //     ]);
        // }
        $update = Telegram::commandsHandler(true);
    }
}
