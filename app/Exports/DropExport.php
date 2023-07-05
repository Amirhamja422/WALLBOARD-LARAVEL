<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

class DropExport implements FromQuery, WithHeadings, WithMapping
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
                ->whereBetween('call_date', [$this->start_date, $this->end_date])
                ->whereIn('status', dropCallList())
                ->select('call_date', 'phone_number', 'campaign_id', 'status', 'queue_seconds', 'term_reason')
                ->orderBy('call_date', 'asc');
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
            $row->campaign_id,
            $row->status,
            $row->queue_seconds,
            $row->term_reason,
        ];
    }

    /**
     * report header
     * 
     * @return text
     */
    public function headings(): array
    {
        return ['Call Date', 'Phone Number', 'Skill Name', 'Status', 'Queue Wait', 'Term Reason'];
    }
}
