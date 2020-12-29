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
        $id = $request->message['chat']['id'];
        try {
            $response = Telegram::getCommands();
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'error' => $e
            ]);
        }

        return response()->json($response);
        // dd($response);
    }
}
