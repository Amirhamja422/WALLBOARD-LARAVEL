<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OutboundExport implements FromQuery, WithHeadings, WithMapping
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
        return DB::table('vicidial_log')
                ->leftJoin('vicidial_agent_log', 'vicidial_log.uniqueid', '=', 'vicidial_agent_log.uniqueid')
                ->leftJoin('park_log', 'vicidial_log.uniqueid', '=', 'park_log.uniqueid')
                ->whereBetween('vicidial_log.call_date', [$this->start_date, $this->end_date])
                ->select('vicidial_log.call_date', 'vicidial_log.phone_number', 'vicidial_log.status', 'vicidial_log.user', 'vicidial_log.campaign_id', 'vicidial_log.length_in_sec', 'vicidial_log.term_reason', 'vicidial_agent_log.talk_sec', 'vicidial_agent_log.dispo_sec', 'vicidial_agent_log.dead_sec')
                ->orderBy('vicidial_log.call_date', 'asc');
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
            $row->dispo_sec,
            $row->dead_sec,
            $row->length_in_sec,
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
        return ['Call Date', 'Phone Number', 'Status', 'Agent', 'Campaign ID', 'Talk Time', 'Dispo Time', 'Dead Time', 'Length', 'Term Reason'];
    }
}
