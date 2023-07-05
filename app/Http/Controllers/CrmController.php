<?php

namespace App\Http\Controllers;

use App\Http\Requests\CrmRequest;
use App\Jobs\CrmExportProcess;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Facades\Bus;

class CrmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crm()
    {
        return view('crm.crm');
    }

    /**
     * CRM datatable report
     *
     * @return json
     */
    public function crmDatatable(CrmRequest $request)
    {
        ## check ajax request found or not
        if ($request->ajax()) {
            ## convert date using helper function
            $start_date = covertDate(removeComma($request->start_date)) . ' 00:00:00';
            $end_date = covertDate(removeComma($request->end_date)) . ' 23:59:59';

            ## query for result
            $crmData = DB::table('crm_food')
                        ->whereBetween('created_at', [$start_date, $end_date]);

            ## return data to datatable
            return DataTables::queryBuilder($crmData)->toJson();
        }
    }

    /**
     * Download CRM report in excel.
     *
     * @return text/xlsx
     */
    public function crmDataDownload($start_date, $end_date)
    {
        ## check validation
        if (!empty($start_date) && !empty($end_date) && ($start_date <= $end_date)) {
            ## convert date using helper function
            $start_date = covertDate(removeComma($start_date));
            $end_date = covertDate(removeComma($end_date));
            $file_name = 'Crm_'. uniqid() .'.xlsx';

            ## generate batch log
            $batchLog = json_encode(array(
                'title' => 'Crm Report',
                'file_name' => $file_name,
                'start' => $start_date,
                'end' => $end_date,
            ));

            ## start batch for queue job for download
            Bus::batch([
                new CrmExportProcess($start_date . ' 00:00:00', $end_date . ' 23:59:59', $file_name),
            ])->name($batchLog)->dispatch();
            
            ## return to report page
            return redirect()->back();
        }
    }
}
