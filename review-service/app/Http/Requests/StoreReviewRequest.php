<?php
// app/Http/Requests/StoreReviewRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|integer',
            'book_id' => 'required|integer',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000'
        ];
    }

    public function messages()
    {
        return [
            'book_id.required' => 'ID buku harus diisi',
            'book_id.integer' => 'ID buku harus berupa angka',
            'rating.required' => 'Rating harus diisi',
            'rating.between' => 'Rating harus antara 1-5',
            'comment.max' => 'Komentar maksimal 1000 karakter'
        ];
    }
}