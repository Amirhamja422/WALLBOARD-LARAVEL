<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InboundExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public $start_date;
    public $end_date;
    
    /**
     * global __construct
     *
     * @param $start_date
     * @param $end_date
     */
    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }


    /**
     * custom query
     *
     * @return void
     */
    public function query()
    {
        return DB::table('vicidial_closer_log')
                ->leftJoin('vicidial_agent_log', 'vicidial_closer_log.uniqueid', '=', 'vicidial_agent_log.uniqueid')
                ->whereBetween('vicidial_closer_log.call_date', [$this->start_date, $this->end_date])
                ->select('vicidial_closer_log.call_date', 'vicidial_closer_log.phone_number', 'vicidial_closer_log.user', 'vicidial_closer_log.status', 'vicidial_closer_log.campaign_id', 'vicidial_closer_log.term_reason', 'vicidial_closer_log.queue_seconds','vicidial_closer_log.length_in_sec', 'vicidial_agent_log.wait_sec', 'vicidial_agent_log.talk_sec', 'vicidial_agent_log.dispo_sec', 'vicidial_agent_log.dead_sec')
                ->orderBy('vicidial_closer_log.call_date', 'asc');
    }

    /**
     * covert data if need
     *
     * @param [type] $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->call_date,
            $row->phone_number,
            $row->status,
            $row->user,
            $row->campaign_id,
            $row->talk_sec,
            $row->dead_sec,
            $row->dispo_sec,
            $row->length_in_sec,
            $row->queue_seconds,
            $row->term_reason
        ];
    }

    /**
     * inbound export report header
     * 
     * @return text
     */
    public function headings(): array
    {
        return ['Call Date', 'Phone Number', 'Status', 'Agent', 'Skill Name', 'Talk Time', 'Dead Time', 'Dispo Time', 'Length', 'Queue Time', 'Term Reason'];
    }
}
