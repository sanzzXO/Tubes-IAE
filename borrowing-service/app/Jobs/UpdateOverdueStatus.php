<?php

namespace App\Jobs;

use App\Models\Borrowing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateOverdueStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Update status overdue untuk peminjaman yang terlambat
        $overdueLoans = Borrowing::where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->get();

        foreach ($overdueLoans as $loan) {
            $loan->updateOverdueStatus();
        }

        \Log::info('Updated ' . $overdueLoans->count() . ' overdue borrowings');
    }
}