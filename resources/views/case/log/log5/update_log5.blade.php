@php
    $row = $adata['case'];
    $log5 = $adata['log5'];
    $caseCompany = $row->caseCompany;
    $company = $row->company;
    $userOfficerID = auth()->user()->officer_id;
    $chkAllowAccess = allowAccessFromHeadOffice();
    $arrOfficerIDs = getCaseOfficerIDs($row->id);
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
            .btn-xxs {
                padding: 0.05rem 0.4rem 0.2rem 0.4rem;
                font-size: 5px;
            }
            .btn-xxs svg {
                width:20px !important;
                height:20px !important;
            }
        </style>
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body row mt-2">
                        <div class="form-group col-sm-4">
                            <a class="btn btn-info custom form-control fw-bold" href="{{ url('export/word/case/log5/'.$log5->id) }}" title="Download" target="_blank">ទាញយកកំណត់ហេតុ
                            </a>
                        </div>
                        <div class="form-group col-sm-4"></div>
                        @if(($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs)) || auth()->user()->id == $row->user_created && count($row->invitationForConcilation) == 0)
                            <div class="form-group col-sm-4">
                                <form name="frmDelete" action = "{{ url('log5'.'/'.$log5->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="form-control btn btn-danger delete-btn fw-bold">
                                        លុបកំណត់ហេតុ
                                    </button>
                                </form>
                            </div>
                        @endif

                    </div>
                    <form name="formCreateCase" action="{{ url('log5/'.$log5->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="id" value="{{ $log5->id }}" >
                        <input type="hidden" name="log_id" value="{{ $log5->log_id }}" >
                        <input type="hidden" name="case_id" value="{{ $log5->case_id }}" >
                        <input type="hidden" name="case_type_id" value="{{ $row->case_type_id }}" >
                        <input type="hidden" name="disputant_id" value="{{ $row->disputant_id }}" >
                        <input type="hidden" name="comID" id="company_id" value="{{ $row->company->company_id_lacms }}" >
                        <input type="hidden" name="company_id" value="{{ $row->company_id }}" >
                        <input type="hidden" name="invitation_type_id" value="{{ $row->invitation_type_id }}" >
                        <div class="card-body text-hanuman-17">
                            <div class="row">
                                <div class="form-group col-sm-6 mt-3">
                                    <label class="text-purple mb-1">ឈ្មោះសហគ្រាស គ្រឹះស្ថាន</label>
                                    <input type="text" name="company_name_khmer" id="company_name_khmer" value="{{ $row->company->company_name_khmer }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-6 mt-3">
                                    <label class="text-purple mb-1" for="company_name_latin">ឈ្មោះជាភាសាឡាតាំង</label>
                                    <input type="text" id="company_name_latin" value="{{ $row->company->company_name_latin }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">អាសយដ្ឋាន</label>
                                    <input type="text" value="{{ $row->company->province->pro_khname }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">លេខទូរស័ព្ទ</label>
                                    <input type="text" value="{{ $row->caseCompany->log5_company_phone_number }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">លេខ TIN</label>
                                    <input type="text" value="{{ $row->company->company_tin }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">លេខចុះបញ្ជីពាណិជ្ជកម្ម</label>
                                    <input type="text" value="{{ $row->company->company_register_number }}" class="form-control" disabled />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="text-purple mb-1">ឈ្មោះកម្មករនិយោជិត</label>
                                    <input type="text" value="{{ $row->disputant->name }}" class="form-control" disabled />
                                </div>
{{--                                <div class="form-group col-sm-3 mt-3">--}}
{{--                                    <label class="text-purple mb-1">ឈ្មោះជាភាសាឡាតាំង</label>--}}
{{--                                    <input type="text" value="{{ $row->disputant->name_latin }}" class="form-control" disabled />--}}
{{--                                </div>--}}
                                @php
                                    $gender = $row->disputant->gender == 1? "ប្រុស":"ស្រី"
                                @endphp
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-2">ភេទ</label>
                                    <input type="text" value="{{ $gender }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">លេខទូរស័ព្ទ</label>
                                    <input type="text" value="{{ $row->disputant->phone_number }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1">មានមុខងារ</label>
                                    <input type="text" value="{{ $row->caseDisputant->occupation }}" class="form-control" disabled />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required">ថ្ងៃខែឆ្នាំជួបប្រជុំ</label>
                                    <input type="text"  name="meeting_date" id="meeting_date" value="{{ old('meeting_date', date2Display($log5->meeting_date)) }}" class="form-control"  data-language="en" required >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required">ម៉ោងចាប់ផ្ដើម</label>
                                    <div class="input-group clockpicker" data-autoclose="true">
                                        <input name="meeting_stime" id="meeting_stime" value="{{ old("meeting_stime", date2Display($log5->meeting_stime,'H:i')) }}"  class="form-control" type="text" data-bs-original-title="" required >
                                    </div>
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required">ម៉ោងបញ្ចប់</label>
                                    <div class="input-group clockpicker" data-autoclose="true">
                                        <input name="meeting_etime" id="meeting_etime" value="{{ old("meeting_etime", date2Display($log5->meeting_etime, 'H:i')) }}"  class="form-control" type="text" data-bs-original-title="" required >
                                    </div>
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required">ទីកន្លែងប្រជុំ</label>
                                    {!! showSelect('meeting_place_id', array(1=>"នៅនាយកដ្ឋានវិវាទការងារ (ភ្នំពេញ)", 2 =>"នៅកន្លែងផ្សេង"), old('meeting_place_id', $log5->meeting_place_id), " select2", "", "", "") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">បើប្រជុំនៅកន្លែងផ្សេង</label>
                                    <input type="text" name="meeting_place_other" value="{{ old('meeting_place_other', $log5->meeting_place_other) }}" class="form-control">
                                </div>

                                <div class="form-group col-sm-9 mt-3">
                                    <label class="fw-bold mb-1 required">អំពីបញ្ហា</label>
                                    <input type="text" name="meeting_about" id="meeting_about" value="{{ old('meeting_about', $log5->meeting_about) }}" class="form-control" required />
                                </div>
                            </div>
                            <div class="row col-12 mt-3">
                                <span class="text-primary text-hanuman-24">វត្តមានក្នុងកិច្ចប្រជុំមាន:</span>
                            </div>
                            @php
                                //$officerId = getCaseOfficer($row->id, 1, 6);
                                $officerId = $adata['head_meeting']->attendant_id ?? 0;
                                $officerNoter = !empty($adata['noter']->attendant_id) ? $adata['noter']->attendant_id : getCaseOfficer($row->id, 1, 8);
//                                $officerNoter = getCaseOfficer($row->id, 1, 8);
                            @endphp
                            <div class="row mt-3">
                                <div class="form-group col-sm-3 mt-4">
                                    <label class="fw-bold mb-1 required">ប្រធានអង្គប្រជុំ</label>
                                    {!! showSelect('head_meeting', arrayOfficer(0,1), old('head_meeting', $officerId), " select2", "", "", "") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-4 mb-2">
                                    <input type="hidden" name="noterid" value="{{ $officerNoter }}" >
                                    <label class="fw-bold mb-1 required">អ្នកកត់ត្រា</label>
                                    {!! showSelect('noter', arrayOfficer(0,1), old('noter', $officerNoter), " select2", "", "", "required") !!}
{{--                                    {!! showSelect('noter', arrayOfficerExcept($officerId, 1, ""), old('noter', $adata['noter']->attendant_id), " select2", "", "", "required") !!}--}}
                                </div>

                                @if(!empty($adata['represent_company']))
                                <div class="form-group col-sm-3 mt-4">
                                    <label class="fw-bold mb-1">
                                        តំណាងក្រុមហ៊ុន
                                        @if($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs) || auth()->user()->id == $row->user_created)
                                        @php
                                            $deleteUrl = url('log5/delete/represent_company/'.$adata['represent_company']->id.'_'.$log5->id);
                                                $onClick = "comfirm_delete_steetalert2('".$deleteUrl."','តើអ្នកពិតជាចង់លុប មែនឫ?')";
                                            $str2='<button type="button" class="btn btn-danger btn-xxs" onClick="'.$onClick.'" title="Delete"><i data-feather="trash"></i></button>';
                                            echo $str2;
                                        @endphp
                                        @endif
                                    </label>
                                    <input type="text" value="{{ $adata['represent_company']->disputant->name }}" class="form-control" disabled />
                                </div>
                                @else
                                    <div class="form-group col-sm-3 mt-4">
                                        <label class="fw-bold mb-1 required">តំណាងក្រុមហ៊ុន</label>
                                        {!! showSelect('attendant_represent_company', arrayRepresentCompany(), old('attendant_represent_company'), " select2", "", "", "") !!}
                                    </div>
                                @endif
                                @if($adata['sub_represent_company']->count() > 0)
                                    @foreach($adata['sub_represent_company'] as $subCompany)
                                        <div class="form-group col-sm-3 mt-4">

                                            <label class="fw-bold mb-1">
                                                អមតំណាងក្រុមហ៊ុន
                                                @if($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs) || auth()->user()->id == $row->user_created)
                                                @php
                                                    $deleteUrl = url('log5/delete/represent_company/'.$subCompany->id.'_'.$log5->id);
                                                        $onClick = "comfirm_delete_steetalert2('".$deleteUrl."','តើអ្នកពិតជាចង់លុប មែនឫ?')";
                                                    $str2='<button type="button" class="btn btn-danger btn-xxs" onClick="'.$onClick.'" title="Delete"><i data-feather="trash"></i></button>';
                                                    echo $str2;
                                                @endphp
                                                @endif
                                            </label>
                                            <input type="text" value="{{ $subCompany->disputant->name }}" class="form-control" disabled />
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="form-group col-12">
                                <div id="response-message" style="display: none;">Waiting for response...</div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label for="case_type" class="text-primary text-hanuman-20 mb-1"> ស្វែងរកឈ្មោះចុងបណ្ដឹង (អ្នកតំណាងក្រុមហ៊ុន)</label>
                                    <input type="text" name="find_represent_company_autocomplete" minlength="2" value="{{ old('find_represent_company_autocomplete') }}" class="form-control" id="find_represent_company_autocomplete" >
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="form-group col-sm-4 mt-3">
                                    <label for="case_type" class="required2 fw-bold mb-1">ឈ្មោះចុងបណ្ដឹង</label>
                                    <input type="text" name="name[]" value="{{ old('name[]') }}" class="form-control" id="represent_company_name" >
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1 required2">ភេទ</label>
                                    {!! showSelect('gender[]', array("1" =>"ប្រុស", "2" => "ស្រី"), old('gender[]'), " select2", "", "represent_company_gender") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="required2 fw-bold mb-1">ថ្ងៃខែឆ្នាំកំណើត</label>
                                    <input type="text"  name="dob[]" id="represent_company_dob" value="{{ old('dob[]') }}" class="form-control"  data-language="en" >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="required2 fw-bold mb-1">សញ្ជាតិ</label>
                                    {!! showSelect('nationality[]', arrayNationality(1), old('nationality'), " select2", "", "represent_company_nationality") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label for="id_number" class="fw-bold mb-1"> លេខអត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
                                    <input type="text" name="id_number[]" value="{{ old('id_number[]') }}" class="form-control" id="represent_company_id_number" >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label for="phone_number" class="required2 fw-bold mb-1">លេខទូរស័ព្ទខ្សែទី១</label>
                                    <input type="text" name="phone_number[]" id="represent_company_phone_number" value="{{ old('phone_number[]') }}" class="form-control" >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label for="phone_number" class="fw-bold mb-1">លេខទូរស័ព្ទខ្សែទី២</label>
                                    <input type="text" name="phone2_number[]" id="represent_company_phone2_number" value="{{ old('phone2_number[]') }}" class="form-control" >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required2" for="occupation">មុខងារ</label>
                                    <input type="text" name="occupation[]" id="represent_company_occupation" value="{{ old('occupation[]') }}" class="form-control" >
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required2">ទីកន្លែងកំណើត ប្រទេស</label>
                                    {!! showSelect('pob_country_id[]', arrayNationality(1), old('pob_country_id', request('pob_country_id')), " select2", "", "represent_company_pob_country_id", "") !!}
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
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1 required2">អាសយដ្ឋានបច្ចុប្បន្ន រាជធានី-ខេត្ត</label>
                                    {!! showSelect('province[]', arrayProvince(1,0), old('province[]'), " select2", "", "represent_company_province", "") !!}
                                </div>

                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1 required2">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                    {!! showSelect('district[]', array(), old('pob_district[]'), " select2", "", "represent_company_district", "") !!}
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1 required2">ឃុំ-សង្កាត់</label>
                                    {!! showSelect('commune[]', array(), old('commune[]'), " select2", "", "represent_company_commune", "") !!}
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1">ភូមិ</label>
                                    {!! showSelect('village[]', array(), old('village[]'), " select2", "", "represent_company_village", "") !!}
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1" for="case_type">ផ្ទះលេខ</label>
                                    <input type="text" name="addr_house_no[]" id="represent_company_addr_house_no" value="{{ old('addr_house_no') }}" class="form-control" >
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1">ផ្លូវ</label>
                                    <input type="text" name="addr_street[]" id="represent_company_addr_street" value="{{ old('addr_street[]') }}" class="form-control" />
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label for="contact_phone" class="fw-bold mb-1">យោបល់របស់ប្រធានអង្គប្រជុំ</label>
                                    {!! showTextarea("head_officer_comment", old('head_officer_comment', $log5->head_officer_comment)) !!}
                                </div>
                            </div>
                            <div class="row col-12 mt-3">
                                <span class="text-purple text-hanuman-24">ក. សាវតារសហគ្រាស:</span>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4 mt-3">
                                    <input type="hidden" name="company_id" value="{{ $row->company->company_id }}" >
                                    <label class="fw-bold mb-1 required">ឈ្មោះក្រុមហ៊ុន</label>
                                    <input type="text" name="company_name_khmer" value="{{ $row->company->company_name_khmer }}" class="form-control" />
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1 required">ឈ្មោះក្រុមហ៊ុនជាភាសាឡាតាំង</label>
                                    <input type="text" name="company_name_latin" value="{{ $row->company->company_name_latin }}" class="form-control" />
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1">កាលបរិច្ឆេទបើកសហគ្រាស</label>
                                    <input type="text"  name="log5_open_date" id="open_date" value="{{ old('open_date', date2Display($company->open_date)) }}" class="form-control"  data-language="en">
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">កាលបរិច្ឆេទចុះបញ្ជីពាណិជ្ជកម្ម</label>
                                    <input type="text"  name="registration_date" id="registration_date" value="{{ old('registration_date', date2Display($company->registration_date)) }}" class="form-control"  data-language="en">
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">លេខចុះបញ្ជីពាណិជ្ជកម្ម</label>
                                    <input type="text"  name="company_register_number" id="company_register_number" value="{{ old('company_register_number', $company->company_register_number) }}" class="form-control">
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">លេខអត្តសញ្ញាណ ប.ស.ស.</label>
                                    <input type="text"  name="nssf_number" id="nssf_number" value="{{ old('nssf_number', $company->nssf_number) }}" class="form-control">
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1">លេខអត្តសញ្ញាណកម្មសារពើពន្ធ (TIN)</label>
                                    <input type="text"  name="company_tin" id="company_tin" value="{{ old('company_tin', $company->company_tin) }}" class="form-control">
                                </div>
                            </div>
                            {{--                                    Address of Head Office--}}
                            @php
                                $head_province = $caseCompany->log5_head_province_id;
                                $head_district = $caseCompany->log5_head_district_id;
                                $head_commune = $caseCompany->log5_head_commune_id;
                                $head_village = $caseCompany->log5_head_village_id;
                                $arrayHeadDistrict = $head_district > 0 ? arrayDistrict($head_province, 1, ""): array();
                                $arrayHeadCommune = $head_commune > 0 ? arrayCommune($head_district, 1, ""): array();
                                $arrayHeadVillage = $head_village > 0 ? arrayVillage($head_commune, 1, ""): array();
                            @endphp
                            <div class="row">
                                <div class="form-group col-sm-5 mt-3">
                                    <label class="fw-bold mb-1 required text-primary">អាសយដ្ឋាននៃមន្ទីរចាត់ការសហគ្រាស រាជធានី-ខេត្ត</label>
                                    {!! showSelect('log5_head_province_id', arrayProvince(1,0), old('log5_head_province_id', $head_province), " select2") !!}
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1 required">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                    {!! showSelect('log5_head_district_id', $arrayHeadDistrict, old('log5_head_district_id', $head_district), " select2", "", "", "required") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required">ឃុំ-សង្កាត់</label>
                                    {!! showSelect('log5_head_commune_id', $arrayHeadCommune, old('log5_head_commune_id', $head_commune), " select2", "", "", "required") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ភូមិ</label>
                                    {!! showSelect('log5_head_village_id', $arrayHeadVillage, old('log5_head_village_id', $head_village), " select2") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ផ្លូវ</label>
                                    <input type="text" name="log5_head_street_no" id="log5_head_street_no" value="{{ old('log5_head_street_no', $caseCompany->log5_head_street_no) }}" class="form-control" />
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">អគារលេខ</label>
                                    <input type="text" name="log5_head_building_no" id="log5_head_building_no" value="{{ old('log5_head_building_no', $caseCompany->log5_head_building_no) }}" class="form-control" />
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">លេខទូរស័ព្ទ</label>
                                    <input type="text" name="log5_head_phone" value="{{ old('log5_head_phone', $caseCompany->log5_head_phone) }}" class="form-control" id="log5_head_phone" placeholder="">
                                </div>

                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">នាមម្ចាស់សហគ្រាស</label>
                                    <input type="text" name="log5_owner_name_khmer" value="{{ old('log5_owner_name_khmer', $caseCompany->log5_owner_name_khmer) }}" class="form-control" id="log5_owner_name_khmer" placeholder="">
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">សញ្ជាតិ</label>
                                    {!! showSelect('log5_owner_nationality_id', arrayNationality(1,0), old('log5_owner_nationality_id', $caseCompany->log5_owner_nationality_id), " select2") !!}
                                </div>

                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">នាមនាយកសហគ្រាស</label>
                                    <input type="text" name="log5_director_name_khmer" value="{{ old('log5_director_name_khmer', $caseCompany->log5_director_name_khmer) }}" class="form-control" id="log5_director_name_khmer" placeholder="">
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">សញ្ជាតិ</label>
                                    {!! showSelect('log5_director_nationality_id', arrayNationality(1,0), old('log5_director_nationality_id', $caseCompany->log5_director_nationality_id), " select2") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">សកម្មភាពអាជីវកម្មចម្បង</label>
                                    <input type="text" name="log5_first_business_act" value="{{ old('log5_first_business_act', $caseCompany->log5_first_business_act) }}" class="form-control" id="log5_first_business_act" placeholder="">
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required">ទ្រង់ទ្រាយសហគ្រាស</label>
                                    {!! showSelect('log5_article_of_company', arrayArticleOfCompany(1,0), old('log5_article_of_company', $caseCompany->log5_article_of_company), " select2") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required">ប្រភេទសហគ្រាស</label>
                                    {!! showSelect('log5_company_type_id', arrayCompanyType(1,0), old('log5_company_type_id', $caseCompany->log5_company_type_id), " select2") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required">វិស័យ</label>
                                    {!! showSelect('log5_sector_id', myArraySector(1,0), old('log5_sector_id', $caseCompany->log5_sector_id), " select2") !!}
                                </div>
                            </div>

                            {{--                                    Address Of Company--}}
                            @php
                                $province_id = $caseCompany->log5_province_id;
                                $district_id = $caseCompany->log5_district_id;
                                $commune_id = $caseCompany->log5_commune_id;
                                $village_id = $caseCompany->log5_village_id;
                                $arrayDistrict = $head_district > 0? arrayDistrict($province_id, 1, ""): array();
                                $arrayCommune = $head_commune > 0? arrayCommune($district_id, 1, ""): array();
                                $arrayVillage = $head_village > 0? arrayVillage($commune_id, 1, ""): array();
                            @endphp
                            <div class="row mt-3">
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required text-primary">អាសយដ្ឋានសហគ្រាស រាជធានី-ខេត្ត</label>
                                    {!! showSelect('log5_province_id', arrayProvince(1,0), old('log5_province_id', $province_id), " select2", "", "", "") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                    {!! showSelect('log5_district_id', $arrayDistrict, old('log5_district_id', $district_id), " select2", "", "", "required") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1 required">ឃុំ-សង្កាត់</label>
                                    {!! showSelect('log5_commune_id', $arrayCommune, old('log5_commune_id', $commune_id), " select2", "", "", "required") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">ភូមិ</label>
                                    {!! showSelect('log5_village_id', $arrayVillage, old('log5_village_id', $village_id), " select2") !!}
                                </div>
                                <div class="form-group col-sm-1 mt-3">
                                    <label class="fw-bold mb-1">ផ្លូវ</label>
                                    <input type="text" name="log5_street_no" id="log5_street_no" value="{{ old('log5_street_no', $caseCompany->log5_street_no) }}" class="form-control" />
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">អគារលេខ</label>
                                    <input type="text" name="log5_building_no" id="log5_building_no" value="{{ old('log5_building_no', $caseCompany->log5_building_no) }}" class="form-control" />
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">លេខទូរស័ព្ទ</label>
                                    <input type="text" name="log5_company_phone_number" value="{{ old('log5_company_phone_number', $caseCompany->log5_company_phone_number) }}" class="form-control" id="log5_company_phone_number" placeholder="">
                                </div>

                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">ចំនួនកម្មករនិយោជិតសរុប</label>
                                    <input type="number" min="0" name="log5_total_employee" value="{{ old('log5_total_employee', $caseCompany->log5_total_employee) }}" class="form-control" id="log5_total_employee" placeholder="">
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">ស្រី</label>
                                    <input type="number" min="0" name="log5_total_employee_female" value="{{ old('log5_total_employee_female', $caseCompany->log5_total_employee_female) }}" class="form-control" id="log5_total_employee_female" placeholder="">
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">សហជីពមូលដ្ឋានដែលបានចុះបញ្ជីរ</label>
                                    <input type="number" min="0" name="log5_union1_number" value="{{ old('log5_union1_number', $caseCompany->log5_union1_number) }}" class="form-control" id="log5_union1_number">
                                </div>
                            </div>

                            <div class="row">
                                @if($log5->union1->count() > 0)
                                    @php $i=1; @endphp
                                    @foreach($log5->union1 as $union1)
                                        <div class="form-group col-sm-6 mt-3">
                                            <div class="row py-1">
                                                <div style="width:2%" class="mt-1">{{ $i }}</div>
                                                <div style="width:96%">
                                                    <input type="hidden" name="union1_id[]" value="{{ $union1->id }}">
                                                    <input type="text" name="union1_name_update[]" value="{{ old('union1_name_update[]', $union1->union1_name) }}" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6 mt-4">
                                            @if($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs) || auth()->user()->id == $row->user_created)
                                            @php
                                                $deleteUrl = url('log5/delete/union1/'.$union1->id.'_'.$log5->id);
                                                    $onClick = "comfirm_delete_steetalert2('".$deleteUrl."','តើអ្នកពិតជាចង់លុប មែនឫ?')";
                                                $str2='<button type="button" class="btn btn-danger" onClick="'.$onClick.'" title="Delete"><i data-feather="trash"></i></button>';
                                                echo $str2;
                                            @endphp
                                            @endif
                                        </div>
                                        @php $i++ @endphp
                                    @endforeach
                                @endif
                            </div><br/>
                            <div id="union_1" class="row">
                                <div class="form-group col-sm-6 mt-3">
                                    <label class="fw-bold mb-1 pink">ឈ្មោះសហជីពមូលដ្ឋាន</label>
                                    <div class="row py-1">
                                        <div style="width:2%" class="col-sm-1 mt-1">1</div>
                                        <div class="col-sm-11" style="width:96%">
                                            <input type="text" name="union1_name[]" value="{{ old('union1_name[]') }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label style="color:#FFFFFF">x</label>
                                    <button type="button" id="btn_add_union" class="btn btn-info form-control fw-bold">បន្ថែមឈ្មោះសហជីពមូលដ្ឋាន</button>
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label style="color:#FFFFFF">x</label>
                                    <button type="button" id="btn_remove_union" class="btn btn-danger form-control fw-bold">លុបឈ្មោះសហជីពមូលដ្ឋាន</button>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 pink" for="contact_phone">-ប្រភេទនៃកិច្ចសន្យាការងារដែលបានចុះជាមួយកម្មករនិយោជិត</label>
                                    {!! showTextarea("contract_type_with_employee", old('contract_type_with_employee', $log5->contract_type_with_employee)) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="text-purple text-hanuman-24" for="contact_phone">ខ. មូលហេតុចម្បងនៃវិវាទ</label>
                                    {!! showTextarea("dispute_cause", old('dispute_cause', $log5->dispute_cause), 6) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="text-purple text-hanuman-24" for="contact_phone">គ. ព័ត៌មានបន្ថែម</label>
                                    {!! showTextarea("dispute_more_info", old('dispute_more_info', $log5->dispute_more_info), 10) !!}
                                </div>
                            </div>
                            @if($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs) || auth()->user()->id == $row->user_created)
                            <div class="row mt-3">
                                <div class="form-group col-sm-3">
                                    <button type="submit" name="btnSubmit" value="save" class="form-control btn btn-success fw-bold">{{ __("btn.button_save") }}</button>

                                </div>
                                <div class="form-group col-sm-4">
                                    <button type="submit" name="btnSubmit" value="next" class="form-control btn btn-success fw-bold">{{ __("btn.button_save2") }}</button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="moreAfterScript">
        @include('case.script.log5_script')
{{--        @include('script.my_sweetalert2')--}}


    </x-slot>
</x-admin.layout-main>
