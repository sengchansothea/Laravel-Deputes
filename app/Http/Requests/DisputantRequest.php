<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class DisputantRequest extends FormRequest
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
            "disputant_name"  => "required",
            "disputant_dob"  => "required",
            "disputant_nationality" => "required",
            "disputant_id_number"  => "required",
            "disputant_address_abroad" => "",
            "disputant_pob_province" => "",
            "disputant_pob_district" => "",
            "disputant_pob_commune" => "",
        ];
    }

    public function messages(): array
    {
        return [
            'disputant_name.required' => 'សូមបំពេញ ឈ្មោះគូវិវាទ',
            'disputant_dob.required' => 'សូមបំពេញ ថ្ងៃខែឆ្នាំកំណើត',
            'disputant_id_number.required' => 'សូមបំពេញ លេខអត្តសញ្ញាណបណ្ណ/ប៉ាស្ព័រ',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->safe()->disputant_nationality == 0) {
                    $validator->errors()->add(
                        'disputant_nationality',
                        'សូមជ្រើសរើស សញ្ជាតិជាមុនសិន'
                    );
                }

                if ($validator->safe()->disputant_nationality > 0 && empty($validator->safe()->disputant_id_number)) {
                    $validator->errors()->add(
                        'disputant_id_number',
                        'សូមបំពេញ លេខអត្តសញ្ញាណបណ្ណ/ប៉ាស្ព័រ'
                    );
                }

                if ($validator->safe()->disputant_nationality != 33 && empty($validator->safe()->disputant_address_abroad)) {
                    $validator->errors()->add(
                        'disputant_address_abroad',
                        'សូមបំពេញ ទីកន្លែងកំណើតក្រៅប្រទេស'
                    );
                }

                if ($validator->safe()->disputant_nationality == 33 && $validator->safe()->disputant_pob_province == 0) {
                    $validator->errors()->add(
                        'disputant_pob_province',
                        'សូមជ្រើសរើស  ខេត្ត/រាជធានី'
                    );
                }

                if ($validator->safe()->disputant_pob_province > 0 && $validator->safe()->disputant_pob_district == 0) {
                    $validator->errors()->add(
                        'disputant_pob_district',
                        'សូមជ្រើសរើស  ស្រុក/ខណ្ណ'
                    );
                }

                if ($validator->safe()->disputant_pob_district > 0 && $validator->safe()->disputant_pob_commune == 0) {
                    $validator->errors()->add(
                        'disputant_pob_commune',
                        'សូមជ្រើសរើស  ឃុំ/សង្កាត់'
                    );
                }

            }
        ];
    }









}
