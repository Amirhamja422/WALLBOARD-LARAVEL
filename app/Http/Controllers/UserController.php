<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function user()
    {
        $phones = $this->activePhoneID();
        $userGroups = $this->userGroups()->toArray();
        $zeroOneStatus = $this->zeroOneStatus();

        return view('userData.user', compact(['phones', 'userGroups', 'zeroOneStatus']));
    }

    /**
     * User store
     */
    public function userStore(UserStoreRequest $request)
    {
        $userStore = DB::table('vicidial_users')->insert([
            'user' => $request->user,
            'full_name' => $request->full_name,
            'pass' => $request->pass,
            'email' => $request->email,
            'agentcall_manual' => $request->agentcall_manual,
            'user_group' => $request->user_group,
            'phone_login' => $request->phone_login,
            'phone_pass' => $this->phoneDataByExtension($request->phone_login)->pass,
        ]);

        ## return message
        if ($userStore) {
            return response()->json(['status' => '200', 'msg' => 'User Insert Successfully']);
        }
    }

    /**
     * User datatable report
     */
    public function userDatatable(Request $request)
    {
        ## check ajax request found or not
        if ($request->ajax()) {
            ## query for result
            $userData = DB::table('vicidial_users')
                ->select('user_id', 'user', 'full_name', 'phone_login', 'email', 'active', 'user_group');

            ## return data to datatable
            return DataTables::queryBuilder($userData)
                ->addColumn('actions', function ($userData) {
                    ## Edit button
                    $html = "<a href='javascript:void(0)' class='badge bg-light text-primary p-1 btn btn-outline-primary' onclick='editData(" . $userData->user_id . ")' title='Edit'><i class='bi bi-pen margin-right-0'></i></a>&nbsp;";

                    ## Delete button
                    if ($userData->active == 'Y') {
                        $html .= "<a href='javascript:void(0)' class='badge bg-light text-danger p-1 btn btn-outline-danger' onclick='changeStatus(" . $userData->user_id . ")' title='Inactive'><i class='bi bi-x-lg margin-right-0'></i></a>";
                    } else {
                        $html .= "<a href='javascript:void(0)' class='badge bg-light text-success p-1 btn btn-outline-success' onclick='changeStatus(" . $userData->user_id . ")' title='Active'><i class='bi bi-check-lg margin-right-0'></i></a>";
                    }

                    return $html;
                })
                ->addColumn('active', function ($userData) {
                    return status($userData->active);
                })
                ->rawColumns(['actions', 'active'])
                ->toJson();
        }
    }

    /**
     * User status change
     */
    public function userStatusChange($id)
    {
        if (!empty($id)) {
            $findData = DB::table('vicidial_users')->select('active')->where('user_id', $id)->first();
            $active = $findData->active == 'Y' ? 'N' : 'Y';
            $update = DB::table('vicidial_users')->where('user_id', $id)->update(['active' => $active]);

            if ($update) {
                return response()->json(['status' => '200', 'msg' => 'Status Changed Successfully']);
            }
        }
    }
}
