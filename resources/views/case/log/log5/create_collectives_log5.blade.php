@php
    $case = $adata['case'];
    $log34 = $adata['log34'];
@endphp
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/date-picker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/timepicker.css') }}">
{{--        Select2 Css--}}
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
                    <form name="formCreateCase" action="{{ url('collectives_log5') }}" method="POST" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <input type="hidden" name="case_type_id" value="{{ $case->case_type_id }}" >
                        <input type="hidden" name="case_id" value="{{ $adata['case_id'] }}" >
                        <input type="hidden" name="invitation_id" value="{{ $adata['invitation_id'] }}" >
                        <input type="hidden" name="disputant_id" value="{{ $case->disputant_id }}" >
                        <input type="hidden" name="comID" id="company_id" value="{{ $case->company->company_id_lacms }}" >
                        <input type="hidden" name="company_id" value="{{ $case->company_id }}" >
                        <div class="card-body text-hanuman-17">
                            <div class="card-block row">
                                <div class="col-sm-12 col-lg-12 col-xl-12">
                                    <div class="row col-12">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1 text-purple">ឈ្មោះសហគ្រាស គ្រឹះស្ថាន</label>
                                            <input type="text" name="company_name_khmer" id="company_name_khmer" value="{{ $case->company->company_name_khmer }}" class="form-control" disabled />
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1 text-purple" for="company_name_latin">ឈ្មោះជាភាសាឡាតាំង</label>
                                            <input type="text" id="company_name_latin" value="{{ $case->company->company_name_latin }}" class="form-control" disabled />
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1">អាសយដ្ឋាន</label>
                                            <input type="text" value="{{ $case->company->province->pro_khname }}" class="form-control" disabled />
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1">លេខទូរស័ព្ទ</label>
                                            <input type="text" value="{{ $case->caseCompany->log5_company_phone_number }}" class="form-control" disabled />
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1">លេខ TIN</label>
                                            <input type="text" value="{{ $case->company->company_tin }}" class="form-control" disabled />
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1">លេខចុះបញ្ជីពាណិជ្ជកម្ម</label>
                                            <input type="text" value="{{ $case->company->company_register_number }}" class="form-control" disabled />
                                        </div>
                                    </div>
                                    <div class="row col-12 mt-3">@php $counterCDis = 1 @endphp
                                        @foreach($case->collectivesCaseDisputantsEmp as $cCDisputant)
                                            <div class="form-group col-sm-3 mb-3">
                                                <label class="fw-bold text-purple" style="margin-bottom: 6px;">
                                                    តំណាងកម្មករនិយោជិតទី{{ Num2Unicode($counterCDis) }}
                                                </label>
                                                <input type="text" value="{{ $cCDisputant->disputant->name }}" class="form-control " disabled />
                                            </div>
                                            @php $counterCDis++ @endphp
                                        @endforeach
                                    </div>

                                    <div class="row col-12">
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1 required">ថ្ងៃខែឆ្នាំជួបប្រជុំ</label>
                                            <input type="text"  name="meeting_date" id="meeting_date" value="{{ old('meeting_date', myDate("d-m-Y")) }}" class="form-control"  data-language="en" required >
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1 required">ម៉ោងចាប់ផ្ដើម</label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input name="meeting_stime" id="meeting_stime" value="{{ old("meeting_stime", myTime()) }}"  class="form-control" type="text" data-bs-original-title="" required >
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1 required">ម៉ោងបញ្ចប់</label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input name="meeting_etime" id="meeting_etime" value="{{ old("meeting_etime") }}"  class="form-control" type="text" data-bs-original-title="" required >
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1 required">ទីកន្លែងប្រជុំ</label>
                                            {!! showSelect('meeting_place_id', array(1=>"នៅនាយកដ្ឋានវិវាទការងារ (ភ្នំពេញ)", 2 =>"នៅកន្លែងផ្សេង"), old('meeting_place_id'), " select2", "", "", "") !!}
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="mb-1 fw-bold">បើប្រជុំនៅកន្លែងផ្សេង</label>
                                            <input type="text" name="meeting_place_other" class="form-control">
                                        </div>

                                        <div class="form-group col-sm-12 mt-3">
{{--                                            @php--}}
{{--                                                $preGender = $case->disputant->gender == 1? "លោក":"លោកស្រី";--}}
{{--                                                $arrCaseDate = getDateAsKhmer($case->case_date_entry);--}}
{{--                                                $meetingAbout = "ចុះថ្ងៃទី" .$arrCaseDate["day"]--}}
{{--                                                        ." ខែ".$arrCaseDate["month"]." ឆ្នាំ".$arrCaseDate["year"];--}}
{{--                                            @endphp--}}
                                            <label class="fw-bold mb-1 required">អំពីបញ្ហា</label>
{{--                                            <input type="text" name="meeting_about" value="" id="meeting_about" class="form-control" required />--}}
                                            {!! showTextarea("meeting_about", old('meeting_about'),"4","required") !!}
{{--                                            <input type="text" name="meeting_about" value="ពាក្យបណ្ដឹងរបស់ {{ $preGender." ".$case->disputant->name." ".$meetingAbout }}" id="meeting_about" class="form-control" required />--}}
                                        </div>
                                    </div>
                                    <div class="row col-12 mt-5">
                                        <span class="text-primary text-hanuman-24">វត្តមានក្នុងកិច្ចប្រជុំមាន:</span>
                                    </div>
                                    @php
                                        $officerId = getCaseOfficer($case->id, 1, 6);
