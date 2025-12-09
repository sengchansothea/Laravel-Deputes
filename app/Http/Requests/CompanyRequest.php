<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CompanyRequest extends FormRequest
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
            "company_name_khmer"  => "required",
            "company_name_latin"  => "required",
//            "open_date"  => "required",
//            "company_phone_number"  => "required",
        ];
    }

    public function messages(): array
    {
        return [
            'company_name_khmer.required' => 'សូមបំពេញ នាមករណ៍សហគ្រាស (ខ្មែរ)',
            'company_name_latin.required' => 'សូមបំពេញ នាមករណ៍សហគ្រាស (ឡាតាំង)',
            'open_date.required' => 'សូមបំពេញ កាលបរិច្ឆេទបើកសហគ្រាស',
            'company_phone_number.required' => 'សូមបំពេញ លេខទូរសព្ទទំនាក់ទំនង',
        ];
    }









}
