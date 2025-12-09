@php
    $row = $adata['case'];
    $caseNumber = !empty($row->case_number) ? $row->case_number : 0;
    $cYear = !empty($row->case_date_entry) ? date2Display($row->case_date_entry, "y") : myDate('y');myDate('y');
    $caseYear = !empty($row->case_date) ? date2Display($row->case_date, "Y") : myDate('Y');

    $arrCaseType = $adata['arrCaseType'];
    $arrSector = $adata['arrSector'];
    $arrCompanyType = $adata['arrCompanyType'];

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
                    <form name="formCreateCase" action="{{ url('cases/'.$row->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="case_id" value="{{ $row->id }}" />
                        <input type="hidden" name="disputant_id" value="{{ $row->disputant_id }}" />
                        <input type="hidden" name="company_id" value="{{ $row->company_id }}" />
                        <div class="card-body text-hanuman-17">
                            <div class="card-block row">
                                <div class="col-sm-12 col-lg-12 col-xl-12">
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label for="case_type_id" class="fw-bold required mb-2"> ប្រភេទពាក្យបណ្ដឹង</label>{!! myToolTip(__("case.case_type")) !!}
                                            {!! showSelect('case_type_id',$arrCaseType, old('case_type_id', $row->case_type_id)) !!}
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label for="case_type_id" class="fw-bold mb-2 required">លេខសំណុំរឿង</label>
                                            <div class="d-flex">
                                                <input style="" type="text" name="case_number" id="case_number" value="{{ old('case_number', $caseNumber) }}" class="form-control" required>
                                                {!! nbs(3) !!}
                                                <input style="" type="text" name="case_num_str" id="case_num_str" value="{{ old('case_num_str') }}" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <label class="text-purple text-hanuman-24">
                                            1. សហគ្រាស គ្រឹះស្ថាន
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label for="company_name_khmer" class="form-label fw-bold mb-1 required">
                                                ឈ្មោះជាភាសាខ្មែរ</label>
                                            <input type="text" name="company_name_khmer" value="{{ old('company_name_khmer', $row->company->company_name_khmer) }}" class="form-control" id="company_name_khmer" placeholder="" readonly>
                                            @error('company_name_khmer')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            {{--                                            <input type="hidden" name="company_id_auto"  id="company_id_auto" value="0" >--}}
                                            {{--                                            <input type="hidden" name="company_id"  id="company_id" value="0" >--}}
                                            {{--                                            <input type="hidden" name="company_option"  id="company_option" value="0" >--}}
                                            <label for="company_name_latin" class="form-label fw-bold mb-1">ឈ្មោះជាភាសាឡាតាំង</label>
                                            <input type="text" name="company_name_latin" value="{{ old('company_name_latin', $row->company->company_name_latin) }}" class="form-control" id="company_name_latin" placeholder="" readonly>
                                            @error('company_name_latin')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label for="sector_id" class="form-label fw-bold mb-1 required">វិស័យ</label>
                                            {!! showSelect('sector_id', $arrSector, old('sector_id', $row->sector_id), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label for="company_type_id" class="form-label fw-bold mb-1 required">ប្រភេទសហគ្រាស</label>
                                            {!! showSelect('company_type_id', $arrCompanyType, old('company_type_id', $row->company_type_id), " select2", "", "", "required") !!}
                                        </div>

                                    </div>
                                    <div class="row mt-4">
                                        <label class="text-pink text-hanuman-20">
                                            -អាសយដ្ឋានបច្ចុប្បន្ន
                                        </label>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="form-group col-sm-4">
                                            <label class="form-label fw-bold mb-1 required">អាសយដ្ឋាន រាជធានី-ខេត្ត</label>
                                            {!! showSelect('province_id', $adata['arrProvinceID'], old('province_id', $adata['provinceID']), " select2", "", "", "required") !!}
{{--                                            {!! showSelect('province_id', arrayProvince(1, ""), old('province_id', $province_id), " select2", "", "", "required") !!}--}}
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="form-label fw-bold mb-1 required">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                            {!! showSelect('district_id', $adata['arrayDistrictID'], old('district_id', $adata['districtID']), " select2", "", "", "required") !!}
{{--                                            {!! showSelect('district_id', $arrayDistrictId, old('district_id', $district_id), " select2", "", "", "required") !!}--}}
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="form-label fw-bold mb-1 required">ឃុំ-សង្កាត់</label>
                                            {!! showSelect('commune_id', $adata['arrayCommuneID'], old('commune_id', $adata['communeID']), " select2", "", "", "required") !!}
{{--                                            {!! showSelect('commune_id', $arrayCommuneId, old('commune_id', $commune_id), " select2", "", "", "required") !!}--}}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="form-label fw-bold mb-1">ភូមិ</label>
                                            {!! showSelect('village_id', $adata['arrayVillageID'], old('village_id', $adata['villageID']), " select2") !!}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="form-label fw-bold mb-1" for="case_type">អគារលេខ</label>
                                            <input type="text" name="building_no" value="{{ old('building_no', $adata['buildingNO']) }}" class="form-control" id="building_no" >
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="form-label fw-bold mb-1">ផ្លូវ</label>
                                            <input type="text" name="street_no" id="street_no" value="{{ old('street_no', $adata['streetNo']) }}" class="form-control short2" />
                                        </div>
                                    </div>
                                    {{--                                    <div class="form-group col-12">--}}
                                    {{--                                        <div id="response-message" style="display: none;">Waiting for response...</div>--}}
                                    {{--                                    </div>--}}
                                    {{--                                    <div class="row col-12">--}}
                                    {{--                                        <div class="form-group col-sm-12 mt-3">--}}
                                    {{--                                            <label for="case_type" class="text-primary text-hanuman-24"> ស្វែងរកឈ្មោះអ្នកប្តឹង</label>--}}
                                    {{--                                            <input type="text" name="find_employee_autocomplete" minlength="2" value="{{ old('find_employee_autocomplete') }}" class="form-control" id="find_employee_autocomplete" >--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}

                                    <div class="row mt-4">
                                        <label class="text-pink text-hanuman-20">
                                            -ទំនាក់ទំនង
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label for="company_phone_number"  class="form-label fw-bold mb-1 required">លេខទូរស័ព្ទក្រុមហ៊ុន (ខ្សែទី១)</label>
                                            <input type="text" name="company_phone_number" id="company_phone_number" value="{{ old('company_phone_number', $adata['companyPhone']) }}" class="form-control" minlength="9" maxlength="10">
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label for="company_phone_number2"  class="form-label fw-bold mb-1">លេខទូរស័ព្ទក្រុមហ៊ុន (ខ្សែទី២)</label>
                                            <input type="text" name="company_phone_number2" id="company_phone_number2" value="{{ old('company_phone_number2', $adata['companyPhone2']) }}" class="form-control" minlength="9" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="row col-12  mt-2">
                                        <label class="text-purple text-hanuman-24">
                                            2. កម្មករនិយោជិត
                                        </label>
                                    </div>
                                    <div class="row col-12 mt-2">
                                        <label class="text-pink text-hanuman-20">
                                            -ព័ត៌មានទូទៅ
                                        </label>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="form-group col-sm-4 mt-3">
                                            <label for="case_type" class="form-label fw-bold mb-1 required">ឈ្មោះអ្នកប្ដឹង</label>
                                            <input type="text" name="name" value="{{ old('name', $adata['disputantName']) }}" class="form-control" id="name" placeholder="" required>
                                            @error('name')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="form-label fw-bold mb-1 required">ភេទ</label>
                                            {!! showSelect('gender', array("1" =>"ប្រុស", "2" => "ស្រី"), old('gender', $adata['disputantGender']), " select2") !!}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label for="" class="form-label fw-bold mb-1 required">សញ្ជាតិ</label>
                                            {!! showSelect('nationality', $adata['arrNationality'], old('nationality', $adata['disputantNationality']), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="form-label fw-bold mb-1 required">ថ្ងៃខែឆ្នាំកំណើត</label>
                                            <input type="text"  name="dob" id="dob" value="{{ old('dob', date2Display($adata['disputantDOB'])) }}" class="form-control" placeholder="DD-MM-YYYY" data-language="en" required>
                                            @error('dob')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-sm-4 mt-3">
                                            <label for="id_number" class="form-label fw-bold mb-1"> លេខអត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
                                            <input type="text" name="id_number" value="{{ old('id_number', $adata['disputantIDNumber']) }}" class="form-control" id="id_number" placeholder="">
                                            @error('id_number')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label for="occupation" class="form-label fw-bold mb-1 required">មុខងារ</label>
                                            <input type="text" name="occupation" value="{{ old('occupation', $adata['caseDisputantOccupation']) }}" class="form-control" id="occupation" placeholder="" required>
                                            @error('occupation')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label for="phone_number" class="form-label fw-bold mb-1">លេខទូរស័ព្ទ (ខ្សែទី១)</label>
                                            <input type="tel" name="phone_number" value="{{ old('phone_number', $adata['caseDisputantPhoneNumber']) }}" class="form-control" id="phone_number" minlength="9" pattern="[0][1-9][0-9]{7}|[0][1-9][0-9]{8}">
                                            @error('phone_number')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label for="phone_number" class="form-label fw-bold mb-1">លេខទូរស័ព្ទ (ខ្សែទី២)</label>
                                            <input type="tel" name="phone_number2" value="{{ old('phone_number2', $adata['caseDisputantPhoneNumber2']) }}" class="form-control" id="phone_number2" minlength="9" pattern="[0][1-9][0-9]{7}|[0][1-9][0-9]{8}">
                                            @error('phone_number2')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <label class="text-pink text-hanuman-20">
                                            -ទីកន្លែងកំណើត
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-3 mt-3">
                                            <label for="" class="form-label fw-bold mb-1">ប្រទេស</label>
                                            {!! showSelect('pob_country_id', $adata['arrNationality'], old('pob_country_id', $adata['pobCountryID']), " select2") !!}
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold required mb-1">ទីកន្លែងកំណើត រាជធានី-ខេត្ត</label>
                                            {!! showSelect('pob_province_id', $adata['arrProvinceID'], old('pob_province_id', $adata['pobProvinceID']), " select2", "", "", "required") !!}
                                        </div>

                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold form-label mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                            {!! showSelect('pob_district_id', $adata['arrPOBDistrictID'], old('pob_district_id', $adata['pobDistrictID']), " select2", "", "", "") !!}
                                        </div>

                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold form-label mb-1">ឃុំ-សង្កាត់</label>
                                            {!! showSelect('pob_commune_id', $adata['arrPOBCommuneID'], old('pob_commune_id', $adata['pobCommuneID']), " select2", "", "", "") !!}
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <label class="text-pink text-hanuman-20">
                                            -អាសយដ្ឋានបច្ចុប្បន្ន
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="form-label fw-bold required mb-1">អាសយដ្ឋានបច្ចុប្បន្ន រាជធានី-ខេត្ត</label>
                                            {!! showSelect('province', $adata['arrProvinceID'], old('province', $adata['caseDisputantProvince']), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="form-label fw-bold mb-1 required">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                            {!! showSelect('district', $adata['arrCaseDisputantDistrict'], old('pob_district', $adata['caseDisputantDistrict']), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="form-label fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                            {!! showSelect('commune', $adata['arrCaseDisputantCommune'], old('commune', $adata['caseDisputantCommune']), " select2", "", "", "") !!}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="form-label fw-bold mb-1">ភូមិ</label>
                                            {!! showSelect('village', $adata['arrCaseDisputantVillage'], old('village', $adata['caseDisputantVillage']), " select2") !!}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="form-label fw-bold mb-1" for="case_type">ផ្ទះលេខ</label>
                                            <input type="text" name="addr_house_no" value="{{ old('addr_house_no', $adata['caseDisputantHouseNo']) }}" class="form-control" id="addr_house_no" >
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="form-label fw-bold mb-1">ផ្លូវ</label>
                                            <input type="text" name="addr_street" id="addr_street" value="{{ old('addr_street', $adata['caseDisputantStreetNo']) }}" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-4">
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
                                            <label class="fw-bold required form-label mb-1">កម្មវត្ថុបណ្ដឹង</label>
                                            {!! showSelect('case_objective_id', arrayObjectiveCase(1), old('case_objective_id', $row->case_objective_id), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="form-label fw-bold m-b1">កម្មវត្ថុបណ្ដឹងផ្សេងៗ</label>
                                            <input type="text" name="case_ojective_other" id="case_ojective_other" value="{{ old('case_ojective_other', $row->case_ojective_other) }}" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="row col-12 mt-2">
                                        <label class="text-pink text-hanuman-20">
                                            -ការផ្ដាច់កិច្ចសន្យា
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="form-label fw-bold mb-1">ថ្ងៃខែឆ្នាំផ្ដាច់កិច្ចសន្យាការងារ</label>
                                            <input type="text"  name="terminated_contract_date" id="terminated_contract_date" value="{{ old('terminated_contract_date', date2Display($row->terminated_contract_date)) }}" class="form-control"  data-language="en" >
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="form-label fw-bold mb-1">ម៉ោងផ្ដាច់កិច្ចសន្យាការងារ</label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input name="terminated_contract_time" id="terminated_contract_time" value="{{ old("terminated_contract_time", date2Display($row->terminated_contract_time,'H:i')) }}"  class="form-control" type="text" data-bs-original-title="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="text-pink text-hanuman-20 mb-2">
                                                -អង្គហេតុនៃវិវាទ
                                            </label>
                                            {!! showTextarea("case_objective_des", old('case_objective_des', $row->case_objective_des)) !!}
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
                                            <input type="text"  name="disputant_sdate_work" id="disputant_sdate_work" value="{{ old('disputant_sdate_work', date2Display($row->disputant_sdate_work)) }}" class="form-control"  data-language="en" required>
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold required mb-1">ប្រភេទកិច្ចសន្យាការងារ</label>
                                            {!! showSelect('disputant_contract_type', array(1 =>"កំណត់", 2 => "មិនកំណត់", 3 => "សាកល្បង"), old('disputant_contract_type', $row->disputant_contract_type), " select2", "", "", "required") !!}
                                        </div>
                                    </div>

                                    <div class="row col-12 mt-4">
                                        <label class="text-pink text-hanuman-20">
                                            -ថិរវេលាធ្វើការ និងប្រាក់ឈ្នួល
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold required mb-1">ចំនួនម៉ោងធ្វើការក្នុងមួយថ្ងៃ</label>
{{--                                            {!! showSelect('disputant_work_hour_day', array(8=>"8ម៉ោង", 9=> "9ម៉ោង"), old('disputant_work_hour_day', $row->disputant_work_hour_day), " select2", "", "", "required") !!}--}}
                                            <input type="number" step="0.01" name="disputant_work_hour_day" id="disputant_work_hour_day" value="{{ old('disputant_work_hour_day', $row->disputant_work_hour_day) }}" class="form-control" required/>
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold required mb-1">ចំនួនម៉ោងធ្វើការក្នុងមួយសម្ដាហ៍</label>
{{--                                            {!! showSelect('disputant_work_hour_week', array(40=>"40ម៉ោង", 48=> "48ម៉ោង"), old('disputant_contract_type', $row->disputant_work_hour_week), " select2", "", "", "required") !!}--}}
                                            <input type="number" step="0.01" name="disputant_work_hour_week" id="disputant_work_hour_week" value="{{ old('disputant_work_hour_week', $row->disputant_work_hour_week) }}" class="form-control" required/>
                                        </div>
                                        <div class="form-group col-sm-4 mt-2">
                                            <label class="fw-bold mb-2">ប្រាក់ឈ្នួលប្រចាំខែ (ដុល្លារ)</label>{!! myToolTip() !!}
                                            <input type="number" step="0.01"  name="disputant_salary" id="disputant_salary" value="{{ old('disputant_salary', $row->disputant_salary) }}" class="form-control" />
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
                                            {!! showSelect('disputant_night_work', array(1=>"ធ្លាប់ធ្វើ", 2=> "ម្ដងម្កាល", 3=> "មិនធ្លាប់ធ្វើ"), old('disputant_night_work', $row->disputant_night_work), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold required mb-1">ការឈប់សម្រាកប្រចាំសប្ដាហ៍</label>
                                            {!! showSelect('disputant_holiday_week', array(1=>"ឈប់តាមប្រកាស", 2=> "ម្ដងម្កាល", 3=> "មិនធ្លាប់បានឈប់"), old('disputant_holiday_week', $row->disputant_holiday_week), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-8 mt-3">
                                            <label class="fw-bold required mb-1">ថ្ងៃបុណ្យជាតិ  និងការឈប់សម្រាកប្រចាំឆ្នាំដោយមានប្រាក់ឈ្នួល</label>
                                            {!! showSelect('disputant_holiday_year', array(1=>"ឈប់តាមប្រកាស", 2=> "ធ្លាប់បានឈប់ម្ដងម្កាល", 3=> "មិនធ្លាប់បានឈប់"), old('disputant_holiday_year', $row->disputant_holiday_year), " select2", "", "", "required") !!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="text-pink text-hanuman-20 mb-2">
                                                -មូលហេតុចម្បងនៃវិវាទ
                                            </label>
                                            {!! showTextarea("case_first_reason", old('case_first_reason', $row->case_first_reason)) !!}
                                        </div>
                                    </div>
                                    <div class="row col-12  mt-4">
                                        <label class="text-purple text-hanuman-24">
                                            5. សំណូមពរ
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="form-label fw-bold mb-1">សំណូមពររបស់អ្នកប្ដឹង</label>
                                            {!! showTextarea("disputant_request", old('disputant_request', $row->disputant_request)) !!}
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-4">
                                        <label class="text-purple text-hanuman-24">
                                            6. កាលបរិច្ឆេទ
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold required mb-1">កាលបរិច្ឆេទធ្វើបណ្ដឹង</label>
                                            <input type="text"  name="case_date" id="case_date" value="{{ old('case_date', date2Display($row->case_date)) }}" class="form-control"  data-language="en" required>
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold required mb-1">កាលបរិច្ឆេទប្តឹងទៅអធិការការងារ</label>
                                            <input type="text"  name="case_date_entry" id="case_date_entry" value="{{ old('case_date_entry', date2Display($row->case_date_entry)) }}" class="form-control"  data-language="en" >
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-4">
                                        <label class="text-purple text-hanuman-24">
                                            7. មន្ត្រីទទួលបន្ទុក
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold required mb-1">អ្នកផ្សះផ្សា</label>
                                            {!! showSelect('officer_id', $adata['arrOfficers'], old('officer_id', $adata['lastOfficers']), " select2", "", "", "required") !!}
{{--                                            {!! showSelect('officer_id', arrayOfficerCaseInHandByDomain($domainID), old('officer_id', getLastOfficerID($row->id, 6)), " select2", "", "", "required") !!}--}}
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1">លេខាកត់ត្រា</label>
                                            {!! showSelect('officer_id8', $adata['arrNoters'], old('officer_id8', $adata['lastNoter']), " select2", "", "", "") !!}
{{--                                            {!! showSelect('officer_id8', arrayOfficerCaseInHand(), old('officer_id8', getLastOfficerID($row->id, 8)), " select2", "", "", "") !!}--}}
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            8. Upload ឯកសារពាក្យបណ្ដឹង
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-4">
                                            <input type="hidden" name="case_file_old" value="{{ $row->case_file }}" >
                                            @php
                                            $showFile = showFile(1, $row->case_file, pathToDeleteFile('case_doc/form1/'.$caseYear."/"), "delete", "tbl_case", "id", $row->id,  "case_file");
                                            if($showFile){
                                                echo $showFile;
                                            }
                                            else{
                                                echo "<div class='py-2'>".upload_file("case_file", "(ប្រភេទឯកសារ pdf មានទំហំធំបំផុត 5MB)")."</div>";
                                            }
                                            @endphp
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
