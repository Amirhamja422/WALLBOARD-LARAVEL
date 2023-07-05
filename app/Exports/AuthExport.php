<?php

namespace App\Exports;

use App\Services\AgentService;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AuthExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    private $agentService;
    /**
     * global __construct
     *
     * @param $start_date
     * @param $end_date
     * @param $agent
     */
    public function __construct($start_date, $end_date, $agent)
    {
        $this->start_date   = $start_date;
        $this->end_date     = $end_date;
        $this->agent        = $agent;
        $this->agentService = new AgentService; 
    }

    /**
     * custom query
     *
     * @return void
     */
    public function query()
    {
        return $this->agentService->authReportService($this->start_date, $this->end_date, $this->agent);
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
            $row->date_time,
            $row->user_name,
            $row->full_name,
            $row->first_login,
            $row->last_logout,
        ];
    }

    /**
     * inbound export report header
     * 
     * @return text
     */
    public function headings(): array
    {
        return ['Date', 'Agent', 'Full Name', 'First Login', 'Last Logout'];
    }
}
