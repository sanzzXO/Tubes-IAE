<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'isbn',
        'book_title',
        'user_name',
        'user_email',
        'borrowed_date',
        'due_date',
        'returned_date',
        'status',
        'fine_amount',
        'notes'
    ];

    protected $casts = [
        'borrowed_date' => 'date',
        'due_date' => 'date',
        'returned_date' => 'date',
        'fine_amount' => 'decimal:2'
    ];

    // Scope untuk buku yang sedang dipinjam
    public function scopeBorrowed($query)
    {
        return $query->where('status', 'borrowed');
    }

    // Scope untuk buku yang terlambat
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->orWhere(function ($q) {
                        $q->where('status', 'borrowed')
                          ->where('due_date', '<', now());
                    });
    }

    // Hitung denda otomatis
    public function calculateFine($dailyFineRate = 1000)
    {
        if ($this->status === 'returned' || $this->due_date >= now()) {
            return 0;
        }

        $overdueDays = now()->diffInDays($this->due_date);
        return $overdueDays * $dailyFineRate;
    }

    // Update status overdue otomatis
    public function updateOverdueStatus()
    {
        if ($this->status === 'borrowed' && $this->due_date < now()) {
            $this->status = 'overdue';
            $this->fine_amount = $this->calculateFine();
            $this->save();
        }
    }
}