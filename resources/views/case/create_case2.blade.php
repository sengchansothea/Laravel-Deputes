@php
    $casePre = $adata['caseNumber'];
    $cYear = myDate('y');
//    $arrOfficersInHand = arrayOfficerCaseInHand(1);
//    $domainID = Auth::user()->officerRole->domain_id ?? 0;
//    $arrOfficersInHand = arrayOfficerCaseInHandByDomain($domainID, 1);
    $arrCaseType = $adata['arrCaseType'];
    $arrSector = $adata['arrSector'];
    $arrCompanyType = $adata['arrCompanyType'];
    $arrProvince = $adata['arrProvince'];
    $arrNationality = $adata['arrNationality'];
    $arrObjectiveCase = $adata['arrObjectiveCase'];
    $arrContractType = $adata['arrContractType'];
    $arrNightWork = $adata['arrNightWork'];
    $arrHolidayWeek = $adata['arrHolidayWeek'];
    $arrHolidayYear = $adata['arrHolidayYear'];
    $arrOfficersInHand = $adata['arrOfficersInHand'];

@endphp
{{--{{ dd(ApiAdmin(341, "30")) }}--}}

<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/date-picker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/timepicker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link href="{{ rurl('assets/wizard.css') }}" rel="stylesheet" id="bootstrap-css">

        <style>
            #response_message_company {
                display: none;
                font-size: 25px;
                color: blue;
                animation: fadeInOut 2s linear infinite;
            }
            #response_message_employee {
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
                    <form name="formCreateCase" id="frmCaseCreated" action="{{ url('cases') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                        @method('POST')
                        @csrf
                        <input type="hidden" name="first_business_act" value=""  id="first_business_act">
                        <input type="hidden" name="article_of_company" value="0"  id="article_of_company">
                        <input type="hidden" name="csic_1" value=""  id="csic_1">
                        <input type="hidden" name="csic_2" value=""  id="csic_2">
                        <input type="hidden" name="csic_3" value=""  id="csic_3">
                        <input type="hidden" name="csic_4" value=""  id="csic_4">
                        <input type="hidden" name="csic_5" value=""  id="csic_5">
                        <input type="hidden" name="business_activity" value=""  id="business_activity">
                        <input type="hidden" name="business_activity1" value=""  id="business_activity1">
                        <input type="hidden" name="business_activity2" value=""  id="business_activity2">
                        <input type="hidden" name="business_activity3" value=""  id="business_activity3">
                        <input type="hidden" name="business_activity4" value=""  id="business_activity4">
                        <input type="hidden" name="company_register_number" value=""  id="company_register_number">
                        <input type="hidden" name="registration_date" value=""  id="registration_date">
                        <input type="hidden" name="company_tin" value=""  id="company_tin">
                        <input type="hidden" name="nssf_number" value=""  id="nssf_number">
                        <input type="hidden" name="single_id" value="" id="single_id">
                        <input type="hidden" name="operation_status" value="" id="operation_status">

                         {{-- <div class="stepwizard">
                            <div class="stepwizard-row setup-panel">
                                <div class="stepwizard-step">
                                    <a href="#step-1" type="button"
                                        class="btn btn-circle {{ $currentStep != 1 ? 'btn-default' : 'btn-primary' }} {{ $currentStep > 1 ? 'completed' : '' }}">1</a>
                                    <p>Step 1</p>
                                </div>
                                <div class="stepwizard-step">
                                    <a href="#step-2" type="button"
                                        class="btn btn-circle {{ $currentStep != 2 ? 'btn-default' : 'btn-primary' }} {{ $currentStep > 2 ? 'completed' : '' }}">2</a>
                                    <p>Step 2</p>
                                </div>
                                <div class="stepwizard-step">
                                    <a href="#step-3" type="button"
                                        class="btn btn-circle {{ $currentStep != 3 ? 'btn-default' : 'btn-primary' }}" disabled="disabled">3</a>
                                    <p>Step 3</p>
                                </div>
                            </div>
                        </div> --}}

                        <div class="card-body text-hanuman-17">
                            <div class="card-block row">
                                <div class="col-sm-12 col-lg-12 col-xl-12">
                                    <div class="form-group col-12">
                                        <div id="response_message_company" style="display: none;">Waiting for response...</div>
                                    </div>

