<?php

namespace App\Console\Commands;

use App\Jobs\UpdateOverdueStatus;
use Illuminate\Console\Command;

class UpdateOverdueLoans extends Command
{
    protected $signature = 'loans:update-overdue';
    protected $description = 'Update overdue loan status';

    public function handle()
    {
        UpdateOverdueStatus::dispatch();
        $this->info('Overdue status update job dispatched');
    }
}