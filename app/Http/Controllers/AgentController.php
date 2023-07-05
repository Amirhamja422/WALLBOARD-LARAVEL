<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuxRequest;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\DailyRequest;
use App\Jobs\AuxExportProcess;
use App\Jobs\DailyExportProcess;
use App\Services\AgentService;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Facades\Bus;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dailyReport()
    {
        return view('agentData.dailyReport');
    }

    /**
     * Daily report datatable
     */
    public function dailyDatatable(DailyRequest $request, AgentService $agentService)
    {
        ## check ajax request found or not
        if ($request->ajax()) {
            ## convert date using helper function
            $start_date = covertDate(removeComma($request->start_date)) . ' 00:00:00';
            $end_date = covertDate(removeComma($request->end_date)) . ' 23:59:59';
            $agent = $request->agent;

            ## query from agent service
            $dailyData = $agentService->dailyReportService($start_date, $end_date, $agent);

            ## return data to datatable
            return DataTables::queryBuilder($dailyData)
                ->addColumn('login_duration', function ($dailyData) {
                    return date('H:i:s', $dailyData->login_duration);
                })
                ->addColumn('break_time', function ($dailyData) {
                    return date('H:i:s', $dailyData->pauseSec);
                })
                ->addColumn('working_time', function ($dailyData) {
                    return date('H:i:s', $dailyData->working_time);
                })
                ->addColumn('after_call_work', function ($dailyData) {
                    return date('H:i:s', $dailyData->after_call_work);
                })
                ->addColumn('incoming_call_answer_time', function ($dailyData) {
                    return date('H:i:s', $dailyData->incoming_call_answer_time);
                })
                ->addColumn('avg_talk_time_in', function ($dailyData) {
                    if ($dailyData->incoming_call_answer > 0) {
                        return date('H:i:s', $dailyData->incoming_call_answer_time / $dailyData->incoming_call_answer);
                    }
                })
                ->addColumn('outgoing_call_answered_time', function ($dailyData) {
                    return date('H:i:s', ($dailyData->outgoing_call_answered_time));
                })
                ->addColumn('avg_talk_time_out', function ($dailyData) {
                    if ($dailyData->outgoing_call_answered > 0) {
                        return date('H:i:s', ($dailyData->outgoing_call_answered_time / $dailyData->outgoing_call_answered));
                    }
                })
                ->addColumn('talk_time_in_out', function ($dailyData) {
                    return date('H:i:s', ($dailyData->incoming_call_answer_time + $dailyData->outgoing_call_answered_time));
                })
                ->addColumn('avg_talk_time_in_out', function ($dailyData) {
                    $total_call = $dailyData->outgoing_call_answered + $dailyData->incoming_call_answer;
                    if ($total_call > 0) {
                        return date('H:i:s', (($dailyData->incoming_call_answer_time + $dailyData->outgoing_call_answered_time) / $total_call));
                    }
                })
                ->rawColumns(['login_duration', 'break_time', 'after_call_work', 'incoming_call_answer_time', 'avg_talk_time_in', 'avg_talk_time_out', 'talk_time_in_out', 'avg_talk_time_in_out'])
                ->toJson();
        }
    }

    /**
     * Download daily report in excel.
     */
    public function dailyReportDownload(DailyRequest $request)
    {
        ## convert date using helper function
        $start_date = covertDate(removeComma($request->start_date));
        $end_date = covertDate(removeComma($request->end_date));
        $agent = $request->agent;
        $file_name = 'Daily_' . uniqid() . '.xlsx';

        ## generate batch log
        $batchLog = json_encode(array(
            'title' => 'Daily Report',
            'file_name' => $file_name,
            'start' => $start_date,
            'end' => $end_date,
        ));

        ## start batch for queue job for download
        $busData = Bus::batch([
            new DailyExportProcess($start_date . ' 00:00:00', $end_date . ' 23:59:59', $agent, $file_name),
        ])->name($batchLog)->dispatch();

        ## return to response
        return response()->json(['status' => '200', 'msg' => 'Daily Report Downloading!']);
    }

    /**
     * Display a listing of the resource.
     */
    public function auxReport(AgentService $agentService)
    {
        ## get pause codes from agent service
        $pauseCodes = $agentService->pauseCodes();
        $rowData = array();

        array_push($rowData, array('name' => 'date_time', 'data' => 'date_time'), array('name' => 'user', 'data' => 'user'));
        foreach ($pauseCodes as $key => $value) {
            $row = array();
            $row['name'] = $value;
            $row['data'] = $value;

            $rowData[] = $row;
        }

        $jsonEncode = json_encode($rowData);

        return view('agentData.auxReport', compact(['pauseCodes', 'jsonEncode']));
    }

    /**
     * AUX report datatable report
     */
    public function auxDatatable(AuxRequest $request, AgentService $agentService)
    {
        ## check ajax request found or not
        if ($request->ajax()) {
            ## convert date using helper function
            $start_date = covertDate(removeComma($request->start_date)) . ' 00:00:00';
            $end_date = covertDate(removeComma($request->end_date)) . ' 23:59:59';
            $search_type = $request->search_type;
            $agent = $request->agent;

            ## get pause codes from agent service
            $pauseCodes = $agentService->pauseCodes();
            $finalData = array();

            ## query for result
            $auxData = DB::table('vicidial_agent_log')
                ->select('user', DB::raw('count(*) as total'), DB::raw("DATE(event_time) AS date_time"))
                ->whereBetween('event_time', [$start_date, $end_date])
                ->orderBy('user', 'asc')
                ->groupBy('date_time', 'user')
                ->get();

            ## get pause code sum using two data
            foreach ($auxData as $data) {
                $json_row = array();
                $json_row['date_time'] = $data->date_time;
                $json_row['user'] = $data->user;
                foreach ($pauseCodes as $pauseCode) {
                    $total = DB::table('vicidial_agent_log')
                        ->where('user', $data->user)
                        ->where('sub_status', $pauseCode)
                        ->whereBetween('event_time', [$data->date_time . ' 00:00:00', $data->date_time . ' 23:59:59'])
                        ->sum('pause_sec');
                    $json_row[$pauseCode] = $total;
                }
                array_push($finalData, $json_row);
            }

            ## return data to datatable
            return DataTables::of($finalData)->toJson();
        }
    }

    /**
     * Download aux report in excel.
     */
    public function auxReportDownload($start_date, $end_date, AgentService $agentService)
    {
        ## check validation
        if (!empty($start_date) && !empty($end_date) && ($start_date <= $end_date)) {
            ## convert date using helper function
            $start_date = covertDate(removeComma($start_date));
            $end_date = covertDate(removeComma($end_date));
            $file_name = 'AUX_' . uniqid() . '.xlsx';

            ## generate batch log
            $batchLog = json_encode(array(
                'title' => 'AUX Report',
                'file_name' => $file_name,
                'start' => $start_date,
                'end' => $end_date,
            ));

            ## start batch for queue job for download
            Bus::batch([
                new AuxExportProcess($start_date . ' 00:00:00', $end_date . ' 23:59:59', $agentService->pauseCodes(), $file_name),
            ])->name($batchLog)->dispatch();

            ## return to report page
            return redirect()->back();
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function authReport()
    {
        return view('agentData.authReport');
    }

    /**
     * Auth report datatable
     */
    public function authDatatable(AuthRequest $request, AgentService $agentService)
    {
        ## check ajax request found or not
        if ($request->ajax()) {
            ## convert date using helper function
            $start_date = covertDate(removeComma($request->start_date)) . ' 00:00:00';
            $end_date = covertDate(removeComma($request->end_date)) . ' 23:59:59';
            $agent = $request->agent;

            ## query from agent service
            $authData = $agentService->authReportService($start_date, $end_date, $agent);

            ## return data to datatable
            return DataTables::queryBuilder($authData)->toJson();
        }
    }

    /**
     * Download auth report in excel.
     */
    public function authReportDownload(AuthRequest $request, AgentService $agentService)
    {
        ## Download from agent service
        $agentService->authReportDownloadService($request->start_date, $request->end_date, $request->agent);

        ## return to report page
        return redirect()->back();
    }
}
