<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function admin()
    {
        $zeroOneStatus = $this->zeroOneStatus();

        return view('adminData.admin', compact(['zeroOneStatus']));
    }

    /**
     * admin datatable report
     */
    public function adminDatatable(Request $request)
    {
        ## check ajax request found or not
        if ($request->ajax()) {
            ## query for result
            $adminData = DB::table('users')
                ->select('id', 'name', 'email', 'status', 'created_at');

            ## return data to datatable
            return DataTables::queryBuilder($adminData)
                ->addColumn('actions', function ($adminData) {
                    ## Edit button
                    $html = "<a href='javascript:void(0)' class='badge bg-light text-primary p-1 btn btn-outline-primary' onclick='editData(" . $adminData->id . ")' title='Edit'><i class='bi bi-pen margin-right-0'></i></a>&nbsp;";

                    ## Delete button
                    if ($adminData->status == 'Y') {
                        $html .= "<a href='javascript:void(0)' class='badge bg-light text-danger p-1 btn btn-outline-danger' onclick='changeStatus(" . $adminData->id . ")' title='Inactive'><i class='bi bi-x-lg margin-right-0'></i></a>";
                    } else {
                        $html .= "<a href='javascript:void(0)' class='badge bg-light text-success p-1 btn btn-outline-success' onclick='changeStatus(" . $adminData->id . ")' title='Active'><i class='bi bi-check-lg margin-right-0'></i></a>";
                    }

                    return $html;
                })
                ->addColumn('status', function ($adminData) {
                    return status_01($adminData->status);
                })
                ->rawColumns(['actions', 'status'])
                ->toJson();
        }
    }

    /**
     * Administrator store
     */
    public function administratorStore(AdminStoreRequest $adminStoreRequest)
    {
        $administratorStoreStore = DB::table('users')->insert([
            'name' => $adminStoreRequest->name,
            'email' => $adminStoreRequest->email,
            'password' => Hash::make($adminStoreRequest->password),
            'status' => $adminStoreRequest->status,
        ]);

        ## return message
        if ($administratorStoreStore) {
            return response()->json(['status' => '200', 'msg' => 'Administrator Insert Successfully']);
        }
    }


    public function administratorStatusChange(Request $request, $id)
    {
        if (!empty($id) && $request->ajax()) {
            $findData = DB::table('users')->select('status')->where('id', $id)->first();
            $status = $findData->status == '1' ? '0' : '1';
            $update = DB::table('users')->where('id', $id)->update(['status' => $status]);

            if ($update) {
                return response()->json(['status' => '200', 'msg' => 'Status Changed Successfully']);
            }
        }
    }
}
