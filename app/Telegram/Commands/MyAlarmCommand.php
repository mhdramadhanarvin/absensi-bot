<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use App\Models\AlarmModel;
use Telegram\Bot\Commands\Command;

class MyAlarmCommand extends Command
{
    protected $name = "myalarm";

    protected $description = "Daftar alarm saya";

    public function handle()
    {
        $this->getData();
    }

    public function getData()
    {
        $fromTelegram = request()->message;
        $data = AlarmModel::where('user_id', $fromTelegram['chat']['id']);

        $response = '';
        foreach ($data as $row) {
            $response .= $row . PHP_EOL;
        }

        $this->replyWithMessage(['text' => $response]);
    }
}
