@php
    $case = $adata['case'];
@endphp
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/date-picker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/timepicker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <style>
            #response-message {
                display: none;
                font-size: 25px;
                color: blue;
                animation: fadeInOut 2s linear infinite;
            }

            @keyframes fadeInOut {
                0%, 100% {
                    opacity: 0;
                }
                50% {
                    opacity: 1;
                }
            }
        </style>
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <form name="formCreateCase" action="{{ url('collectives_log34') }}" method="POST" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <input type="hidden" name="case_type_id" value="{{ $case->case_type_id }}" >
                        <input type="hidden" name="case_id" value="{{ $adata['case_id'] }}" >
                        <input type="hidden" name="invitation_id" value="{{ $adata['invitation_id'] }}" >
                        <input type="hidden" name="company_id" value="{{ $case->company_id }}" >
                        <div class="card-body text-hanuman-17">
                            <div class="card-block row">
                                <div class="col-sm-12 col-lg-12 col-xl-12">
                                    <div class="row col-12 mt-3">
                                        <span class="text-primary text-hanuman-24 col-sm-8 mb-3">តំណាងប្រតិភូចរចា (តំណាងកម្មករនិយោជិត)</span>
{{--                                        <div class="form-group col-sm-4">--}}
{{--                                            <button id="btnAddCollectivesEmp" value="0" type="button" class="form-control btn btn-info text-hanuman-16">បញ្ចូលពត៌មានលម្អិត តំណាងកម្មករនិយោជិត</button>--}}
{{--                                        </div>--}}
{{--                                        <label class="form-label text-danger fw-bold mt-2">***សូមបញ្ចូលពត៌មានលម្អិតរបស់ តំណាងប្រតិភូចរចា (តំណាងកម្មករនិយោជិត) អោយបានគ្រប់គ្នា [សរុបចំនួន {{ Num2Unicode(count($case->collectivesRepresentatives)) }} នាក់]</label>--}}
                                    </div>
                                    <div id="addEmpBlock" class="mb-3">
                                        <div class="form-group col-12">
                                            <div id="collectives_response_message" style="display: none;">Waiting for response...</div>
                                        </div>
                                        <div class="row col-12">
                                            <div class="form-group col-sm-12 mt-3">
                                                <label for="collectives_emp_autocompleted" class="text-primary text-hanuman-20 fw-bold mb-1"> ស្វែងរកឈ្មោះ អ្នកតំណាងកម្មករនិយោជិត</label>
                                                <input type="hidden" id="company_id" value="{{ $case->company->company_id_lacms }}">
                                                <input type="text" name="collectives_emp_autocompleted" minlength="2" value="{{ old('collectives_emp_autocompleted') }}" class="form-control" id="collectives_emp_autocompleted" >
                                            </div>
                                        </div>
                                        <div class="row col-12 mt-3">
                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="collectives_emp_name" class="fw-bold mb-1 required2">ឈ្មោះតំណាងកម្មករនិយោជិត</label>
                                                <input type="text" name="collectives_emp_name" value="{{ old('collectives_emp_name') }}" class="form-control" id="collectives_emp_name" >
                                            </div>
                                            <div class="form-group col-sm-2 mt-3">
                                                <label class="fw-bold mb-1 required2">ភេទ</label>
                                                {!! showSelect('collectives_emp_gender', array("1" => "ប្រុស", "2" => "ស្រី"), old('collectives_emp_gender'), " select2", "", "collectives_emp_gender") !!}
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="fw-bold mb-1 required2">ថ្ងៃខែឆ្នាំកំណើត</label>
                                                <input type="text"  name="collectives_emp_dob" id="collectives_emp_dob" value="{{ old('collectives_emp_dob') }}" class="form-control"  data-language="en" >
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="fw-bold mb-1 required2">សញ្ជាតិ</label>
                                                {!! showSelect('collectives_emp_nationality', arrayNationality(1), old('collectives_emp_nationality'), " select2", "", "collectives_emp_nationality") !!}
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label for="collectives_id_number" class="fw-bold mb-1"> លេខអត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
                                                <input type="text" name="collectives_id_number" value="{{ old('collectives_id_number') }}" class="form-control" id="collectives_id_number">
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label for="collectives_phone_number" class="required2 fw-bold mb-1">លេខទូរស័ព្ទខ្សែទី១</label>
                                                <input type="text" name="collectives_phone_number" id="collectives_phone_number" value="{{ old('collectives_phone_number') }}" class="form-control" placeholder="012XXXXXX" >
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label for="collectives_phone_number2" class="fw-bold mb-1">លេខទូរស័ព្ទខ្សែទី២</label>
                                                <input type="text" name="collectives_phone2_number" id="collectives_phone2_number" value="{{ old('collectives_phone2_number') }}" class="form-control" placeholder="012XXXXXX" >
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label for="collectives_emp_occupation" class="fw-bold mb-1 required2">មុខងារ</label>
                                                <input type="text" name="collectives_emp_occupation" id="collectives_emp_occupation" value="{{ old('collectives_emp_occupation') }}" class="form-control" >
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="fw-bold mb-1 required2">ទីកន្លែងកំណើត រាជធានី-ខេត្ត</label>
                                                {!! showSelect('collectives_emp_pob_pro_id', arrayProvince(1,0), old('collectives_emp_pob_pro_id', request('collectives_emp_pob_pro_id')), " select2", "", "collectives_emp_pob_pro_id", "") !!}
                                            </div>

                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                                {!! showSelect('collectives_emp_pob_dis_id', array(), old('collectives_emp_pob_dis_id'), " select2", "", "collectives_emp_pob_dis_id", "") !!}
                                            </div>

                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                                {!! showSelect('collectives_pob_commune_id', array(), old('collectives_pob_commune_id'), " select2", "", "collectives_pob_commune_id", "") !!}
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="fw-bold mb-1 required2">អាសយដ្ឋានបច្ចុប្បន្ន រាជធានី-ខេត្ត</label>
                                                {!! showSelect('collectives_emp_pro_id', arrayProvince(1,0), old('collectives_emp_pro_id'), " select2", "", "collectives_emp_pro_id", "") !!}
                                            </div>

                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="fw-bold mb-1 required2">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                                {!! showSelect('collectives_emp_dis_id', array(), old('collectives_emp_dis_id'), " select2", "", "collectives_emp_dis_id", "") !!}
                                            </div>

                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="fw-bold mb-1 required2">ឃុំ-សង្កាត់</label>
                                                {!! showSelect('collectives_emp_com_id', array(), old('collectives_emp_com_id'), " select2", "", "collectives_emp_com_id", "") !!}
                                            </div>
                                            <div class="form-group col-sm-2 mt-3">
                                                <label class="fw-bold mb-1">ភូមិ</label>
                                                {!! showSelect('collectives_emp_vil_id', array(), old('collectives_emp_vil_id'), " select2", "", "collectives_emp_vil_id", "") !!}
                                            </div>
                                            <div class="form-group col-sm-2 mt-3">
                                                <label for="case_type" class="fw-bold mb-1">ផ្ទះលេខ</label>
                                                <input type="text" name="collectives_emp_house_no" id="collectives_emp_house_no" value="{{ old('collectives_emp_house_no') }}" class="form-control" >
                                            </div>
                                            <div class="form-group col-sm-2 mt-3">
                                                <label class="fw-bold mb-1">ផ្លូវ</label>
                                                <input type="text" name="collectives_emp_street_no" id="collectives_emp_street_no" value="{{ old('collectives_emp_street_no') }}" class="form-control" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row col-12 mt-4">
                                        <span class="text-primary text-hanuman-24">ដំណើរការនិតិវិធី</span>
                                    </div>

                                    <div class="row col-12">
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold required mb-1">ថ្ងៃខែឆ្នាំជួបប្រជុំ</label>
                                            <input type="text"  name="meeting_date" id="meeting_date" value="{{ old('meeting_date', myDate("d-m-Y")) }}" class="form-control"  data-language="en" required >
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold required mb-1">ម៉ោងចាប់ផ្ដើម</label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input name="meeting_stime" id="meeting_stime" value="{{ old("meeting_stime", myTime()) }}"  class="form-control" type="text" data-bs-original-title="" required >
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold required mb-1">ម៉ោងបញ្ចប់</label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input name="meeting_etime" id="meeting_etime" value="{{ old("meeting_etime") }}"  class="form-control" type="text" data-bs-original-title="" required >
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="fw-bold required mb-1">បានផ្ដល់ព័ត៌មានស្ដីពី</label>
                                            {!! showTextarea("disputant_give_info", old('disputant_give_info'),"4","required") !!}
                                        </div>
                                    </div>

                                    <div class="row col-12 mt-5">
                                        <span class="text-primary text-hanuman-24 col-sm-8">វត្តមានក្នុងកិច្ចប្រជុំមាន</span>
{{--                                        <div class="form-group col-sm-4">--}}
{{--                                            <button id="btnAddCollectivesOtherAttendant" value="0" type="button" class="form-control btn btn-secondary text-hanuman-16">បញ្ចូលពត៌មាន អ្នកមានវត្តមានក្នុងកិច្ចប្រជុំ</button>--}}
{{--                                        </div>--}}
                                        <div class="form-group col-sm-4">
                                            <button id="btnAddCollectivesSubEmp" value="0" type="button" class="form-control btn btn-info text-hanuman-16">បញ្ចូលពត៌មាន អ្នកអមតំណាងកម្មករនិយោជិត</button>
                                        </div>
{{--                                        <label class="form-label text-danger fw-bold mt-2">***សូមបញ្ចូលពត៌មានលម្អិតរបស់ តំណាងប្រតិភូចរចា (តំណាងកម្មករនិយោជិត) អោយបានគ្រប់គ្នា</label>--}}
                                    </div>
                                    @php
                                        $officerId = getCaseOfficer($case->id, 1, 6);
                                        $officerNoter = getCaseOfficer($case->id, 1, 8);
                                    @endphp
                                    <div class="row col-12 mt-1">
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold required mb-1">ប្រធានអង្គប្រជុំ</label>
                                            {!! showSelect('head_meeting', arrayOfficer(0,1), old('head_meeting', $officerId), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold required mb-1">អ្នកកត់ត្រា</label>
                                            {!! showSelect('noter', arrayOfficer(0,1), old('noter', $officerNoter), " select2", "", "", "required") !!}
{{--                                            {!! showSelect('noter', arrayOfficer($officerNoter), old('noter'), " select2", "", "", "required") !!}--}}
{{--                                            {!! showSelect('noter', arrayOfficerExcept($officerId, 1, ""), old('noter'), " select2", "", "", "required") !!}--}}
                                        </div>
{{--                                        <div class="form-group col-sm-4 mt-3">--}}
{{--                                            <label class="fw-bold required">អ្នកប្ដឹង</label>--}}
{{--                                            {!! showSelect('attendant_disputant_id', arrayDisputant($row->disputant_id), old('attendant_disputant_id'), " select2", "", "", "") !!}--}}
{{--                                        </div>--}}
                                    </div>
                                    <div id="addOtherEmpBlock" class="mb-3" style="display: none">
                                        <div class="form-group col-12">
                                            <div id="response_message_other_employee" style="display: none;">Waiting for response...</div>
                                        </div>
                                        <div class="row col-12">
                                            <div class="form-group col-sm-12 mt-3">
                                                <label for="find_other_employee_autocomplete" class="text-primary text-hanuman-20 mb-1"> ស្វែងរកឈ្មោះ អ្នកតំណាងកម្មករនិយោជិត</label>
                                                <input type="text" name="find_other_employee_autocomplete" minlength="2" value="{{ old('find_other_employee_autocomplete') }}" class="form-control" id="find_other_employee_autocomplete" >
                                            </div>
                                        </div>
                                        <div class="row col-12 mt-3">
                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="name_other" class="mb-1">ឈ្មោះអ្នកអម តំណាងកម្មករនិយោជិត</label>
                                                <input type="text" name="name_other" value="{{ old('name_other') }}" class="form-control" id="name_other" >
                                            </div>
                                            <div class="form-group col-sm-2 mt-3">
                                                <label class="mb-1" for="gender_other">ភេទ</label>
                                                {!! showSelect('gender_other', array("1" =>"ប្រុស", "2" => "ស្រី"), old('gender'), " select2", "", "gender") !!}
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="mb-1" for="dob_other">ថ្ងៃខែឆ្នាំកំណើត</label>
                                                <input type="text"  name="dob_other" id="dob_other" value="{{ old('dob_other') }}" class="form-control"  data-language="en" >
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="mb-1" for="nationality_other">សញ្ជាតិ</label>
                                                {!! showSelect('nationality_other', arrayNationality(1), old('nationality_other'), " select2", "", "nationality_other") !!}
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="id_number_other" class="mb-1"> លេខអត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
                                                <input type="text" name="id_number_other" value="{{ old('id_number_other') }}" class="form-control" id="id_number_other" >
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label for="phone_number_other" class="required2 fw-bold mb-1">លេខទូរស័ព្ទខ្សែទី១</label>
                                                <input type="text" name="phone_number_other" id="phone_number_other" value="{{ old('phone_number_other') }}" class="form-control" placeholder="012XXXXXX" >
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label for="phone2_number_other" class="fw-bold mb-1">លេខទូរស័ព្ទខ្សែទី២</label>
                                                <input type="text" name="phone2_number_other" id="phone2_number_other" value="{{ old('phone2_number_other') }}" class="form-control" placeholder="012XXXXXX" >
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="occupation_other" class="mb-1">មុខងារ</label>
                                                <input type="text" name="occupation" id="occupation_other" value="{{ old('occupation_other') }}" class="form-control" >
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="mb-1" for="pob_province_id_other">ទីកន្លែងកំណើត រាជធានី-ខេត្ត</label>
                                                {!! showSelect('pob_province_id_other', arrayProvince(1,0), old('pob_province_id_other', request('pob_province_id_other')), " select2", "", "pob_province_id_other", "") !!}
                                            </div>

                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="mb-1" for="pob_district_id">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                                {!! showSelect('pob_district_id_other', array(), old('pob_district_id_other'), " select2", "", "pob_district_id_other", "") !!}
                                            </div>

                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="mb-1" for="pob_commune_id_other">ឃុំ-សង្កាត់</label>
                                                {!! showSelect('pob_commune_id_other', array(), old('pob_commune_id_other'), " select2", "", "pob_commune_id_other", "") !!}
                                            </div>
                                        </div>
                                        <div class="row col-12">
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="mb-1" for="province_other">អាសយដ្ឋានបច្ចុប្បន្ន រាជធានី-ខេត្ត</label>
                                                {!! showSelect('province_other', arrayProvince(1,0), old('province_other'), " select2", "", "province_other", "") !!}
                                            </div>

                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="mb-1" for="district_other">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                                {!! showSelect('district_other', array(), old('district_other'), " select2", "", "district_other", "") !!}
                                            </div>

                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="mb-1" for="commune_other">ឃុំ-សង្កាត់</label>
                                                {!! showSelect('commune_other', array(), old('commune_other'), " select2", "", "commune_other", "") !!}
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="mb-1" for="village_other">ភូមិ</label>
                                                {!! showSelect('village_other', array(), old('village_other'), " select2", "", "village_other", "") !!}
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="addr_house_no_other" class="mb-1">ផ្ទះលេខ</label>
                                                <input type="text" name="addr_house_no_other" id="addr_house_no_other" value="{{ old('addr_house_no_other') }}" class="form-control" >
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="mb-1" for="addr_street_other">ផ្លូវ</label>
                                                <input type="text" name="addr_street_other" id="addr_street_other" value="{{ old('addr_street_other') }}" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div id="addSubEmpBlock" class="mb-3" style="display: none">
                                        <div class="form-group col-12">
                                            <div id="response_message_employee" style="display: none;">Waiting for response...</div>
                                        </div>
                                        <div class="row col-12">
                                            <div class="form-group col-sm-12 mt-3">
                                                <label for="find_employee_autocomplete" class="text-primary text-hanuman-20 mb-1"> ស្វែងរកឈ្មោះ អ្នកអមតំណាងកម្មករនិយោជិត</label>
                                                <input type="text" name="find_employee_autocomplete" minlength="2" value="{{ old('find_employee_autocomplete') }}" class="form-control" id="find_employee_autocomplete" >
                                            </div>
                                        </div>
                                        <div class="row col-12 mt-3">
                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="name" class="required2 fw-bold mb-1">ឈ្មោះអ្នកអម តំណាងកម្មករនិយោជិត</label>
                                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="name" >
                                            </div>
                                            <div class="form-group col-sm-2 mt-3">
                                                <label class="required2 fw-bold mb-1" for="gender">ភេទ</label>
                                                {!! showSelect('gender', array("1" =>"ប្រុស", "2" => "ស្រី"), old('gender'), " select2", "", "gender") !!}
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="required2 fw-bold mb-1" for="dob">ថ្ងៃខែឆ្នាំកំណើត</label>
                                                <input type="text"  name="dob" id="dob" value="{{ old('dob') }}" class="form-control"  data-language="en" >
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="required2 fw-bold mb-1" for="nationality">សញ្ជាតិ</label>
                                                {!! showSelect('nationality', arrayNationality(1), old('nationality'), " select2", "", "nationality") !!}
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label for="id_number" class="fw-bold mb-1"> លេខអត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
                                                <input type="text" name="id_number" value="{{ old('id_number') }}" class="form-control" id="id_number" >
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label for="phone_number" class="required2 fw-bold mb-1">លេខទូរស័ព្ទខ្សែទី១</label>
                                                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" class="form-control" >
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label for="phone_number" class="fw-bold mb-1">លេខទូរស័ព្ទខ្សែទី២</label>
                                                <input type="text" name="phone2_number" id="phone2_number" value="{{ old('phone2_number') }}" class="form-control" >
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label for="occupation" class="required2 fw-bold mb-1">មុខងារ</label>
                                                <input type="text" name="occupation" id="occupation" value="{{ old('occupation') }}" class="form-control" >
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="required2 fw-bold mb-1" for="pob_province_id">ទីកន្លែងកំណើត រាជធានី-ខេត្ត</label>
                                                {!! showSelect('pob_province_id', arrayProvince(1,0), old('pob_province_id', request('pob_province_id')), " select2", "", "pob_province_id", "") !!}
                                            </div>

                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="fw-bold mb-1" for="pob_district_id">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                                {!! showSelect('pob_district_id', array(), old('pob_district_id'), " select2", "", "pob_district_id", "") !!}
                                            </div>

                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="fw-bold mb-1" for="pob_commune_id">ឃុំ-សង្កាត់</label>
                                                {!! showSelect('pob_commune_id', array(), old('pob_commune_id'), " select2", "", "pob_commune_id", "") !!}
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="required2 fw-bold mb-1" for="province">អាសយដ្ឋានបច្ចុប្បន្ន រាជធានី-ខេត្ត</label>
                                                {!! showSelect('province', arrayProvince(1,0), old('province'), " select2", "", "province", "") !!}
                                            </div>

                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="required2 fw-bold mb-1" for="district">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                                {!! showSelect('district', array(), old('district'), " select2", "", "district", "") !!}
                                            </div>

                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="required2 fw-bold mb-1" for="commune">ឃុំ-សង្កាត់</label>
                                                {!! showSelect('commune', array(), old('commune'), " select2", "", "commune", "") !!}
                                            </div>
                                            <div class="form-group col-sm-2 mt-3">
                                                <label class="fw-bold mb-1" for="village">ភូមិ</label>
                                                {!! showSelect('village', array(), old('village'), " select2", "", "village", "") !!}
                                            </div>
                                            <div class="form-group col-sm-2 mt-3">
                                                <label for="addr_house_no" class="fw-bold mb-1">ផ្ទះលេខ</label>
                                                <input type="text" name="addr_house_no" id="addr_house_no" value="{{ old('addr_house_no') }}" class="form-control" >
                                            </div>
                                            <div class="form-group col-sm-2 mt-3">
                                                <label class="fw-bold mb-1" for="addr_street">ផ្លូវ</label>
                                                <input type="text" name="addr_street" id="addr_street" value="{{ old('addr_street') }}" class="form-control" />
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row col-12 mt-3">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label for="contact_phone" class="text-primary text-hanuman-20 mb-1">* យោបល់របស់ប្រធានអង្គប្រជុំ</label>
                                            {!! showTextarea("collectives_head_meeting_comment", old('collectives_head_meeting_comment')) !!}
                                        </div>
                                    </div>

                                    <div class="row col-12 mt-3">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label for="contact_phone" class="text-primary text-hanuman-20 mb-1">* យោបល់របស់តំណាងកម្មករនិយោជិត</label>
                                            {!! showTextarea("collectives_representatives_comment", old('collectives_representatives_comment')) !!}
                                        </div>
                                    </div>

{{--                                    <div class="row mt-3">--}}
{{--                                        <div class="form-group col-sm-12 mt-3">--}}
{{--                                            <label for="contact_phone" class="text-primary text-hanuman-20 mb-1">* សំណូមពរ</label>--}}
{{--                                            {!! showTextarea("log34_1", old('log34_1')) !!}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

                                    <div class="row col-12 mt-5">
                                        <label class="text-primary text-hanuman-24 col-6">ចំណុចទាមទាររបស់កម្មករនិយោជិត</label>
                                        <button class="btn btn-info col-2 text-hanuman-16" type="button" id="btn-add-issue">បន្ថែមចំណុចទាមទារ</button>
                                        <label class="col-1"></label>
                                        <button class="btn btn-danger col-2 text-hanuman-16" type="button" id="btn-remove-issue">លុបចំណុចទាមទារ</button>
                                    </div>

                                    <div class="row col-12">
                                        @if(count($case->collectivesIssues) > 0)
                                            @foreach($case->collectivesIssues as $cIssue)
                                                <div class="form-group col-12 mt-3 mb-3">
                                                    <label class="mb-1" for="issues1">ចំណុចទាមទារ</label>
                                                    <div class="d-flex align-items-center">
                                                        <input type="hidden" name="issueID[]" value="{{ $cIssue->id }}">
                                                        <textarea name="issues[]" class="form-control" rows="4" style="flex: 1;">{{ $cIssue->issue }}</textarea>
                                                        <button type="button" title="" class="btn btn-danger delete-issue ms-2">
                                                            <i data-feather="trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <!-- Container to append new sections -->
                                    <div id="collectiveIssue_1" class="row col-12">
                                        <div class="form-group col-12 mt-3 mb-3">
                                            <label class="mb-1" for="issues1">ចំណុចទាមទារ</label>
                                            <div class="d-flex align-items-center">
                                                <input type="hidden" name="issueID[]" value="0">
                                                <textarea name="issues[]" id="issues1" class="form-control" rows="4" style="flex: 1;"></textarea>
                                                {{--                                                <button type="button" title="" class="btn btn-danger delete-btn ms-2">--}}
                                                {{--                                                    <i data-feather="trash"></i>--}}
                                                {{--                                                </button>--}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row col-12">
                                        <div class="form-group col-sm-2 mt-3">
                                            <button type="submit" class="btn btn-success form-control">រក្សាទុក</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="moreAfterScript">
        @include('case.script.log34_script')
        @include('script.my_sweetalert2')
        <script>
            $(document).ready(function () {
                var counterIssue = 2;

                /** បន្ថែមចំណុចទាមទារ (Issues) */
                $("#btn-add-issue").click(function () {
                    if(counterIssue > 10){
                        return false;
                    }
                    // Create a jQuery object from the HTML code for Sub Disputant

                    var html = $('<div>', {
                        class: 'row',
                        id: 'collectiveIssue_'+ counterIssue,
                        html : `
                        <div class="form-group col-12 mt-3 mb-3">
                            <label class="mb-1">ចំណុចទាមទារ</label>
                            <div class="d-flex align-items-center">
                                <input type="hidden" name="issueID[]" value="0">
                                <textarea name="issues[]" class="form-control" rows="4" style="flex: 1;"></textarea>
                            </div>
                        </div>

                    `
                    });

                    // Append the HTML code to the div with id "disputant_emp"
                    $('#collectiveIssue_' + (counterIssue - 1)).after(html);
                    counterIssue ++;
                });
                /** លុបតំណាងប្រតិភូចរចា (តំណាងកម្មករនិយោជិត) */
                $("#btn-remove-issue").on("click", function() {
                    if(counterIssue == 2){
                        return false;
                    }
                    // Remove the last added input element
                    $('#collectiveIssue_' + (counterIssue - 1)).remove();
                    counterIssue--;
                });


                $('.delete-issue').on('click', function () {
                    const parentGroup = $(this).closest('.form-group'); // Find the parent group
                    Swal.fire({
                        title: 'តើអ្នកពិតជាចង់ លុបមែនឫ?',
                        // text: 'តើអ្នកពិតជាចង់ លុបមែនឫ?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'ពិតមែនហើយ',
                        cancelButtonText: 'អត់ទេ'
                    }).then((result) => {
                        if (result.isConfirmed) {

                            // parentGroup.remove(); // Remove the element if confirmed

                            // Remove the closest parent `.form-group` container
                            $(this).closest('.form-group').remove();

                            // Swal.fire(
                            //     'Deleted!',
                            //     'The item has been deleted.',
                            //     'success'
                            // );
                        }
                    });
                });
            });
        </script>
    </x-slot>
</x-admin.layout-main>
