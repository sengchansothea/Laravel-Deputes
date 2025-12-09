@php
    $case = $adata['case'];
    $comCase = $case->caseCompany;
    $cYear = myDate('y');
    $caseYear = !empty($case->case_date) ? date2Display($case->case_date, "Y") : myDate('Y');
    $arrOfficersInHand = arrayOfficerCaseInHand(0, 1);

    $province = $comCase->log5_province_id;
    $district = $comCase->log5_district_id;
    $commune = $comCase->log5_commune_id;
    $village = $comCase->log5_village_id;
    $arrayDistrict = $district > 0 ? arrayDistrict($province, 1, ""): array();
    $arrayCommune = $commune > 0 ? arrayCommune($district, 1, ""): array();
    $arrayVillage = $village > 0 ? arrayVillage($commune, 1, ""): array();
    $addr_building_no = $comCase->log5_building_no;
    $addr_street_no = $comCase->log5_street_no;
@endphp
{{--{{ dd($caseYear) }}--}}
{{--{{ dd(ApiAdmin(341, "30")) }}--}}
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/date-picker.css') }}">
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
                    <form name="formUpdateCollectives" action="{{ url('collective_cases/'. $case->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="first_business_act" value="{{ $comCase->log5_first_business_act }}"  id="first_business_act">
                        <input type="hidden" name="case_year" value="{{ $caseYear }}" >
                        <input type="hidden" name="article_of_company" value="{{ $comCase->log5_article_of_company }}"  id="article_of_company">
                        <input type="hidden" name="business_activity1" value="{{ $comCase->log5_business_activity1 }}"  id="business_activity1">
                        <input type="hidden" name="business_activity2" value="{{ $comCase->log5_business_activity2 }}"  id="business_activity2">
                        <input type="hidden" name="business_activity3" value="{{ $comCase->log5_business_activity3 }}"  id="business_activity3">
                        <input type="hidden" name="business_activity4" value="{{ $comCase->log5_business_activity4 }}"  id="business_activity4">
                        <input type="hidden" name="company_register_number" value="{{ $case->company->company_register_number }}"  id="company_register_number">
                        <input type="hidden" name="registration_date" value="{{ $case->company->registration_date }}"  id="registration_date">
                        <input type="hidden" name="company_tin" value="{{ $case->company->company_tin }}"  id="company_tin">
                        <input type="hidden" name="nssf_number" value="{{ $case->company->nssf_number }}"  id="nssf_number">
                        <div class="card-body text-hanuman-17">
                            <div class="card-block row">
                                <div class="col-sm-12 col-lg-12 col-xl-12">
{{--                                    <div class="form-group col-12">--}}
{{--                                        <div id="response_message_company" style="display: none;">Waiting for response...</div>--}}
{{--                                    </div>--}}
{{--                                    {{ dd($caseIndex) }};--}}
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-4">
                                            <label for="case_type_id" class="fw-bold required mb-1"> ប្រភេទពាក្យបណ្ដឹង</label>{!! myToolTip(__("case.case_type")) !!}
                                            {!! showSelect('case_type_id',arrCaseType(3), old('case_type_id', $case->case_type_id)) !!}
                                        </div>
                                        <div class="form-group col-sm-6 mt-4">
                                            <label for="case_type_id" class="fw-bold mb-1 required">លេខសំណុំរឿង</label>
                                            <input type="text" name="case_number" id="case_number" value="{{ old('case_number', $case->case_number) }}" class="form-control col-sm-2" required>

                                        </div>
                                    </div>

