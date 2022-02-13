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
    }

    public function confirm($text)
    {
        return $text;
    }

    public function do($text)
    {
        $arguments = explode('=', $text);
        if ($this->checkFormat($arguments) == true) {
            return $this->checkOwner($arguments);
        }
    }

    public function checkFormat($arguments)
    {
        if (count($arguments) != 2) {
            $message = "Format tidak valid `1`" . PHP_EOL . "Contoh:" . PHP_EOL . "Jika edit alarm ID `3` dengan menjadi `10:00`" . PHP_EOL . "Maka format `3=10:00`";
            Telegram::sendChatAction(['chat_id' => $this->to, 'action' => Actions::TYPING]);
            Telegram::sendMessage([
                'chat_id'   => $this->to,
                'text'  => $message,
                'parse_mode' => "MarkdownV2",
            ]);
            return "ChangeAlarm_" . $message;
            exit;
        } else {
            $format = preg_match("/^(?:2[0-4]|[01][1-9]|10):([0-5][0-9])$/", $arguments[1]);
            if (!is_numeric($arguments[0])) {
                $message = "Format tidak valid `2`" . PHP_EOL . "Contoh:" . PHP_EOL . "Jika edit alarm ID `3` dengan menjadi `10:00`" . PHP_EOL . "Maka format `3=10:00`";
                Telegram::sendChatAction(['chat_id' => $this->to, 'action' => Actions::TYPING]);
                Telegram::sendMessage([
                    'chat_id'   => $this->to,
                    'text'  => $message,
                    'parse_mode' => "MarkdownV2",
                ]);
                return "ChangeAlarm_" . $message;
                exit;
            } else if (!$format) {
                $message = "Format tidak valid `3`" . PHP_EOL . "Contoh:" . PHP_EOL . "Jika edit alarm ID `3` dengan menjadi `10:00`" . PHP_EOL . "Maka format `3=10:00`";
                Telegram::sendChatAction(['chat_id' => $this->to, 'action' => Actions::TYPING]);
                Telegram::sendMessage([
                    'chat_id'   => $this->to,
                    'text'  => $message,
                    'parse_mode' => "MarkdownV2",
                ]);
                return "ChangeAlarm_" . $message;
                exit;
            } else {
                return true;
            }
        }
    }

    public function checkOwner($arguments)
    {
        $alarm = AlarmModel::find($arguments[0]);
        if ($alarm) {
            if ($alarm->user_id != $this->to) {
                $message = "Permintaan tidak dapat diproses!";
                Telegram::sendChatAction(['chat_id' => $this->to, 'action' => Actions::TYPING]);
                Telegram::sendMessage([
                    'chat_id'   => $this->to,
                    "text" => $message
                ]);
                return "ChangeAlarm_" . $message;
                exit;
            }
        } else {
            $message = "Alarm tidak ditemukan!";
            Telegram::sendChatAction(['chat_id' => $this->to, 'action' => Actions::TYPING]);
            Telegram::sendMessage([
                'chat_id'   => $this->to,
                "text" => $message
            ]);
            return "ChangeAlarm_" . $message;
            exit;
        }

        return $this->updateAlarm($alarm, $arguments);
    }

    public function updateAlarm(AlarmModel $alarm, $arguments)
    {
        $alarm->time = $arguments[1];
        $alarm->save();

        $message = "Alarm berhasil diperbarui.";
        Telegram::sendChatAction(['chat_id' => $this->to, 'action' => Actions::TYPING]);
        Telegram::sendMessage([
            'chat_id'   => $this->to,
            'text' => $message,
            'reply_markup'  => (new StartingKeyboard)->keyboard
        ]);

        return "ChangeAlarm_" . $message;
        // exit;
    }
}
