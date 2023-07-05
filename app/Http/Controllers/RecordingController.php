<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecordingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class RecordingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function recording()
    {
        return view('recording.recording');
    }

    /**
     * recording qc datatable report
     */
    public function recordingDatatable(RecordingRequest $request)
    {
        ## check ajax request found or not
        if ($request->ajax()) {
            ## convert date using helper function
            $start_date = covertDate(removeComma($request->start_date)) . ' 00:00:00';
            $end_date = covertDate(removeComma($request->end_date)) . ' 23:59:59';

            ## query for result
            $recordingData = DB::table('recording_log')
                    ->select('start_time', 'recording_id', 'user', 'filename', DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(filename, '_', 2), '_', -1) AS campaign_id"), DB::raw("SUBSTRING_INDEX(filename, '_', -1) AS phone_number"))
                    ->whereBetween('start_time', [$start_date, $end_date]);

            ## return data to datatable
            return DataTables::queryBuilder($recordingData)
                    ->addColumn('player', function ($recordingData) {
                        $html = '<audio controls>
                                    <source src="http://116.193.217.4:9081/RECORDINGS/MP3/'.$recordingData->filename.'-all.mp3" type="audio/mpeg">
                                </audio>';
                        return $html;
                    })
                    ->rawColumns(['player'])
                    ->toJson();
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function recordingQC()
    {
        return view('recording.recordingQC');
    }
}
