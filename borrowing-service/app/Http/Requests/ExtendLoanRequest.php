<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Borrowing;
use Illuminate\Validation\Rule;

class ExtendLoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if the borrowing record exists and belongs to the authenticated user
        $borrowingId = $this->route('id'); // Assuming the route parameter is 'id'
        $borrowing = Borrowing::find($borrowingId);
        
        if (!$borrowing) {
            return false;
        }

        // You can add additional authorization logic here
        // For example, check if the user is the owner of the borrowing record
        // or if they have admin privileges
        
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
            'additional_days' => [
                'required',
                'integer',
                'min:1',
                'max:14',
            ],
            'reason' => [
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
            'additional_days.required' => 'Jumlah hari tambahan harus diisi.',
            'additional_days.integer' => 'Jumlah hari tambahan harus berupa angka.',
            'additional_days.min' => 'Jumlah hari tambahan minimal 1 hari.',
            'additional_days.max' => 'Jumlah hari tambahan maksimal 14 hari.',
            'reason.max' => 'Alasan perpanjangan maksimal 500 karakter.',
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
            'additional_days' => 'jumlah hari tambahan',
            'reason' => 'alasan perpanjangan',
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
                $validator->errors()->add('status', 'Tidak dapat memperpanjang buku yang sudah dikembalikan.');
            }

            // Check if the borrowing is overdue
            if ($borrowing->due_date < now()) {
                $validator->errors()->add('due_date', 'Tidak dapat memperpanjang peminjaman yang terlambat. Silakan kembalikan buku terlebih dahulu.');
            }

            // Check if maximum extensions reached
            if ($borrowing->extension_count >= 2) {
                $validator->errors()->add('extension_count', 'Maksimal perpanjangan telah tercapai.');
            }
        });
    }
}
