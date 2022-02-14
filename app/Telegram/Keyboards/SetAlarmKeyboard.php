<?php

namespace App\Telegram\Keyboards;

use Telegram\Bot\Actions;
use App\Models\AlarmModel;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Laravel\Facades\Telegram;

class SetAlarmKeyboard
{
    protected $to;

    public function __construct($to)
    {
        $this->to = $to;
    }

    public function pre()
    {
        Telegram::sendChatAction([
            'chat_id'   => $this->to,
            'action'    => Actions::TYPING
        ]);
        $keyboard = [
            ['⛔ Batalkan']
        ];
        Cache::put($this->to, 'SetAlarm_');
        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);
        $response = Telegram::sendMessage([
            'chat_id' => $this->to,
            'text' => "Masukkan waktu " . PHP_EOL . "Contoh `10:20`",
            'parse_mode'    => "MarkdownV2",
            'reply_markup' => $reply_markup
        ]);
    }

    public function do($text)
    {
        if (!$this->checkFormat($text)) return "CHECK FORMAT!!";
        if (!$this->checkLimit($text)) return "CHECK LIMIT!";
        $this->saveAlarm($text);
    }

    public function checkFormat($text)
    {
        $format = preg_match("/^(?:2[0-4]|[01][1-9]|10):([0-5][0-9])$/", $text);
        if (!$format) {
            Telegram::sendMessage([
                'chat_id' => $this->to,
                'text' => "Format tidak valid" . PHP_EOL . "Contoh: `09:00`",
                'parse_mode' => "MarkdownV2",
            ]);
            return false;
        }

        return true;
    }

    public function checkLimit($text)
    {
        $alarm = AlarmModel::where('user_id', $this->to)->count();

        if ($alarm >= 3) {
            Telegram::sendMessage([
                'chat_id' => $this->to,
                'text' => "Alarm sudah mencapai batas, max 3 alarm."
            ]);
            return false;
        }
        Telegram::sendMessage([
            'chat_id' => $this->to,
            'text' => "OK, alarm disimpan."
        ]);
        return true;
    }

    public function saveAlarm($text)
    {
        $alarm = new AlarmModel;
        $alarm->user_id = $this->to;
        $alarm->time = $text;
        $alarm->save();
    }
}
