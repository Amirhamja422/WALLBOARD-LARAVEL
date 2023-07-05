<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlockStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function block()
    {
        $phoneGroupID = $this->phoneGroupID();

        return view('blockData.block', compact(['phoneGroupID']));
    }

    /**
     * store block list
     *
     * @param Request $request
     * @return void
     */
    public function blockStore(BlockStoreRequest $blockStoreRequest)
    {
        $blockStore = DB::table('vicidial_filter_phone_numbers')->insert([
            'filter_phone_group_id' => $blockStoreRequest->filter_phone_group_id,
            'phone_number' => $blockStoreRequest->phone_number,
            'reason' => $blockStoreRequest->reason,
            'date' => date('Y-m-d H:i:s'),
        ]);

        ## return message
        if ($blockStore) {
            return response()->json(['status' => '200', 'msg' => 'Number Blocked']);
        }
    }

    /**
     * block list datatable report
     */
    public function blockDatatable(Request $request)
    {
        ## check ajax request found or not
        if ($request->ajax()) {
            ## query for result
            $blockData = DB::table('vicidial_filter_phone_numbers')
                ->select('phone_number', 'filter_phone_group_id', 'reason', 'date');

            ## return data to datatable
            return DataTables::queryBuilder($blockData)
                ->addColumn('actions', function ($blockData) {
                    ## Edit button
                    $html = "<a href='javascript:void(0)' class='badge bg-light text-primary p-1 btn btn-outline-primary' onclick='editData(" . $blockData->phone_number . ")' title='Edit'><i class='bi bi-pen margin-right-0'></i></a>&nbsp;";

                    ## Delete button
                    $html .= "<a href='javascript:void(0)' class='badge bg-light text-danger p-1 btn btn-outline-danger' onclick='deleteData(" . $blockData->phone_number . ")' title='Delete'><i class='bi bi-trash margin-right-0'></i></a>";

                    return $html;
                })
                ->rawColumns(['actions'])
                ->toJson();
        }
    }

    /**
     * destroy block list
     *
     * @param [type] $phone_number
     * @return void
     */
    public function blockDestroy($phone_number)
    {
        if (!empty($phone_number)) {
            $deleted = DB::table('vicidial_filter_phone_numbers')->where('phone_number', $phone_number)->delete();

            if ($deleted) {
                return response()->json(['status' => '200', 'msg' => 'Number Deleted']);
            }
        }
    }
}
