<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Telegram\Bot\Api;
use App\Models\AlarmModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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

        dd(Telegram::getWebhookUpdates());
        return 'OK KOK';
    }
}
