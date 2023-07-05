<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class PhoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function phone()
    {
        $yesNoStatus = $this->yesNoStatus();

        return view('phoneData.phone', compact('yesNoStatus'));
    }

    /**
     * store phone
     *
     * @param Request $request
     * @return void
     */
    public function phoneStore(PhoneStoreRequest $request)
    {
        $phoneStore = DB::table('phones')->insert([
            'extension' => $request->extension,
            'voicemail_id' => $request->extension,
            'dialplan_number' => $request->extension,
            'status' => 'ACTIVE',
            'active' => 'Y',
            'server_ip' => $request->ip(),
            'login' => $request->extension,
            'pass' => $request->pass,
            'conf_secret' => $request->conf_secret,
            'is_webphone' => $request->is_webphone,
            'template_id' => webPhoneTemp($request->is_webphone),
        ]);

        ## return message
        if ($phoneStore) {
            return response()->json(['status' => '200', 'msg' => 'Phone Insert Successfully']);
        }
    }

    /**
     * Phone datatable report
     */
    public function phoneDatatable(Request $request)
    {
        ## check ajax request found or not
        if ($request->ajax()) {
            ## query for result
            $phoneData = DB::table('phones')
                ->select('extension', 'server_ip', 'conf_secret', 'is_webphone', 'pass');

            ## return data to datatable
            return DataTables::queryBuilder($phoneData)
                ->addColumn('actions', function ($phoneData) {
                    ## Edit button
                    $html = "<a href='javascript:void(0)' class='badge bg-light text-primary p-1 btn btn-outline-primary' onclick='editData(" . $phoneData->extension . ")' title='Edit'><i class='bi bi-pen margin-right-0'></i></a>&nbsp;";

                    ## Delete button
                    $html .= "<a href='javascript:void(0)' class='badge bg-light text-danger p-1 btn btn-outline-danger' onclick='deleteData(" . $phoneData->extension . ")' title='Delete'><i class='bi bi-trash margin-right-0'></i></a>";

                    return $html;
                })
                ->addColumn('is_webphone', function ($phoneData) {
                    return status($phoneData->is_webphone);
                })
                ->rawColumns(['actions', 'is_webphone'])
                ->toJson();
        }
    }

    /**
     * destroy phone
     *
     * @param [type] $extension
     * @return void
     */
    public function phoneDestroy($extension)
    {
        if (!empty($extension)) {
            $deleted = DB::table('phones')->where('extension', $extension)->delete();

            if ($deleted) {
                return response()->json(['status' => '200', 'msg' => 'Phone Deleted Successfully']);
            }
        }
    }
}
