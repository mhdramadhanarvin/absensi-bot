<?php

namespace App\Telegram\Keyboards;

use Telegram\Bot\Actions;
use App\Models\AlarmModel;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Laravel\Facades\Telegram;

class DeleteAlarmKeyboard
{
    public $to;

    public function __construct($to)
    {
        $this->to = $to;
    }

    public function pre()
    {
        try {
            $response = "Silahkan masukkan ID alarm yang ingin dihapus";

            $reply_markup = Keyboard::make([
                'keyboard' => [
                    ['⛔ Batalkan'],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ]);
            Cache::put($this->to, 'DeleteAlarm_');
            Telegram::sendChatAction([
                'chat_id'   => $this->to,
                'action'    => Actions::TYPING
            ]);
            Telegram::sendMessage([
                'chat_id'   => $this->to,
                'text'      => $response,
                'parse_mode'    => "MarkdownV2",
                'reply_markup' => $reply_markup
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function filter($text)
    {
        $split = explode("_", Cache::get($this->to));
        // 2 = confirm
        // 4 = delete
        if (count($split) == 2 && $this->checkFormat($text) && $this->checkOwner($text)) $this->confirm($text);
        if (count($split) == 4 && $text == '✔️ Yakin') return $this->do($split[1]);
    }

    public function checkFormat($text)
    {
        try {
            if (!is_numeric($text)) {
                Telegram::sendMessage([
                    "chat_id" => $this->to,
                    "text" => "Format tidak valid" . PHP_EOL . " Contoh: `2`",
                    'parse_mode' => "MarkdownV2"
                ]);
                return false;
            }
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function checkOwner($text)
    {
        try {
            $alarm = AlarmModel::where('id', $text)->where('user_id', $this->to)->first();

            if (!$alarm) {
                Telegram::sendMessage([
                    "chat_id" => $this->to,
                    "text" => "Alarm dengan ID `" . $text . "` tidak ditemukan",
                    'parse_mode' => "MarkdownV2"
                ]);
                return false;
            }
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function confirm($text)
    {
        try {
            $reply_markup = Keyboard::make([
                'keyboard' => [
                    ['✔️ Yakin', '⛔ Batalkan'],
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ]);
            Cache::put($this->to, 'DeleteAlarm_' . $text . "_Confirm_");
            Telegram::sendChatAction([
                'chat_id'   => $this->to,
                'action'    => Actions::TYPING
            ]);
            Telegram::sendMessage([
                'chat_id'   => $this->to,
                'text'      => "Yakin ingin menghapus alarm dengan ID  `" . $text . "`",
                'parse_mode'    => "MarkdownV2",
                'reply_markup' => $reply_markup
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function do($text)
    {
        try {
            Cache::forget($this->to);
            $alarm = AlarmModel::find($text);
            $alarm->delete();
            Telegram::sendMessage([
                'chat_id'   => $this->to,
                'text'      => "Alarm berhasil dihapus",
                'reply_markup' => (new StartingKeyboard)->keyboard
            ]);

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
