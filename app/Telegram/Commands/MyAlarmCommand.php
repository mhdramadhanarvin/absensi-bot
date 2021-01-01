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
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $fromTelegram = request()->message;
        $data = AlarmModel::where('user_id', $fromTelegram['chat']['id'])->get();

        $response = "ALARM SAYA" . PHP_EOL . PHP_EOL;
        foreach ($data as $row) {
            $response .= "`" . $row->id . "\t\t" . $row->time . "`" . PHP_EOL;
        }

        $this->replyWithMessage(['text' => $response , 'parse_mode' => "MarkdownV2"]);
    }
}
