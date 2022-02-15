<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Telegram\Bot\Api;
use App\Models\AlarmModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Telegram\Keyboards\CheckInKeyboard;
use App\Telegram\Keyboards\CanceledKeyboard;
use App\Telegram\Keyboards\CheckOutKeyboard;
use App\Telegram\Keyboards\SetAlarmKeyboard;
use App\Telegram\Keyboards\InfoAlarmKeyboard;
use App\Telegram\Keyboards\ChangeAlarmKeyboard;
use App\Telegram\Keyboards\DeleteAlarmKeyboard;
use App\Telegram\Helpers\SubActionHandlerHelper;

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

    public function setWebhook()
    {
        $setwebhook = Telegram::setWebhook([
            'url' => config('telegram.webhook_url')
        ]);

        if ($setwebhook) return "BERHASIL";
    }

    public function command()
    {
        Telegram::commandsHandler(true);

        $webhook = Telegram::getWebhookUpdates();
        $text = $webhook->message->text;
        $to = $webhook->message->chat->id;

        switch ($text):
            case "⏰ Info Alarm":
                new InfoAlarmKeyboard;
                break;
            case "⏺️ Set Alarm":
                (new SetAlarmKeyboard($to))->pre();
                break;
            case "🟢 Check In":
                new CheckInKeyboard;
                return "Check In";
                break;
            case "🔴 Check Out":
                new CheckOutKeyboard;
                return "Check Out";
                break;
            case "⌛ History In & Out":
                // new HistoryInOutKeyboard;
                return "Check Out";
                break;
            case "⛔ Batalkan":
                new CanceledKeyboard($webhook->message->chat->id);
                return "BATAL";
                break;
            case '✏️ Ubah Alarm':
                return (new ChangeAlarmKeyboard($to))->pre();
                break;
            case '🗑️ Hapus Alarm':
                return (new DeleteAlarmKeyboard($to))->pre();
                break;
            default:
                return (new SubActionHandlerHelper($text))->handle();
                break;
        endswitch;
    }
}
