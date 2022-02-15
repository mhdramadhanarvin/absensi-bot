<?php

namespace App\Telegram\Keyboards;

use App\Models\AbsensiModel;
use App\Models\AlarmModel;
use Telegram\Bot\Actions;
use Telegram\Bot\Laravel\Facades\Telegram;

class CheckInKeyboard
{
    protected $to;

    public function __construct($to)
    {
        $this->to = $to;
    }

    public function saveCheckIn()
    {
        $absensi = new AbsensiModel;
        $absensi->user_id = $this->to;
        $absensi->time = date("H:i");
        $absensi->type = 1 /** 1=IN */;
        $absensi->save();

        $this->sendMessage();

        return "Berhasil CheckIn";
    }

    public function sendMessage()
    {
        Telegram::sendChatAction([
            'chat_id'   => $this->to,
            'action'    => Actions::TYPING
        ]);
        $response = Telegram::sendMessage([
            'chat_id' => $this->to,
            'text' => "Berhasil Check In"
        ]);
    }
}
