<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class ReturnBookRequest extends FormRequest
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
            'returned_date' => [
                'nullable',
                'date',
                'after_or_equal:borrowed_date',
                'before_or_equal:today',
            ],
            'return_notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'book_condition' => [
                'nullable',
                'string',
                Rule::in(['excellent', 'good', 'fair', 'poor', 'damaged']),
            ],
            'return_location' => [
                'nullable',
                'string',
                'max:255',
            ],
            'returned_by' => [
                'nullable',
                'string',
                'max:255',
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
            'returned_date.date' => 'Tanggal pengembalian harus berupa tanggal yang valid.',
            'returned_date.after_or_equal' => 'Tanggal pengembalian tidak boleh sebelum tanggal peminjaman.',
            'returned_date.before_or_equal' => 'Tanggal pengembalian tidak boleh di masa depan.',
            'return_notes.max' => 'Catatan pengembalian maksimal 1000 karakter.',
            'book_condition.in' => 'Kondisi buku harus salah satu dari: excellent, good, fair, poor, damaged.',
            'return_location.max' => 'Lokasi pengembalian maksimal 255 karakter.',
            'returned_by.max' => 'Nama pengembali maksimal 255 karakter.',
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
            'returned_date' => 'tanggal pengembalian',
            'return_notes' => 'catatan pengembalian',
            'book_condition' => 'kondisi buku',
            'return_location' => 'lokasi pengembalian',
            'returned_by' => 'dikembalikan oleh',
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

            // Check if the book is already returned
            if ($borrowing->status === 'returned') {
                $validator->errors()->add('status', 'Buku sudah dikembalikan sebelumnya.');
            }

            // Check if the borrowing is in a valid state for return
            if (!in_array($borrowing->status, ['borrowed', 'overdue'])) {
                $validator->errors()->add('status', 'Buku tidak dapat dikembalikan dalam status saat ini.');
            }

            // Validate returned_date against borrowed_date
            if ($this->filled('returned_date')) {
                $returnedDate = Carbon::parse($this->returned_date);
                $borrowedDate = Carbon::parse($borrowing->borrowed_date);

                if ($returnedDate->lt($borrowedDate)) {
                    $validator->errors()->add('returned_date', 'Tanggal pengembalian tidak boleh sebelum tanggal peminjaman.');
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
        // Set default returned_date to current time if not provided
        if (!$this->has('returned_date')) {
            $this->merge([
                'returned_date' => now()->toDateTimeString()
            ]);
        }

        // Clean and format the data
        if ($this->has('return_notes')) {
            $this->merge([
                'return_notes' => trim($this->return_notes)
            ]);
        }

        if ($this->has('return_location')) {
            $this->merge([
                'return_location' => trim($this->return_location)
            ]);
        }

        if ($this->has('returned_by')) {
            $this->merge([
                'returned_by' => trim($this->returned_by)
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

        // Ensure returned_date is properly formatted
        if (isset($validated['returned_date'])) {
            $validated['returned_date'] = Carbon::parse($validated['returned_date']);
        }

        return $validated;
    }
}
