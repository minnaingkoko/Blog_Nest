<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Authorization handled via middleware
    }

    public function rules()
    {
        return [
            'title'         => 'required|string|max:255',
            'slug'          => 'required|string|max:255|unique:posts',
            'content'       => 'required|string',
            'category_id'   => 'required|exists:categories,id',
            'tags'          => 'nullable|array',
            'tags.*'        => 'exists:tags,id',
            'featured_image'=> 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'        => ['required', Rule::in(['draft', 'pending', 'published'])],
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'A category is required.',
            'tags.*.exists' => 'One or more selected tags are invalid.',
        ];
    }
}