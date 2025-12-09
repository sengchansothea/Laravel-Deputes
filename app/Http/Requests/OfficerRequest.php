<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class OfficerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "officer_name_khmer"  => "required",
            "officer_name_latin"  => "required",
            "officer_id2"  => "required",
            "officer_role"  => "required",
            "sex"  => "required",
        ];
    }

    public function messages(): array
    {
        return [
            'officer_name_khmer.required' => 'សូមបំពេញ ឈ្មោះមន្ត្រីផ្សះផ្សា (ខ្មែរ)',
            'officer_name_latin.required' => 'សូមបំពេញ ឈ្មោះមន្ត្រីផ្សះផ្សា (ឡាតាំង)',
            'officer_id2.required' => 'សូមបំពេញ អត្តលេខមន្ត្រី',
            'officer_role.required' => 'សូមបំពេញ មុខងារមន្ត្រី',
            'sex.required' => 'សូមជ្រើសរើស ភេទមន្ត្រី',
        ];
    }









}
