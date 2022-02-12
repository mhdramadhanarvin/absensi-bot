<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Telegram\Bot\Api;
use App\Models\AlarmModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Telegram\Keyboards\InfoKeyboard;
use App\Telegram\Keyboards\CheckInKeyboard;
use App\Telegram\Keyboards\CheckOutKeyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{

    public function index()
    {
        return "OK";
    }

    public function scheduler()
    {
        $artisan = Artisan::call('schedule:run');

        return response()->json($artisan);
    }

    public function command()
    {
        Telegram::commandsHandler(true);

        $webhook = Telegram::getWebhookUpdates();
        $text = $webhook->message->text;


        switch ($text):
            case "ℹ️ Info":
                (new InfoKeyboard);
                break;
            case "🟢 Check In":
                new CheckInKeyboard;
                return "Check In";
                break;
            case "🔴 Check Out":
                new CheckOutKeyboard;
                return "Check Out";
                break;
            default:
                return $text;
                break;
        endswitch;
    }
}
