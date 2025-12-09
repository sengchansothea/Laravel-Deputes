<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateMenu3GarmentRequest extends FormRequest
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
        $rules= [
            "emp_3_1_kh_total"  => "required|numeric|min:0",
            "emp_3_1_kh_female"  => "required|numeric|min:0",
            "emp_3_1_for_total"  => "required|numeric|min:0",
            "emp_3_1_for_female"  => "required|numeric|min:0",

            "emp_3_2_12_15y_total"  => "required|numeric|min:0",
            "emp_3_2_12_15y_female"  => "required|numeric|min:0",
            "emp_3_2_15_18y_total"  => "required|numeric|min:0",
            "emp_3_2_15_18y_female"  => "required|numeric|min:0",
            "emp_3_2_from_18y_total"  => "required|numeric|min:0",
            "emp_3_2_from_18y_female"  => "required|numeric|min:0",
//
            "emp_3_3_leader_total"  => "required|numeric|min:0",
            "emp_3_3_leader_female"  => "required|numeric|min:0",
            "emp_3_3_leader_disabled"  => "required|numeric|min:0",
            "emp_3_3_leader_for"  => "required|numeric|min:0",
            "emp_3_3_leader_for_female"  => "required|numeric|min:0",
//
            "emp_3_3_supervior_total"  => "required|numeric|min:0",
            "emp_3_3_supervior_female"  => "required|numeric|min:0",
            "emp_3_3_supervior_disabled"  => "required|numeric|min:0",
            "emp_3_3_supervior_for"  => "required|numeric|min:0",
            "emp_3_3_supervior_for_female"  => "required|numeric|min:0",

            "emp_3_3_office_total"  => "required|numeric|min:0",
            "emp_3_3_office_female"  => "required|numeric|min:0",
            "emp_3_3_office_disabled"  => "required|numeric|min:0",
            "emp_3_3_office_for"  => "required|numeric|min:0",
            "emp_3_3_office_for_female"  => "required|numeric|min:0",

            "emp_3_3_expert_total"  => "required|numeric|min:0",
            "emp_3_3_expert_female"  => "required|numeric|min:0",
            "emp_3_3_expert_disabled"  => "required|numeric|min:0",
            "emp_3_3_expert_for"  => "required|numeric|min:0",
            "emp_3_3_expert_for_female"  => "required|numeric|min:0",

            "emp_3_3_worker_total"  => "required|numeric|min:0",
            "emp_3_3_worker_female"  => "required|numeric|min:0",
            "emp_3_3_worker_disabled"  => "required|numeric|min:0",
            "emp_3_3_worker_for"  => "required|numeric|min:0",
            "emp_3_3_worker_for_female"  => "required|numeric|min:0",
//
//            "emp_3_4_1_a"  => "required|numeric|min:0",
//            "emp_3_4_1_b"  => "required|numeric|min:0",
//            "emp_3_4_1_c"  => "required|numeric|min:0",
//
//            "emp_3_4_2"  => "required|numeric|min:0",
//            "emp_3_4_2_1"  => "required|numeric|min:0",
//            "emp_3_4_2_2"  => "required|numeric|min:0",
//            "emp_3_4_2_3"  => "required|numeric|min:0",
//            "emp_3_4_2_4"  => "required|numeric|min:0",
//            "emp_3_4_2_5"  => "required|numeric|min:0",
//            "emp_3_4_2_6"  => "required|numeric|min:0",
//            "emp_3_4_2_7"  => "required|numeric|min:0",
//            "emp_3_4_2_8"  => "required|numeric|min:0",
//            "emp_3_4_2_9"  => "required|numeric|min:0",
//
//            "emp_3_5_2_a"  => "required|numeric|min:0",
//            "emp_3_5_3"  => "required|numeric|min:0",
//
//            "emp_3_5_3_e"  => "required|numeric|min:0",
//            "emp_3_5_3_f"  => "required|numeric|min:0",
//            "emp_3_5_3_g"  => "required|numeric|min:0",
//
            "emp_3_6_1_kh_total"  => "required|numeric|min:0",
            "emp_3_6_1_kh_female"  => "required|numeric|min:0",
            "emp_3_6_1_for_total"  => "required|numeric|min:0",
            "emp_3_6_1_for_female"  => "required|numeric|min:0",

            "emp_3_6_2_kh_total"  => "required|numeric|min:0",
            "emp_3_6_2_kh_female"  => "required|numeric|min:0",
            "emp_3_6_2_for_total"  => "required|numeric|min:0",
            "emp_3_6_2_for_female"  => "required|numeric|min:0",

            "emp_3_6_3_kh_total"  => "required|numeric|min:0",
            "emp_3_6_3_kh_female"  => "required|numeric|min:0",
            "emp_3_6_3_for_total"  => "required|numeric|min:0",
            "emp_3_6_3_for_female"  => "required|numeric|min:0",

            "emp_3_6_4_kh_total"  => "required|numeric|min:0",
            "emp_3_6_4_kh_female"  => "required|numeric|min:0",
            "emp_3_6_4_for_total"  => "required|numeric|min:0",
            "emp_3_6_4_for_female"  => "required|numeric|min:0",

            "emp_3_6_5_kh_total"  => "required|numeric|min:0",
            "emp_3_6_5_kh_female"  => "required|numeric|min:0",
            "emp_3_6_5_for_total"  => "required|numeric|min:0",
            "emp_3_6_5_for_female"  => "required|numeric|min:0",
        ];

        return $rules;
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

                if ($validator->safe()->emp_3_2_12_15y_total < $validator->safe()->emp_3_2_12_15y_female) {
                    $validator->errors()->add(
                        'emp_3_2_12_15y_total',
                        'ចំនួន (សរុប) ត្រូវធំជាងចំនួន (ស្រី)'
                    );
                }

                if ($validator->safe()->emp_3_2_15_18y_total < $validator->safe()->emp_3_2_15_18y_female) {
                    $validator->errors()->add(
                        'emp_3_2_15_18y_total',
                        'ចំនួន (សរុប) ត្រូវធំជាងចំនួន (ស្រី)'
                    );
                }

                if ($validator->safe()->emp_3_2_from_18y_total < $validator->safe()->emp_3_2_from_18y_female) {
                    $validator->errors()->add(
                        'emp_3_2_from_18y_total',
                        'ចំនួន (សរុប) ត្រូវធំជាងចំនួន (ស្រី)'
                    );
                }
                /** ===================== emp_3_3 1 leader ===================== */
                if ($validator->safe()->emp_3_3_leader_female >  $validator->safe()->emp_3_3_leader_total || $validator->safe()->emp_3_3_leader_disabled >  $validator->safe()->emp_3_3_leader_total || $validator->safe()->emp_3_3_leader_for >  $validator->safe()->emp_3_3_leader_total || $validator->safe()->emp_3_3_leader_for_female >  $validator->safe()->emp_3_3_leader_total  || $validator->safe()->emp_3_3_leader_for_female >  $validator->safe()->emp_3_3_leader_for) {
                    $validator->errors()->add(
                        'emp_3_3_leader_total',
                        "ចំនួនដែលបំពេញមិនត្រឹមត្រូវ សូមពិនិត្យឡើងវិញ"
                    );
                }
                /** ===================== emp_3_3 2 Supervisor ===================== */
                if($validator->safe()->emp_3_3_supervior_female >  $validator->safe()->emp_3_3_supervior_total || $validator->safe()->emp_3_3_supervior_disabled >  $validator->safe()->emp_3_3_supervior_total || $validator->safe()->emp_3_3_supervior_for >  $validator->safe()->emp_3_3_supervior_total || $validator->safe()->emp_3_3_supervior_for_female >  $validator->safe()->emp_3_3_supervior_total  || $validator->safe()->emp_3_3_supervior_for_female >  $validator->safe()->emp_3_3_supervior_for) {
                    $validator->errors()->add(
                        'emp_3_3_supervior_total',
                        "ចំនួនដែលបំពេញមិនត្រឹមត្រូវ សូមពិនិត្យឡើងវិញ"
                    );
                }
                /** ===================== emp_3_3 3 Officer ===================== */
                if ($validator->safe()->emp_3_3_office_female >  $validator->safe()->emp_3_3_office_total || $validator->safe()->emp_3_3_office_disabled >  $validator->safe()->emp_3_3_office_total || $validator->safe()->emp_3_3_office_for >  $validator->safe()->emp_3_3_office_total || $validator->safe()->emp_3_3_office_for_female >  $validator->safe()->emp_3_3_office_total  || $validator->safe()->emp_3_3_office_for_female >  $validator->safe()->emp_3_3_office_for)  {
                    $validator->errors()->add(
                        'emp_3_3_office_total',
                        "ចំនួនដែលបំពេញមិនត្រឹមត្រូវ សូមពិនិត្យឡើងវិញ"
                    );
                }
                /** ===================== emp_3_3 4 Expert ===================== */
                if ($validator->safe()->emp_3_3_expert_female >  $validator->safe()->emp_3_3_expert_total || $validator->safe()->emp_3_3_expert_disabled >  $validator->safe()->emp_3_3_expert_total || $validator->safe()->emp_3_3_expert_for >  $validator->safe()->emp_3_3_expert_total || $validator->safe()->emp_3_3_expert_for_female >  $validator->safe()->emp_3_3_expert_total  || $validator->safe()->emp_3_3_expert_for_female >  $validator->safe()->emp_3_3_expert_for)  {
                    $validator->errors()->add(
                        'emp_3_3_expert_total',
                        "ចំនួនដែលបំពេញមិនត្រឹមត្រូវ សូមពិនិត្យឡើងវិញ"
                    );
                }
                /** ===================== emp_3_3 5 Worker ===================== */
                if ($validator->safe()->emp_3_3_worker_female >  $validator->safe()->emp_3_3_worker_total || $validator->safe()->emp_3_3_worker_disabled >  $validator->safe()->emp_3_3_worker_total || $validator->safe()->emp_3_3_worker_for >  $validator->safe()->emp_3_3_worker_total || $validator->safe()->emp_3_3_worker_for_female >  $validator->safe()->emp_3_3_worker_total  || $validator->safe()->emp_3_3_worker_for_female >  $validator->safe()->emp_3_3_worker_for) {
                    $validator->errors()->add(
                        'emp_3_3_worker_total',
                        "ចំនួនដែលបំពេញមិនត្រឹមត្រូវ សូមពិនិត្យឡើងវិញ"
                    );
                }
                /** ===================== emp_3_6 1 ===================== */
                if ($validator->safe()->emp_3_6_1_kh_female >  $validator->safe()->emp_3_6_1_kh_total || $validator->safe()->emp_3_6_1_for_female >  $validator->safe()->emp_3_6_1_for_total ) {
                    $validator->errors()->add(
                        'emp_3_6_1_kh_total',
                        'ចំនួន (សរុប) ត្រូវធំជាងចំនួន (ស្រី)'
                    );
                }
                /** ===================== emp_3_6 2 ===================== */
                if ($validator->safe()->emp_3_6_2_kh_female >  $validator->safe()->emp_3_6_2_kh_total || $validator->safe()->emp_3_6_2_for_female >  $validator->safe()->emp_3_6_2_for_total )  {
                    $validator->errors()->add(
                        'emp_3_6_2_kh_total',
                        'ចំនួន (សរុប) ត្រូវធំជាងចំនួន (ស្រី)'
                    );
                }
                /** ===================== emp_3_6 3 ===================== */
                if ($validator->safe()->emp_3_6_3_kh_female >  $validator->safe()->emp_3_6_3_kh_total || $validator->safe()->emp_3_6_3_for_female >  $validator->safe()->emp_3_6_3_for_total )  {
                    $validator->errors()->add(
                        'emp_3_6_3_kh_total',
                        'ចំនួន (សរុប) ត្រូវធំជាងចំនួន (ស្រី)'
                    );
                }
                /** ===================== emp_3_6 4 ===================== */
                if ($validator->safe()->emp_3_6_4_kh_female >  $validator->safe()->emp_3_6_4_kh_total || $validator->safe()->emp_3_6_4_for_female >  $validator->safe()->emp_3_6_4_for_total ) {
                    $validator->errors()->add(
                        'emp_3_6_4_kh_total',
                        'ចំនួន (សរុប) ត្រូវធំជាងចំនួន (ស្រី)'
                    );
                }
                /** ===================== emp_3_6 5 ===================== */
                if($validator->safe()->emp_3_6_5_kh_female >  $validator->safe()->emp_3_6_5_kh_total || $validator->safe()->emp_3_6_5_for_female >  $validator->safe()->emp_3_6_5_for_total )  {
                    $validator->errors()->add(
                        'emp_3_6_5_kh_total',
                        'ចំនួន (សរុប) ត្រូវធំជាងចំនួន (ស្រី)'
                    );
                }









            }
        ];
    }






}
