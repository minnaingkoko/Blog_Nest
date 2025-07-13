<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Only authenticated users (handled via middleware)
    }

    public function rules()
    {
        return [
            'content'   => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id', // For nested replies
        ];
    }

    public function messages()
    {
        return [
            'content.required' => 'A comment cannot be empty.',
        ];
    }
}