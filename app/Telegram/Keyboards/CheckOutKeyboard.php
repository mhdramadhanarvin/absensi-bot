<?php

namespace App\Telegram\Keyboards;

use Telegram\Bot\Actions;
use App\Models\AbsensiModel;
use Telegram\Bot\Laravel\Facades\Telegram;

class CheckOutKeyboard
{
    protected $to;

    public function __construct($to)
    {
        $this->to = $to;
    }

    public function saveCheckOut()
    {
        $absensi = new AbsensiModel;
        $absensi->user_id = $this->to;
        $absensi->time = date("H:i");
        $absensi->type = 2 /** 2=OUT */;
        $absensi->save();

        $this->sendMessage();

        return "Berhasil CheckOut";
    }

    public function sendMessage()
    {
        Telegram::sendChatAction([
            'chat_id'   => $this->to,
            'action'    => Actions::TYPING
        ]);
        $response = Telegram::sendMessage([
            'chat_id' => $this->to,
            'text' => "Berhasil Check Out"
        ]);
    }
}
