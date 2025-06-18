<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class StoreBorrowingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // You can add authorization logic here
        // For example, check if the user is authenticated
        // or if they have permission to borrow books
        
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
                'required',
                'string',
                'max:255',
            ],
            'book_id' => [
                'required',
                'string',
                'max:255',
            ],
            'borrowed_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'loan_period_days' => [
                'nullable',
                'integer',
                'min:1',
                'max:30',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
            // Optional fields for direct user/book data (if not using external services)
            'user_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'user_email' => [
                'nullable',
                'email',
                'max:255',
            ],
            'isbn' => [
                'nullable',
                'string',
                'max:50',
            ],
            'book_title' => [
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
            'user_id.required' => 'ID pengguna harus diisi.',
            'user_id.string' => 'ID pengguna harus berupa string.',
            'user_id.max' => 'ID pengguna maksimal 255 karakter.',
            'book_id.required' => 'ID buku harus diisi.',
            'book_id.string' => 'ID buku harus berupa string.',
            'book_id.max' => 'ID buku maksimal 255 karakter.',
            'borrowed_date.date' => 'Tanggal peminjaman harus berupa tanggal yang valid.',
            'borrowed_date.before_or_equal' => 'Tanggal peminjaman tidak boleh di masa depan.',
            'loan_period_days.integer' => 'Periode peminjaman harus berupa angka.',
            'loan_period_days.min' => 'Periode peminjaman minimal 1 hari.',
            'loan_period_days.max' => 'Periode peminjaman maksimal 30 hari.',
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
            'loan_period_days' => 'periode peminjaman',
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
            // Check if user already has an active loan for this book
            $existingBorrow = Borrowing::where('user_id', $this->user_id)
                                     ->where('book_id', $this->book_id)
                                     ->whereIn('status', ['borrowed', 'overdue'])
                                     ->first();

            if ($existingBorrow) {
                $validator->errors()->add('book_id', 'Pengguna sudah meminjam buku ini.');
            }

            // Check if user has reached maximum borrowing limit
            $activeLoans = Borrowing::where('user_id', $this->user_id)
                                  ->whereIn('status', ['borrowed', 'overdue'])
                                  ->count();

            if ($activeLoans >= 5) {
                $validator->errors()->add('user_id', 'Pengguna sudah mencapai batas maksimal peminjaman (5 buku).');
            }

            // Validate borrowed_date is not too far in the past
            if ($this->filled('borrowed_date')) {
                $borrowedDate = Carbon::parse($this->borrowed_date);
                $maxPastDate = now()->subDays(30); // Max 30 days in the past
                
                if ($borrowedDate->lt($maxPastDate)) {
                    $validator->errors()->add('borrowed_date', 'Tanggal peminjaman tidak boleh lebih dari 30 hari yang lalu.');
                }
            }
        });
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Set default borrowed_date to current time if not provided
        if (!$this->has('borrowed_date')) {
            $this->merge([
                'borrowed_date' => now()->toDateString()
            ]);
        }

        // Set default loan_period_days if not provided
        if (!$this->has('loan_period_days')) {
            $this->merge([
                'loan_period_days' => 14 // Default 14 days
            ]);
        }

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

        // Ensure borrowed_date is properly formatted
        if (isset($validated['borrowed_date'])) {
            $validated['borrowed_date'] = Carbon::parse($validated['borrowed_date']);
        }

        // Calculate due_date based on borrowed_date and loan_period_days
        if (isset($validated['borrowed_date']) && isset($validated['loan_period_days'])) {
            $validated['due_date'] = $validated['borrowed_date']->copy()->addDays($validated['loan_period_days']);
        }

        // Set default status
        $validated['status'] = 'borrowed';

        return $validated;
    }

    /**
     * Get the calculated due date for the borrowing.
     *
     * @return Carbon|null
     */
    public function getDueDate(): ?Carbon
    {
        $borrowedDate = $this->get('borrowed_date') ? Carbon::parse($this->get('borrowed_date')) : now();
        $loanPeriod = $this->get('loan_period_days', 14);
        
        return $borrowedDate->copy()->addDays($loanPeriod);
    }
}
