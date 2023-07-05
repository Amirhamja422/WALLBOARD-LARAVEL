<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 4000; $i ++){
            ## set value
            $date_time = '2023-0' . rand(1, 6) . '-' . rand(1, 31) . ' 13:00:01';

            ## status
            $statusArray = statusList();
            $status = $statusArray[array_rand($statusArray)];

            ## users
            $usersArray = usersList();
            $user = $usersArray[array_rand($usersArray)];

            ## pause code
            $pauseCodeArray = pauseCodeList();
            $pauseCode = $pauseCodeArray[array_rand($pauseCodeArray)];

            ## closer log data
            $closer_data[] = [
                "lead_id" => $i,
                "list_id" => '998',
                "campaign_id" => '0_Ban_Talk_to_Cus_RP',
                "call_date" => $date_time,
                "start_epoch" => '1685117939',
                "end_epoch" => '1685117939',
                "length_in_sec" => rand(1, 500),
                "status" => $status,
                "phone_code" => '1',
                "phone_number" => "015" . rand(1000000, 9999999),
                "user" => $user,
                "comments" => 'AUTO',
                "processed" => 'N',
                "queue_seconds" => rand(1, 300),
                "user_group" => 'Agent',
                "xfercallid" => '0',
                "term_reason" => 'AGENT',
                "uniqueid" => $i,
                "agent_only" => '',
                "queue_position" => '1',
                "called_count" => '1',
            ];

            ## agent log data
            $agent_data[] = [
                "agent_log_id" => $i,
                "user" => $user,
                "server_ip" => '192.168.1.116',
                "event_time" => $date_time,
                "lead_id" => $i,
                "campaign_id" => 'BUTTERFL',
                "pause_epoch" => '1685117939',
                "pause_sec" => rand(1, 300),
                "wait_epoch" => '1685117939',
                "wait_sec" => rand(1, 300),
                "talk_epoch" => '1685117939',
                "talk_sec" => rand(1, 300),
                "dispo_epoch" => '1685117939',
                "dispo_sec" => rand(1, 300),
                "status" => $status,
                "user_group" => 'Agent',
                "comments" => 'MANUAL',
                "sub_status" => $pauseCode,
                "dead_epoch" => '1685117939',
                "dead_sec" => rand(1, 300),
                "processed" => '1',
                "uniqueid" =>  $i,
                "pause_type" => 'AGENT',
            ];

            ## vicidial log data
            $vicidial_data[] = [
                "uniqueid" => $i,
                "lead_id" => $i,
                "list_id" => '998',
                "campaign_id" => 'OUTBOUND',
                "call_date" => $date_time,
                "start_epoch" => '1625680833',
                "end_epoch" => '1625680833',
                "length_in_sec" => rand(1, 300),
                "status" => $status,
                "phone_code" => '1',
                "phone_number" => "015" . rand(1000000, 9999999),
                "user" => $user,
                "comments" => 'MANUAL',
                "processed" => 'N',
                "user_group" => 'Agent',
                "term_reason" => 'AGENT',
                "alt_dial" => 'MANUAL',
                "called_count" => '1'
            ];

            ## crm data
            $crm_data[] = [
                "id" => $i,
                "agent" => 'Abdul Alim',
                "phone" => "015" . rand(1000000, 9999999),
                "email" => rand(1000000, 9999999) . '@gmail.com',
                "name" => $user,
                "nid" => 'Dhaka',
                "alt_phone" => 'Dhaka',
                "gender" => 'Male',
                "dofb" => '1996-10-20',
                "division" => 'Dhaka',
                "district" => 'Dhaka',
                "upazila" => 'Dhanmondi',
                "present_address" => 'Present Address',
                "permanent_address" => 'Permanent Address',
                "type" => 'Ticket type',
                "remark" => 'remarks',
                "call_status" => 'Call Status',
                "caller_type" => 'Caller Type',
                "organization_type" => 'Organization Type',
                "organization_name" => 'Organization Name',
                "lead_id" => $i,
                "recording_id" => 'Recording ID',
                "recording_filename" => 'Recording File Name',
                "uniqueid" => $i,
                "occupation" => 'Web Developer',
                "sub_ctype" => 'Sub category type',
                "cat_name" => 'Category Name',
                "con_ad" => 'Con Ad',
                "com_ad" => 'Com Ad',
                "consumer_type" => 'Consumer Type',
                "con_dis" => 'Con Dis',
                "com_dis" => 'Com Dis',
                "con_thana" => 'Com Thana',
                "com_thana" => 'Com Thana',
                "con_write" => 'Con Write',
                "com_write" => 'Com Write',
                "con_query" => 'Con Query',
            ];
        }

        ## insert closer log data
        $closer_chunks = array_chunk($closer_data, 2000);
        foreach($closer_chunks as $closer_chunk){
            DB::table('vicidial_closer_log')->insert($closer_chunk);
        }

        ## insert agent log data
        $agent_chunks = array_chunk($agent_data, 2000);
        foreach($agent_chunks as $agent_chunk){
            DB::table('vicidial_agent_log')->insert($agent_chunk);
        }

        ## insert vicidial log data
        $vicidial_log_chunks = array_chunk($vicidial_data, 2000);
        foreach($vicidial_log_chunks as $vicidial_log_chunk){
            DB::table('vicidial_log')->insert($vicidial_log_chunk);
        }

        ## insert crm data
        $crm_data_chunks = array_chunk($crm_data, 200);
        foreach($crm_data_chunks as $crm_data_chunk){
            DB::table('crm_food')->insert($crm_data_chunk);
        }
    }
}
