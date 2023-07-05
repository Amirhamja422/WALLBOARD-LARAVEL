<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DashService
{
    /**
     * inboundCalls count
     *
     * @param [date] $start_date
     * @param [date] $end_date
     * @return mixed
     */
    public function inboundCalls($start_date, $end_date)
    {
        return DB::table('vicidial_closer_log')->whereBetween('call_date', [$start_date . ' 00:00:01', $end_date . ' 23:59:59'])->count();
    }

    /**
     * outboundCalls count
     *
     * @param [date] $start_date
     * @param [date] $end_date
     * @return mixed
     */
    public function outboundCalls($start_date, $end_date)
    {
        return DB::table('vicidial_log')->whereBetween('call_date', [$start_date . ' 00:00:01', $end_date . ' 23:59:59'])->count();
    }

    /**
     * dropCalls count
     *
     * @param [date] $start_date
     * @param [date] $end_date
     * @return mixed
     */
    public function dropCalls($start_date, $end_date)
    {
        return DB::table('vicidial_closer_log')->whereBetween('call_date', [$start_date . ' 00:00:01', $end_date . ' 23:59:59'])->whereIn('status', dropCallList())->count();
    }

    /**
     * realTimeAgentService list
     *
     * @return mixed
     */
    public function realTimeAgentService()
    {
        $realtimeData = DB::table('vicidial_live_agents')
            ->leftJoin('vicidial_users', 'vicidial_live_agents.user', '=', 'vicidial_users.user')
            ->select('vicidial_live_agents.user', 'vicidial_live_agents.status', 'vicidial_live_agents.campaign_id', 'vicidial_live_agents.extension', 'vicidial_live_agents.last_update_time', 'vicidial_live_agents.last_state_change', 'vicidial_live_agents.callerid', 'vicidial_live_agents.pause_code', 'vicidial_users.full_name');

        return $realtimeData;
    }

    /**
     * barChartService
     *
     * @return void
     */
    public function barChartService()
    {
        ## define variable
        $days = array();
        $totalInbound = array();
        $totalOutbound = array();

        ## get count
        $countData = DB::table('vicidial_agent_log')
            ->select(
                DB::raw('count(*) as total'),
                DB::raw("DATE(event_time) AS date_time"),
                DB::raw("(SELECT count(*) FROM vicidial_closer_log WHERE DATE(call_date) between date_time AND date_time) AS incoming"),
                DB::raw('(SELECT count(*) FROM vicidial_log WHERE DATE(call_date) between date_time AND date_time) AS outgoing')
            )
            ->whereBetween('event_time', [date('Y-m-d', strtotime('-5 days')), date('Y-m-d')])
            ->orderBy('date_time', 'asc')
            ->groupBy('date_time')
            ->get();

        ## process array
        foreach ($countData as $value) {
            array_push($days, $value->date_time);
            array_push($totalInbound, $value->incoming);
            array_push($totalOutbound, $value->outgoing);
        }

        ## put chart data
        return array('days' => $days, 'totalInbound' => $totalInbound, 'totalOutbound' => $totalOutbound);
    }

    public function inboundCallsChart()
    {
        ## define variable
        $days = array();
        $totalInbound = array();
        $totalAnswered = array();
        $totalDrop = array();

        ## process drop list from helper function
        $dropList = implode("','", dropCallList());

        ## get count
        $countData = DB::table('vicidial_closer_log')
            ->select(
                DB::raw('count(*) as total'),
                DB::raw("DATE(call_date) AS date_time"),
                DB::raw("(SELECT count(*) FROM vicidial_closer_log WHERE DATE(call_date) between date_time AND date_time) AS incoming"),
                DB::raw("(SELECT count(*) FROM vicidial_closer_log WHERE DATE(call_date) between date_time AND date_time AND status IN ('" . $dropList . "')) AS drop_call")
            )
            ->whereBetween('call_date', [date('Y-m-d', strtotime('-5 days')), date('Y-m-d')])
            ->orderBy('date_time', 'asc')
            ->groupBy('date_time')
            ->get();

        ## process array
        foreach ($countData as $value) {
            array_push($days, $value->date_time);
            array_push($totalInbound, $value->total);
            array_push($totalAnswered, ($value->incoming - $value->drop_call));
            array_push($totalDrop, $value->drop_call);
        }

        ## put chart data
        return array('days' => $days, 'totalInbound' => $totalInbound, 'totalAnswered' => $totalAnswered, 'totalDrop' => $totalDrop);
    }


    public function outboundCallsChart()
    {
        ## define variable
        $days = array();
        $totalOutbound = array();
        $totalAnswered = array();
        $totalDrop = array();

        ## get count
        $countData = DB::table('vicidial_log')
            ->select(
                DB::raw('count(*) as total'),
                DB::raw("DATE(call_date) AS date_time"),
                DB::raw("(SELECT count(*) FROM vicidial_log WHERE DATE(call_date) between date_time AND date_time) AS outgoing"),
                DB::raw('(SELECT COUNT(vicidial_carrier_log.uniqueid) FROM vicidial_log LEFT JOIN vicidial_carrier_log ON vicidial_log.uniqueid = vicidial_carrier_log.uniqueid WHERE vicidial_carrier_log.dialstatus = "ANSWER" AND DATE(vicidial_log.call_date) between date_time AND date_time AND DATE(vicidial_carrier_log.call_date) between date_time AND date_time) AS answered')
            )
            ->whereBetween('call_date', [date('Y-m-d', strtotime('-5 days')), date('Y-m-d')])
            ->orderBy('date_time', 'asc')
            ->groupBy('date_time')
            ->get();

        ## process array
        foreach ($countData as $value) {
            array_push($days, $value->date_time);
            array_push($totalOutbound, $value->total);
            array_push($totalAnswered, $value->answered);
            array_push($totalDrop, ($value->outgoing - $value->answered));
        }

        ## put chart data
        return array('days' => $days, 'totalOutbound' => $totalOutbound, 'totalAnswered' => $totalAnswered, 'totalDrop' => $totalDrop);
    }
}