//                                        $officerNoter = $adata['noter']->attendant_id;
                                        $officerNoter = getCaseOfficer($case->id, 1, 8);
                                    @endphp
                                    <div class="row col-12 mt-3">
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1 required">ប្រធានអង្គប្រជុំ</label>
                                            {!! showSelect('head_meeting', arrayOfficer(0,1), old('head_meeting', $officerId), " select2", "", "", "") !!}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1 required">អ្នកកត់ត្រា</label>
                                            {!! showSelect('noter',  arrayOfficer(0,1), old('noter', $officerNoter), " select2", "", "", "required") !!}
{{--                                            {!! showSelect('noter', arrayOfficerExcept($officerId, 1, ""), old('noter'), " select2", "", "", "required") !!}--}}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1 required">ចុងបណ្ដឹង (តំណាងក្រុមហ៊ុន)</label>
                                            {!! showSelect('attendant_represent_company', arrayRepresentCompany(), old('attendant_represent_company'), " select2", "", "", "") !!}
                                        </div>
                                    </div>

                                    <div class="form-group col-12 mt-3">
                                        <div id="response_message_represent_company" style="display: none;">Waiting for response...</div>
                                    </div>
                                    <div class="row col-12 mt-3">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label for="case_type" class="text-primary text-hanuman-20 mb-1"> ស្វែងរកឈ្មោះចុងបណ្ដឹង (អ្នកតំណាងក្រុមហ៊ុន)</label>
                                            <input type="text" name="find_represent_company_autocomplete" minlength="2" value="{{ old('find_represent_company_autocomplete') }}" class="form-control" id="find_represent_company_autocomplete" >
                                        </div>
                                    </div>
                                    <div class="row col-12 mt-3">
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1 required2" for="case_type">ឈ្មោះចុងបណ្ដឹង</label>
                                            <input type="text" name="name[]" value="{{ old('name[]') }}" class="form-control" id="represent_company_name" >
                                        </div>
                                        <div class="form-group col-sm-2 mt-3">
                                            <label class="fw-bold mb-1 required2">ភេទ</label>
                                            {!! showSelect('gender[]', array("1" =>"ប្រុស", "2" => "ស្រី"), old('gender[]'), " select2", "", "represent_company_gender") !!}
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1 required2">ថ្ងៃខែឆ្នាំកំណើត</label>
                                            <input type="text"  name="dob[]" id="represent_company_dob" value="{{ old('dob[]') }}" class="form-control"  data-language="en" >
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1 required2">សញ្ជាតិ</label>
                                            {!! showSelect('nationality[]', arrayNationality(1), old('nationality'), " select2", "", "represent_company_nationality") !!}
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1" for="id_number"> លេខអត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
                                            <input type="text" name="id_number[]" value="{{ old('id_number[]') }}" class="form-control" id="represent_company_id_number" >
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1 required2" for="phone_number">លេខទូរស័ព្ទខ្សែទី១</label>
                                            <input type="text" name="phone_number[]" id="represent_company_phone_number" value="{{ old('phone_number[]') }}" class="form-control" >
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1" for="phone_number">លេខទូរស័ព្ទខ្សែទី២</label>
                                            <input type="text" name="phone2_number[]" id="represent_company_phone2_number" value="{{ old('phone2_number[]') }}" class="form-control" >
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1 required2" for="occupation">មុខងារ</label>
                                            <input type="text" name="occupation[]" id="represent_company_occupation" value="{{ old('occupation[]') }}" class="form-control" >
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1 required2">ទីកន្លែងកំណើត រាជធានី-ខេត្ត</label>
                                            {!! showSelect('pob_province_id[]', arrayProvince(1,0), old('pob_province_id', request('pob_province_id')), " select2", "", "represent_company_pob_province_id", "") !!}
                                        </div>

                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                            {!! showSelect('pob_district_id[]', array(), old('pob_district_id[]'), " select2", "", "represent_company_pob_district_id", "") !!}
                                        </div>

                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                            {!! showSelect('pob_commune_id[]', array(), old('pob_commune_id[]'), " select2", "", "represent_company_pob_commune_id", "") !!}
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1 required2">អាសយដ្ឋានបច្ចុប្បន្ន រាជធានី-ខេត្ត</label>
                                            {!! showSelect('province[]', arrayProvince(1,0), old('province[]'), " select2", "", "represent_company_province", "") !!}
                                        </div>

                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1 required2">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                            {!! showSelect('district[]', array(), old('pob_district[]'), " select2", "", "represent_company_district", "") !!}
                                        </div>

                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1 required2">ឃុំ-សង្កាត់</label>
                                            {!! showSelect('commune[]', array(), old('commune[]'), " select2", "", "represent_company_commune", "") !!}
                                        </div>
                                        <div class="form-group col-sm-2 mt-3">
                                            <label class="fw-bold mb-1">ភូមិ</label>
                                            {!! showSelect('village[]', array(), old('village[]'), " select2", "", "represent_company_village", "") !!}
                                        </div>
                                        <div class="form-group col-sm-2 mt-3">
                                            <label class="fw-bold mb-1" for="case_type">ផ្ទះលេខ</label>
                                            <input type="text" name="addr_house_no[]" id="represent_company_addr_house_no" value="{{ old('addr_house_no') }}" class="form-control" >
                                        </div>
                                        <div class="form-group col-sm-2 mt-3">
                                            <label class="fw-bold mb-1">ផ្លូវ</label>
                                            <input type="text" name="addr_street[]" id="represent_company_addr_street" value="{{ old('addr_street[]') }}" class="form-control" />
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row col-12">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="fw-bold mb-2 required" for="contact_phone">យោបល់របស់ប្រធានអង្គប្រជុំ</label>
                                            {!! showTextarea("head_officer_comment", old('head_officer_comment'),4,"required") !!}
                                        </div>
                                    </div>
                                    <div class="row col-12 mt-3">
                                        <span class="text-purple text-hanuman-24">ក. សាវតារសហគ្រាស:</span>
                                    </div>
                                    <div class="row col-12">
                                        <div class="form-group col-sm-6 mt-3">
{{--                                            <input type="hidden" name="company_id" value="{{ $row->company->company_id }}" >--}}
                                            <label class="fw-bold mb-1 required">ឈ្មោះក្រុមហ៊ុន</label>
                                            <input type="text" name="company_name_khmer" value="{{ $case->company->company_name_khmer }}" class="form-control" />
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1 required">ឈ្មោះក្រុមហ៊ុនជាភាសាឡាតាំង</label>
                                            <input type="text" name="company_name_latin" value="{{ $case->company->company_name_latin }}" class="form-control" />
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1">កាលបរិច្ឆេទបើកសហគ្រាស</label>
                                            <input type="text"  name="log5_open_date" id="open_date" value="{{ old('open_date', date2Display($case->company->open_date)) }}" class="form-control"  data-language="en">
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1">កាលបរិច្ឆេទចុះបញ្ជីពាណិជ្ជកម្ម</label>
                                            <input type="text"  name="registration_date" id="registration_date" value="{{ old('registration_date', date2Display($case->company->registration_date)) }}" class="form-control"  data-language="en">
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1">លេខចុះបញ្ជីពាណិជ្ជកម្ម</label>
                                            <input type="text"  name="company_register_number" id="company_register_number" value="{{ old('company_register_number', $case->company->company_register_number) }}" class="form-control">
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1">លេខអត្តសញ្ញាណ ប.ស.ស.</label>
                                            <input type="text"  name="nssf_number" id="nssf_number" value="{{ old('nssf_number', $case->company->nssf_number) }}" class="form-control">
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1">លេខអត្តសញ្ញាណកម្មសារពើពន្ធ (TIN)</label>
                                            <input type="text"  name="company_tin" id="company_tin" value="{{ old('company_tin', $case->company->company_tin) }}" class="form-control">
                                        </div>

                                    </div>
                                    {{--                                    Address of Head Office--}}
                                    @php
                                        $province_id = $case->company->province_id;
                                        $district_id = $case->company->district_id;
                                        $commune_id = $case->company->commune_id;
                                        $village_id = $case->company->village_id;

                                        $arrayDistrict = $district_id > 0? arrayDistrict($province_id, 1, ""): array();
                                        $arrayCommune = $commune_id > 0? arrayCommune($district_id, 1, ""): array();
                                        $arrayVillage = $commune_id > 0? arrayVillage($commune_id, 1, ""): array();

                                    @endphp
                                    <div class="row col-12">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1 required text-primary">អាសយដ្ឋាននៃមន្ទីរចាត់ការសហគ្រាស រាជធានី-ខេត្ត</label>
                                            {!! showSelect('log5_head_province_id', arrayProvince(1,0), old('log5_head_province_id', $province_id), " select2") !!}
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1 required">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                            {!! showSelect('log5_head_district_id', $arrayDistrict, old('log5_head_district_id', $district_id), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1 required">ឃុំ-សង្កាត់</label>
                                            {!! showSelect('log5_head_commune_id', $arrayCommune, old('log5_head_commune_id', $commune_id), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1">ភូមិ</label>
                                            {!! showSelect('log5_head_village_id', $arrayVillage, old('log5_head_village_id', $village_id), " select2") !!}
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1">ផ្លូវ</label>
                                            <input type="text" name="log5_head_street_no" id="log5_head_street_no" value="{{ old('log5_head_street_no', $case->company->street_no) }}" class="form-control" />
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1">អគារលេខ</label>
                                            <input type="text" name="log5_head_building_no" id="log5_head_building_no" value="{{ old('log5_head_building_no', $case->company->building_no) }}" class="form-control" />
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1">លេខទូរស័ព្ទ</label>
                                            <input type="text" name="log5_head_phone" value="{{ old('log5_head_phone', $case->company->company_phone_number) }}" class="form-control" id="log5_head_phone" placeholder="">
                                        </div>

                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1">នាមម្ចាស់សហគ្រាស</label>
                                            <input type="text" name="log5_owner_name_khmer" value="{{ old('log5_owner_name_khmer') }}" class="form-control" id="log5_owner_name_khmer" placeholder="">
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1">សញ្ជាតិ</label>
                                            {!! showSelect('log5_owner_nationality_id', arrayNationality(1,0), old('log5_owner_nationality_id'), " select2") !!}
                                        </div>

                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1">នាមនាយកសហគ្រាស</label>
                                            <input type="text" name="log5_director_name_khmer" value="{{ old('log5_director_name_khmer') }}" class="form-control" id="log5_director_name_khmer" placeholder="">
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1">សញ្ជាតិ</label>
                                            {!! showSelect('log5_director_nationality_id', arrayNationality(1,0), old('log5_director_nationality_id'), " select2") !!}
                                        </div>
                                    </div>
                                    <div class="row col-12 mt-3">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1">សកម្មភាពអាជីវកម្មចម្បង</label>
                                            <input type="text" name="log5_first_business_act" value="{{ old('log5_first_business_act', $case->company->first_business_act) }}" class="form-control" id="log5_first_business_act" placeholder="">
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1 required">ទ្រង់ទ្រាយសហគ្រាស</label>
                                            {!! showSelect('log5_article_of_company', arrayArticleOfCompany(1,0), old('log5_article_of_company', $case->company->article_of_company), " select2") !!}
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1 required">ប្រភេទសហគ្រាស</label>
                                            {!! showSelect('log5_company_type_id', arrayCompanyType(1,0), old('log5_company_type_id', $case->company->company_type_id), " select2") !!}
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1 required">វិស័យ</label>
                                            {!! showSelect('log5_sector_id', myArraySector(1,0), old('log5_sector_id', $case->company->sector_id), " select2") !!}
                                        </div>
                                        {{--                                                                            Business Activity 1, 2, 3, 4--}}
                                        @php
                                            $business_activity1 = $case->company->business_activity1;
                                            $business_activity2 = $case->company->business_activity2;
                                            $business_activity3 = $case->company->business_activity3;
                                            $business_activity4 = $case->company->business_activity4;

//                                            $arrayBus2 = $business_activity2 > 0? arrayBusinessActivity2($business_activity1, 1, ""): array();
//                                            $arrayBus3 = $business_activity3 > 0? arrayBusinessActivity3($business_activity1, $business_activity2, 1, ""): array();
//                                            $arrayBus4 = $business_activity4 > 0? arrayBusinessActivity4($business_activity1, $business_activity2, $business_activity3, 1, ""): array();
                                        @endphp

                                        <input type="hidden" name="log5_business_activity" value="{{ $case->company->business_activity }}" >
                                        <input type="hidden" name="log5_business_activity1" value="{{ $business_activity1 }}" >
                                        <input type="hidden" name="log5_business_activity2" value="{{ $business_activity2 }}" >
                                        <input type="hidden" name="log5_business_activity3" value="{{ $business_activity3 }}" >
                                        <input type="hidden" name="log5_business_activity4" value="{{ $business_activity4 }}" >
{{--                                        <div class="form-group col-sm-4 mt-3">--}}
{{--                                            <label class="fw-bold required">សកម្មភាពសេដ្ឋកិច្ចកម្រិត១</label>--}}
{{--                                            {!! showSelect('log5_business_activity1', arrayBusinessActivity1(1,0), old('log5_business_activity1', $business_activity1), " select2", "", "business_activity1", "") !!}--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group col-sm-4 mt-3">--}}
{{--                                            <label>សកម្មភាពសេដ្ឋកិច្ចកម្រិត២</label>--}}
{{--                                            {!! showSelect('log5_business_activity2', $arrayBus2, old('log5_business_activity2', $business_activity2), " select2", "", "business_activity2", "") !!}--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group col-sm-4 mt-3">--}}
{{--                                            <label>សកម្មភាពសេដ្ឋកិច្ចកម្រិត៣</label>--}}
{{--                                            {!! showSelect('log5_business_activity3', $arrayBus3, old('log5_business_activity3', $business_activity3), " select2", "", "business_activity3", "") !!}--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group col-sm-4 mt-3">--}}
{{--                                            <label>សកម្មភាពសេដ្ឋកិច្ចកម្រិត៤</label>--}}
{{--                                            {!! showSelect('log5_business_activity4', $arrayBus4, old('log5_business_activity4', $business_activity4), " select2", "", "business_activity4", "") !!}--}}
{{--                                        </div>--}}
                                        </div>

                                    </div>
{{--                                    Address Of Company--}}
                                    <div class="row col-12 mt-3">
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1 required text-primary">អាសយដ្ឋានសហគ្រាស រាជធានី-ខេត្ត</label>
                                            {!! showSelect('log5_province_id', arrayProvince(1,0), old('log5_province_id', $province_id), " select2", "", "", "") !!}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1 required">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                            {!! showSelect('log5_district_id', $arrayDistrict, old('log5_district_id', $district_id), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1 required">ឃុំ-សង្កាត់</label>
                                            {!! showSelect('log5_commune_id', $arrayCommune, old('log5_commune_id', $commune_id), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1">ភូមិ</label>
                                            {!! showSelect('log5_village_id', $arrayVillage, old('log5_village_id', $village_id), " select2") !!}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1">ផ្លូវ</label>
                                            <input type="text" name="log5_street_no" id="log5_street_no" value="{{ old('log5_street_no', $case->company->street_no) }}" class="form-control" />
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1">អគារលេខ</label>
                                            <input type="text" name="log5_building_no" id="log5_building_no" value="{{ old('log5_head_building_no', $case->company->building_no) }}" class="form-control" />
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-1">លេខទូរស័ព្ទ</label>
                                            <input type="text" name="log5_company_phone_number" value="{{ old('log5_company_phone_number', $case->company->company_phone_number) }}" class="form-control" id="log5_company_phone_number" placeholder="">
                                        </div>

                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1">ចំនួនកម្មករនិយោជិតសរុប</label>
                                            <input type="number" min="0" name="log5_total_employee" value="{{ old('log5_total_employee', 0) }}" class="form-control" id="log5_total_employee" placeholder="">
                                        </div>
                                        <div class="form-group col-sm-2 mt-3">
                                            <label class="fw-bold mb-1">ស្រី</label>
                                            <input type="number" min="0" name="log5_total_employee_female" value="{{ old('log5_total_employee_female', 0) }}" class="form-control" id="log5_total_employee_female" placeholder="">
                                        </div>
                                        <div class="form-group col-sm-3 mt-3">
                                            <label class="fw-bold mb-1">សហជីពមូលដ្ឋានដែលបានចុះបញ្ជីរ</label>
                                            <input type="number" min="0" name="log5_union1_number" value="{{ old('log5_union1_number', 0) }}" class="form-control" id="log5_union1_number">
                                        </div>
                                    </div>

                                    <div id="union_1" class="row col-12" style="display: none;">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1">ឈ្មោះសហជីពមូលដ្ឋាន</label>
                                            <div class="row py-1">
                                                <div style="width:2%" class="col-sm-1 mt-1">1</div>
                                                <div style="width:96%" class="col-sm-11">
                                                    <input type="hidden" name="union1_id[]" value="0">
                                                    <input type="text" name="union1_name[]" value="{{ old('union1_name[]') }}" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-3 mt-4">
                                            <label style="visibility: hidden">x</label>
                                            <button type="button" id="btn_add_union" class="btn btn-info form-control">បន្ថែមឈ្មោះសហជីពមូលដ្ឋាន</button>
                                        </div>
                                        <div class="form-group col-sm-3 mt-4">
                                            <label style="visibility: hidden">x</label>
                                            <button type="button" id="btn_remove_union" class="btn btn-danger form-control">លុបឈ្មោះសហជីពមូលដ្ឋាន</button>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row col-12">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="fw-bold mb-2 pink" for="contact_phone">ប្រភេទនៃកិច្ចសន្យាការងារដែលបានចុះជាមួយកម្មករនិយោជិត</label>
                                            {!! showTextarea("contract_type_with_employee", old('contract_type_with_employee')) !!}
                                        </div>
                                    </div>
                                    <div class="row col-12">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="text-purple text-hanuman-24 mb-1" for="contact_phone">ខ. អង្គហេតុចម្បងនៃវិវាទ</label>
                                            {!! showTextarea("dispute_cause", old('dispute_cause'), 6) !!}
                                        </div>
                                    </div>
                                    <div class="row col-12">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="text-purple text-hanuman-24 mb-1" for="contact_phone">គ. ព័ត៌មានបន្ថែម</label>
                                            {!! showTextarea("dispute_more_info", old('dispute_more_info'), 4) !!}
                                        </div>
                                    </div>
                                <div class="row col-12 mt-5">
                                    <label class="pink text-hanuman-24 col-6">*ការឆ្លើយតបទៅនឹងចំណុចទាមទាររបស់កម្មករនិយោជិត</label>
{{--                                    <button class="btn btn-info col-2 text-hanuman-16" type="button" id="btn-add-issue">បន្ថែមចំណុចទាមទារ</button>--}}
{{--                                    <label class="col-1"></label>--}}
{{--                                    <button class="btn btn-danger col-2 text-hanuman-16" type="button" id="btn-remove-issue">លុបចំណុចទាមទារ</button>--}}
                                </div>
                                <div class="row col-12">
                                    @if(!empty($log34->log34Issues) && count($log34->log34Issues) > 0)
                                        @foreach($log34->log34Issues as $log34Issue)
                                            <div class="form-group col-6 mt-3 mb-3">
                                                <label class="mb-2 fw-bold text-danger">ចំណុចទាមទារ</label>
                                                <div class="d-flex align-items-center">
                                                    <input type="hidden" name="issueID[]" value="{{ $log34Issue->id }}">
                                                    <textarea name="issues[]" class="form-control" rows="4" style="flex: 1;" disabled>{{ $log34Issue->issue }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group col-6 mt-3 mb-3">
                                                <label class="mb-2 fw-bold blue">ការឆ្លើយតប</label>
                                                <div class="d-flex align-items-center">
                                                    <input type="hidden" name="provideID[]" value="0">
                                                    <textarea name="provides[]" class="form-control" rows="4" style="flex: 1;"></textarea>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                    <div class="row col-12">
                                        <div class="form-group col-sm-2 mt-3">
                                            <label style="color:#FFFFFF">x</label>
                                            <button type="submit" class="btn btn-success form-control fw-bold text-hanuman-16">រក្សាទុក</button>
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
        {{--        <script src="https://code.jquery.com/jquery-3.6.4.js"></script>--}}
        <!-- Plugins for Autocomplete -->
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        @include('case.script.log5_script')
        @include('script.my_sweetalert2')
    </x-slot>
</x-admin.layout-main>

