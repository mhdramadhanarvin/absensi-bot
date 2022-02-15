<?php

namespace App\Telegram\Keyboards;

use App\Models\AbsensiModel;
use App\Models\AlarmModel;
use Telegram\Bot\Actions;
use Telegram\Bot\Laravel\Facades\Telegram;

class HistoryInOutKeyboard
{
    protected $to;

    public function __construct($to)
    {
        $this->to = $to;
    }

    public function print()
    {
        $data = AbsensiModel::where('user_id', $this->to)
                            ->whereMonth('created_at', date('m'))
                            ->whereYear('created_at', date('Y'))
                            ->orderBy('created_at', 'ASC')
                            ->get();

        $response = "ABSENSI SAYA" . PHP_EOL . PHP_EOL;
        $response .= "Bulan : ". date('M Y') . PHP_EOL;
        $date = [];
        $response .= "`";
        $response .= "TGL\t|\tJENIS\t|\tWAKTU" . PHP_EOL;
        foreach ($data as $row) {

            $date = substr($row->created_at, 8, 2);
            $date = is_numeric(strpos($response, $date . "\t | \t")) ? "  " : $date;
            $type = ($row->type == 1) ? "\t IN\t\t" : "\tOUT\t\t";
            $response .= $date  . "\t |\t".$type."|\t" . $row->time . PHP_EOL;

        }
        if (count($data) == 0) $response .= "DATA KOSONG";

        $response .= "`";

        Telegram::sendMessage([
            'chat_id' => $this->to,
            'text' => $response,
            'parse_mode' => "MarkdownV2"
        ]);
    }
}
