<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use DataTables;
use App\Http\Requests\InboundRequest;
use App\Http\Requests\OutboundRequest;
use App\Http\Requests\DropRequest;
use App\Jobs\DropExportProcess;
use App\Jobs\InboundExportProcess;
use App\Jobs\OutboundExportProcess;
use Illuminate\Support\Facades\Bus;


class RawDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inbound()
    {
        return view('rawData.inbound');
    }

    /**
     * Inbound datatable report
     */
    public function inboundDatatable(InboundRequest $request)
    {
        ## check ajax request found or not
        if ($request->ajax()) {
            ## convert date using helper function
            $start_date = covertDate(removeComma($request->start_date)) . ' 00:00:00';
            $end_date = covertDate(removeComma($request->end_date)) . ' 23:59:59';

            ## query for result
            $closerData = DB::table('vicidial_closer_log')
                ->leftJoin('vicidial_agent_log', 'vicidial_closer_log.uniqueid', '=', 'vicidial_agent_log.uniqueid')
                ->whereBetween('vicidial_closer_log.call_date', [$start_date, $end_date])
                ->select('vicidial_closer_log.call_date', 'vicidial_closer_log.phone_number', 'vicidial_closer_log.user', 'vicidial_closer_log.status', 'vicidial_closer_log.campaign_id', 'vicidial_closer_log.queue_seconds', 'vicidial_closer_log.length_in_sec', 'vicidial_closer_log.term_reason', 'vicidial_agent_log.wait_sec', 'vicidial_agent_log.talk_sec', 'vicidial_agent_log.dispo_sec', 'vicidial_agent_log.dead_sec');

            ## return data to datatable
            return DataTables::queryBuilder($closerData)->toJson();
        }
    }

    /**
     * Download inbound report in excel.
     */
    public function inboundDataDownload($start_date, $end_date)
    {
        ## check validation
        if (!empty($start_date) && !empty($end_date) && ($start_date <= $end_date)) {
            ## convert date using helper function
            $start_date = covertDate(removeComma($start_date));
            $end_date = covertDate(removeComma($end_date));
            $file_name = 'Inbound_' . uniqid() . '.xlsx';

            ## generate batch log
            $batchLog = json_encode(array(
                'user' => auth()->user()->id,
                'title' => 'Inbound Report',
                'file_name' => $file_name,
                'start' => $start_date,
                'end' => $end_date,
            ));

            ## start batch for queue job for download
            $batch = Bus::batch([
                new InboundExportProcess($start_date . ' 00:00:00', $end_date . ' 23:59:59', $file_name),
            ])->name($batchLog)->dispatch();

            ## return to report page
            return response()->json(['status' => '200', 'msg' => $batch->id]);
        }
    }


    /**
     * view of outbound
     *
     * @return void
     */
    public function outbound()
    {
        return view('rawData.outbound');
    }

    /**
     * outbound datatable report
     *
     * @return json
     */
    public function outboundDatatable(OutboundRequest $request)
    {
        ## check ajax request found or not
        if ($request->ajax()) {
            ## convert date using helper function
            $start_date = covertDate(removeComma($request->start_date)) . ' 00:00:00';
            $end_date = covertDate(removeComma($request->end_date)) . ' 23:59:59';

            ## query for result
            $vicidialLogData = DB::table('vicidial_log')
                ->leftJoin('vicidial_agent_log', 'vicidial_log.uniqueid', '=', 'vicidial_agent_log.uniqueid')
                ->leftJoin('park_log', 'vicidial_log.uniqueid', '=', 'park_log.uniqueid')
                ->whereBetween('vicidial_log.call_date', [$start_date, $end_date])
                ->select('vicidial_log.call_date', 'vicidial_log.phone_number', 'vicidial_log.status', 'vicidial_log.user', 'vicidial_log.campaign_id', 'vicidial_log.length_in_sec', 'vicidial_log.term_reason', 'vicidial_agent_log.talk_sec', 'vicidial_agent_log.dispo_sec', 'vicidial_agent_log.dead_sec');

            ## return data to datatable
            return DataTables::queryBuilder($vicidialLogData)->toJson();
        }
    }

    /**
     * Download inbound report in excel.
     *
     * @return text/xlsx
     */
    public function outboundDataDownload($start_date, $end_date)
    {
        ## check validation
        if (!empty($start_date) && !empty($end_date) && ($start_date <= $end_date)) {
            ## convert date using helper function
            $start_date = covertDate(removeComma($start_date));
            $end_date = covertDate(removeComma($end_date));
            $file_name = 'Outbound_' . uniqid() . '.xlsx';

            ## generate batch log
            $batchLog = json_encode(array(
                'title' => 'Outbound Report',
                'file_name' => $file_name,
                'start' => $start_date,
                'end' => $end_date,
            ));

            ## start batch for queue job for download
            Bus::batch([
                new OutboundExportProcess($start_date . ' 00:00:00', $end_date . ' 23:59:59', $file_name),
            ])->name($batchLog)->dispatch();

            ## return to report page
            return redirect()->back();
        }
    }

    /**
     * view of drop
     *
     * @return void
     */
    public function drop()
    {
        return view('rawData.drop');
    }

    /**
     * drop datatable report
     *
     * @return json
     */
    public function dropDatatable(DropRequest $request)
    {
        ## check ajax request found or not
        if ($request->ajax()) {
            ## convert date using helper function
            $start_date = covertDate(removeComma($request->start_date)) . ' 00:00:00';
            $end_date = covertDate(removeComma($request->end_date)) . ' 23:59:59';

            ## query for result
            $dropData = DB::table('vicidial_closer_log')
                ->whereBetween('call_date', [$start_date, $end_date])
                ->whereIn('status', dropCallList())
                ->select('call_date', 'phone_number', 'campaign_id', 'status', 'queue_seconds', 'term_reason');

            ## return data to datatable
            return DataTables::queryBuilder($dropData)->toJson();
        }
    }

    /**
     * Download inbound report in excel.
     *
     * @return text/xlsx
     */
    public function dropDataDownload($start_date, $end_date)
    {
        ## check validation
        if (!empty($start_date) && !empty($end_date) && ($start_date <= $end_date)) {
            ## convert date using helper function
            $start_date = covertDate(removeComma($start_date));
            $end_date = covertDate(removeComma($end_date));
            $file_name = 'Drop_' . uniqid() . '.xlsx';

            ## generate batch log
            $batchLog = json_encode(array(
                'title' => 'Drop Report',
                'file_name' => $file_name,
                'start' => $start_date,
                'end' => $end_date,
            ));

            ## start batch for queue job for download
            Bus::batch([
                new DropExportProcess($start_date . ' 00:00:00', $end_date . ' 23:59:59', $file_name),
            ])->name($batchLog)->dispatch();

            ## return to report page
            return redirect()->back();
        }
    }
}
