<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete()
    {
        DB::table('vicidial_agent_log')->truncate();
        DB::table('vicidial_closer_log')->truncate();
        DB::table('vicidial_log')->truncate();
        DB::table('crm_food')->truncate();
        // DB::table('failed_jobs')->truncate();
        // DB::table('jobs')->truncate();
    }

    public function batch($id)
    {
        // return $batchData = DB::table('job_batches')->limit(10)->orderBy('created_at', 'desc')->get();
        return Bus::findBatch($id);
    }
}
