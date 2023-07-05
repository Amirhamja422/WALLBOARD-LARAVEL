<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CrmExport implements FromQuery, WithHeadings, WithMapping
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
        return DB::table('crm_food')
                ->whereBetween('created_at', [$this->start_date, $this->end_date])
                ->limit(1000)
                ->orderBy('created_at', 'asc');
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
            $row->created_at,
            $row->name,
            $row->phone,
            $row->email,
            $row->type,
            $row->alt_phone,
            $row->nid,
            $row->caller_type,
            $row->gender,
            $row->occupation,
            $row->cat_name,
            $row->con_ad,
            $row->con_dis,
            $row->com_thana,
            $row->con_thana,
            $row->con_write,
            $row->com_ad,
            $row->com_dis,
            $row->com_write,
            $row->consumer_type,
            $row->organization_type,
            $row->organization_name,
            $row->call_status,
            $row->con_query,
            $row->remark,

        ];
    }

    /**
     * export report header
     * 
     * @return text
     */
    public function headings(): array
    {
        return ['Date', 'Name', 'Phone', 'Email', 'Call Type', 'Alt Mob', 'Nid', 'Sub call Type', 'Gender', 'Occupation', 'Category Name', 'Con. Address Division', 'Con. District', 'Con. Thana', 'Con. Write Address', 'Com. Address Division', 'Com. District', 'Com. Thana', 'Com. Write Address', 'Consumer Type', 'Organization Type', 'Organization Name', 'Call status', 'Agent Feedback', 'Consumer Query'];
    }
}
