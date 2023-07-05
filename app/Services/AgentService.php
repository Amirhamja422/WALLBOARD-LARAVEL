<?php

namespace App\Services;

use App\Jobs\AuthExportProcess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;

class AgentService
{

    public function pauseCodes()
    {
        $pauseCodes = DB::table('vicidial_agent_log')
            ->distinct()
            ->where('sub_status', '!=', '')
            ->pluck('sub_status');

        return $pauseCodes;
    }

    /**
     * daily report service
     *
     * @param [type] $start_date
     * @param [type] $end_date
     * @param [type] $agent
     * @return void
     */
    public function dailyReportService($start_date, $end_date, $agent)
    {
        ## query for result
        $dailyData = DB::table('vicidial_agent_log')
            ->select(
                'vicidial_agent_log.user AS user_name',
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
                DB::raw('(SELECT (SUM(talk_sec) - SUM(dead_sec)) FROM vicidial_agent_log WHERE DATE(event_time) between date_time AND date_time AND user = user_name AND comments = "MANUAL") AS outgoing_call_answered_time'),
            )
            ->whereBetween('vicidial_agent_log.event_time', [$start_date, $end_date])
            ->when($agent != 'all', function ($q) use ($agent) {
                return $q->where('vicidial_agent_log.user', $agent);
            })
            ->orderBy('vicidial_agent_log.user', 'asc')
            ->groupBy('date_time', 'vicidial_agent_log.user');

        return $dailyData;
    }

    /**
     * auth report
     *
     * @param [type] $start_date
     * @param [type] $end_date
     * @param [type] $agent
     * @return void
     */
    public function authReportService($start_date, $end_date, $agent)
    {
        ## query for result
        $authData = DB::table('vicidial_user_log')
            ->select(
                'vicidial_user_log.user AS user_name',
                DB::raw('count(*) as total'),
                DB::raw("DATE(event_date) AS date_time"),
                'vicidial_users.full_name',
                DB::raw('(SELECT event_date FROM vicidial_user_log WHERE DATE(event_date) between date_time AND date_time AND event = "LOGIN" AND user = user_name ORDER BY event_date ASC LIMIT 1) AS first_login'),
                DB::raw('(SELECT event_date FROM vicidial_user_log WHERE DATE(event_date) between date_time AND date_time AND event = "LOGOUT" AND user = user_name ORDER BY event_date DESC LIMIT 1) AS last_logout')
            )
            ->leftJoin('vicidial_users', 'vicidial_user_log.user', '=', 'vicidial_users.user')
            ->whereBetween('event_date', [$start_date, $end_date])
            ->when($agent != 'all', function ($q) use ($agent) {
                return $q->where('vicidial_user_log.user', $agent);
            })
            ->orderBy('vicidial_user_log.user', 'asc')
            ->groupBy('date_time', 'vicidial_user_log.user');

        return $authData;
    }


    /**
     * auth report download
     *
     * @param [type] $start_date
     * @param [type] $end_date
     * @param [type] $agent
     * @return void
     */
    public function authReportDownloadService($start_date, $end_date, $agent)
    {
        ## convert date using helper function
        $start_date = covertDate(removeComma($start_date));
        $end_date = covertDate(removeComma($end_date));
        $file_name = 'AUTH_' . uniqid() . '.xlsx';

        ## generate batch log
        $batchLog = json_encode(array(
            'title' => 'AUTH Report',
            'file_name' => $file_name,
            'start' => $start_date,
            'end' => $end_date,
        ));

        ## start batch for queue job for download
        Bus::batch([
            new AuthExportProcess($start_date . ' 00:00:00', $end_date . ' 23:59:59', $agent, $file_name),
        ])->name($batchLog)->dispatch();
    }
}
