<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateMenu3OtherRequest extends FormRequest
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
            "emp_3_1_kh_total"  => "required|numeric|min:0",
            "emp_3_1_kh_female"  => "required|numeric|min:0",
            "emp_3_1_for_total"  => "required|numeric|min:0",
            "emp_3_1_for_female"  => "required|numeric|min:0",
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->safe()->emp_3_1_kh_total < $validator->safe()->emp_3_1_kh_female) {
                    $validator->errors()->add(
                        'emp_3_1_kh_total',
                        'ចំនួន (សរុប) ត្រូវធំជាងចំនួន (ស្រី)'
                    );
                }
                if ($validator->safe()->emp_3_1_for_total < $validator->safe()->emp_3_1_for_female) {
                    $validator->errors()->add(
                        'emp_3_1_for_total',
                        'ចំនួនបរទេស (សរុប) ត្រូវធំជាងចំនួន (ស្រី)'
                    );
                }
            }
        ];
    }







}
