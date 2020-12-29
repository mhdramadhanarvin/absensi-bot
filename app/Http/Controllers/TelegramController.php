<?php

namespace App\Http\Controllers;

use Telegram\Bot\Api;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{

    public function __construct()
    {
        Telegram::commandsHandler(true);
    }
    // public function index()
    // {
    //     // $command = new \App\Telegram\Commands\StartCommand;
    //     Telegram::addCommands([
    //         \App\Telegram\Commands\StartCommand::class,
    //         \Telegram\Bot\Commands\HelpCommand::class
    //     ]);
    // }

    public function webhook(Request $request)
    {
        $id = $request->message['chat']['id'];
        // try {
            // $response = Telegram::getCommands();
            $response = Telegram::sendMessage([
                'chat_id'   => $id,
                'text'      => rand(1,9)
            ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => $e->getMessage(),
        //         'error' => $e
        //     ]);
        // }

        return response()->json($response);
        // dd($response);
    }
}
