<?php
// app/Http/Requests/UpdateReviewRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|integer',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000'
        ];
    }

    public function messages()
    {
        return [
            'rating.between' => 'Rating harus antara 1-5',
            'comment.max' => 'Komentar maksimal 1000 karakter'
        ];
    }
}