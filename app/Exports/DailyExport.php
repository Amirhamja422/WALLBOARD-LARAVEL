<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DailyExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public $start_date;
    public $end_date;
    public $agent;

    /**
     * global __construct
     *
     * @param $start_date
     * @param $end_date
     */
    public function __construct($start_date, $end_date, $agent)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->agent = $agent;
    }

    /**
     * custom query
     *
     * @return void
     */
    public function query()
    {
        return $query = DB::table('vicidial_agent_log')
                ->select('vicidial_agent_log.user AS user_name', 
                DB::raw('count(*) as total'),
                DB::raw("DATE(event_time) AS date_time"), 
                DB::raw('SUM(pause_sec) as pauseSec'),
                DB::raw('SUM(dispo_sec + dead_sec) as after_call_work'),
                DB::raw('SUM(pause_sec + wait_sec + talk_sec + dispo_sec) as login_duration'),
                DB::raw('SUM((pause_sec + wait_sec + talk_sec + dispo_sec) - pause_sec) as working_time'),
                DB::raw('(SELECT count(*) FROM vicidial_closer_log WHERE DATE(call_date) between date_time AND date_time AND user = user_name) AS incoming_call_answer'),
                DB::raw('(SELECT (SUM(talk_sec) - SUM(dead_sec)) FROM vicidial_agent_log WHERE DATE(event_time) between date_time AND date_time AND user = user_name AND comments = "INBOUND") AS incoming_call_answer_time'),
                DB::raw('(SELECT count(*) FROM vicidial_log WHERE DATE(call_date) between date_time AND date_time AND user = user_name) AS dialed_call'),
                DB::raw('(SELECT COUNT(vicidial_carrier_log.uniqueid) FROM vicidial_log LEFT JOIN vicidial_carrier_log ON vicidial_log.uniqueid = vicidial_carrier_log.uniqueid WHERE vicidial_carrier_log.dialstatus = "ANSWER" AND DATE(vicidial_log.call_date) between date_time AND date_time AND DATE(vicidial_carrier_log.call_date) between date_time AND date_time AND vicidial_log.user = user_name) AS outgoing_call_answered'),
                DB::raw('(SELECT (SUM(talk_sec) - SUM(dead_sec)) FROM vicidial_agent_log WHERE DATE(event_time) between date_time AND date_time AND user = user_name AND comments = "MANUAL") AS outgoing_call_answered_time'),)
                ->whereBetween('vicidial_agent_log.event_time', [$this->start_date, $this->end_date])
                ->when($this->agent != 'all', function ($q) {
                    return $q->where('vicidial_agent_log.user', $this->agent);
                })
                ->orderBy('vicidial_agent_log.user', 'asc')
                ->groupBy('date_time', 'vicidial_agent_log.user');
    }

    /**
     * covert data if need
     *
     * @param [type] $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->date_time,
            $row->user_name,
            date('H:i:s', $row->login_duration),
            date('H:i:s', $row->pauseSec),
            date('H:i:s', $row->working_time),
            date('H:i:s', $row->after_call_work),
            $row->incoming_call_answer,
            date('H:i:s', $row->incoming_call_answer_time),
            date('H:i:s', $this->avg_talk_time($row->incoming_call_answer_time, $row->incoming_call_answer)),
            $row->dialed_call,
            $row->outgoing_call_answered,
            date('H:i:s', $row->outgoing_call_answered_time),
            date('H:i:s', $this->avg_talk_time($row->outgoing_call_answered_time, $row->outgoing_call_answered)),
            date('H:i:s', $row->incoming_call_answer_time + $row->outgoing_call_answered_time),
            date('H:i:s', $this->avg_talk_time(($row->incoming_call_answer_time + $row->outgoing_call_answered_time), ($row->outgoing_call_answered + $row->incoming_call_answer))),
        ];
    }

    /**
     * inbound export report header
     * 
     * @return text
     */
    public function headings(): array
    {
        return ['Call Date', 'Agent ID', 'Login Duration', 'Total Break', 'Working Hour', 'ACW', 'Call Answered(IN)', 'Call Answered Time(IN)', 'AVG Talk Time(IN)', 'Dialed Calls(OUT)', 'Call Answered(OUT)', 'Call Answered Time(OUT)', 'AVG Talk Time(OUT)', 'Call Answered Time(IN + OUT)', 'AVG Talk Time(IN + OUT)'];
    }

    public function avg_talk_time($talk_time, $call_count)
    {
        if ($call_count > 0) {
            return $talk_time/$call_count;
        }
    }
}
