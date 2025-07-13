<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'site_name'     => 'required|string|max:255',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'owner_email'   => 'required|email',
            'theme'         => ['required', Rule::in(['light', 'dark'])],
        ];
    }
}
