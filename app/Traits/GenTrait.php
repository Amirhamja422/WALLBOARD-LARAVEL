<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait GenTrait
{

    /**
     * return active phone
     */
    public function activePhoneID()
    {
        $phones = DB::table('phones')
            ->select('extension')
            ->where('active', 'Y')
            ->get();

        return $phones;
    }

    /**
     * return user group
     */
    public function userGroups()
    {
        $userGroups = DB::table('vicidial_user_groups')
            ->select('user_group', 'group_name')
            ->where('user_group', '!=', 'ADMIN')
            ->get();

        return $userGroups;
    }

    /**
     *  Y, N data function
     */
    public function yesNoStatus()
    {
        return array('Y', 'N');
    }

    /**
     *  0, 1 data function
     */
    public function zeroOneStatus()
    {
        return array('0', '1');
    }

    /**
     * return user group
     */
    public function phoneDataByExtension($extension)
    {
        $phoneData = DB::table('phones')
            ->select('extension', 'pass')
            ->where('extension', $extension)
            ->first();

        return $phoneData;
    }

    /**
     * return phone group
     */
    public function phoneGroupID()
    {
        $phoneGroupID = DB::table('vicidial_filter_phone_groups')
            ->select('filter_phone_group_id', 'filter_phone_group_name')
            ->get();

        return $phoneGroupID;
    }
}
