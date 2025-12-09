@php
    $cYear = myDate('y');
//    $thisYear = myDate('Y');
//    $arrYears = [];
//    for ($i = 0; $i < 8; $i++) {
//        $arrYears[] = $thisYear - $i;
//    }

//$arrYears = array_reverse($arrYears); // Optional: to keep the years in ascending order
@endphp
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/date-picker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/timepicker.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <style>
            #response_message_company {
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
                    <form name="formCreateCase" action="{{ url('joint_cases') }}" method="POST" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <input type="hidden" name="company_id"  id="company_id">
                        <input type="hidden" name="company_phone_number" id="company_phone_number">
                        <input type="hidden" name="first_business_act" id="first_business_act">
                        <input type="hidden" name="article_of_company" id="article_of_company">
                        <input type="hidden" name="business_activity" id="business_activity">
                        <input type="hidden" name="business_activity1" id="business_activity1">
                        <input type="hidden" name="business_activity2" id="business_activity2">
                        <input type="hidden" name="business_activity3" id="business_activity3">
                        <input type="hidden" name="business_activity4" id="business_activity4">
                        <input type="hidden" name="company_register_number" id="company_register_number">
                        <input type="hidden" name="registration_date" id="registration_date">
                        <input type="hidden" name="company_tin" id="company_tin">
                        <input type="hidden" name="nssf_number" id="nssf_number">
                        <input type="hidden" name="street_no" id="street_no">
                        <input type="hidden" name="building_no" id="building_no">
                        <input type="hidden" name="vil_id" id ="vil_id">
                        <input type="hidden" name="com_id" id="com_id">
                        <input type="hidden" name="dis_id" id="dis_id">
                        <input type="hidden" name="pro_id" id="pro_id">

                        <div class="card-body text-hanuman-17">
                            <div class="card-block row">
                                <div class="col-sm-12 col-lg-12 col-xl-12">
                                    <div class="form-group col-12">
                                        <div id="response_message_company" style="display: none;">Waiting for response...</div>
                                    </div>
                                    <div class="row col-12  mt-4">
                                        <label class="text-purple text-hanuman-24" for="contact_phone">
                                            1. សហគ្រាស គ្រឹះស្ថាន
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label for="case_type" class="text-primary text-hanuman-22"> ស្វែងរកឈ្មោះសហគ្រាស គ្រឹះស្ថាន</label>
                                            <input type="text" name="find_company" minlength="2" value="{{ old('find_company') }}" class="form-control" id="find_company_autocomplete" >
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label for="case_type" class="fw-bold required">
                                                ឈ្មោះជាភាសាខ្មែរ</label>
                                            <input type="text" name="company_name_khmer" value="{{ old('company_name_khmer') }}" class="form-control" id="company_name_khmer" placeholder="" required>
                                            @error('company_name_khmer')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label for="case_type">ឈ្មោះជាភាសាឡាតាំង</label>
                                            <input type="text" name="company_name_latin" value="{{ old('company_name_latin') }}" class="form-control" id="company_name_latin" placeholder="">
                                            @error('company_name_latin')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label for="sector_id" class="fw-bold required">វិស័យ</label>
                                            {!! showSelect('sector_id', myArraySector(1,0), old('sector_id', request('sector_id')), " select2") !!}
                                        </div>

                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold required">ប្រភេទសហគ្រាស</label>
                                            {!! showSelect('company_type_id', arrayCompanyType(1,0), old('company_type_id', request('company_type_id')), " select2") !!}
                                        </div>
                                    </div>


                                    <div class="row mt-4">
                                        <div class="form-group col-sm-4">
                                            <label class="fw-bold required">ឆ្នាំនៃវិវាទ</label>
                                            <input name="case_year" class="datepicker-here form-control digits" type="text" data-language="en" data-min-view="years" data-view="years" data-date-format="yyyy" required>
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <label for="case_type" class="fw-bold required">កម្មករពាក់ព័ន្ធនឹងវិវាទ</label>
                                            <input type="number" min="0" name="total_disputed_emp" value="{{ old('total_disputed_emp') }}" class="form-control" id="total_disputed_emp" placeholder="" required>
                                            @error('total_disputed_emp')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label for="case_type" class="fw-bold required">ចំនួនកម្មករសរុប</label>
                                            <input type="number" min="0" name="total_emp" value="{{ old('total_emp') }}" class="form-control" id="total_emp" placeholder="">
                                            @error('total_emp')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="row col-12  mt-4">
                                        <label class="text-pink text-hanuman-20" for="contact_phone">
                                            -អាសយដ្ឋាន
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold required">រាជធានី-ខេត្ត</label>
                                            {!! showSelect('province_id', arrayProvince(1, ""), old('province_id', request('province_id')), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold required">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                            {!! showSelect('district_id', array(), old('district_id', request('district_id')), " select2", "", "", "required") !!}
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            2. ពត៌មានទូទៅនៃវិវាទ
                                        </label>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="text-pink text-hanuman-20 mb-2">
                                                -មូលហេតុចម្បងនៃវិវាទ
                                            </label>
                                            {!! showTextarea("disputed_reason", old('disputed_reason')) !!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="text-pink text-hanuman-20 mb-2">
                                                -សហជីពតំណាង
                                            </label>
                                            {!! showTextarea("union_representative", old('union_representative')) !!}
                                        </div>
                                    </div>


                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            3. Upload ឯកសារលទ្វផលនៃវិវាទ
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-4">
                                            {!! upload_file("result_file", " សូមជ្រើសរើសឯកសារ (មានទំហំធំបំផុត 15MB) ") !!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="text-pink text-hanuman-20 mb-2">
                                                -លទ្ធផលសះជា
                                            </label>
                                            {!! showTextarea("agree_result", old('agree_result')) !!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="text-pink text-hanuman-20 mb-2">
                                                -លទ្ធផលមិនសះជា
                                            </label>
                                            {!! showTextarea("disagree_result", old('disagree_result')) !!}
                                        </div>
                                    </div>



                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            4. Upload ឯកសារដំណោះស្រាយនៃវិវាទ
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-4">
                                            {!! upload_file("dispute_resolution_file", " សូមជ្រើសរើសឯកសារ (មានទំហំធំបំផុត 15MB) ") !!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="text-pink text-hanuman-20 mb-2">
                                                -ដំណោះស្រាយ
                                            </label>
                                            {!! showTextarea("dispute_resolution", old('dispute_resolution')) !!}
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            5. Upload ឯកសារវិធានការបន្ត
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-4">
                                            {!! upload_file("next_measure_file", " សូមជ្រើសរើសឯកសារ (មានទំហំធំបំផុត 15MB) ") !!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="text-pink text-hanuman-20 mb-2">
                                                -វិធានការបន្ត
                                            </label>
                                            {!! showTextarea("next_measure", old('next_measure')) !!}
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            6. អង្គភាព និងអ្នកទទួលបន្ទុក
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold required">អង្គភាព</label>
                                            {!! showSelect('unit_id', arrayUnit(0, 1, ""), old('unit_id'), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold">អ្នកទទួលបន្ទុក</label>
                                                <input type="text" name="responsible_person" id="responsible_person" value="{{ old('responsible_person') }}" class="form-control short2" />
{{--                                            {!! showSelect('officer_id8', arrayOfficer(0,1, ""), old('officer_id8'), " select2", "", "", "") !!}--}}
                                        </div>
                                    </div>
                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            7. Upload ឯកសារពាក្យបណ្ដឹងវិវាទរួម
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-4">
                                            {!! upload_file("joint_case_file", " សូមជ្រើសរើសឯកសារ (មានទំហំធំបំផុត 15MB) ") !!}
                                        </div>
                                    </div>

                                    <br/>
                                    <div class="row">
                                        <div class="form-group col-md-3">
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
        @include('case.script.joint_case_script')
        @include('script.my_sweetalert2')
        @include('case.script.joint_case_address_script')
    </x-slot>
</x-admin.layout-main>
