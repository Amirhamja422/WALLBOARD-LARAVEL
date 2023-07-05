<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use App\Exports\AuxExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class AuxExportProcess implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $start_date;
    public $end_date;
    public $file_name;
    public $pauseCodes = array();

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($start_date, $end_date, $file_name, $pauseCodes)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->file_name = $file_name;
        $this->pauseCodes = $pauseCodes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Excel::store(new AuxExport($this->start_date, $this->end_date, $this->pauseCodes), $this->file_name, 'public');
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        // Send user notification of failure, etc...
    }
}
