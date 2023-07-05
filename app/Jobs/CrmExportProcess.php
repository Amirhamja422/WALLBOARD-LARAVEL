<?php

namespace App\Jobs;

use App\Exports\CrmExport;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class CrmExportProcess implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $start_date;
    public $end_date;
    public $file_name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($start_date, $end_date, $file_name)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->file_name = $file_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Excel::store(new CrmExport($this->start_date, $this->end_date), $this->file_name, 'public');
    }
}
