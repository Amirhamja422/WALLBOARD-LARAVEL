<?php

namespace App\Http\Controllers;

use App\Services\DashService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Facades\Bus;

class DashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DashService $dashService)
    {
        ## define variable
        $date = date('Y-m-d');

        ## count todays data
        $inbound = $dashService->inboundCalls($date, $date);
        $outbound = $dashService->outboundCalls($date, $date);
        $total = ($inbound + $outbound);

        ## bar chart data
        $chart = $dashService->barChartService();

        ## put data
        $data = array(
            'total' => $total,
            'inbound' => $inbound,
            'outbound' => $outbound,
            'drop' => $dashService->inboundCalls($date, $date),
            'chart' => $chart
        );

        return view('dashboard.home', compact('data'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function realTimeAgent()
    {
        return view('dashboard.realtimeAgent');
    }

    /**
     * Realtime datatable report
     */
    public function realtimeAgentDatatable(Request $request, DashService $dashService)
    {
        ## check ajax request found or not
        if ($request->ajax()) {
            ## query for result
            $realtimeData = $dashService->realTimeAgentService();

            ## return data to datatable
            return DataTables::queryBuilder($realtimeData)
                ->addColumn('last_update_time', function ($realtimeData) {
                    return date("H:i:s", strtotime($realtimeData->last_update_time) - (strtotime($realtimeData->last_state_change)));
                })
                ->addColumn('first_login_time', function ($realtimeData) {
                    return 'First Login Time';
                })
                ->addColumn('status', function ($realtimeData) {
                    return realTimeAgentStatus($realtimeData->status, $realtimeData->callerid, $realtimeData->pause_code);
                })
                ->rawColumns(['last_update_time', 'status', 'first_login_time'])
                ->toJson();
        }
    }

    /**
     * Display the specified resource.
     *
     * @return resource
     */
    public function queueCall()
    {
        return view('dashboard.queueCall');
    }

    /**
     * queueDatatable
     *
     * @param Request $request
     * @return void
     */
    public function queueDatatable(Request $request)
    {
        ## check ajax request found or not
        if ($request->ajax()) {
            ## query for result
            $realtimeData = DB::table('vicidial_auto_calls')
                ->select('campaign_id', 'phone_number')
                ->where('status', 'LIVE');

            ## return data to datatable
            return DataTables::queryBuilder($realtimeData)
                ->toJson();
        }
    }

    /**
     * Display the specified resource.
     *
     * @return resource
     */
    public function charts(DashService $dashService)
    {
        ## inbound call service
        $inboundCalls = $dashService->inboundCallsChart();
        $outboundCalls = $dashService->outboundCallsChart();

        ## put data
        $data = array(
            'inboundCalls' => $inboundCalls,
            'outboundCalls' => $outboundCalls,
        );

        return view('dashboard.charts', compact('data'));
    }

    /**
     * downloadNotifications list
     *
     * @param Request $request
     * @return void
     */
    public function downloadNotifications(Request $request)
    {
        ## check ajax request found or not
        if ($request->ajax()) {
            ## get unseen list
            $batchData = DB::table('job_batches')->limit(10)->orderBy('created_at', 'desc')->get();

            ## update unseen status to seen after show list
            DB::table('job_batches')->where('seen', 'Unseen')->update(['seen' => 'seen']);

            ## return to report page
            return response()->json(['status' => '200', 'msg' => $batchData]);
        }
    }

    /**
     * downloadUnseenCount
     *
     * @param Request $request
     * @return void
     */
    public function downloadUnseenCount(Request $request)
    {
        ## check ajax request found or not
        if ($request->ajax()) {
            ## get unseen count
            $batchDataUnseenCount = DB::table('job_batches')->where('seen', 'Unseen')->count();

            ## return to report page
            return response()->json(['status' => '200', 'msg' => $batchDataUnseenCount]);
        }
    }
}