{{--                                    {{ dd($caseIndex) }};--}}
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-4">
                                            <label for="case_type_id" class="fw-bold required mb-2"> ប្រភេទពាក្យបណ្ដឹង</label>{!! myToolTip(__("case.case_type")) !!}
                                            {!! showSelect('case_type_id',$arrCaseType, old('case_type_id', 1)) !!}
{{--                                            {!! showSelect('case_type_id',arrCaseType(1), old('case_type_id', 1)) !!}--}}
                                        </div>
                                        <div class="form-group col-sm-6 mt-4">
                                            <label for="case_type_id" class="fw-bold mb-2 required">លេខសំណុំរឿង</label>
                                            <input type="text" name="case_number" id="case_number" value="{{ old('case_number', $casePre) }}" class="form-control col-sm-2" required>

                                        </div>
                                    </div>
{{--                                    Plantiff Block--}}
                                    <div id="plantiff_block">
                                        <div class="row col-12  mt-4">
                                            <label class="text-purple text-hanuman-24" for="contact_phone">
                                                1. សហគ្រាស គ្រឹះស្ថាន
                                            </label>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-12 mt-3">
                                                <label for="case_type" class="text-primary text-hanuman-22 mb-1"> ស្វែងរកឈ្មោះសហគ្រាស គ្រឹះស្ថាន</label>
                                                <input type="text" name="find_company" minlength="2" value="{{ old('find_company') }}" class="form-control" id="find_company_autocomplete">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-12 mt-3">
                                                <button type="button" id="btn_search_company" value="0" class="btn btn-danger mb-3 text-hanuman-16">បិទព័ត៌មានលម្អិតរបស់សហគ្រាស គ្រឹះស្ថាន</button>
                                            </div>
                                            <div class="form-group col-sm-12" id="div_company_result">
                                                <textarea rows=10 name="company_result" id="company_result" class="form-control" readonly>
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6 mt-3">
                                                <label for="case_type" class="fw-bold required mb-1">
                                                    ឈ្មោះជាភាសាខ្មែរ</label>
                                                <input type="text" name="company_name_khmer" value="{{ old('company_name_khmer') }}" class="form-control" id="company_name_khmer" placeholder="" required>
                                                @error('company_name_khmer')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-6 mt-3">
                                                <input type="hidden" name="company_id_auto"  id="company_id_auto" value="0" >
                                                <input type="hidden" name="company_id"  id="company_id" value="0" >
                                                <input type="hidden" name="company_option"  id="company_option" value="0" >
                                                <label for="case_type" class="fw-bold mb-1">ឈ្មោះជាភាសាឡាតាំង</label>
                                                <input type="text" name="company_name_latin" value="{{ old('company_name_latin') }}" class="form-control" id="company_name_latin" placeholder="" required>
                                                @error('company_name_latin')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-6 mt-3">
                                                <label for="sector_id" class="fw-bold required mb-1">វិស័យ</label>
                                                {!! showSelect('sector_id', $arrSector, old('sector_id', request('sector_id')), " select2", "", "", "required") !!}
                                                {{--                                                {!! showSelect('sector_id', myArraySector(1, 0), old('sector_id', request('sector_id')), " select2", "", "", "required") !!}--}}

                                            </div>

                                            <div class="form-group col-sm-6 mt-3">
                                                <label class="fw-bold required mb-1">ប្រភេទសហគ្រាស</label>
                                                {!! showSelect('company_type_id', $arrCompanyType, old('company_type_id', request('company_type_id')), " select2", "", "", "required") !!}
                                                {{--                                                {!! showSelect('company_type_id', arrayCompanyType(1,0), old('company_type_id', request('company_type_id')), " select2", "", "", "required") !!}--}}

                                            </div>

                                        </div>
                                        <div class="row col-12  mt-4">
                                            <label class="text-pink text-hanuman-20" for="contact_phone">
                                                -អាសយដ្ឋាន
                                            </label>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold required mb-1">រាជធានី-ខេត្ត</label>
                                                {!! showSelect('province_id', $arrProvince, old('province_id', request('province_id')), " select2", "", "", "required") !!}
                                                {{--                                                {!! showSelect('province_id', arrayProvince(1, ""), old('province_id', request('province_id')), " select2", "", "", "required") !!}--}}
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold required mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                                {!! showSelect('district_id', array(), old('district_id', request('district_id')), " select2", "", "", "required") !!}
                                            </div>

                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold required mb-1">ឃុំ-សង្កាត់</label>
                                                {!! showSelect('commune_id', array(), old('commune_id', request('commune_id')), " select2", "", "", "required") !!}
                                            </div>

                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold mb-1">ភូមិ</label>
                                                {!! showSelect('village_id', array(), old('village_id', request('village_id')), " select2") !!}
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="case_type" class="fw-bold mb-1">អគារលេខ</label>
                                                <input type="text" name="building_no" value="{{ old('building_no') }}" class="form-control" id="building_no" >
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold mb-1">ផ្លូវ</label>
                                                <input type="text" name="street_no" id="street_no" value="{{ old('street_no') }}" class="form-control short2" />
                                            </div>
                                        </div>
                                        <div class="row col-12  mt-4">
                                            <label class="text-pink text-hanuman-20" for="contact_phone">
                                                -ទំនាក់ទំនង
                                            </label>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6 mt-3">
                                                <label for="company_phone_number"  class="fw-bold mb-1 required">លេខទូរស័ព្ទក្រុមហ៊ុន (ខ្សែទី១)</label>
                                                <input type="text" name="company_phone_number" id="company_phone_number" value="{{ old('company_phone_number') }}" class="form-control" minlength="9">
                                            </div>
                                            <div class="form-group col-sm-6 mt-3">
                                                <label for="company_phone_number2"  class="fw-bold mb-1">លេខទូរស័ព្ទក្រុមហ៊ុន (ខ្សែទី២)</label>
                                                <input type="text" name="" id="company_phone_number2" value="{{ old('company_phone_number2') }}" class="form-control" minlength="9">
                                            </div>
                                        </div>
                                    </div>

                                    


                                    {{--                                    Defendant Block--}}
                                    <div id="defendant_block">
                                        <div class="row col-12  mt-5">
                                            <label class="text-purple text-hanuman-24">
                                                2. កម្មករនិយោជិត
                                            </label>
                                        </div>
                                        <div class="form-group col-12">
                                            <div id="response_message_employee" style="display: none;">Waiting for response...</div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-12 mt-3">
                                                <label for="case_type" class="text-primary text-hanuman-18 mb-1"> ស្វែងរកឈ្មោះកម្មករនិយោជិត (អ្នកប្តឹង)</label>
                                                <input type="text" name="find_employee_autocomplete" value="{{ old('find_employee_autocomplete') }}" class="form-control" id="find_employee_autocomplete" >
                                            </div>
                                        </div>
                                        <div class="row col-12  mt-4">
                                            <label class="text-pink text-hanuman-20">
                                                -ព័ត៌មានទូទៅ
                                            </label>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="form-group col-sm-4">
                                                <label for="case_type" class="fw-bold required mb-1">ឈ្មោះអ្នកប្ដឹង</label>
                                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="name" placeholder="" required>
                                                @error('name')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="fw-bold mb-1 required">ភេទ</label>
                                                {!! showSelect('gender', array("1" =>"ប្រុស", "2" => "ស្រី"), old('gender'), " select2") !!}
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label for="" class="fw-bold required mb-1">សញ្ជាតិ</label>
                                                {{--                                                {!! showSelect('nationality', arrayNationality(1), old('nationality'), " select2") !!}--}}
                                                {!! showSelect('nationality', $arrNationality, old('nationality'), " select2") !!}
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold required mb-1">ថ្ងៃខែឆ្នាំកំណើត</label>
                                                <input type="text"  name="dob" id="dob" value="{{ old('dob') }}" class="form-control" placeholder="DD-MM-YYYY" data-language="en" required>
                                                @error('dob')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="id_number" class="fw-bold mb-1"> លេខអត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
                                                <input type="text" name="id_number"   value="{{ old('id_number') }}" class="form-control" id = "id_number" placeholder="">
                                                @error('id_number')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="occupation" class="fw-bold required mb-1">មុខងារ</label>
                                                <input type="text" name="occupation" value="{{ old('occupation') }}" class="form-control" id="occupation" placeholder="" required>
                                                @error('occupation')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="phone_number" class="fw-bold mb-1 required">លេខទូរស័ព្ទ (ខ្សែទី១)</label>
                                                <input type="tel" name="phone_number" value="{{ old('phone_number') }}" class="form-control" id="phone_number" minlength="9"  pattern="[0][1-9][0-9]{7}|[0][1-9][0-9]{8}">
                                                @error('phone_number')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="phone_number" class="fw-bold mb-1">លេខទូរស័ព្ទ (ខ្សែទី២)</label>
                                                <input type="tel" name="phone_number2" value="{{ old('phone_number2') }}" class="form-control" id="phone_number2" minlength="9" pattern="[0][1-9][0-9]{7}|[0][1-9][0-9]{8}">
                                                @error('phone_number2')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row col-12  mt-5">
                                            <label class="text-pink text-hanuman-20">
                                                -ទីកន្លែងកំណើត
                                            </label>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="form-group col-sm-3">
                                                <label for="" class="fw-bold mb-1">ប្រទេស</label>
                                                {!! showSelect('pob_country_id', $arrNationality, old('pob_country_id'), " select2") !!}
                                                {{--                                                {!! showSelect('pob_country_id', arrayNationality(1), old('pob_country_id'), " select2") !!}--}}
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label class="fw-bold mb-1 required">រាជធានី-ខេត្ត</label>
                                                {!! showSelect('pob_province_id', $arrProvince, old('pob_province_id', request('pob_province_id')), " select2") !!}
                                                {{--                                                {!! showSelect('pob_province_id', arrayProvince(1,0), old('pob_province_id', request('pob_province_id')), " select2") !!}--}}
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                                {!! showSelect('pob_district_id', array(), old('pob_district_id', request('pob_district_id')), " select2") !!}
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                                {!! showSelect('pob_commune_id', array(), old('pob_commune_id', request('pob_commune_id')), " select2") !!}
                                            </div>
                                        </div>
                                        <div class="row col-12  mt-5">
                                            <label class="text-pink text-hanuman-20">
                                                -អាសយដ្ឋានបច្ចុប្បន្ន
                                            </label>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="form-group col-sm-4">
                                                <label class="fw-bold required mb-1">រាជធានី-ខេត្ត</label>
                                                {!! showSelect('province', $arrProvince, old('province', request('province')), " select2", "", "", "required") !!}
                                                {{--                                                {!! showSelect('province', arrayProvince(1,0), old('province', request('province')), " select2", "", "", "required") !!}--}}
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="fw-bold required mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                                {!! showSelect('district', array(), old('pob_district', request('district')), " select2", "", "", "required") !!}
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="fw-bold mb-1 required">ឃុំ-សង្កាត់</label>
                                                {!! showSelect('commune', array(), old('commune', request('commune')), " select2", "", "", "") !!}
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold mb-1">ភូមិ</label>
                                                {!! showSelect('village', array(), old('village', request('village')), " select2") !!}
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="case_type" class="fw-bold mb-1">ផ្ទះលេខ</label>
                                                <input type="text" name="addr_house_no" value="{{ old('addr_house_no') }}" class="form-control" id="addr_house_no" >
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold mb-1">ផ្លូវ</label>
                                                <input type="text" name="addr_street" id="addr_street" value="{{ old('addr_street') }}" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            3. កម្មវត្ថុ
                                        </label>
                                    </div>
                                    <div class="row col-12 mt-2">
                                        <label class="text-pink text-hanuman-20">
                                            -កម្មវត្ថុបណ្ដឹង
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold required mb-1">កម្មវត្ថុបណ្ដឹង</label>
                                            {!! showSelect('case_objective_id', $arrObjectiveCase, old('case_objective_id', request('case_objective_id')), " select2", "", "", "required") !!}
                                            {{--                                            {!! showSelect('case_objective_id', arrayObjectiveCase(1), old('case_objective_id', request('case_objective_id')), " select2", "", "", "required") !!}--}}
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1">កម្មវត្ថុបណ្ដឹងផ្សេងៗ</label>
                                            <input type="text" name="case_ojective_other" id="case_ojective_other" value="{{ old('case_ojective_other') }}" class="form-control" />
                                        </div>

                                    </div>
                                    <div class="row col-12 mt-4">
                                        <label class="text-pink text-hanuman-20">
                                            -ការផ្ដាច់កិច្ចសន្យា
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1">ថ្ងៃខែឆ្នាំផ្ដាច់កិច្ចសន្យាការងារ</label>
                                            <input type="text"  name="terminated_contract_date" id="terminated_contract_date" value="{{ old('terminated_contract_date') }}" class="form-control"  data-language="en">
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1">ម៉ោងផ្ដាច់កិច្ចសន្យាការងារ</label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input name="terminated_contract_time" id="terminated_contract_time" value="{{ old("terminated_contract_time") }}"  class="form-control" type="text" data-bs-original-title="">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="text-pink text-hanuman-20 mb-2">
                                                -អង្គហេតុនៃវិវាទ
                                            </label>
                                            {!! showTextarea("case_objective_des", old('case_objective_des')) !!}
                                        </div>
                                    </div>
                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            4. កិច្ចសន្យាការងារ និងលក្ខខណ្ឌការងារ
                                        </label>
                                    </div>
                                    <div class="row col-12 mt-4">
                                        <label class="text-pink text-hanuman-20">
                                            -កិច្ចសន្យាការងារ
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold required mb-1">ថ្ងៃខែឆ្នាំចូលបម្រើការងារ</label>
                                            <input type="text"  name="disputant_sdate_work" id="disputant_sdate_work" value="{{ old('disputant_sdate_work') }}" class="form-control"  data-language="en" required>
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold required mb-1">ប្រភេទកិច្ចសន្យាការងារ</label>
                                            {!! showSelect('disputant_contract_type', $arrContractType, old('disputant_contract_type', request('disputant_contract_type')), " select2", "", "", "required") !!}
                                            {{--                                            {!! showSelect('disputant_contract_type', array(1 =>"កំណត់", 2 => "មិនកំណត់", 3 => "សាកល្បង"), old('disputant_contract_type', request('disputant_contract_type')), " select2", "", "", "required") !!}--}}
                                        </div>
                                    </div>
                                    <div class="row col-12 mt-4">
                                        <label class="text-pink text-hanuman-20">
                                            -ថិរវេលាធ្វើការ និងប្រាក់ឈ្នួល
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="mb-2 fw-bold required mb-1">ចំនួនម៉ោងធ្វើការក្នុងមួយថ្ងៃ</label>
                                            {{--                                            {!! showSelect('disputant_work_hour_day', array(8=>"8ម៉ោង", 9=> "9ម៉ោង"), old('disputant_work_hour_day', request('disputant_work_hour_day')), " select2", "", "", "required") !!}--}}
                                            <input type="number" step="0.01" name="disputant_work_hour_day" id="disputant_work_hour_day" value="{{ old('disputant_work_hour_day') }}" class="form-control" required/>
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="mb-2 fw-bold required mb-1">ចំនួនម៉ោងធ្វើការក្នុងមួយសប្ដាហ៍</label>
                                            {{--                                            {!! showSelect('disputant_work_hour_week', array(40=>"40ម៉ោង", 48=> "48ម៉ោង"), old('disputant_contract_type', request('disputant_work_hour_week')), " select2", "", "", "required") !!}--}}
                                            <input type="number" step="0.01"  name="disputant_work_hour_week" id="disputant_work_hour_week" value="{{ old('disputant_work_hour_week') }}" class="form-control" required/>
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-2">ប្រាក់ឈ្នួលប្រចាំខែ (ដុល្លារ)</label>{!! myToolTip() !!}
                                            <input type="number" step="0.01" name="disputant_salary" id="disputant_salary" value="{{ old('disputant_salary') }}" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="row col-12 mt-4">
                                        <label class="text-pink text-hanuman-20">
                                            -លក្ខខណ្ឌការងារ
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold required mb-1">ការងារវេនយប់</label>
                                            {!! showSelect('disputant_night_work', $arrNightWork, old('disputant_night_work', request('disputant_night_work')), " select2", "", "", "required") !!}
                                            {{--                                            {!! showSelect('disputant_night_work', array(1=>"ធ្លាប់ធ្វើ", 2=> "ម្ដងម្កាល", 3=> "មិនធ្លាប់ធ្វើ"), old('disputant_night_work', request('disputant_night_work')), " select2", "", "", "required") !!}--}}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold required mb-1">ការឈប់សម្រាកប្រចាំសប្ដាហ៍</label>
                                            {!! showSelect('disputant_holiday_week', $arrHolidayWeek, old('disputant_holiday_week', request('disputant_holiday_week')), " select2", "", "", "required") !!}
                                            {{--                                            {!! showSelect('disputant_holiday_week', array(1=>"ឈប់តាមប្រកាស", 2=> "ម្ដងម្កាល", 3=> "មិនធ្លាប់បានឈប់"), old('disputant_holiday_week', request('disputant_holiday_week')), " select2", "", "", "required") !!}--}}
                                        </div>
                                        <div class="form-group col-sm-8 mt-3">
                                            <label class="fw-bold required mb-1">ថ្ងៃបុណ្យជាតិ  និងការឈប់សម្រាកប្រចាំឆ្នាំដោយមានប្រាក់ឈ្នួល</label>
                                            {!! showSelect('disputant_holiday_year', $arrHolidayYear, old('disputant_holiday_year', request('disputant_holiday_year')), " select2", "", "", "required") !!}
                                            {{--                                            {!! showSelect('disputant_holiday_year', array(1=>"ឈប់តាមប្រកាស", 2=> "ធ្លាប់បានឈប់ម្ដងម្កាល", 3=> "មិនធ្លាប់បានឈប់"), old('disputant_holiday_year', request('disputant_holiday_year')), " select2", "", "", "required") !!}--}}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="text-pink text-hanuman-20 mb-2">
                                                -មូលហេតុចម្បងនៃវិវាទ
                                            </label>
                                            {!! showTextarea("case_first_reason", old('case_first_reason')) !!}
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            5. សំណូមពរ
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="fw-bold mb-1">សំណូមពររបស់អ្នកប្ដឹង</label>
                                            {!! showTextarea("disputant_request", old('disputant_request')) !!}
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            6. កាលបរិច្ឆេទ
                                        </label>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="form-group col-sm-6">
                                            <label class="fw-bold required mb-1">កាលបរិច្ឆេទធ្វើបណ្ដឹង</label>
                                            <input type="text"  name="case_date" id="case_date" value="{{ old('case_date') }}" class="form-control"  data-language="en" required>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="fw-bold mb-1 required">កាលបរិច្ឆេទប្តឹងទៅអធិការការងារ</label>
                                            <input type="text"  name="case_date_entry" id="case_date_entry" value="{{ old('case_date_entry') }}" class="form-control"  data-language="en" required>
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            7. មន្ត្រីទទួលបន្ទុក
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1 required">អ្នកផ្សះផ្សា</label>
                                            {{--                                            {!! showSelect('officer_id', arrayOfficer(0,1, ""), old('officer_id'), " select2", "", "", "") !!}--}}
                                            {!! showSelect('officer_id', $arrOfficersInHand, old('officer_id'), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1">លេខាកត់ត្រា</label>
                                            {{--                                            {!! showSelect('officer_id8', arrayOfficer(0,1, ""), old('officer_id8'), " select2", "", "", "") !!}--}}
                                            {!! showSelect('officer_id8', $arrOfficersInHand, old('officer_id8'), " select2", "", "", "") !!}
                                        </div>
                                    </div>
                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            8. Upload ឯកសារពាក្យបណ្ដឹង
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-4">
                                            {!! upload_file("case_file", "សូមជ្រើសរើសឯកសារ(មានទំហំធំបំផុត 5MB)") !!}
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-success form-control fw-bold">រក្សាទុក</button>
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
        @include('case.script.case_script')
    </x-slot>
</x-admin.layout-main>
