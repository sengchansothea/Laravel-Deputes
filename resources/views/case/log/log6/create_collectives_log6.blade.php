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
            #response_message {
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
                    <form name="formCreateCase" action="{{ url('collectives_log6') }}" method="POST" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <input type="hidden" name="case_type_id" value="{{ $case->case_type_id }}" >
                        <input type="hidden" name="case_id" value="{{ $adata['case_id'] }}" >
                        <input type="hidden" name="invitation_id_employee" value="{{ $adata['invitation_id_employee'] }}" >
                        <input type="hidden" name="invitation_id_company" value="{{ $adata['invitation_id_company'] }}" >
                        <input type="hidden" name="disputant_id" value="{{ $case->disputant_id }}" >
                        <input type="hidden" name="comID" id="company_id" value="{{ $case->company->company_id_lacms }}" >
                        <input type="hidden" name="company_id" value="{{ $case->company_id }}" >
                        <input type="hidden" name="status_id" id="status_id" value="1" >
                        <input type="hidden" name="current_status_id" id="current_status_id" value="1" >
                        <div class="card-body text-hanuman-17">
                            {{--                                    Company Blog--}}
                            <div class="row" style="display: none;">
                                <div class="form-group col-sm-4 mt-3">
                                    <label>ឈ្មោះក្រុមហ៊ុន</label>
                                    <input type="text" value="{{ $case->company->company_name_khmer }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label>ឈ្មោះក្រុមហ៊ុនជាភាសាឡាតាំង</label>
                                    <input type="text" value="{{ $case->company->company_name_latin }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label>អាសយដ្ចាន</label>
                                    <input type="text" value="{{ $case->caseCompany->province->pro_khname }}" class="form-control" disabled />
                                </div>
                            </div>

                            <div class="row col-12  mt-4">
                                <label class="text-purple text-hanuman-24" for="contact_phone">
                                    ក. សេចក្ដីលម្អិតស្ដីពីការផ្ដួចផ្ដើមដំណើរការផ្សះផ្សា
                                </label>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1 required">កាលបរិចេ្ឆទនៃការចាប់ផ្ដើមវិវាទរវាងគូភាគី</label>
                                    <input type="text"  value="{{ old('case_date', date2Display($case->case_date)) }}" class="form-control"  data-language="en" disabled >
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1 required">កាលបរិចេ្ឆទនៃវិវាទដែលបានប្ដឹងទៅអធិការការងារ</label>
                                    <input type="text" value="{{ old('case_date_entry', date2Display($case->case_date_entry)) }}" class="form-control"  data-language="en" disabled >
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1 required">កាលបរិចេ្ឆទដែលរដ្ឋមន្ដ្រីជ្រើសតាំងអ្នកផ្សះផ្សា</label>
                                    <input type="text" value="{{ old('assigned_officer_date', date2Display($case->collectives_assigned_officer_date)) }}" class="form-control"  data-language="en" disabled >
                                </div>
                            </div>
                            <div class="row col-12 mt-5">
                                <label class="text-purple text-hanuman-24" for="contact_phone">
                                    ខ. សេចក្ដីលម្អិតនៃកិច្ចប្រជុំ
                                </label>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required">កាលបរិច្ឆេទប្រជុំ</label>
                                    <input type="text"  name="log6_date" id="log6_date" value="{{ old('log6_date', myDate("d-m-Y")) }}" class="form-control"  data-language="en" required >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required">ម៉ោងចាប់ផ្ដើម</label>
                                    <div class="input-group clockpicker" data-autoclose="true">
                                        <input name="log6_stime" id="log6_stime" value="{{ old("log6_stime", myTime()) }}"  class="form-control" type="text" data-bs-original-title="" required >
                                    </div>
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required">ម៉ោងបញ្ចប់</label>
                                    <div class="input-group clockpicker" data-autoclose="true">
                                        <input name="log6_etime" id="log6_etime" value="{{ old("log6_etime") }}"  class="form-control" type="text" data-bs-original-title="" required >
                                    </div>
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required">ទីកន្លែងប្រជុំ</label>
                                    {!! showSelect('log6_meeting_place_id', array(1=>"នៅនាយកដ្ឋានវិវាទការងារ (ភ្នំពេញ)", 2 =>"នៅកន្លែងផ្សេង"), old('log6_meeting_place_id'), " select2", "", "", "") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 mb-1">បើប្រជុំនៅកន្លែងផ្សេង</label>
                                    <input type="text" name="log6_meeting_other" class="form-control">
                                </div>

                                <div class="form-group col-sm-9 mt-3">
                                    <label class="fw-bold mb-1 required">ដើម្បីផ្សះផ្សាវិវាទបុគ្គល ស្ដីពី</label>
                                    <input type="text" name="log6_meeting_about" id="log6_meeting_about" class="form-control" required />
                                </div>
                            </div>
                            <div class="row col-12 mt-5">
                                <label class="text-purple text-hanuman-24" for="contact_phone">
                                    គ. សេចក្ដីពិស្ដារអំពីសហជីព ឬក្រុមកម្មករនិយោជិត
                                    @if($case->case_type_id == 3)
                                        (ដើមបណ្ដឹង)
                                    @else
                                        (ចុងបណ្ដឹង)
                                    @endif
{{--                                    <button type="button" id="btn_employee" value="1" class="btn btn-success">បង្ហាញព័ត៌មានលម្អិត</button>--}}
                                </label>
                            </div>
                            <div class="row col-12 mt-3">@php $counterCDis = 1 @endphp
                                @foreach($case->collectivesCaseDisputantsEmp as $cCDisputant)
                                    <div class="form-group col-sm-3 mb-3">
                                        <label class="fw-bold" style="margin-bottom: 6px;">
                                            តំណាងកម្មករនិយោជិតទី{{ Num2Unicode($counterCDis) }}
                                        </label>
                                        <input type="text" value="{{ $cCDisputant->disputant->name }}" class="form-control " disabled />
                                    </div>
                                    @php $counterCDis++ @endphp
                                @endforeach
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6 mt-3">
                                    <label class="fw-bold mb-1 mb-1 required">កម្មករពាក់ព័ន្ធនឹងវិវាទ</label>
                                    <input type="number" name="log6_emp_involved" id="log6_emp_involved" class="form-control" value="{{ old('log6_emp_involved') }}" required>
                                </div>

                                <div class="form-group col-sm-6 mt-3">
                                    <label class="fw-bold mb-1 required">ចំនួនកម្មករសរុប</label>
                                    <input type="number" name="log6_emp_total" id="log6_emp_total" class="form-control" value="{{ old('log6_emp_total') }}" required />
                                </div>
                            </div>
                            {{--                                    Sub Employee--}}
                            <div class="row col-12 mt-4">
                                <label class="fw-bold pink">
                                    *អ្នកដែលអមកម្មករនិយោជិត និង/ឬ តំណាងកម្មករនិយោជិត
                                </label>
                            </div>
                            <div class="form-group col-12">
                                <div id="response_message_sub_employee" style="display: none;">Waiting for response...</div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label for="case_type" class="fw-bold mb-1 text-primary text-hanuman-18"> ស្វែងរកឈ្មោះអ្នកអមកម្មករ</label>
                                    <input type="text" name="find_sub_employee_autocomplete" minlength="2" value="{{ old('find_sub_employee_autocomplete') }}" class="form-control" id="find_sub_employee_autocomplete" >
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="required2 fw-bold mb-1">ឈ្មោះអ្នកអមកម្មករ</label>
                                    <input type="text" name="sub_employee_name[]" value="{{ old('sub_employee_name[]') }}" class="form-control" id="sub_employee_name" >
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">ភេទ</label>
                                    {!! showSelect('sub_employee_gender[]', array("1" =>"ប្រុស", "2" => "ស្រី"), old('sub_employee_gender[]'), " select2", "", "sub_employee_gender") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="required2 fw-bold mb-1">ថ្ងៃខែឆ្នាំកំណើត</label>
                                    <input type="text"  name="sub_employee_dob[]" id="sub_employee_dob" value="{{ old('sub_employee_dob[]') }}" class="form-control"  data-language="en" >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="required2 fw-bold mb-1">សញ្ជាតិ</label>
                                    {!! showSelect('sub_employee_nationality[]', arrayNationality(1), old('sub_employee_nationality[]'), " select2", "", "sub_employee_nationality","") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label for="id_number" class="required2 fw-bold mb-1"> លេខអត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
                                    <input type="text" name="sub_employee_id_number[]" value="{{ old('sub_employee_id_number[]') }}" class="form-control" id="sub_employee_id_number" >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label for="phone_number" class="required2 fw-bold mb-1">លេខទូរស័ព្ទខ្សែទី១</label>
                                    <input type="text" name="sub_employee_phone_number[]" id="sub_employee_phone_number" value="{{ old('sub_employee_phone_number[0]') }}" class="form-control" >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label for="phone_number" class=" fw-bold mb-1">លេខទូរស័ព្ទខ្សែទី២</label>
                                    <input type="text" name="sub_employee_phone2_number[]" id="sub_employee_phone2_number" value="{{ old('sub_employee_phone2_number[0]') }}" class="form-control" >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label for="occupation" class="fw-bold mb-1">មុខងារ</label>
                                    <input type="text" name="sub_employee_occupation[]" id="sub_employee_occupation" value="{{ old('sub_employee_occupation[0]') }}" class="form-control" >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ទីកន្លែងកំណើត រាជធានី-ខេត្ត</label>
                                    {!! showSelect('sub_employee_pob_province_id[]', arrayProvince(1,0), old('sub_employee_pob_province_id'), " select2", "", "sub_employee_pob_province_id", "") !!}
                                </div>

                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                    {!! showSelect('sub_employee_pob_district_id[]', array(), old('sub_employee_pob_district_id[]'), " select2", "", "sub_employee_pob_district_id", "") !!}
                                </div>

                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                    {!! showSelect('sub_employee_pob_commune_id[]', array(), old('sub_employee_pob_commune_id[]'), " select2", "", "sub_employee_pob_commune_id", "") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">អាសយដ្ឋានបច្ចុប្បន្ន រាជធានី-ខេត្ត</label>
                                    {!! showSelect('sub_employee_province[]', arrayProvince(1,0), old('sub_employee_province[]'), " select2", "", "sub_employee_province", "") !!}
                                </div>

                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                    {!! showSelect('sub_employee_district[]', array(), old('sub_employee_district[]'), " select2", "", "sub_employee_district", "") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                    {!! showSelect('sub_employee_commune[]', array(), old('sub_employee_commune[]'), " select2", "", "sub_employee_commune", "") !!}
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">ភូមិ</label>
                                    {!! showSelect('sub_employee_village[]', array(), old('sub_employee_village[]'), " select2", "", "sub_employee_village", "") !!}
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1" for="case_type">ផ្ទះលេខ</label>
                                    <input type="text" name="sub_employee_addr_house_no[]" id="sub_employee_addr_house_no" value="{{ old('sub_employee_addr_house_no') }}" class="form-control" >
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">ផ្លូវ</label>
                                    <input type="text" name="sub_employee_addr_street[]" id="sub_employee_addr_street" value="{{ old('sub_employee_addr_street[]') }}" class="form-control" />
                                </div>
                            </div>

                            <div class="row col-12 mt-5">
                                <label class="text-purple text-hanuman-24" for="contact_phone">
                                    ឃ. សេចក្ដីពិស្ដារអំពីនិយោជក
                                    @if($case->case_type_id == 3)
                                        (ចុងបណ្ដឹង)
                                    @else
                                        (ដើមបណ្ដឹង)
                                    @endif
                                    <button type="button" id="btn_company" value="1" class="btn btn-info fw-bold">បង្ហាញព័ត៌មានលម្អិត</button>
                                </label>
                            </div>
                            <div id="r1Company" class="row" style="display: none">
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1">ឈ្មោះសហគ្រាសគ្រឹះស្ថាន</label>
                                    <input type="text" value="{{ $case->company->company_name_khmer }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1">ឈ្មោះសហគ្រាសគ្រឹះស្ថានជាភាសាឡាតាំង</label>
                                    <input type="text" value="{{ $case->company->company_name_latin }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1">អាសយដ្ឋានអគារលេខ</label>
                                    <input type="text" value="{{ $case->caseCompany->log5_head_building_no }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">ផ្លូវ</label>
                                    <input type="text" value="{{ $case->caseCompany->log5_head_street_no }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">ភូមិ</label>
                                    <input type="text" value="@if(!empty($case->caseCompany->village)) {{ $case->caseCompany->village->vil_khname }} @endif" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                    <input type="text" value="{{ $case->caseCompany->commune->com_khname }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                    <input type="text" value="{{ $case->caseCompany->district->dis_khname }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">រាជធានី-ខេត្ត</label>
                                    <input type="text" value="{{ $case->caseCompany->province->pro_khname }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">លេខទូរស័ព្ទ</label>
                                    <input type="text" value="{{ $case->caseCompany->log5_company_phone_number }}" class="form-control" disabled />
                                </div>
                            </div>
                            <div id="r2Company" class="row" style="display: none">
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1">ចំនួនកម្មករនិយោជិត</label>
                                    <input type="text" value="{{ $case->caseCompany->log5_total_employee }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1">ចំនួនកម្មករនិយោជិតស្រី</label>
                                    <input type="text" value="{{ $case->caseCompany->log5_total_employee_female }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1">សកម្មភាពអាជីវកម្មចម្បង</label>
                                    <input type="text" value="{{ $case->caseCompany->log5_first_business_act }}" class="form-control" disabled />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 mb-1 required">ចំនួនគ្រឹះស្ថានដែលពាក់ព័ន្ធ នៅក្នុងវិវាទការងាររួមនេះមានចំនួន</label>
                                    <input type="number" name="log6_com_involved" id="log6_com_involved" class="form-control" value="{{ old('log6_com_involved') }}" required>
                                </div>
                            </div>
                            {{--            តំណាងនិយោជក           Represent Company--}}
                            <div class="row col-12 mt-4">
                                <label class="fw-bold mb-1 pink">*តំណាងនិយោជក</label>
                            </div>
                            <div class="form-group col-12">
                                <div id="response_message_company" style="display: none;">Waiting for response...</div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label for="case_type" class="text-primary text-hanuman-18 fw-bold mb-1"> ស្វែងរកឈ្មោះតំណាងនិយោជក</label>
                                    <input type="text" name="find_represent_company_autocomplete" minlength="2" value="{{ old('find_represent_company_autocomplete') }}" class="form-control" id="find_represent_company_autocomplete" >
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1 required2">ឈ្មោះតំណាងនិយោជក</label>
                                    <input type="text" name="represent_company_name" value="{{ old('represent_company_name') }}" class="form-control" id="represent_company_name"  >
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">ភេទ</label>
                                    {!! showSelect('represent_company_gender', array("1" =>"ប្រុស", "2" => "ស្រី"), old('represent_company_gender'), " select2", "", "") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required2">ថ្ងៃខែឆ្នាំកំណើត</label>
                                    <input type="text"  name="represent_company_dob" id="represent_company_dob" value="{{ old('represent_company_dob') }}" class="form-control"  data-language="en"  >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required2">សញ្ជាតិ</label>
                                    {!! showSelect('represent_company_nationality', arrayNationality(1), old('represent_company_nationality'), " select2", "", "represent_company_nationality", "") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label  class="fw-bold mb-1" for="id_number"> លេខអត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
                                    <input type="text" name="represent_company_id_number" value="{{ old('represent_company_id_number') }}" class="form-control" id="represent_company_id_number"  >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required2" for="represent_company_phone_number">លេខទូរស័ព្ទខ្សែទី១</label>
                                    <input type="text" name="represent_company_phone_number" id="represent_company_phone_number" value="{{ old('represent_company_phone_number') }}" class="form-control"  >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1" for="represent_company_phone2_number">លេខទូរស័ព្ទខ្សែទី២</label>
                                    <input type="text" name="represent_company_phone2_number" id="represent_company_phone2_number" value="{{ old('represent_company_phone2_number') }}" class="form-control"  >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label for="occupation" class="fw-bold mb-1">មុខងារ</label>
                                    <input type="text" name="represent_company_occupation" id="represent_company_occupation" value="{{ old('represent_company_occupation') }}" class="form-control" >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ទីកន្លែងកំណើត រាជធានី-ខេត្ត</label>
                                    {!! showSelect('represent_company_pob_province_id', arrayProvince(1,0), old('represent_company_pob_province_id'), " select2", "", "represent_company_pob_province_id", "") !!}
                                </div>

                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                    {!! showSelect('represent_company_pob_district_id', array(), old('represent_company_pob_district_id'), " select2", "", "represent_company_pob_district_id", "") !!}
                                </div>

                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                    {!! showSelect('represent_company_pob_commune_id', array(), old('represent_company_pob_commune_id'), " select2", "", "represent_company_pob_commune_id", "") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">អាសយដ្ឋានបច្ចុប្បន្ន រាជធានី-ខេត្ត</label>
                                    {!! showSelect('represent_company_province', arrayProvince(1,0), old('represent_company_province'), " select2", "", "", "") !!}
                                </div>

                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                    {!! showSelect('represent_company_district', array(), old('represent_company_district'), " select2", "", "represent_company_district", "") !!}
                                </div>

                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                    {!! showSelect('represent_company_commune', array(), old('represent_company_commune'), " select2", "", "represent_company_commune", "") !!}
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">ភូមិ</label>
                                    {!! showSelect('represent_company_village', array(), old('represent_company_village'), " select2", "", "represent_company_village", "") !!}
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label for="case_type" class="fw-bold mb-1">ផ្ទះលេខ</label>
                                    <input type="text" name="represent_company_addr_house_no" id="represent_company_addr_house_no" value="{{ old('represent_company_addr_house_no') }}" class="form-control" >
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">ផ្លូវ</label>
                                    <input type="text" name="represent_company_addr_street" id="represent_company_addr_street" value="{{ old('represent_company_addr_street') }}" class="form-control" />
                                </div>

                            </div>

{{--                            Sub Company Representativ អ្នកដែលអមនិយោជក--}}
                            <div class="row col-12 mt-4">
                                <label class="fw-bold mb-1 pink">*អ្នកដែលអមនិយោជក</label>
                            </div>
                            <div class="form-group col-12">
                                <div id="response_message_sub_company" style="display: none;">Waiting for response...</div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label for="case_type" class="text-primary text-hanuman-18 fw-bold mb-1"> ស្វែងរកឈ្មោះអ្នកអមនិយោជក</label>
                                    <input type="text" name="find_sub_company_autocomplete" minlength="2" value="{{ old('find_sub_company_autocomplete') }}" class="form-control" id="find_sub_company_autocomplete" >
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1 required2">ឈ្មោះអ្នកអមនិយោជក</label>
                                    <input type="text" name="sub_company_name[]" value="{{ old('sub_company_name[]') }}" class="form-control" id="sub_company_name" >
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">ភេទ</label>
                                    {!! showSelect('sub_company_gender[]', array("1" =>"ប្រុស", "2" => "ស្រី"), old('sub_company_gender[]'), " select2", "", "sub_company_gender") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="required2 fw-bold mb-1">ថ្ងៃខែឆ្នាំកំណើត</label>
                                    <input type="text"  name="sub_company_dob[]" id="sub_company_dob" value="{{ old('sub_company_dob[]') }}" class="form-control"  data-language="en" >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="required2 fw-bold mb-1">សញ្ជាតិ</label>
                                    {!! showSelect('sub_company_nationality[]', arrayNationality(1), old('sub_company_nationality[]'), " select2", "", "sub_company_nationality") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label for="id_number" class="fw-bold mb-1"> លេខអត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
                                    <input type="text" name="sub_company_id_number[]" value="{{ old('sub_company_id_number[]') }}" class="form-control" id="sub_company_id_number" >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label for="phone_number" class="required2 fw-bold mb-1">លេខទូរស័ព្ទខ្សែទី១</label>
                                    <input type="text" name="sub_company_phone_number[]" id="sub_company_phone_number" value="{{ old('sub_company_phone_number[]') }}" class="form-control" >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label for="phone_number" class="fw-bold mb-1">លេខទូរស័ព្ទខ្សែទី២</label>
                                    <input type="text" name="sub_company_phone2_number[]" id="sub_company_phone2_number" value="{{ old('sub_company_phone2_number[]') }}" class="form-control" >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label for="occupation" class="fw-bold mb-1">មុខងារ</label>
                                    <input type="text" name="sub_company_occupation[]" id="sub_company_occupation" value="{{ old('sub_company_occupation[]') }}" class="form-control" >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ទីកន្លែងកំណើត រាជធានី-ខេត្ត</label>
                                    {!! showSelect('sub_company_pob_province_id[]', arrayProvince(1,0), old('sub_company_pob_province_id'), " select2", "", "sub_company_pob_province_id", "") !!}
                                </div>

                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                    {!! showSelect('sub_company_pob_district_id[]', array(), old('sub_company_pob_district_id[]'), " select2", "", "sub_company_pob_district_id", "") !!}
                                </div>

                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                    {!! showSelect('sub_company_pob_commune_id[]', array(), old('sub_company_pob_commune_id[]'), " select2", "", "sub_company_pob_commune_id", "") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">អាសយដ្ឋានបច្ចុប្បន្ន រាជធានី-ខេត្ត</label>
                                    {!! showSelect('sub_company_province[]', arrayProvince(1,0), old('sub_company_province[]'), " select2", "", "sub_company_province", "") !!}
                                </div>

                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                    {!! showSelect('sub_company_district[]', array(), old('sub_company_district[]'), " select2", "", "sub_company_district", "") !!}
                                </div>

                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                    {!! showSelect('sub_company_commune[]', array(), old('sub_company_commune[]'), " select2", "", "sub_company_commune", "") !!}
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">ភូមិ</label>
                                    {!! showSelect('sub_company_village[]', array(), old('sub_company_village[]'), " select2", "", "sub_company_village", "") !!}
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label for="case_type" class="fw-bold mb-1">ផ្ទះលេខ</label>
                                    <input type="text" name="sub_company_addr_house_no[]" id="sub_company_addr_house_no" value="{{ old('sub_company_addr_house_no') }}" class="form-control" >
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">ផ្លូវ</label>
                                    <input type="text" name="sub_company_addr_street[]" id="sub_company_addr_street" value="{{ old('sub_company_addr_street[]') }}" class="form-control" />
                                </div>
                            </div>
                            <div class="row col-12  mt-5">
                                <label class="text-purple text-hanuman-24" for="contact_phone">
                                    ង. ដំណើរការនៃកិច្ចប្រជុំ
                                </label>
                            </div>
                            @php
                                $officerId = getCaseOfficer($case->id, 1, 6);
                                $officerNoter = getCaseOfficer($case->id, 1, 8);
                                $arrExcludedOfficerID = [];
                                array_push($arrExcludedOfficerID, $officerId);
                                array_push($arrExcludedOfficerID, $officerNoter);
                            @endphp
                            <div class="row">
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required">អ្នកផ្សះផ្សា </label>
                                    {!! showSelect('head_meeting', arrayOfficer($officerId), old('head_meeting', request('pob_province_id')), " select2", "", "", "") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required">អ្នកកត់ត្រា</label>
                                    {!! showSelect('noter', arrayOfficer($officerNoter, 1, 0), old('noter'), " select2", "", "", "required") !!}
                                </div>
                            </div>
                            <div  id="officer_1" class="row">
                                <div id="officer_1" class="form-group col-sm-6 mt-3">
                                    <label class="fw-bold mb-1 required pink">
                                        តំណាងមកពីក្រសួងការងារ និងបណ្ដុះបណ្ដាលវិជ្ជាជីវៈ</label>
                                    <div class="row py-1">
                                        <div style="width:2%" class="mt-1">1</div>
                                        <div style="width:96%">
                                            {!! showSelect('sub_officer[]', myArrOfficerExcept($arrExcludedOfficerID, 1, 0), old('sub_officer'), " select2", "", "sub_officer1", "") !!}
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group col-sm-2 mt-4">
                                    <label style="visibility: hidden">x</label>
                                    <button type="button" id="btn_add_officer" class="btn btn-info form-control">បន្ថែមឈ្មោះមន្ត្រី</button>
                                </div>
                                <div class="form-group col-sm-2 mt-4">
                                    <label style="visibility: hidden">x</label>
                                    <button type="button" id="btn_remove_officer" class="btn btn-danger form-control">លុបឈ្មោះមន្ត្រី</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 text-primary" for="contact_phone">
                                        +អ្នកផ្សះផ្សាបានបើកកិច្ចប្រជុំដោយធើ្វការពន្យល់ដូចខាងក្រោម
                                    </label>
                                    {!! showTextarea("log6_17", old('log6_17'), 6) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 text-primary" for="contact_phone">
                                        +ការពិពណ៌នាត្រួសៗ អំពីការទាមទាររបស់គូភាគី
                                        <br><span class="m-l-30">-ការទាមទាររបស់កម្មករនិយោជិត</span>
                                    </label>
                                    {!! showTextarea("log6_181", old('log6_181'), 6) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 text-primary m-l-30" for="contact_phone">
                                        -ការទាមទាររបស់និយោជក
                                    </label>
                                    {!! showTextarea("log6_182", old('log6_182'), 6) !!}
                                </div>
                            </div>

                            <div class="row col-12  mt-5">
                                <label class="text-purple text-hanuman-24" for="contact_phone">
                                    ច. លទ្ធផលនៃការផ្សះផ្សា
                                </label>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 text-primary" for="contact_phone">
                                        +អនុសាសន៍របស់អ្នកផ្សះផ្សាចំពោះគូភាគី
                                    </label>
                                    {!! showTextarea("log6_19", old('log6_19'), 6) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 text-primary" for="contact_phone">
                                        +ផ្អែកលើបទប្បញ្ញតិ្ដច្បាប់ដូចខាងក្រោម (ប្រសិនជាមាន)
                                    </label>
                                    {!! showTextarea("log6_19a", old('log6_19a')) !!}
                                </div>
                            </div>
                            <div class="row col-12 mt-5">
                                <label class="fw-bold mb-1 text-primary">+ចំណុចព្រមព្រៀងរបស់គូភាគី</label>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-5">
                                    <label class="fw-bold mb-1 pink">ចំណុចព្រមព្រៀង</label>
                                </div>
                                <div class="form-group col-sm-5">
                                    <label class="fw-bold mb-1 blue">ដំណោះស្រាយ</label>
                                </div>
                            </div>
                            <div id="log620_1" class="row">
                                <div class="form-group col-sm-5 mt-3">
                                    <div class="row py-0">
                                        <div style="width:96%">
{{--                                            <input type="text" name="log620_agree_point[]" value="{{ old('log620_agree_point[]') }}" class="form-control">--}}
                                            <input type="hidden" name="log620_id[]" value="0">
                                            <textarea rows="4" name="log620_agree_point[]" class="form-control">{{ old('log620_agree_point[]') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-5 mt-3">
                                    <div class="row py-0">
                                        <div style="width:96%">
{{--                                            <input type="text" name="log620_solution[]" value="{{ old('log620_solution[]') }}" class="form-control">--}}
                                            <textarea rows="4" name="log620_solution[]" class="form-control">{{ old('log620_solution[]') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-1 mt-3">
                                    <button type="button" id="btn_add_log620" class="btn btn-info form-control fw-bold">បន្ថែម</button>
                                </div>
                                <div class="form-group col-sm-1 mt-3">

                                    <button type="button" id="btn_remove_log620" class="btn btn-danger form-control fw-bold">លុប</button>
                                </div>
                            </div>
                            <div class="row col-12 mt-5">
                                <label class="fw-bold mb-1 text-primary">+ចំណុចមិនសះជារបស់គូភាគី</label>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-5">
                                    <label class="fw-bold mb-1 pink">ចំណុចមិនសះជា</label>
                                </div>
                                <div class="form-group col-sm-5">
                                    <label class="fw-bold mb-1 blue">ដំណោះស្រាយ</label>
                                </div>
                            </div>
                            <div id="log621_1" class="row">
                                <div class="form-group col-sm-5 mt-1">
                                    <div class="row py-0">
                                        <div style="width:96%">
                                            <input type="hidden" name="log621_id[]" value="0">
                                            <textarea rows="4" name="log621_disagree_point[]" class="form-control">{{ old('log621_disagree_point[]') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-5 mt-1">
                                    <div class="row py-0">
                                        <div style="width:96%">
{{--                                            <input type="text" name="log621_solution[]" value="{{ old('log621_solution[]') }}" class="form-control">--}}
                                            <textarea rows="4" name="log621_solution[]" class="form-control">{{ old('log621_solution[]') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-1 mt-3">
                                    <button type="button" id="btn_add_log621" class="btn btn-info form-control fw-bold">បន្ថែម</button>
                                </div>
                                <div class="form-group col-sm-1 mt-3">
                                    <button type="button" id="btn_remove_log621" class="btn btn-danger form-control fw-bold">លុប</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 text-primary" for="contact_phone">
                                        +វិធានការដែលត្រូវអនុវត្ដបន្ដ
                                    </label>
                                    {!! showTextarea("log6_22", old('log6_22'), 6) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 text-primary" for="contact_phone">
                                        +ក្នុងករណីវិវាទបញ្ជូនទៅក្រុមប្រឹក្សាអាជ្ញាកណ្ដាល
                                    </label>
                                    {!! showTextarea("log6_22", old('log6_22'), 6) !!}
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="form-group col-sm-4">
                                    <label class="fw-bold mb-1">ឈ្មោះអ្នកបកប្រែបើមាន</label>
                                    <input type="text" name="translator" value="{{ old('translator') }}" class="form-control" >
                                </div>
                                <div class="form-group col-sm-8">
                                    <label class="fw-bold mb-1">លិខិតផ្ទេរសិទ្ធិ</label>
                                    {!! upload_file("translator_letter", "(ប្រភេទឯកសារ pdf មានទំហំធំបំផុត 5MB)") !!}
                                </div>
                            </div>
                            <div class="row col-12  mt-5">
                                <label class="text-purple text-hanuman-24" for="contact_phone">
                                    ឆ. បំពេញដោយអ្នកធើ្វកំណត់ហេតុក្នុងពេលប្រជុំជាមួយភាគីវិវាទ
                                </label>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-9 mt-3">
                                    <label class="fw-bold mb-1 text-primary">
                                        +មូលហេតុសំខាន់ៗ នៃវិវាទ
                                    </label>
                                    {!! showSelect('log624_cause_id', arrayLog624(1, ""), old('log624_cause_id'), " select2", "", "", "") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">បើសិនជ្រើសរើស (បញ្ហាដទៃទៀត) </label>
                                    <input type="text" name="log624_cause_other" class="form-control">
                                </div>
                            </div>
                            <div class="row col-12  mt-5">
                                <label class="text-purple text-hanuman-24" for="contact_phone">
                                    ជ. បំពេញដោយនាយកដ្ឋានវិវាទការងារបន្ទាប់ពីប្រជុំជាមួយភាគីវិវាទ
                                </label>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-9 mt-3">
                                    <label class="fw-bold mb-1 text-primary">
                                        +វិវាទ
                                    </label>
                                    {!! showSelect('log625_solution_id', arrayLog625(1, ""), old('log625_solution_id'), " select2", "", "", "") !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3" style="text-align: center;">
                                    <div class="m-t-30 m-checkbox-inline custom-radio-ml">
                                        @foreach(arrayLog6StatusExclude() as $key => $val)
                                            <div class="form-check form-check-inline radio radio-primary">
                                                <input  class="form-check-input" id="status{{ $key }}" type="radio" name="status_id" value="{{ $key }}" @if($key == 1) checked @endif >
                                                <label class="form-check-label mb-4 fw-bold text-danger text-hanuman-17" for="status{{ $key }}"  >
                                                    <span>{{ $val }} {!! nbs(5) !!}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <button type="submit" class="btn btn-success form-control fw-bold">រក្សាទុក</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="moreAfterScript">
        @include('case.script.log6_script')
    </x-slot>
</x-admin.layout-main>
