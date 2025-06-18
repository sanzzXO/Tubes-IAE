<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class UpdateBorrowingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if the borrowing record exists
        $borrowingId = $this->route('id'); // Assuming the route parameter is 'id'
        $borrowing = Borrowing::find($borrowingId);
        
        if (!$borrowing) {
            return false;
        }

        // You can add additional authorization logic here
        // For example, check if the user is the owner of the borrowing record
        // or if they have admin/librarian privileges
        
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'book_id' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'borrowed_date' => [
                'sometimes',
                'date',
                'before_or_equal:today',
            ],
            'due_date' => [
                'sometimes',
                'date',
                'after:borrowed_date',
            ],
            'returned_date' => [
                'sometimes',
                'nullable',
                'date',
                'after_or_equal:borrowed_date',
                'before_or_equal:today',
            ],
            'status' => [
                'sometimes',
                'string',
                Rule::in(['borrowed', 'overdue', 'returned', 'cancelled']),
            ],
            'fine_amount' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
                'max:999999.99',
            ],
            'notes' => [
                'sometimes',
                'nullable',
                'string',
                'max:1000',
            ],
            // Optional fields for direct user/book data updates
            'user_name' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],
            'user_email' => [
                'sometimes',
                'nullable',
                'email',
                'max:255',
            ],
            'isbn' => [
                'sometimes',
                'nullable',
                'string',
                'max:50',
            ],
            'book_title' => [
                'sometimes',
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    /**
     * Get custom validation messages for the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.string' => 'ID pengguna harus berupa string.',
            'user_id.max' => 'ID pengguna maksimal 255 karakter.',
            'book_id.string' => 'ID buku harus berupa string.',
            'book_id.max' => 'ID buku maksimal 255 karakter.',
            'borrowed_date.date' => 'Tanggal peminjaman harus berupa tanggal yang valid.',
            'borrowed_date.before_or_equal' => 'Tanggal peminjaman tidak boleh di masa depan.',
            'due_date.date' => 'Tanggal jatuh tempo harus berupa tanggal yang valid.',
            'due_date.after' => 'Tanggal jatuh tempo harus setelah tanggal peminjaman.',
            'returned_date.date' => 'Tanggal pengembalian harus berupa tanggal yang valid.',
            'returned_date.after_or_equal' => 'Tanggal pengembalian tidak boleh sebelum tanggal peminjaman.',
            'returned_date.before_or_equal' => 'Tanggal pengembalian tidak boleh di masa depan.',
            'status.in' => 'Status harus salah satu dari: borrowed, overdue, returned, cancelled.',
            'fine_amount.numeric' => 'Jumlah denda harus berupa angka.',
            'fine_amount.min' => 'Jumlah denda tidak boleh negatif.',
            'fine_amount.max' => 'Jumlah denda maksimal 999,999.99.',
            'notes.max' => 'Catatan maksimal 1000 karakter.',
            'user_name.max' => 'Nama pengguna maksimal 255 karakter.',
            'user_email.email' => 'Email pengguna harus berupa email yang valid.',
            'user_email.max' => 'Email pengguna maksimal 255 karakter.',
            'isbn.max' => 'ISBN maksimal 50 karakter.',
            'book_title.max' => 'Judul buku maksimal 500 karakter.',
        ];
    }

    /**
     * Get custom validation attributes for the request.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'user_id' => 'ID pengguna',
            'book_id' => 'ID buku',
            'borrowed_date' => 'tanggal peminjaman',
            'due_date' => 'tanggal jatuh tempo',
            'returned_date' => 'tanggal pengembalian',
            'status' => 'status',
            'fine_amount' => 'jumlah denda',
            'notes' => 'catatan',
            'user_name' => 'nama pengguna',
            'user_email' => 'email pengguna',
            'isbn' => 'ISBN',
            'book_title' => 'judul buku',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $borrowingId = $this->route('id');
            $borrowing = Borrowing::find($borrowingId);

            if (!$borrowing) {
                $validator->errors()->add('borrowing', 'Data peminjaman tidak ditemukan.');
                return;
            }

            // Check if user_id is being changed and if it would create a duplicate
            if ($this->filled('user_id') && $this->filled('book_id')) {
                $existingBorrow = Borrowing::where('user_id', $this->user_id)
                                         ->where('book_id', $this->book_id)
                                         ->where('id', '!=', $borrowingId)
                                         ->whereIn('status', ['borrowed', 'overdue'])
                                         ->first();

                if ($existingBorrow) {
                    $validator->errors()->add('book_id', 'Pengguna sudah meminjam buku ini.');
                }
            }

            // Check if user has reached maximum borrowing limit when changing user_id
            if ($this->filled('user_id') && $this->user_id !== $borrowing->user_id) {
                $activeLoans = Borrowing::where('user_id', $this->user_id)
                                      ->whereIn('status', ['borrowed', 'overdue'])
                                      ->count();

                if ($activeLoans >= 5) {
                    $validator->errors()->add('user_id', 'Pengguna sudah mencapai batas maksimal peminjaman (5 buku).');
                }
            }

            // Validate status transitions
            if ($this->filled('status')) {
                $this->validateStatusTransition($validator, $borrowing, $this->status);
            }

            // Validate dates consistency
            $this->validateDateConsistency($validator, $borrowing);
        });
    }

    /**
     * Validate status transitions for the borrowing.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @param Borrowing $borrowing
     * @param string $newStatus
     * @return void
     */
    protected function validateStatusTransition($validator, $borrowing, $newStatus)
    {
        $currentStatus = $borrowing->status;
        
        // Define valid status transitions
        $validTransitions = [
            'borrowed' => ['overdue', 'returned', 'cancelled'],
            'overdue' => ['returned', 'cancelled'],
            'returned' => [], // Cannot change from returned
            'cancelled' => [], // Cannot change from cancelled
        ];

        if (!in_array($newStatus, $validTransitions[$currentStatus] ?? [])) {
            $validator->errors()->add('status', "Tidak dapat mengubah status dari '{$currentStatus}' ke '{$newStatus}'.");
        }

        // Additional validation for specific status changes
        if ($newStatus === 'returned' && !$this->filled('returned_date')) {
            $validator->errors()->add('returned_date', 'Tanggal pengembalian harus diisi ketika status diubah menjadi returned.');
        }

        if ($newStatus === 'cancelled' && $currentStatus === 'returned') {
            $validator->errors()->add('status', 'Tidak dapat membatalkan peminjaman yang sudah dikembalikan.');
        }
    }

    /**
     * Validate date consistency for the borrowing.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @param Borrowing $borrowing
     * @return void
     */
    protected function validateDateConsistency($validator, $borrowing)
    {
        $borrowedDate = $this->get('borrowed_date') ? Carbon::parse($this->get('borrowed_date')) : $borrowing->borrowed_date;
        $dueDate = $this->get('due_date') ? Carbon::parse($this->get('due_date')) : $borrowing->due_date;
        $returnedDate = $this->get('returned_date') ? Carbon::parse($this->get('returned_date')) : $borrowing->returned_date;

        // Validate due_date is after borrowed_date
        if ($dueDate && $borrowedDate && $dueDate->lte($borrowedDate)) {
            $validator->errors()->add('due_date', 'Tanggal jatuh tempo harus setelah tanggal peminjaman.');
        }

        // Validate returned_date is after borrowed_date
        if ($returnedDate && $borrowedDate && $returnedDate->lt($borrowedDate)) {
            $validator->errors()->add('returned_date', 'Tanggal pengembalian tidak boleh sebelum tanggal peminjaman.');
        }

        // Validate returned_date is not before due_date (unless it's an early return)
        if ($returnedDate && $dueDate && $returnedDate->lt($dueDate)) {
            // This is allowed for early returns, but we might want to log it
            // No validation error, just informational
        }
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Clean and format the data
        if ($this->has('notes')) {
            $this->merge([
                'notes' => trim($this->notes)
            ]);
        }

        if ($this->has('user_name')) {
            $this->merge([
                'user_name' => trim($this->user_name)
            ]);
        }

        if ($this->has('user_email')) {
            $this->merge([
                'user_email' => trim(strtolower($this->user_email))
            ]);
        }

        if ($this->has('isbn')) {
            $this->merge([
                'isbn' => trim($this->isbn)
            ]);
        }

        if ($this->has('book_title')) {
            $this->merge([
                'book_title' => trim($this->book_title)
            ]);
        }

        // Format fine_amount to 2 decimal places
        if ($this->has('fine_amount')) {
            $this->merge([
                'fine_amount' => number_format((float) $this->fine_amount, 2, '.', '')
            ]);
        }
    }

    /**
     * Get the validated data from the request.
     *
     * @param array|int|string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        // Ensure dates are properly formatted
        if (isset($validated['borrowed_date'])) {
            $validated['borrowed_date'] = Carbon::parse($validated['borrowed_date']);
        }

        if (isset($validated['due_date'])) {
            $validated['due_date'] = Carbon::parse($validated['due_date']);
        }

        if (isset($validated['returned_date'])) {
            $validated['returned_date'] = Carbon::parse($validated['returned_date']);
        }

        return $validated;
    }
}
