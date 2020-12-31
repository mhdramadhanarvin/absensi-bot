<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Actions;
use App\Models\AlarmModel;
use Telegram\Bot\Commands\Command;

class MyAlarmCommand extends Command
{
    protected $name = "myalarm";

    protected $description = "List My Alarm";

    public function handle()
    {
        $this->getData();
    }

    public function getData()
    {
        $fromTelegram = request()->message;
        $data = AlarmModel::where('user_id', $fromTelegram['chat']['id']);

        dd($data);
    }
}
