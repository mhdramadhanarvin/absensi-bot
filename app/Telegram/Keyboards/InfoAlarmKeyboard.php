<?php

namespace App\Telegram\Keyboards;

use Telegram\Bot\Actions;
use App\Models\AlarmModel;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class InfoAlarmKeyboard
{
    public function __construct()
    {
        $webhook = Telegram::getWebhookUpdates();
        $to = $webhook->message->chat->id;

        Telegram::sendChatAction(['chat_id' => $to, 'action' => Actions::TYPING]);

        $data = AlarmModel::where('user_id', $to)->get();

        $response = "ALARM SAYA" . PHP_EOL;
        $response .= "Frekuensi alarm: Harian" . PHP_EOL . PHP_EOL;
        $response .= "`ID\t\tWAKTU`" . PHP_EOL;
        foreach ($data as $row) {
            $response .= "`" . $row->id . "\t\t\t" . $row->time . "`" . PHP_EOL;
        }
        if (count($data) == 0) $response .= "`DATA KOSONG`";

        $reply_markup = Keyboard::make([
            'keyboard' => [
                ['✏️ Ubah Alarm', '🗑️ Hapus Alarm'],
                ['⛔ Batalkan'],
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);

        Telegram::sendMessage([
            'chat_id' => $to,
            'text' => $response,
            'parse_mode' => "MarkdownV2",
            'reply_markup' => $reply_markup
        ]);
    }
}
