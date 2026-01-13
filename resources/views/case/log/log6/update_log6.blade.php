@php
    $row = $adata['case'];
    $log6 = $adata['log6'];
    $caseYear = !empty($row->case_date) ? date2Display($row->case_date, 'Y') : myDate('Y');
    $userOfficerID = auth()->user()->officer_id;
    $chkAllowAccess = allowAccessFromHeadOffice();
    $arrOfficerIDs = getCaseOfficerIDs($row->id);
@endphp
{{-- {{ dd($arrOfficerIDs) }} --}}
<x-admin.layout-main :adata="$adata">
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

                0%,
                100% {
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
                    <div class="card-body row mt-2">
                        <div class="form-group col-sm-4">
                            <a class="btn btn-info custom form-control"
                                href="{{ url('export/word/case/log6/' . $log6->id) }}" title="Download"
                                target="_blank">ទាញយកកំណត់ហេតុ
                            </a>
                        </div>
                        <div class="form-group col-sm-4">
                            {{--                            @if ($log6->status_id == 2) --}}
                            {{--                            <a href='#' class='btn btn-success form-control' style='margin-bottom: 3px;' --}}
                            {{--                               onClick="comfirm_sweetalert2('{{ url("log6/generate/new/log/".$log6->id."/".$log6->status_id) }}', 'Are You Sure?')" >បង្កើតកំណត់ហេតុសុំផ្សះផ្សាឡើងវិញ</a> --}}
                            {{--                            @elseif($log6->status_id == 3) --}}
                            {{--                                <a href='#' class='btn btn-success form-control' style='margin-bottom: 3px;' --}}
                            {{--                                   onClick="comfirm_sweetalert2('{{ url("log6/generate/new/log/".$log6->id."/".$log6->status_id) }}', 'Are You Sure?')" >បង្កើតកំណត់ហេតុលើកពេលផ្សះផ្សា</a> --}}
                            {{--                            @endif --}}
                        </div>
                        @if ($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs) || auth()->user()->id == $row->user_created)
                            <div class="form-group col-sm-4">
                                <form name="frmDelete" action = "{{ url('log6' . '/' . $log6->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="form-control btn btn-danger delete-btn">
                                        លុបកំណត់ហេតុ
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    <form name="formCreateCase" action="{{ url('log6/' . $log6->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf

                        <input type="hidden" name="log_id" value="{{ $log6->log_id }}">
                        <input type="hidden" name="case_id" value="{{ $log6->case_id }}">
                        <input type="hidden" name="status_id" value="{{ $log6->status_id }}">
                        <input type="hidden" name="case_type_id" value="{{ $row->case_type_id }}">
                        <input type="hidden" name="case_year" value="{{ $caseYear }}">
                        <input type="hidden" name="invitation_id_employee"
                            value="{{ $log6->invitation_id_employee }}">
                        <input type="hidden" name="invitation_id_company" value="{{ $log6->invitation_id_company }}">
                        <input type="hidden" name="disputant_id" value="{{ $row->disputant_id }}">
                        <input type="hidden" name="comID" id="company_id"
                            value="{{ $row->company->company_id_lacms }}">
                        <input type="hidden" name="company_id" value="{{ $row->company_id }}">
                        <input type="hidden" name="current_status_id" id="current_status_id"
                            value="{{ $log6->status_id }}">

                        <div class="card-body text-hanuman-17">
                            {{--                                    Company Blog --}}
                            <div class="row col-12" style="display: none;">
                                <div class="form-group col-sm-4 mt-3">
                                    <label>ឈ្មោះក្រុមហ៊ុន</label>
                                    <input type="text" value="{{ $row->company->company_name_khmer }}"
                                        class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-4 mt-3">
                                    <label>ឈ្មោះក្រុមហ៊ុនជាភាសាឡាតាំង</label>
                                    <input type="text" value="{{ $row->company->company_name_latin }}"
                                        class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label>អាសយដ្ចាន</label>
                                    <input type="text" value="{{ $row->caseCompany->province->pro_khname }}"
                                        class="form-control" disabled />
                                </div>
                            </div>
                            {{--                                    Employee Blog --}}
                            <div class="row col-12" style="display: none;">
                                <div class="form-group col-sm-2 mt-3">
                                    <label>ឈ្មោះកម្មករ</label>
                                    <input type="text" value="{{ $row->disputant->name }}" class="form-control"
                                        disabled />
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label>ឈ្មោះជាភាសាឡាតាំង</label>
                                    <input type="text" value="{{ $row->disputant->name_latin }}"
                                        class="form-control" disabled />
                                </div>
                                @php
                                    $gender = $row->disputant->gender == 1 ? 'ប្រុស' : 'ស្រី';
                                @endphp
                                <div class="form-group col-sm-2 mt-3">
                                    <label>ភេទ</label>
                                    <input type="text" value="{{ $gender }}" class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label>លេខទូរស័ព្ទ</label>
                                    <input type="text" value="{{ $row->disputant->phone_number }}"
                                        class="form-control" disabled />
                                </div>
                                <div class="form-group col-sm-2 mt-3">
                                    <label>មានមុខងារ</label>
                                    <input type="text" value="{{ $row->caseDisputant->occupation }}"
                                        class="form-control" disabled />
                                </div>
                            </div>

                            <!--plaintiff Block -->
                            <div id="plantiff_block">

                                <div class="row mt-4">
                                    <label class="text-purple text-hanuman-24" for="contact_phone">
                                        ក. សេចក្ដីលម្អិតស្ដីពីការផ្ដួចផ្ដើមដំណើរការផ្សះផ្សា
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="fw-bold mb-1">១. កាលបរិចេ្ឆទនៃការចាប់ផ្ដើមវិវាទរវាងគូភាគី</label>
                                        <input type="text"
                                            value="{{ old('meeting_date', date2Display($row->case_date)) }}"
                                            class="form-control" data-language="en" disabled>
                                    </div>
                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="fw-bold mb-1">២.
                                            កាលបរិចេ្ឆទនៃវិវាទដែលបានប្ដឹងទៅអធិការការងារ</label>
                                        <input type="text"
                                            value="{{ old('meeting_date', date2Display($row->case_date_entry)) }}"
                                            class="form-control" data-language="en" disabled>
                                    </div>
                                </div>

                                <div class="row col-12  mt-5">
                                    <label class="text-purple text-hanuman-24" for="contact_phone">
                                        ខ. សេចក្ដីលម្អិតនៃកិច្ចប្រជុំ
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required">៣. កាលបរិច្ឆេទប្រជុំ</label>
                                        <input type="text" name="log6_date" id="log6_date"
                                            value="{{ old('log6_date', $log6->log6_date) }}" class="form-control"
                                            data-language="en" required>
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required">ម៉ោងចាប់ផ្ដើម</label>
                                        <div class="input-group clockpicker" data-autoclose="true">
                                            <input name="log6_stime" id="log6_stime"
                                                value="{{ old('log6_stime', date2Display($log6->log6_stime, 'H:i')) }}"
                                                class="form-control" type="text" data-bs-original-title=""
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required">ម៉ោងបញ្ចប់</label>
                                        <div class="input-group clockpicker" data-autoclose="true">
                                            <input name="log6_etime" id="log6_etime"
                                                value="{{ old('log6_etime', date2Display($log6->log6_etime, 'H:i')) }}"
                                                class="form-control" type="text" data-bs-original-title=""
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required">៤. ទីកន្លែងប្រជុំ</label>
                                        {!! showSelect(
                                            'log6_meeting_place_id',
                                            [1 => 'នៅនាយកដ្ឋានវិវាទការងារ (ភ្នំពេញ)', 2 => 'នៅកន្លែងផ្សេង'],
                                            old('log6_meeting_place_id', $log6->log6_meeting_place_id),
                                            ' select2',
                                            '',
                                            '',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">បើប្រជុំនៅកន្លែងផ្សេង</label>
                                        <input type="text" name="log6_meeting_other"
                                            value="{{ old('log6_meeting_other', $log6->log6_meeting_other) }}"
                                            class="form-control">
                                    </div>

                                    <div class="form-group col-sm-9 mt-3">
                                        <label class="fw-bold mb-1 required">ដើម្បីផ្សះផ្សាវិវាទបុគ្គល ស្ដីពី</label>
                                        <input type="text" name="log6_meeting_about"
                                            value="{{ old('log6_meeting_about', $log6->log6_meeting_about) }}"
                                            id="log6_meeting_about" class="form-control" required />
                                    </div>
                                </div>

                                <div class="row mt-5">
                                    <label class="text-purple text-hanuman-24" for="contact_phone">
                                        គ. សេចក្ដីពិស្ដារអំពីកម្មករនិយោជិត
                                        @if ($row->case_type_id == 1)
                                            (ដើមបណ្ដឹង)
                                        @elseif($row->case_type_id == 2)
                                            (ចុងបណ្ដឹង)
                                        @endif
                                        <button type="button" id="btn_employee" value="1"
                                            class="btn btn-success">បង្ហាញព័ត៌មានលម្អិត</button>
                                    </label>
                                </div>
                                <div id="r1Employee" class="row" style="display: none">
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">៥. ឈ្មោះកម្មករ</label>
                                        <input type="text" value="{{ $row->disputant->name }}"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">ឈ្មោះជាភាសាឡាតាំង</label>
                                        <input type="text" value="{{ $row->disputant->name_latin }}"
                                            class="form-control" disabled />
                                    </div>
                                    @php
                                        $gender = $row->disputant->gender == 1 ? 'ប្រុស' : 'ស្រី';
                                    @endphp
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">ភេទ</label>
                                        <input type="text" value="{{ $gender }}" class="form-control"
                                            disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">ថ្ងៃខែឆ្នាំកំណើត</label>
                                        <input type="text" value="{{ $row->disputant->dob }}"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">៦. មានមុខងារ</label>
                                        <input type="text" value="{{ $row->caseDisputant->occupation }}"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-4 mt-3">
                                        <label class="fw-bold mb-1">លេខទូរស័ព្ទ</label>
                                        <input type="text" value="{{ $row->caseDisputant->phone_number }}"
                                            class="form-control" disabled />
                                    </div>

                                    <div class="form-group col-sm-4 mt-3">
                                        <label class="fw-bold mb-1">៧. ទីកន្លែងកំណើត ឃុំ-សង្កាត់</label>
                                        <input type="text"
                                            value="@if (!empty($row->disputant->pobCommune)) {{ $row->disputant->pobCommune->com_khname }} @endif"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                        <input type="text"
                                            value="@if (!empty($row->disputant->pobDistrict)) {{ $row->disputant->pobDistrict->dis_khname }} @endif"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">រាជធានី-ខេត្ត</label>
                                        <input type="text"
                                            value="@if (!empty($row->disputant->pobProvince)) {{ $row->disputant->pobProvince->pro_khname }} @endif"
                                            class="form-control" disabled />
                                    </div>

                                </div>
                                <div id="r2Employee" class="row" style="display: none">
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">៨. អាសយដ្ឋាន ផ្ទះលេខ</label>
                                        <input type="text" value="{{ $row->caseDisputant->house_no }}"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-1 mt-3">
                                        <label class="fw-bold mb-1">ផ្លូវ</label>
                                        <input type="text" value="{{ $row->caseDisputant->street }}"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">ភូមិ</label>
                                        <input type="text"
                                            value="@if (!empty($row->caseDisputant->addressVillage)) {{ $row->caseDisputant->addressVillage->vil_khname }} @endif"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                        <input type="text"
                                            value="@if (!empty($row->caseDisputant->addressCommune)) {{ $row->caseDisputant->addressCommune->com_khname }} @endif"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                        <input type="text"
                                            value="@if (!empty($row->caseDisputant->addressDistrict)) {{ $row->caseDisputant->addressDistrict->dis_khname }} @endif"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">រាជធានី-ខេត្ត</label>
                                        <input type="text"
                                            value="@if (!empty($row->caseDisputant->addressProvince)) {{ $row->caseDisputant->addressProvince->pro_khname }} @endif"
                                            class="form-control" disabled />
                                    </div>
                                </div>

                                {{--                                    Sub Employee --}}
                                <div class="row col-12 mt-4">
                                    <label class="fw-bold mb-1">
                                        ៩. អ្នកដែលអមកម្មករនិយោជិត និង/ឬ តំណាងកម្មករនិយោជិត
                                    </label>
                                </div>
                                <div class="row col-12">
                                    @if ($adata['employee_sub']->count() > 0)
                                        @foreach ($adata['employee_sub'] as $employeeSub)
                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="fw-bold" style="margin-bottom: 6px;">
                                                    ឈ្មោះ
                                                    @if ($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs) || auth()->user()->id == $row->user_created)
                                                        @php
                                                            $deleteUrl = url(
                                                                'log6/delete/log_attendant/' .
                                                                    $employeeSub->id .
                                                                    '_' .
                                                                    $log6->id,
                                                            );
                                                            $onClick =
                                                                "comfirm_delete_steetalert2('" .
                                                                $deleteUrl .
                                                                "','តើអ្នកពិតជាចង់លុប មែនឫ?')";
                                                            $str2 =
                                                                '<button type="button" class="btn btn-danger btn-xxs" onClick="' .
                                                                $onClick .
                                                                '" title="Delete"><i data-feather="trash"></i></button>';
                                                            echo $str2;
                                                        @endphp
                                                    @endif
                                                </label>
                                                <input type="text" value="{{ $employeeSub->disputant->name }}"
                                                    class="form-control" disabled />
                                                <input type="hidden" name="employee_sub_id[]"
                                                    value="{{ $employeeSub->disputant->id }}" />
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="form-group col-12 mt-3">
                                    <div id="response_message_sub_employee" class="text-warning fw-bold"
                                        style="display: none;">Waiting for response...</div>
                                </div>
                                <div class="row2">
                                    <div class="form-group col-sm-12 mt-3">
                                        <label for="case_type" class="fw-bold pink text-hanuman-18 fw-bold mb-1">
                                            ស្វែងរកឈ្មោះអ្នកអមកម្មករ</label>
                                        <input type="text" name="find_sub_employee_autocomplete" minlength="2"
                                            value="{{ old('find_sub_employee_autocomplete') }}" class="form-control"
                                            id="find_sub_employee_autocomplete">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="form-group col-sm-4 mt-3">
                                        <label class="required2 fw-bold mb-1 fw-bold mb-1">ឈ្មោះអ្នកអមកម្មករ</label>
                                        <input type="text" name="sub_employee_name[]"
                                            value="{{ old('sub_employee_name[]') }}" class="form-control"
                                            id="sub_employee_name">
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1 required2">ភេទ</label>
                                        {!! showSelect(
                                            'sub_employee_gender[]',
                                            ['1' => 'ប្រុស', '2' => 'ស្រី'],
                                            old('sub_employee_gender[]'),
                                            ' select2',
                                            '',
                                            'sub_employee_gender',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="required2 fw-bold mb-1">ថ្ងៃខែឆ្នាំកំណើត</label>
                                        <input type="text" name="sub_employee_dob[]" id="sub_employee_dob"
                                            value="{{ old('sub_employee_dob[]') }}" class="form-control"
                                            data-language="en">
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="required2 fw-bold mb-1">សញ្ជាតិ</label>
                                        {!! showSelect(
                                            'sub_employee_nationality[]',
                                            arrayNationality(1),
                                            old('sub_employee_nationality[]'),
                                            ' select2',
                                            '',
                                            'sub_employee_nationality',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label for="id_number" class="fw-bold mb-1">
                                            លេខអត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
                                        <input type="text" name="sub_employee_id_number[]"
                                            value="{{ old('sub_employee_id_number[]') }}" class="form-control"
                                            id="sub_employee_id_number">
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label for="phone_number"
                                            class="required2 fw-bold mb-1">លេខទូរស័ព្ទខ្សែទី១</label>
                                        <input type="text" name="sub_employee_phone_number[]"
                                            id="sub_employee_phone_number"
                                            value="{{ old('sub_employee_phone_number[0]') }}" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label for="phone_number" class="fw-bold mb-1">លេខទូរស័ព្ទខ្សែទី២</label>
                                        <input type="text" name="sub_employee_phone2_number[]"
                                            id="sub_employee_phone2_number"
                                            value="{{ old('sub_employee_phone2_number[0]') }}" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label for="occupation" class="fw-bold mb-1 required2">មុខងារ</label>
                                        <input type="text" name="sub_employee_occupation[]"
                                            id="sub_employee_occupation"
                                            value="{{ old('sub_employee_occupation[]') }}" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">ទីកន្លែងកំណើត រាជធានី-ខេត្ត</label>
                                        {!! showSelect(
                                            'sub_employee_pob_province_id[]',
                                            arrayProvince(1, 0),
                                            old('sub_employee_pob_province_id'),
                                            ' select2',
                                            '',
                                            'sub_employee_pob_province_id',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                        {!! showSelect(
                                            'sub_employee_pob_district_id[]',
                                            [],
                                            old('sub_employee_pob_district_id[]'),
                                            ' select2',
                                            '',
                                            'sub_employee_pob_district_id',
                                            '',
                                        ) !!}
                                    </div>

                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                        {!! showSelect(
                                            'sub_employee_pob_commune_id[]',
                                            [],
                                            old('sub_employee_pob_commune_id[]'),
                                            ' select2',
                                            '',
                                            'sub_employee_pob_commune_id',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">អាសយដ្ឋានបច្ចុប្បន្ន
                                            រាជធានី-ខេត្ត</label>
                                        {!! showSelect(
                                            'sub_employee_province[]',
                                            arrayProvince(1, 0),
                                            old('sub_employee_province[]'),
                                            ' select2',
                                            '',
                                            'sub_employee_province',
                                            '',
                                        ) !!}
                                    </div>

                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                        {!! showSelect(
                                            'sub_employee_district[]',
                                            [],
                                            old('sub_employee_district[]'),
                                            ' select2',
                                            '',
                                            'sub_employee_district',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">ឃុំ-សង្កាត់</label>
                                        {!! showSelect(
                                            'sub_employee_commune[]',
                                            [],
                                            old('sub_employee_commune[]'),
                                            ' select2',
                                            '',
                                            'sub_employee_commune',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">ភូមិ</label>
                                        {!! showSelect(
                                            'sub_employee_village[]',
                                            [],
                                            old('sub_employee_village[]'),
                                            ' select2',
                                            '',
                                            'sub_employee_village',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label for="case_type" class="fw-bold mb-1">ផ្ទះលេខ</label>
                                        <input type="text" name="sub_employee_addr_house_no[]"
                                            id="sub_employee_addr_house_no"
                                            value="{{ old('sub_employee_addr_house_no') }}" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">ផ្លូវ</label>
                                        <input type="text" name="sub_employee_addr_street[]"
                                            id="sub_employee_addr_street"
                                            value="{{ old('sub_employee_addr_street[]') }}" class="form-control" />
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12 text-end">
                                        <button type="button" id="btn_next_to_defendant" class="btn btn-primary">
                                            ទៅដំណាក់កាលបន្ទាប់ &gt;
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!--Defendant Block -->
                            <div id="defendant_block" style="display:none;">
                                <div class="row col-12  mt-5">
                                    <label class="text-purple text-hanuman-24" for="contact_phone">
                                        ឃ. សេចក្ដីពិស្ដារអំពីនិយោជក
                                        @if ($row->case_type_id == 1)
                                            (ចុងបណ្ដឹង)
                                        @elseif($row->case_type_id == 2)
                                            (ដើមបណ្ដឹង)
                                        @endif
                                        <button type="button" id="btn_company" value="1"
                                            class="btn btn-success">បង្ហាញព័ត៌មានលម្អិត</button>
                                    </label>
                                </div>
                                <div id="r1Company" class="row col-12" style="display: none">
                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="fw-bold mb-1">១០. ឈ្មោះសហគ្រាសគ្រឹះស្ថាន</label>
                                        <input type="text" value="{{ $row->company->company_name_khmer }}"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="fw-bold mb-1">ឈ្មោះសហគ្រាសគ្រឹះស្ថានជាភាសាឡាតាំង</label>
                                        <input type="text" value="{{ $row->company->company_name_latin }}"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-4 mt-3">
                                        <label class="fw-bold mb-1">១១. អាសយដ្ឋានអគារលេខ</label>
                                        <input type="text" value="{{ $row->caseCompany->log5_head_building_no }}"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-4 mt-3">
                                        <label class="fw-bold mb-1">ផ្លូវ</label>
                                        <input type="text" value="{{ $row->caseCompany->log5_head_building_no }}"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-4 mt-3">
                                        <label class="fw-bold mb-1">ភូមិ</label>
                                        <input type="text"
                                            value="@if (!empty($row->caseCompany->village->vil_khname)) {{ $row->caseCompany->village->vil_khname }} @endif"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                        <input type="text" value="{{ $row->caseCompany->commune->com_khname }}"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                        <input type="text" value="{{ $row->caseCompany->district->dis_khname }}"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">រាជធានី-ខេត្ត</label>
                                        <input type="text" value="{{ $row->caseCompany->province->pro_khname }}"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">លេខទូរស័ព្ទ</label>
                                        <input type="text"
                                            value="{{ $row->caseCompany->log5_company_phone_number }}"
                                            class="form-control" disabled />
                                    </div>
                                </div>
                                <div id="r2Company" class="row col-12" style="display: none">
                                    <div class="form-group col-sm-4 mt-3">
                                        <label class="fw-bold mb-1">១២. ចំនួនកម្មករនិយោជិត</label>
                                        <input type="text" value="{{ $row->caseCompany->log5_total_employee }}"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-4 mt-3">
                                        <label class="fw-bold mb-1">ចំនួនកម្មករនិយោជិតស្រី</label>
                                        <input type="text"
                                            value="{{ $row->caseCompany->log5_total_employee_female }}"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-4 mt-3">
                                        <label class="fw-bold mb-1">១៣. សកម្មភាពអាជីវកម្មចម្បង</label>
                                        <input type="text"
                                            value="{{ $row->caseCompany->log5_first_business_act }}"
                                            class="form-control" disabled />
                                    </div>
                                </div>
                                {{--                                    Represent Company --}}
                                <div class="row col-12 mt-4">
                                    <label class="fw-bold mb-1">១៤. តំណាងនិយោជក</label>
                                </div>
                                <div class="row col-12">
                                    @if ($adata['company_main']->count() > 0)
                                        @foreach ($adata['company_main'] as $companyMain)
                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="fw-bold mb-1" style="margin-bottom: 6px;">
                                                    ឈ្មោះ
                                                    @if ($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs) || auth()->user()->id == $row->user_created)
                                                        @php
                                                            $deleteUrl = url(
                                                                'log6/delete/log_attendant/' .
                                                                    $companyMain->id .
                                                                    '_' .
                                                                    $log6->id,
                                                            );
                                                            $onClick =
                                                                "comfirm_delete_steetalert2('" .
                                                                $deleteUrl .
                                                                "','តើអ្នកពិតជាចង់លុប មែនឫ?')";
                                                            $str2 =
                                                                '<button type="button" class="btn btn-danger btn-xxs" onClick="' .
                                                                $onClick .
                                                                '" title="Delete"><i data-feather="trash"></i></button>';
                                                            echo $str2;
                                                        @endphp
                                                    @endif
                                                </label>
                                                <input type="text" name="company_main_name"
                                                    value="{{ $companyMain->disputant->name }}" class="form-control"
                                                    disabled />
                                                <input type="hidden" name="company_main_id"
                                                    value="{{ $companyMain->disputant->id }}" />
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="form-group col-12 mt-3">
                                    <div id="response_message_company" class="text-warning fw-bold"
                                        style="display: none;">Waiting for response...</div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 mt-3">
                                        <label for="case_type" class="pink text-hanuman-18 fw-bold mb-1">
                                            ស្វែងរកឈ្មោះតំណាងនិយោជក</label>
                                        <input type="text" name="find_represent_company_autocomplete"
                                            minlength="2" value="{{ old('find_represent_company_autocomplete') }}"
                                            class="form-control" id="find_represent_company_autocomplete">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="form-group col-sm-4 mt-3">
                                        <label class="required2 fw-bold mb-1">ឈ្មោះតំណាងនិយោជក</label>
                                        <input type="text" name="represent_company_name"
                                            value="{{ old('represent_company_name') }}" class="form-control"
                                            id="represent_company_name">
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1 required2">ភេទ</label>
                                        {!! showSelect(
                                            'represent_company_gender',
                                            ['1' => 'ប្រុស', '2' => 'ស្រី'],
                                            old('represent_company_gender'),
                                            ' select2',
                                            '',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="required2 fw-bold mb-1 required2">ថ្ងៃខែឆ្នាំកំណើត</label>
                                        <input type="text" name="represent_company_dob" id="represent_company_dob"
                                            value="{{ old('represent_company_dob') }}" class="form-control"
                                            data-language="en">
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="required2 fw-bold mb-1 required2">សញ្ជាតិ</label>
                                        {!! showSelect(
                                            'represent_company_nationality',
                                            arrayNationality(1),
                                            old('represent_company_nationality'),
                                            ' select2',
                                            '',
                                            'represent_company_nationality',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label for="id_number" class="fw-bold mb-1">
                                            លេខអត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
                                        <input type="text" name="represent_company_id_number"
                                            value="{{ old('represent_company_id_number') }}" class="form-control"
                                            id="represent_company_id_number">
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2"
                                            for="represent_company_phone_number">លេខទូរស័ព្ទខ្សែទី១</label>
                                        <input type="text" name="represent_company_phone_number"
                                            id="represent_company_phone_number"
                                            value="{{ old('represent_company_phone_number') }}" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1"
                                            for="represent_company_phone2_number">លេខទូរស័ព្ទខ្សែទី២</label>
                                        <input type="text" name="represent_company_phone2_number"
                                            id="represent_company_phone2_number"
                                            value="{{ old('represent_company_phone2_number') }}"
                                            class="form-control">
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label for="occupation" class="fw-bold mb-1 required2">មុខងារ</label>
                                        <input type="text" name="represent_company_occupation"
                                            id="represent_company_occupation"
                                            value="{{ old('represent_company_occupation') }}" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">ទីកន្លែងកំណើត រាជធានី-ខេត្ត</label>
                                        {!! showSelect(
                                            'represent_company_pob_province_id',
                                            arrayProvince(1, 0),
                                            old('represent_company_pob_province_id'),
                                            ' select2',
                                            '',
                                            'represent_company_pob_province_id',
                                            '',
                                        ) !!}
                                    </div>

                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                        {!! showSelect(
                                            'represent_company_pob_district_id',
                                            [],
                                            old('represent_company_pob_district_id'),
                                            ' select2',
                                            '',
                                            'represent_company_pob_district_id',
                                            '',
                                        ) !!}
                                    </div>

                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                        {!! showSelect(
                                            'represent_company_pob_commune_id',
                                            [],
                                            old('represent_company_pob_commune_id'),
                                            ' select2',
                                            '',
                                            'represent_company_pob_commune_id',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">អាសយដ្ឋានបច្ចុប្បន្ន
                                            រាជធានី-ខេត្ត</label>
                                        {!! showSelect(
                                            'represent_company_province',
                                            arrayProvince(1, 0),
                                            old('represent_company_province'),
                                            ' select2',
                                            '',
                                            '',
                                            '',
                                        ) !!}
                                    </div>

                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                        {!! showSelect(
                                            'represent_company_district',
                                            [],
                                            old('represent_company_district'),
                                            ' select2',
                                            '',
                                            'represent_company_district',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">ឃុំ-សង្កាត់</label>
                                        {!! showSelect(
                                            'represent_company_commune',
                                            [],
                                            old('represent_company_commune'),
                                            ' select2',
                                            '',
                                            'represent_company_commune',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">ភូមិ</label>
                                        {!! showSelect(
                                            'represent_company_village',
                                            [],
                                            old('represent_company_village'),
                                            ' select2',
                                            '',
                                            'represent_company_village',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label for="case_type" class="fw-bold mb-1">ផ្ទះលេខ</label>
                                        <input type="text" name="represent_company_addr_house_no"
                                            id="represent_company_addr_house_no"
                                            value="{{ old('represent_company_addr_house_no') }}"
                                            class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">ផ្លូវ</label>
                                        <input type="text" name="represent_company_addr_street"
                                            id="represent_company_addr_street"
                                            value="{{ old('represent_company_addr_street') }}"
                                            class="form-control" />
                                    </div>
                                </div>

                                {{--                                    Sub Represent Company --}}
                                <div class="row col-12 mt-4">
                                    <label class="fw-bold mb-1">១៥. អ្នកដែលអមនិយោជក</label>
                                </div>
                                <div class="row col-12">
                                    @if ($adata['company_sub']->count() > 0)
                                        @foreach ($adata['company_sub'] as $companySub)
                                            <div class="form-group col-sm-3 mt-3">

                                                <label class="fw-bold" style="margin-bottom: 6px;">
                                                    ឈ្មោះ
                                                    @if ($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs) || auth()->user()->id == $row->user_created)
                                                        @php
                                                            $deleteUrl = url(
                                                                'log6/delete/log_attendant/' .
                                                                    $companySub->id .
                                                                    '_' .
                                                                    $log6->id,
                                                            );
                                                            $onClick =
                                                                "comfirm_delete_steetalert2('" .
                                                                $deleteUrl .
                                                                "','តើអ្នកពិតជាចង់លុប មែនឫ?')";
                                                            $str2 =
                                                                '<button type="button" class="btn btn-danger btn-xxs" onClick="' .
                                                                $onClick .
                                                                '" title="Delete"><i data-feather="trash"></i></button>';
                                                            echo $str2;
                                                        @endphp
                                                    @endif
                                                </label>
                                                <input type="text" value="{{ $companySub->disputant->name }}"
                                                    class="form-control" disabled />
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="form-group col-12 mt-3">
                                    <div id="response_message_sub_company" class="text-warning fw-bold"
                                        style="display: none;">Waiting for response...</div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 mt-3">
                                        <label for="case_type" class="pink text-hanuman-18 mb-1 fw-bold">
                                            ស្វែងរកឈ្មោះអ្នកអមនិយោជក</label>
                                        <input type="text" name="find_sub_company_autocomplete" minlength="2"
                                            value="{{ old('find_sub_company_autocomplete') }}" class="form-control"
                                            id="find_sub_company_autocomplete">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="form-group col-sm-4 mt-3">
                                        <label class="required2 fw-bold mb-1">ឈ្មោះអ្នកអមនិយោជក</label>
                                        <input type="text" name="sub_company_name[]"
                                            value="{{ old('sub_company_name[]') }}" class="form-control"
                                            id="sub_company_name">
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1 required2">ភេទ</label>
                                        {!! showSelect(
                                            'sub_company_gender[]',
                                            ['1' => 'ប្រុស', '2' => 'ស្រី'],
                                            old('sub_company_gender[]'),
                                            ' select2',
                                            '',
                                            'sub_company_gender',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="required2 fw-bold mb-1 required2">ថ្ងៃខែឆ្នាំកំណើត</label>
                                        <input type="text" name="sub_company_dob[]" id="sub_company_dob"
                                            value="{{ old('sub_company_dob[]') }}" class="form-control"
                                            data-language="en">
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="required2 fw-bold mb-1 required2">សញ្ជាតិ</label>
                                        {!! showSelect(
                                            'sub_company_nationality[]',
                                            arrayNationality(1),
                                            old('sub_company_nationality[]'),
                                            ' select2',
                                            '',
                                            'sub_company_nationality',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label for="id_number" class="fw-bold mb-1">
                                            លេខអត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
                                        <input type="text" name="sub_company_id_number[]"
                                            value="{{ old('sub_company_id_number[]') }}" class="form-control"
                                            id="sub_company_id_number">
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label for="phone_number"
                                            class="required2 fw-bold mb-1">លេខទូរស័ព្ទខ្សែទី១</label>
                                        <input type="text" name="sub_company_phone_number[]"
                                            id="sub_company_phone_number"
                                            value="{{ old('sub_company_phone_number[]') }}" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label for="phone_number" class="fw-bold mb-1">លេខទូរស័ព្ទខ្សែទី២</label>
                                        <input type="text" name="sub_company_phone2_number[]"
                                            id="sub_company_phone2_number"
                                            value="{{ old('sub_company_phone2_number[]') }}" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label for="occupation" class="fw-bold mb-1 required2">មុខងារ</label>
                                        <input type="text" name="sub_company_occupation[]"
                                            id="sub_company_occupation" value="{{ old('sub_company_occupation[]') }}"
                                            class="form-control">
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">ទីកន្លែងកំណើត រាជធានី-ខេត្ត</label>
                                        {!! showSelect(
                                            'sub_company_pob_province_id[]',
                                            arrayProvince(1, 0),
                                            old('sub_company_pob_province_id'),
                                            ' select2',
                                            '',
                                            'sub_company_pob_province_id',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                        {!! showSelect(
                                            'sub_company_pob_district_id[]',
                                            [],
                                            old('sub_company_pob_district_id[]'),
                                            ' select2',
                                            '',
                                            'sub_company_pob_district_id',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                        {!! showSelect(
                                            'sub_company_pob_commune_id[]',
                                            [],
                                            old('sub_company_pob_commune_id[]'),
                                            ' select2',
                                            '',
                                            'sub_company_pob_commune_id',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">អាសយដ្ឋានបច្ចុប្បន្ន
                                            រាជធានី-ខេត្ត</label>
                                        {!! showSelect(
                                            'sub_company_province[]',
                                            arrayProvince(1, 0),
                                            old('sub_company_province[]'),
                                            ' select2',
                                            '',
                                            'sub_company_province',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                        {!! showSelect(
                                            'sub_company_district[]',
                                            [],
                                            old('sub_company_district[]'),
                                            ' select2',
                                            '',
                                            'sub_company_district',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1 required2">ឃុំ-សង្កាត់</label>
                                        {!! showSelect(
                                            'sub_company_commune[]',
                                            [],
                                            old('sub_company_commune[]'),
                                            ' select2',
                                            '',
                                            'sub_company_commune',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">ភូមិ</label>
                                        {!! showSelect(
                                            'sub_company_village[]',
                                            [],
                                            old('sub_company_village[]'),
                                            ' select2',
                                            '',
                                            'sub_company_village',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label for="case_type" class="fw-bold mb-1">ផ្ទះលេខ</label>
                                        <input type="text" name="sub_company_addr_house_no[]"
                                            id="sub_company_addr_house_no"
                                            value="{{ old('sub_company_addr_house_no') }}" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">ផ្លូវ</label>
                                        <input type="text" name="sub_company_addr_street[]"
                                            id="sub_company_addr_street"
                                            value="{{ old('sub_company_addr_street[]') }}" class="form-control" />
                                    </div>
                                </div>

                                <div class="row col-12  mt-5">
                                    <label class="text-purple text-hanuman-24" for="contact_phone">
                                        ង. ដំណើរការនៃកិច្ចប្រជុំ
                                    </label>
                                </div>
                                <div class="row">
                                    @php
                                        $arrExcludedOfficerID = [];
                                        $officerId = $adata['conflict_officer']->attendant_id;
                                        //                                    $officerId = !empty($adata['conflict_officer']) ?  $adata['conflict_officer']->attendant_id : getCaseOfficer($row->id, 1, 6);
                                        $officerNoter = $adata['conflict_noter']->attendant_id;
                                        //                                    $officerNoter = getCaseOfficer($row->id, 1, 8);
                                        array_push($arrExcludedOfficerID, $officerId);
                                        array_push($arrExcludedOfficerID, $officerNoter);
                                    @endphp
                                    <div class="form-group col-sm-3 mt-4">
                                        <label class="fw-bold required mb-3">
                                            ១៦. អ្នកផ្សះផ្សា </label>
                                        {!! showSelect(
                                            'head_meeting',
                                            arrayOfficer($officerId),
                                            old('head_meeting', request('pob_province_id')),
                                            ' select2',
                                            '',
                                            '',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-4">
                                        <label class="fw-bold required mb-3">អ្នកកត់ត្រា</label>
                                        {!! showSelect('noter', arrayOfficer(), old('noter', $officerNoter), ' select2', '', '', 'required') !!}
                                    </div>
                                    @if ($adata['company_main']->count() > 0)
                                        @foreach ($adata['sub_officer'] as $officerSub)
                                            <div class="form-group col-sm-3 mt-3">
                                                <label class="fw-bold mb-2">
                                                    តំណាងមកពីក្រសួង
                                                    @if ($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs) || auth()->user()->id == $row->user_created)
                                                        @php
                                                            array_push(
                                                                $arrExcludedOfficerID,
                                                                $officerSub->attendant_id,
                                                            );
                                                            $deleteUrl = url(
                                                                'log6/delete/log_attendant/' .
                                                                    $officerSub->id .
                                                                    '_' .
                                                                    $log6->id,
                                                            );
                                                            $onClick =
                                                                "comfirm_delete_steetalert2('" .
                                                                $deleteUrl .
                                                                "','តើអ្នកពិតជាចង់លុប មែនឫ?')";
                                                            $str2 =
                                                                '<button type="button" class="btn btn-danger btn-xxs" onClick="' .
                                                                $onClick .
                                                                '" title="Delete"><i data-feather="trash"></i></button>';
                                                            echo $str2;
                                                        @endphp
                                                    @endif
                                                </label>
                                                <input type="text"
                                                    value="{{ $officerSub->officer->officer_name_khmer }}"
                                                    class="form-control" disabled />
                                            </div>
                                        @endforeach
                                    @endif

                                </div>
                                <div id="officer_1" class="row col-12">
                                    <div id="officer_1" class="form-group col-sm-6 mt-3">
                                        <label class="fw-bold mb-2 required">
                                            មន្ត្រីដទៃទៀតដែលមានវត្ដមាននៅក្នុងកិច្ចប្រជុំនេះ </label>
                                        <div class="row">
                                            <div style="width:2%" class="">1</div>
                                            <div style="width:96%">
                                                {{--                                            {!! showSelect('sub_officer[]', arrayOfficer(0, 1, 0), old('sub_officer'), " select2", "", "sub_officer1", "") !!} --}}
                                                {!! showSelect(
                                                    'sub_officer[]',
                                                    myArrOfficerExcept($arrExcludedOfficerID, 1, 0),
                                                    old('sub_officer'),
                                                    ' select2',
                                                    '',
                                                    'sub_officer1',
                                                    '',
                                                ) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-2 align-self-end">

                                        <button type="button" id="btn_add_officer"
                                            class="btn btn-info form-control">បន្ថែមឈ្មោះមន្ត្រី</button>
                                    </div>
                                    <div class="form-group col-sm-2 align-self-end">

                                        <button type="button" id="btn_remove_officer"
                                            class="btn btn-danger form-control">លុបឈ្មោះមន្ត្រី</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 mt-3">
                                        <label for="contact_phone" class="fw-bold mb-1">
                                            ១៧. អ្នកផ្សះផ្សាបានបើកកិច្ចប្រជុំដោយធើ្វការពន្យល់ដូចខាងក្រោម
                                        </label>
                                        {!! showTextarea('log6_17', old('log6_17', $log6->log6_17), 6) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 mt-3">
                                        <label for="contact_phone" class="fw-bold mb-1">
                                            ១៨. ការពិពណ៌នាត្រួសៗ អំពីការទាមទាររបស់គូភាគី
                                            <br><span class="m-l-30">១៨.១. ការទាមទាររបស់កម្មករនិយោជិត</span>
                                        </label>
                                        {!! showTextarea('log6_181', old('log6_181', $log6->log6_181), 6) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 mt-3">
                                        <label for="contact_phone" class="m-l-30 fw-bold mb-1">
                                            ១៨.២. ការទាមទាររបស់និយោជក
                                        </label>
                                        {!! showTextarea('log6_182', old('log6_182', $log6->log6_182), 6) !!}
                                    </div>
                                </div>

                                <!-- Back and Submit Buttons -->
                                <div class="row mt-4">
                                    <div class="col-6 text-start">
                                        <button type="button" id="btn_back_to_plantiff" class="btn btn-secondary">
                                            &lt; ត្រឡប់ក្រោយ
                                        </button>
                                    </div>
                                    <div class="col-6 text-end">
                                        <button type="button" id="btn_next_to_contract"
                                            class="btn btn-primary">ទៅដំណាក់កាលបន្ទាប់ &gt;
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 4: Employment Contract & Conditions, Main Reason, Requests, Dates, Officers, File Upload -->
                            <div id="contract_block" style="display:none;">
                                <div class="row col-12  mt-5">
                                    <label class="text-purple text-hanuman-24 fw-bold mb-1" for="contact_phone">
                                        ច. លទ្ធផលនៃការផ្សះផ្សា
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 mt-3">
                                        <label for="contact_phone" class="fw-bold mb-1">
                                            ១៩. អនុសាសន៍របស់អ្នកផ្សះផ្សាចំពោះគូភាគី
                                        </label>
                                        {!! showTextarea('log6_19', old('log6_19', $log6->log6_19), 6) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 mt-3">
                                        <label for="contact_phone" class="fw-bold mb-1">
                                            ផ្អែកលើបទប្បញ្ញតិ្ដច្បាប់ដូចខាងក្រោម (ប្រសិនជាមាន)
                                        </label>
                                        {!! showTextarea('log6_19a', old('log6_19a', $log6->log6_19a)) !!}
                                    </div>
                                </div>

                                <div class="row col-12 mt-3">
                                    <label class="fw-bold mb-1">២០. ចំណុចព្រមព្រៀងរបស់គូភាគី <span
                                            class="text-danger">(ប្រសិនបើមាន សូមបំពេញទាំងចំណុចព្រមព្រៀង និង
                                            ដំណោះស្រាយ)</span></label>
                                </div>
                                <div class="row mt-3">
                                    <div class="form-group col-sm-5 ">
                                        <label class="fw-bold mb-1 pink">ចំណុចព្រមព្រៀង</label>
                                    </div>
                                    <div class="form-group col-sm-5">
                                        <label class="fw-bold mb-1 blue">ដំណោះស្រាយ</label>
                                    </div>
                                </div>
                                <div class="row">
                                    @if ($log6->log620->count() > 0)
                                        @php $i=1; @endphp
                                        @foreach ($log6->log620 as $log620)
                                            <div class="form-group col-sm-5 mt-2">
                                                <div class="row py-1">
                                                    <div style="width:96%">
                                                        <input type="hidden" name="log620_id[]"
                                                            value="{{ $log620->id }}">
                                                        <textarea rows="4" name="log620_agree_point[]" class="form-control">{{ old('log620_agree_point[]', $log620->agree_point) }}</textarea>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-5 mt-2">
                                                <div class="row py-1">
                                                    <div style="width:96%">
                                                        <textarea rows="4" name="log620_solution[]" class="form-control">{{ old('log620_solution[]', $log620->solution) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-2 mt-2">
                                                @if ($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs) || auth()->user()->id == $row->user_created)
                                                    @php
                                                        $deleteUrl = url(
                                                            'log6/delete/log620/' . $log620->id . '_' . $log6->id,
                                                        );
                                                        $onClick =
                                                            "comfirm_delete_steetalert2('" .
                                                            $deleteUrl .
                                                            "','តើអ្នកពិតជាចង់លុប មែនឫ?')";
                                                        $str2 =
                                                            '<button type="button" class="btn btn-danger" onClick="' .
                                                            $onClick .
                                                            '" title="Delete"><i data-feather="trash"></i></button>';
                                                        echo $str2;
                                                    @endphp
                                                @endif
                                            </div>
                                            @php $i++ @endphp
                                        @endforeach
                                    @endif
                                </div>
                                <div id="log620_1" class="row">
                                    <div class="form-group col-sm-5">
                                        <label></label>
                                        <div class="row py-1">
                                            <div style="width:96%">
                                                <input type="hidden" name="log620_id[]" value="0">
                                                <textarea rows="4" name="log620_agree_point[]" class="form-control">{{ old('log620_agree_point[]') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-5">
                                        <label></label>
                                        <div class="row py-1">
                                            <div style="width:96%">
                                                {{--                                            <input type="text" name="log620_solution[]" value="{{ old('log620_solution[]') }}" class="form-control"> --}}
                                                <textarea rows="4" name="log620_solution[]" class="form-control">{{ old('log620_solution[]') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-1 mt-1">
                                        <label style="color:#FFFFFF">x</label>
                                        <button type="button" id="btn_add_log620"
                                            class="btn btn-info form-control">បន្ថែម</button>
                                    </div>
                                    <div class="form-group col-sm-1 mt-1">
                                        <label style="color:#FFFFFF">x</label>
                                        <button type="button" id="btn_remove_log620"
                                            class="btn btn-danger form-control">លុប</button>
                                    </div>
                                </div>

                                <div class="row col-12 mt-3">
                                    <label class="fw-bold mb-1">២១. ចំណុចមិនសះជារបស់គូភាគី <span
                                            class="text-danger">(ប្រសិនបើមាន សូមបំពេញទាំងចំណុចមិនសះជា និង
                                            ដំណោះស្រាយ)</span></label>
                                </div>
                                <div class="row mt-3">
                                    <div class="form-group col-sm-5">
                                        <label class="fw-bold mb-1 pink">ចំណុចមិនសះជា</label>
                                    </div>
                                    <div class="form-group col-sm-5">
                                        <label class="fw-bold mb-1 blue">ដំណោះស្រាយ</label>
                                    </div>
                                </div>
                                <div class="row">
                                    @if ($log6->log621->count() > 0)
                                        @php $i=1; @endphp
                                        @foreach ($log6->log621 as $log621)
                                            <div class="form-group col-sm-5 mt-3">
                                                <div class="row py-1">
                                                    <div style="width:96%">
                                                        <input type="hidden" name="log621_id[]"
                                                            value="{{ $log621->id }}">
                                                        <textarea rows="4" name="log621_disagree_point[]" class="form-control">{{ old('log621_disagree_point[]', $log621->disagree_point) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-5 mt-3">
                                                <div class="row py-1">
                                                    <div style="width:96%">
                                                        <textarea rows="4" name="log621_solution[]" class="form-control">{{ old('log621_solution[]', $log621->solution) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-2 mt-4">
                                                @if ($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs) || auth()->user()->id == $row->user_created)
                                                    @php
                                                        $deleteUrl = url(
                                                            'log6/delete/log621/' . $log621->id . '_' . $log6->id,
                                                        );
                                                        $onClick =
                                                            "comfirm_delete_steetalert2('" .
                                                            $deleteUrl .
                                                            "','តើអ្នកពិតជាចង់លុប មែនឫ?')";
                                                        $str2 =
                                                            '<button type="button" class="btn btn-danger" onClick="' .
                                                            $onClick .
                                                            '" title="Delete"><i data-feather="trash"></i></button>';
                                                        echo $str2;
                                                    @endphp
                                                @endif
                                            </div>
                                            @php $i++ @endphp
                                        @endforeach
                                    @endif
                                </div>
                                <div id="log621_1" class="row">
                                    <div class="form-group col-sm-5">
                                        <label></label>
                                        <div class="row py-1">
                                            <div style="width:96%">
                                                <input type="hidden" name="log621_id[]" value="0">
                                                <textarea rows="4" name="log621_disagree_point[]" class="form-control">{{ old('log621_agree_point[]') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-5">
                                        <label></label>
                                        <div class="row py-1">
                                            <div style="width:96%">
                                                <textarea rows="4" name="log621_solution[]" class="form-control">{{ old('log621_solution[]') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-1 mt-1">
                                        <label style="color:#FFFFFF">x</label>
                                        <button type="button" id="btn_add_log621"
                                            class="btn btn-info form-control">បន្ថែម</button>
                                    </div>
                                    <div class="form-group col-sm-1 mt-1">
                                        <label style="color:#FFFFFF">x</label>
                                        <button type="button" id="btn_remove_log621"
                                            class="btn btn-danger form-control">លុប</button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-12 mt-3">
                                        <label for="contact_phone" class="fw-bold mb-1">
                                            ២២. វិធានការដែលត្រូវអនុវត្ដបន្ដ
                                        </label>
                                        {!! showTextarea('log6_22', old('log6_22', $log6->log6_22), 6) !!}
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="form-group col-sm-4">
                                        <label class="fw-bold mb-1">ឈ្មោះអ្នកបកប្រែបើមាន</label>

                                        <input type="text" name="translator"
                                            value="{{ old('translator', $log6->translator) }}" class="form-control">
                                    </div>
                                    @if ($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs) || auth()->user()->id == $row->user_created)
                                        <div class="form-group col-sm-8">
                                            <label class="fw-bold mb-1">លិខិតផ្ទេរសិទ្ធិ</label>
                                            {{--                                            {!! upload_file("case_file", "(ប្រភេទឯកសារ pdf មានទំហំធំបំផុត 5MB)") !!} --}}
                                            <input type="hidden" name="translator_letter_old"
                                                value="{{ $log6->translator_letter }}">
                                            @php
                                                $show_file = showFile(
                                                    1,
                                                    $log6->translator_letter,
                                                    pathToDeleteFile('case_doc/log6/' . $caseYear . '/'),
                                                    'delete',
                                                    'tbl_case_log6',
                                                    'log_id',
                                                    $log6->log_id,
                                                    'translator_letter',
                                                );
                                                if ($show_file) {
                                                    echo "<br><div class='mt-1'>" . $show_file . '</div>';
                                                } else {
                                                    echo "<div class='py-1'>" .
                                                        upload_file(
                                                            'translator_letter',
                                                            '(ប្រភេទឯកសារ pdf មានទំហំធំបំផុត 5MB)',
                                                        ) .
                                                        '</div>';
                                                }
                                            @endphp
                                        </div>
                                    @endif
                                </div>

                                <div class="row col-12  mt-5">
                                    <label class="text-purple text-hanuman-24 fw-bold mb-1" for="contact_phone">
                                        ឆ. បំពេញដោយអ្នកធើ្វកំណត់ហេតុក្នុងពេលប្រជុំជាមួយភាគីវិវាទ
                                    </label>
                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-9 mt-3">
                                        <label class="fw-bold mb-1">
                                            ២៤. មូលហេតុសំខាន់ៗ នៃវិវាទ
                                        </label>
                                        {!! showSelect(
                                            'log624_cause_id',
                                            arrayLog624(1, ''),
                                            old('log624_cause_id', $log6->log624_cause_id),
                                            ' select2',
                                            '',
                                            '',
                                            '',
                                        ) !!}
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">បើសិនជ្រើសរើស (បញ្ហាដទៃទៀត) </label>
                                        <input type="text" name="log624_cause_other"
                                            value="{{ old('log624_cause_other', $log6->log624_cause_other) }}"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="row col-12  mt-5">
                                    <label class="text-purple text-hanuman-24" for="contact_phone">
                                        ជ. បំពេញដោយនាយកដ្ឋានវិវាទការងារបន្ទាប់ពីប្រជុំជាមួយភាគីវិវាទ
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-9 mt-3">
                                        <label class="fw-bold mb-1">
                                            ២៥. វិវាទ
                                        </label>
                                        {!! showSelect(
                                            'log625_solution_id',
                                            arrayLog625(1, ''),
                                            old('log625_solution_id', $log6->log625_solution_id),
                                            ' select2',
                                            '',
                                            '',
                                            '',
                                        ) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-10" style="text-align: center;">
                                        <div class="m-t-20 m-checkbox-inline custom-radio-ml">
                                            @php
                                                $excludeStatus = getArrayExcludeStatus($log6->status_id);
                                            @endphp
                                            @foreach (arrayLog6StatusExclude($excludeStatus) as $key => $val)
                                                <div class="form-check form-check-inline radio radio-primary">
                                                    <input class="form-check-input radio_status_id"
                                                        id="status{{ $key }}" type="radio"
                                                        name="radio_status_id" value="{{ $key }}"
                                                        @if ($log6->status_id == $key) checked @endif>
                                                    <label
                                                        class="form-check-label mb-4 fw-bold text-danger text-hanuman-17"
                                                        for="status{{ $key }}">
                                                        <span>{{ $val }} {!! nbs(5) !!}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div id="div_reopen_upload_file" class="">
                                    {{--                                <div id="check_reopen_status" class="form-group col-sm-2"> --}}
                                    {{--                                    <label style="color:#FFFFFF">x</label> --}}
                                    {{--                                    <div class="form-inline"> --}}
                                    {{--                                        <div class="form-check checkbox checkbox-solid-danger mb-0" > --}}
                                    {{--                                            <input type="checkbox" name="reopen_status" id="reopen_status" value="{{ $log6->reopen_status }}" @if ($log6->reopen_status == 1) checked @endif  /> --}}
                                    {{--                                            <label class="form-check-label text-hanuman-18 fw-bold" for="reopen_status"> --}}
                                    {{--                                                សុំផ្សះផ្សាឡើងវិញ --}}
                                    {{--                                            </label> --}}
                                    {{--                                        </div> --}}
                                    {{--                                    </div> --}}
                                    {{--                                </div> --}}
                                    <div id="show_upload_status_letter" class="row">
                                        <div class="form-group col-sm-3">
                                            <label class="fw-bold">កាលបរិច្ឆេទណាត់ជួប</label>
                                            <input type="text" name="status_date" id="status_date"
                                                value="{{ old('status_date', date2Display($log6->status_date)) }}"
                                                class="form-control" data-language="en">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label class="fw-bold">ម៉ោង</label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input name="status_time" id="status_time"
                                                    value="{{ old('status_time', date2Display($log6->status_time, 'H:i')) }}"
                                                    class="form-control" type="text" data-bs-original-title=""
                                                    required>
                                            </div>
                                        </div>
                                        @if ($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs) || auth()->user()->id == $row->user_created)
                                            <div class="form-group col-sm-6">
                                                <label class="fw-bold">លិខិតសុំផ្សះផ្សាឡើងវិញ</label>
                                                <input type="hidden" name="status_letter_old"
                                                    value="{{ $log6->status_letter }}">
                                                @php
                                                    $show_file = showFile(
                                                        1,
                                                        $log6->status_letter,
                                                        pathToDeleteFile(
                                                            'case_doc/log6/status_letter/' . $caseYear . '/',
                                                        ),
                                                        'delete',
                                                        'tbl_case_log6',
                                                        'log_id',
                                                        $log6->log_id,
                                                        'status_letter',
                                                    );
                                                    if ($show_file) {
                                                        echo "<br><div class='mt-1'>" . $show_file . '</div>';
                                                    } else {
                                                        echo "<div class='py-1'>" .
                                                            upload_file(
                                                                'status_letter',
                                                                '(ប្រភេទឯកសារ pdf មានទំហំធំបំផុត 5MB)',
                                                            ) .
                                                            '</div>';
                                                    }
                                                @endphp
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if ($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs) || auth()->user()->id == $row->user_created)
                                    <div class="row mt-4 align-items-center">
                                        <!-- Back Button -->
                                        <div class="col-md-4 text-start mb-2 mb-md-0">
                                            <button type="button" id="btn_back_to_plantiff_contract"
                                                class="btn btn-secondary px-4">
                                                &larr; ត្រឡប់ក្រោយ
                                            </button>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="col-md-8 text-end">
                                            <div class="d-inline-flex gap-2">
                                                <button type="submit" name="btnSubmit" value="save"
                                                    class="btn btn-success px-4">
                                                    {{ __('btn.button_save') }}
                                                </button>

                                                <button type="submit" name="btnSubmit" value="next"
                                                    class="btn btn-primary px-4">
                                                    {{ __('btn.button_save2') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sections = {
                plantiff: document.getElementById('plantiff_block'),
                defendant: document.getElementById('defendant_block'),
                contract: document.getElementById('contract_block')
            };

            const scrollToSection = (section) => {
                section.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            };

            const validateInputs = (inputs) => {
                let valid = true;
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.classList.add('is-invalid');
                        valid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });
                return valid;
            };

            // Next from Plantiff -> Defendant
            document.getElementById('btn_next_to_defendant').addEventListener('click', function() {
                const requiredFields = sections.plantiff.querySelectorAll('input[required]');
                if (!validateInputs(requiredFields)) return;
                sections.plantiff.style.display = 'none';
                sections.defendant.style.display = 'block';
                scrollToSection(sections.defendant);
            });

            // Back from Defendant -> Plantiff
            document.getElementById('btn_back_to_plantiff').addEventListener('click', function() {
                sections.defendant.style.display = 'none';
                sections.plantiff.style.display = 'block';
                scrollToSection(sections.plantiff);
            });

            // Next from Defendant -> Contract
            document.getElementById('btn_next_to_contract').addEventListener('click', function() {
                const requiredFields = sections.defendant.querySelectorAll(
                    'input[required], select[required]');
                if (!validateInputs(requiredFields)) return;
                sections.defendant.style.display = 'none';
                sections.contract.style.display = 'block';
                scrollToSection(sections.contract);
            });

            // Back from Contract -> Defendant using existing button
            const btnBackFromContract = document.getElementById('btn_back_to_plantiff_contract');
            if (btnBackFromContract) {
                btnBackFromContract.addEventListener('click', function() {
                    sections.contract.style.display = 'none';
                    sections.defendant.style.display = 'block';
                    scrollToSection(sections.defendant);
                });
            }
        });
    </script>

    <x-slot name="moreAfterScript">
        @include('case.script.log6_script')
        @include('script.my_sweetalert2')
    </x-slot>
</x-admin.layout-main>
