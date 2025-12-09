@php
    $row = $adata['case'];
    $log34 = $adata['log34'];
    //dd($row->disputant_id);
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
                            <a class="btn btn-info custom form-control fw-bold" href="{{ url('export/word/case/log34/'.$log34->id) }}" title="Download" target="_blank">ទាញយកកំណត់ហេតុ
                            </a>
                        </div>
                        <div class="form-group col-sm-4"></div>
                        @if(($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs)) || auth()->user()->id == $row->user_created && $row->invitationForConcilation->isEmpty())
                            <div class="form-group col-sm-4">
                                <form name="frmDelete" action = "{{ url('log34'.'/'.$log34->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="form-control btn btn-danger delete-btn fw-bold">
                                        លុបកំណត់ហេតុ
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    <form name="formUpdateLog34" action="{{ url('log34/'.$log34->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="id" value="{{ $log34->id }}" >
                        <input type="hidden" name="case_type_id" value="{{ $row->case_type_id }}" >
                        <input type="hidden" name="case_id" value="{{ $adata['case_id'] }}" >
                        <input type="hidden" name="log_id" value="{{ $log34->log_id }}" >
                        <input type="hidden" name="disputant_id" value="{{ $row->disputant_id }}" >
                        <input type="hidden" name="comID" id="company_id" value="{{ $row->company->company_id_lacms }}" >
                        <input type="hidden" name="company_id" value="{{ $row->company_id }}" >
                        <input type="hidden" name="invitation_type_id" value="{{ $row->invitation_type_id }}" >
                        <div class="card-body text-hanuman-17">
                            <div class="row">
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1">ឈ្មោះកម្មករ</label>
                                    <input type="text" value="{{ $row->disputant->name }} {{ $row->disputant->name_latin }}" class="form-control" disabled />
                                </div>
                                @php
                                    $gender = $row->disputant->gender == 1? "ប្រុស":"ស្រី"
                                @endphp
                                <div class="form-group col-sm-2 mt-3">
                                    <label class="fw-bold mb-1">ភេទ</label>
                                    <input type="text" value="{{ $gender }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">មានមុខងារ</label>
                                    <input type="text" value="{{ $row->caseDisputant->occupation }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">លេខទូរស័ព្ទ</label>
                                    <input type="text" value="{{ $row->caseDisputant->phone_number }}" class="form-control" disabled />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6 mt-3">
                                    <label class="fw-bold mb-1">ឈ្មោះក្រុមហ៊ុន</label>
                                    <input type="text" value="{{ $row->company->company_name_khmer }} {{ $row->company->company_name_latin }}" class="form-control" disabled />
                                </div>

                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">អាសយដ្ចាន</label>
                                    <input type="text" value="{{ $row->caseCompany->province->pro_khname }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold mb-1">លេខទូរស័ព្ទ</label>
                                    <input type="text" value="{{ $row->caseCompany->log5_company_phone_number }}" class="form-control" disabled />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1 required">ថ្ងៃខែឆ្នាំជួបប្រជុំ</label>
                                    <input type="text"  name="meeting_date" id="meeting_date" value="{{ old('meeting_date', date2Display($log34->meeting_date)) }}" class="form-control"  data-language="en" required >
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1 required">ម៉ោងចាប់ផ្ដើម</label>
                                    <div class="input-group clockpicker" data-autoclose="true">
                                        <input name="meeting_stime" id="meeting_stime" value="{{ old("meeting_stime", date2Display($log34->meeting_stime,"H:i")) }}"  class="form-control" type="text" data-bs-original-title="" required >
                                    </div>
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label class="fw-bold mb-1 required">ម៉ោងបញ្ចប់</label>
                                    <div class="input-group clockpicker" data-autoclose="true">
                                        <input name="meeting_etime" id="meeting_etime" value="{{ old("meeting_etime", date2Display($log34->meeting_etime,"H:i")) }}"  class="form-control" type="text" data-bs-original-title="" required >
                                    </div>
                                </div>

                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 required">បានផ្ដល់ព័ត៌មានស្ដីពី</label>
{{--                                    <input type="text" name="disputant_give_info" id="disputant_give_info" value="{{ old('disputant_give_info', $log34->disputant_give_info) }}" class="form-control" />--}}
                                    {!! showTextarea("disputant_give_info", old('disputant_give_info', $log34->disputant_give_info),"4","required") !!}
                                </div>
                            </div>
                            <div class="row col-12 mt-3">
                                <span class="text-primary text-hanuman-24">វត្តមានក្នុងកិច្ចប្រជុំមាន:</span>
                            </div>
                            @php
                                //$officerId = getCaseOfficer($row->id, 1, 6);
                                $officerId = $adata['head_meeting']->attendant_id;
                                $officerNoter = !empty($adata['log34_noter']) ? $adata['log34_noter']->attendant_id : getCaseOfficer($row->id, 1, 8);