{{--                                    Plantiff Block--}}
                                    <div id="plantiff_block">
                                        <div class="row col-12  mt-4">
                                            <label class="text-purple text-hanuman-24" for="contact_phone">
                                                1. សហគ្រាស គ្រឹះស្ថាន
                                            </label>
                                        </div>
{{--                                        <div class="row">--}}
{{--                                            <div class="form-group col-sm-12 mt-3">--}}
{{--                                                <label for="case_type" class="text-primary text-hanuman-22 mb-1"> ស្វែងរកឈ្មោះសហគ្រាស គ្រឹះស្ថាន</label>--}}
{{--                                                <input type="text" name="find_company" minlength="2" value="{{ old('find_company') }}" class="form-control" id="find_company_autocomplete">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="row">--}}
{{--                                            <div class="form-group col-sm-12 mt-3">--}}
{{--                                                <button type="button" id="btn_search_company" value="0" class="btn btn-danger mb-3 text-hanuman-16">បិទព័ត៌មានលម្អិតរបស់សហគ្រាស គ្រឹះស្ថាន</button>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group col-sm-12" id="div_company_result">--}}
{{--                                                <textarea rows=6" id="company_result" class="form-control">--}}
{{--                                                </textarea>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="row">
                                            <div class="form-group col-sm-6 mt-3">
                                                <label for="case_type" class="fw-bold required mb-1">
                                                    ឈ្មោះជាភាសាខ្មែរ</label>
                                                <input type="text" name="company_name_khmer" value="{{ old('company_name_khmer', $case->company->company_name_khmer) }}" class="form-control" id="company_name_khmer" placeholder="" readonly>
                                                @error('company_name_khmer')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-6 mt-3">
                                                <input type="hidden" name="company_id"  id="company_id" value="{{ $case->company_id }}" >
                                                <input type="hidden" name="company_option"  id="company_option" value="{{ $case->company_option }}" >
                                                <label for="case_type" class="mb-1">ឈ្មោះជាភាសាឡាតាំង</label>
                                                <input type="text" name="company_name_latin" value="{{ old('company_name_latin', $case->company->company_name_latin) }}" class="form-control" id="company_name_latin" placeholder="" readonly>
                                                @error('company_name_latin')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="sector_id" class="fw-bold required mb-1">វិស័យ</label>
                                                {!! showSelect('sector_id', myArraySector(1,0), old('sector_id', $case->sector_id), " select2", "", "", "required") !!}
                                            </div>

                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold required mb-1">ប្រភេទសហគ្រាស</label>
                                                {!! showSelect('company_type_id', arrayCompanyType(1, 0), old('company_type_id', $case->company_type_id), " select2", "", "", "required") !!}
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold required mb-1">សកម្មភាពសេដ្ឋកិច្ច</label>
                                                {!! showSelect('business_activity', myArrBusinessActivity(0, 1), old('business_activity', $case->business_activity), " select2", "", "", "required") !!}
                                            </div>

                                        </div>
                                        <div class="row col-12  mt-4">
                                            <label class="text-pink text-hanuman-20 mb-1" for="contact_phone">
                                                -អាសយដ្ឋាន
                                            </label>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold required mb-1">រាជធានី-ខេត្ត</label>
                                                {!! showSelect('province_id', arrayProvince(1, ""), old('province_id', $province), " select2", "", "", "required") !!}
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold required mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                                {!! showSelect('district_id', $arrayDistrict, old('district_id', $district), " select2", "", "", "required") !!}
                                            </div>

                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                                {!! showSelect('commune_id', $arrayCommune, old('commune_id', $commune), " select2", "", "", "") !!}
                                            </div>

                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="mb-1">ភូមិ</label>
                                                {!! showSelect('village_id', $arrayVillage, old('village_id', $village), " select2") !!}
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="case_type" class="mb-1">អគារលេខ</label>
                                                <input type="text" name="building_no" value="{{ old('building_no', $addr_building_no) }}" class="form-control" id="building_no" >
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="mb-1">ផ្លូវ</label>
                                                <input type="text" name="street_no" id="street_no" value="{{ old('street_no', $addr_street_no) }}" class="form-control short2" />
                                            </div>
                                        </div>
                                        <div class="row col-12  mt-4">
                                            <label class="text-pink text-hanuman-20" for="contact_phone">
                                                -ទំនាក់ទំនង
                                            </label>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6 mt-3">
                                                <label for="company_phone_number"  class="mb-1 required fw-bold">លេខទូរស័ព្ទក្រុមហ៊ុន (ខ្សែទី១)</label>
                                                <input type="text" name="company_phone_number" id="company_phone_number" value="{{ old('company_phone_number', $comCase->log5_company_phone_number) }}" class="form-control" minlength="9" maxlength="10" required>
                                            </div>
                                            <div class="form-group col-sm-6 mt-3">
                                                <label for="company_phone_number2"  class="mb-1">លេខទូរស័ព្ទក្រុមហ៊ុន (ខ្សែទី២)</label>
                                                <input type="text" name="company_phone_number2" id="company_phone_number2" value="{{ old('company_phone_number2', $comCase->log5_company_phone_number2) }}" class="form-control" minlength="9" maxlength="10">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row col-12 mt-5">
                                        <label class="text-purple text-hanuman-24 col-5">
                                            2. តំណាងប្រតិភូចរចា (តំណាងកម្មករនិយោជិត)
                                        </label>
                                        <button class="btn btn-primary col-2 text-hanuman-16" type="button" id="btn-add-representative">បន្ថែមតំណាងប្រតិភូចរចា</button>
                                        <label class="col-1"></label>
                                        <button class="btn btn-danger col-2 text-hanuman-16" type="button" id="btn-remove-representative">លុបតំណាងប្រតិភូចរចា</button>
                                    </div>

                                    <div class="row">
                                        @if(count($case->collectivesRepresentatives) > 0)
{{--                                            @php $i = 1; @endphp--}}
                                            @foreach($case->collectivesRepresentatives as $cRepre)
                                                <div class="form-group col-7 mt-3 mb-3">
                                                    <label class="fw-bold pink mb-1" for="repName1">ឈ្មោះតំណាងកម្មករនិយោជិត</label>
                                                    <div class="d-flex align-items-center">
                                                        <input type="hidden" name="repID[]" value="{{ $cRepre->id }}">
                                                        <input type="text" name="repName[]" value="{{ $cRepre->fullname }}" id="repName1" class="form-control" style="flex: 1;">
{{--                                                        <button type="button" title="" class="btn btn-danger rep-delete-btn ms-2">--}}
{{--                                                            <i data-feather="trash"></i>--}}
{{--                                                        </button>--}}
                                                        @php
                                                            $deleteUrl = url('collectives/delete/representative/'.$cRepre->id.'_'.$case->id);
                                                                $onClick = "comfirm_sweetalert2('".$deleteUrl."','តើអ្នកពិតជាចង់លុប មែនឫ?')";
                                                            $str2 ='<button type="button" class="btn btn-danger ms-2" onClick="'.$onClick.'" title="Delete"><i data-feather="trash"></i></button>';
                                                            echo $str2;
                                                        @endphp
                                                    </div>
                                                </div>
{{--                                                @php $i++ @endphp--}}
                                            @endforeach
                                        @endif
                                    </div>
                                    <div id="collectiveRepre_1" class="row">
                                        <!-- Default form group -->
                                        <div class="form-group col-7 mt-3 mb-3 dynamic-form-group">
                                            <label class="fw-bold pink mb-1" for="repName1">ឈ្មោះតំណាងកម្មករនិយោជិត</label>
                                            <div class="d-flex align-items-center">
                                                <input type="hidden" name="repID[]" value="0">
                                                <input type="text" name="repName[]" id="repName1" class="form-control" style="flex: 1;">
{{--                                                <button type="button" title="" class="btn btn-danger rep-delete-btn ms-2">--}}
{{--                                                    <i data-feather="trash"></i>--}}
{{--                                                </button>--}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row col-12 mt-5">
                                        <label class="text-purple text-hanuman-24 col-5">
                                            3. ចំណុចទាមទារ (Issues)
                                        </label>
{{--                                        <button class="btn btn-danger col-2 text-hanuman-16" type="button" id="btn-issues">ចុចបន្ថែមចំណុចទាមទារ</button>--}}
                                        <button class="btn btn-primary col-2 text-hanuman-16" type="button" id="btn-add-issue">បន្ថែមចំណុចទាមទារ</button>
                                        <label class="col-1"></label>
                                        <button class="btn btn-danger col-2 text-hanuman-16" type="button" id="btn-remove-issue">លុបចំណុចទាមទារ</button>
                                    </div>

                                    <div class="row">
                                        @if(count($case->collectivesIssues) > 0)
                                            @foreach($case->collectivesIssues as $cIssue)
                                                <div class="form-group col-12 mt-3 mb-3">
                                                    <label class="fw-bold text-danger mb-1" for="issues1">ចំណុចទាមទារ</label>
                                                    <div class="d-flex align-items-center">
                                                        <input type="hidden" name="issueID[]" value="{{ $cIssue->id }}">
                                                        <textarea name="issues[]" class="form-control" rows="4" style="flex: 1;">{{ $cIssue->issue }}</textarea>
                                                        @php
                                                            $deleteUrl = url('collectives/delete/issue/'.$cIssue->id.'_'.$case->id);
                                                                $onClick = "comfirm_sweetalert2('".$deleteUrl."','តើអ្នកពិតជាចង់លុប មែនឫ?')";
                                                            $str2 ='<button type="button" class="btn btn-danger ms-2" onClick="'.$onClick.'" title="Delete"><i data-feather="trash"></i></button>';
                                                            echo $str2;
                                                        @endphp
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <!-- Container to append new sections -->
                                    <div id="collectiveIssue_1" class="row">
                                        <div class="form-group col-12 mt-3 mb-3">
                                            <label class="fw-bold text-danger mb-1" for="issues1">ចំណុចទាមទារ</label>
                                            <div class="d-flex align-items-center">
                                                <input type="hidden" name="issueID[]" value="0">
                                                <textarea name="issues[]" id="issues1" class="form-control" rows="4" style="flex: 1;"></textarea>
{{--                                                <button type="button" title="" class="btn btn-danger delete-btn ms-2">--}}
{{--                                                    <i data-feather="trash"></i>--}}
{{--                                                </button>--}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            4. សេចក្ដីពិស្ដារស្ដីពីការផ្ដួចផ្ដើមដំណើរការផ្សះផ្សា
                                        </label>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="form-group col-sm-4">
                                            <label class="fw-bold required mb-1">កាលបរិចេ្ឆទដែលបណ្ដឹងសារទុក្ខរវាងគូភាគីចាប់ផ្ដើម</label>
                                            <input type="text"  name="case_date" id="case_date" value="{{ old('case_date', date2Display($case->case_date)) }}" class="form-control"  data-language="en" required>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="fw-bold required mb-1">កាលបរិចេ្ឆទនៃវិវាទដែលបានប្ដឹងទៅអធិការការងារ</label>
                                            <input type="text"  name="case_date_entry" id="case_date_entry" value="{{ old('case_date_entry', date2Display($case->case_date_entry)) }}" class="form-control"  data-language="en" required>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="fw-bold mb-1 required">ដើមហេតុនៃវិវាទ</label>
                                            {!! showSelect('collectives_cause_id', arrCollectivesCause(0, 1), old('collectives_cause_id', $case->collectives_cause_id), " select2", "", "", "required") !!}
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            5. ការចាត់តាំងអ្នកផ្សះផ្សា (លិខិតបង្គាប់ការរបស់ឯកឧត្ដមរដ្ឋមន្ត្រី)
                                        </label>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="form-group col-sm-6">
                                            <label class="fw-bold mb-1">លេខលិខិតបង្គាប់ការ</label>
                                            <input type="text"  name="collectives_order_letter_num" id="collectives_order_letter_num" value="{{ old('collectives_order_letter_num', $case->collectives_order_letter_num) }}" class="form-control">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="fw-bold mb-1">កាលបរិចេ្ឆទដែលរដ្ឋមន្ដ្រីជ្រើសតាំងអ្នកផ្សះផ្សា</label>
                                            <input type="text"  name="collectives_assigned_officer_date" id="collectives_assigned_officer_date" value="{{ old('collectives_assigned_officer_date', date2Display($case->collectives_assigned_officer_date)) }}" class="form-control"  data-language="en">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="form-group col-sm-6">
                                            <label class="fw-bold mb-1">អ្នកផ្សះផ្សា</label>
                                            {!! showSelect('officer_id', $arrOfficersInHand, old('officer_id', getLastOfficerID($case->id, 6)), " select2", "", "", "") !!}
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="fw-bold mb-1">ឋានៈ</label>
                                            <input type="text"  name="collectives_officer_rank" id="collectives_officer_rank" value="{{ old('collectives_officer_rank', $case->collectives_officer_rank) }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-4">
                                        <label class="text-pink text-hanuman-20 mb-1" for="contact_phone">
                                            ភ្ជាប់លិខិតបង្គាប់ការ
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-4">
                                            <input type="hidden" name="order_letter_file_old" value="{{ $case->collectives_order_letter_file }}" >
                                            @php
                                                $show_file = showFile(1, $case->collectives_order_letter_file, pathToDeleteFile('case_doc/collectives/'.$caseYear."/"), "delete", "tbl_case", "id", $case->id, "collectives_order_letter_file");
                                                if($show_file){
                                                    echo $show_file;
                                                }
                                                else{
                                                    echo "<div class='py-2'>".upload_file("collectives_order_letter_file", "(ប្រភេទឯកសារ pdf មានទំហំធំបំផុត 5MB)")."</div>";
                                                }
                                            @endphp
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            6. ឯកសារយោង (ពាក្យបណ្ដឹង និងបញ្ជីស្នាមម្រាមដៃកម្មករ និយោជិត)
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-4">
                                            <input type="hidden" name="collectives_file_old" value="{{ $case->collectives_case_file }}" >
                                            @php
                                                $show_file = showFile(1, $case->collectives_case_file, pathToDeleteFile('case_doc/collectives/'.$caseYear."/"), "delete", "tbl_case", "id", $case->id, "collectives_case_file");
                                                if($show_file){
                                                    echo $show_file;
                                                }
                                                else{
                                                    echo "<div class='py-2'>".upload_file("collectives_case_file", "(ប្រភេទឯកសារ pdf មានទំហំធំបំផុត 5MB)")."</div>";
                                                }
                                            @endphp
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            7. ឯកសារយោង (ផ្សេងៗ)
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-4">
                                            <input type="hidden" name="other_file_old" value="{{ $case->collectives_other_file }}" >
                                            @php
                                                $show_file = showFile(1, $case->collectives_other_file, pathToDeleteFile('case_doc/collectives/'.$caseYear."/"), "delete", "tbl_case", "id", $case->id, "collectives_other_file");
                                                if($show_file){
                                                    echo $show_file;
                                                }
                                                else{
                                                    echo "<div class='py-2'>".upload_file("collectives_other_file", "(ប្រភេទឯកសារ pdf មានទំហំធំបំផុត 5MB)")."</div>";
                                                }
                                            @endphp
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
        @include('case.script.collective_case_script')
        @include('script.my_sweetalert2')
    </x-slot>
</x-admin.layout-main>

