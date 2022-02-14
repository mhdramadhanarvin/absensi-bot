<?php

namespace App\Telegram\Helpers;

use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Telegram\Keyboards\SetAlarmKeyboard;
use App\Telegram\Keyboards\ChangeAlarmKeyboard;
use App\Telegram\Keyboards\DeleteAlarmKeyboard;

class SubActionHandlerHelper
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function handle()
    {
        $webhook = Telegram::getWebhookUpdates();
        $to = $webhook->message->chat->id;
        $text = $webhook->message->text;
        $action = explode("_", Cache::get($to));
        switch ($action[0]) {
            case "ChangeAlarm":
                return (new ChangeAlarmKeyboard($to))->do($text);
                break;
            case "DeleteAlarm":
                return (new DeleteAlarmKeyboard($to))->filter($text);
                break;
            case "SetAlarm":
                return (new SetAlarmKeyboard($to))->do($text);
                break;
            default:
                # code...
                break;
        }
        return $action;
    }
}