//                                $officerNoter = getCaseOfficer($row->id, 1, 8);
                            @endphp
                            <div class="row mt-3">
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold required mb-2">ប្រធានអង្គប្រជុំ</label>
                                    {!! showSelect('head_meeting', arrayOfficer(0,1), old('head_meeting', $officerId), " select2", "", "", "") !!}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <input type="hidden" name="noter_id" value="{{ $officerNoter }}" >
                                    <label class="fw-bold required mb-2">អ្នកកត់ត្រា</label>
                                    {!! showSelect('noter', arrayOfficer(), old('noter', $officerNoter), " select2", "", "", "required") !!}
{{--                                    {!! showSelect('noter', arrayOfficer($officerNoter), old('noter', $adata['noter']->attendant_id), " select2", "", "", "required") !!}--}}
{{--                                    {!! showSelect('noter', arrayOfficerExcept($officerId, 1, ""), old('noter', $adata['noter']->attendant_id), " select2", "", "", "required") !!}--}}
                                </div>
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold required mb-2">អ្នកប្ដឹង</label>
                                    {!! showSelect('attendant_disputant_id', arrayDisputant($row->disputant_id), old('attendant_disputant_id'), " select2", "", "", "") !!}
                                </div>
                                @foreach($adata['sub_disputant'] as $row_sub_disputant)
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold" style="margin-bottom: 6px;">
                                            អមអ្នកប្ដឹង
                                            @if($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs) || auth()->user()->id == $row->user_created)
                                            @php
                                                $deleteUrl = url('log34/delete/sub_disputant/'.$row_sub_disputant->id.'_'.$log34->id);
                                                    $onClick = "comfirm_delete_steetalert2('".$deleteUrl."','តើអ្នកពិតជាចង់លុប មែនឫ?')";
                                                $str2='<button type="button" class="btn btn-danger btn-xxs" onClick="'.$onClick.'" title="Delete"><i data-feather="trash"></i></button>';
                                                echo $str2;
                                            @endphp
                                            @endif
                                        </label>
                                        <input type="text" value="{{ $row_sub_disputant->disputant->name }}" class="form-control " disabled />
                                    </div>
                                @endforeach
                                <div class="form-group col-sm-3 mt-3">
                                    <label class="fw-bold required mb-2" style="visibility: hidden ">X</label>
                                    <button id="btn_add_employee_sub" value="0" type="button" class="form-control btn btn-primary fw-bold">បន្ថែមឈ្មោះអមកម្មករ</button>
                                </div>

                            </div>
                            <div id="add_employee_blog" style="display: none;">
                                <div class="form-group col-12">
                                    <div id="response-message" style="display: none;">Waiting for response...</div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 mt-3">
                                        <label for="find_employee_autocomplete" class="text-primary text-hanuman-20 mb-1"> ស្វែងរកឈ្មោះអមអ្នកប្តឹង</label>
                                        <input type="text" name="find_employee_autocomplete" minlength="2" value="{{ old('find_employee_autocomplete') }}" class="form-control" id="find_employee_autocomplete" >
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="form-group col-sm-4 mt-3">
                                        <label class="fw-bold mb-1 required2" for="name">ឈ្មោះអមអ្នកប្ដឹង</label>
                                        <input type="text" name="name[]" value="{{ old('name[]') }}" class="form-control" id="name" >
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1 required2">ភេទ</label>
                                        {!! showSelect('gender[]', array("1" =>"ប្រុស", "2" => "ស្រី"), old('gender[]'), " select2", "", "gender") !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">ថ្ងៃខែឆ្នាំកំណើត</label>
                                        <input type="text"  name="dob[]" id="dob" value="{{ old('dob[]') }}" class="form-control"  data-language="en" >
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">សញ្ជាតិ</label>
                                        {!! showSelect('nationality[]', arrayNationality(1), old('nationality'), " select2", "", "nationality") !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label for="id_number"> លេខអត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
                                        <input type="text" name="id_number[]" value="{{ old('id_number[]') }}" class="form-control" id="id_number" >
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2" for="phone_number">លេខទូរស័ព្ទខ្សែទី១</label>
                                        <input type="text" name="phone_number[]" id="phone_number" value="{{ old('phone_number[]') }}" class="form-control" >
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1" for="phone_number">លេខទូរស័ព្ទខ្សែទី២</label>
                                        <input type="text" name="phone2_number[]" id="phone2_number" value="{{ old('phone2_number[]') }}" class="form-control" >
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2" for="occupation">មុខងារ</label>
                                        <input type="text" name="occupation[]" id="occupation" value="{{ old('occupation[]') }}" class="form-control" >
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">ទីកន្លែងកំណើត រាជធានី-ខេត្ត</label>
                                        {!! showSelect('pob_province_id[]', arrayProvince(1,0), old('pob_province_id', request('pob_province_id')), " select2", "", "pob_province_id", "") !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                        {!! showSelect('pob_district_id[]', array(), old('pob_district_id[]'), " select2", "", "pob_district_id", "") !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                        {!! showSelect('pob_commune_id[]', array(), old('pob_commune_id[]'), " select2", "", "pob_commune_id", "") !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">អាសយដ្ឋានបច្ចុប្បន្ន រាជធានី-ខេត្ត</label>
                                        {!! showSelect('province[]', arrayProvince(1,0), old('province[]'), " select2", "", "province", "") !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                        {!! showSelect('district[]', array(), old('pob_district[]'), " select2", "", "district", "") !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">ឃុំ-សង្កាត់</label>
                                        {!! showSelect('commune[]', array(), old('commune[]'), " select2", "", "commune", "") !!}
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">ភូមិ</label>
                                        {!! showSelect('village[]', array(), old('village[]'), " select2", "", "village", "") !!}
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1" for="case_type">ផ្ទះលេខ</label>
                                        <input type="text" name="addr_house_no[]" id="addr_house_no" value="{{ old('addr_house_no') }}" class="form-control" >
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">ផ្លូវ</label>
                                        <input type="text" name="addr_street[]" id="addr_street" value="{{ old('addr_street[]') }}" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 pink" for="log34_1">១-អំពីសភាពការណ៍រួមរបស់សហគ្រាស</label>
                                    {!! showTextarea("log34_1", old('log34_1', $log34->log34_1)) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 pink" for="log34_2">២-អំពីកិច្ចសន្យាការងារ</label>
                                    {!! showTextarea("log34_2", old('log34_2', $log34->log34_2)) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 pink" for="log34_3">៣-អំពីថិរវេលាធ្វើការ</label>
                                    {!! showTextarea("log34_3", old('log34_3', $log34->log34_3)) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 pink" for="log34_4">៤-អំពីការងារពេលយប់</label>
                                    {!! showTextarea("log34_4", old('log34_4', $log34->log34_4)) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 pink" for="log34_5">៥-អំពីការឈប់សម្រាកប្រចាំសប្ដាហ៍</label>
                                    {!! showTextarea("log34_5", old('log34_5', $log34->log34_5)) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 pink" for="log34_6">៦-អំពីថ្ងៃបុណ្យជាតិ ឈប់សម្រាកប្រចាំឆ្នាំដែលត្រូវមានប្រាក់ឈ្នួល</label>
                                    {!! showTextarea("log34_6", old('log34_6', $log34->log34_6)) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 pink" for="log34_7">៧-អំពីប្រាក់ឈ្នួល ប្រាក់បន្ទុកគ្រួសារ និងប្រាក់រង្វាន់ផេ្សងៗ</label>
                                    {!! showTextarea("log34_7", old('log34_7', $log34->log34_7)) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 pink" for="log34_8">៨-អំពីការរំលាយកិច្ចសន្យា មូលហេតុនៃការរំលាយកិច្ចសន្យា</label>
                                    {!! showTextarea("log34_8", old('log34_8', $log34->log34_8)) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 pink" for="log34_9">៩-អំពីការជូនដំណឹងមុនផ្ដាច់កិច្ចសន្យាការងារ</label>
                                    {!! showTextarea("log34_9", old('log34_9', $log34->log34_9)) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 pink" for="log34_10">១០-អំពីប្រាក់បំណាច់ផ្ដាច់កិច្ចសន្យាការងារ</label>
                                    {!! showTextarea("log34_10", old('log34_10', $log34->log34_10)) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold mb-1 pink" for="log34_11">១១-សំណូមពរ</label>
                                    {!! showTextarea("log34_11", old('log34_11', $log34->log34_11)) !!}
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
        @include('case.script.log34_script')
    </x-slot>
</x-admin.layout-main>
