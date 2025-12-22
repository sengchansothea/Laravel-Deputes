@php
    $row = $adata['case'];
    $inOrOutDomain = $adata['caseDomain'] == $adata['domainOfficer'] ? 1 : 2;
    $entryUser = $row->entryUser;
    $disputant = $row->disputant;
    $company = $row->company;
    $caseCompany = $row->caseCompany;
    $caseDisputant = $row->caseDisputant;

    $caseYear = !empty($row->case_date) ? date2Display($row->case_date, 'Y') : myDate('Y');
    $caseNumber = !empty($row->case_number) ? $row->case_number : 0;
    $casePre = '';
    $cYear = '';

    $lastOfficer = $adata['lastOfficer'];
    $lastNoter = $adata['lastNoter'];

    $userOfficerID = $adata['userOfficerID'];
    $entryUserID = $adata['entryUserID'];
    $caseOfficerIDs = $adata['caseOfficerIDs'];
    $hasFullAccess =
        $adata['allowAccess'] || in_array($userOfficerID, $caseOfficerIDs) || $userOfficerID == $entryUserID;

    $invitationEmployee = $adata['invEmployeeData'];
    $showCaseLog34 = $adata['log34Data'];
    $invitationCompany = $adata['invCompanyData'];
    $showCaseLog5 = $adata['log5Data'];
    $invitationBoth = $adata['invitationBoth'];
    $showCaseLog6All = $adata['log6Data'];

@endphp
<x-admin.layout-main :adata="$adata">
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <input type="hidden" name="disputant_id" value="{{ $row->disputant_id }}" />
                    <input type="hidden" name="company_id" value="{{ $row->company_id }}" />
                    <div class="card-body text-hanuman-17">
                        <div class="card-block">
                            <div class="row">
                                <div class="form-group col-sm-4 mt-3">
                                    <label for="case_type_id" class="fw-bold required mb-2">
                                        ប្រភេទពាក្យបណ្ដឹង</label>{!! myToolTip(__('case.case_type')) !!}
                                    <input type="text" value="{{ $row->caseType->case_desc }}" class="form-control"
                                        disabled />
                                </div>
                                @if (!empty($caseDisputant->phone_number2))
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="mb-2">ឈ្មោះកម្មករនិយោជិត</label>
                                        <input type="text"
                                            value="{{ $disputant->name }} {{ $disputant->name_latin }}"
                                            class="form-control" disabled />
                                    </div>
                                    @php
                                        $gender = $disputant->gender == 1 ? 'ប្រុស' : 'ស្រី';
                                    @endphp
                                    <div class="form-group col-sm-1 mt-3">
                                        <label class="mb-2">ភេទ</label>
                                        <input type="text" value="{{ $gender }}" class="form-control"
                                            disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="mb-2">លេខទូរស័ព្ទ (ខ្សែទី១)</label>
                                        <input type="text" value="{{ $caseDisputant->phone_number }}"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="mb-2">លេខទូរស័ព្ទ (ខ្សែទី២)</label>
                                        <input type="text" value="{{ $caseDisputant->phone_number2 }}"
                                            class="form-control" disabled />
                                    </div>
                                @else
                                    @if (!empty($disputant))
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="mb-2">ឈ្មោះកម្មករនិយោជិត</label>
                                            <input type="text"
                                                value="{{ $disputant->name }} {{ $disputant->name_latin }}"
                                                class="form-control" disabled />
                                        </div>
                                        @php
                                            $gender = $disputant->gender == 1 ? 'ប្រុស' : 'ស្រី';
                                        @endphp
                                        <div class="form-group col-sm-2 mt-3">
                                            <label class="mb-2">ភេទ</label>
                                            <input type="text" value="{{ $gender }}" class="form-control"
                                                disabled />
                                        </div>
                                        <div class="form-group col-sm-2 mt-3">
                                            <label class="mb-2">លេខទូរស័ព្ទ</label>
                                            <input type="text" value="{{ $caseDisputant->phone_number }}"
                                                class="form-control" disabled />
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <div class="row">
                                @if (!empty($caseCompany->log5_company_phone_number2))
                                    <div class="form-group col-sm-8 mt-3">
                                        <label class="mb-2">ឈ្មោះសហគ្រាស គ្រឹះស្ថាន</label>
                                        <input type="text" name="company_name_khmer" id="company_name_khmer"
                                            value="{{ $company->company_name_khmer }} {{ $company->company_name_latin }}"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="mb-2">លេខទូរស័ព្ទ (ខ្សែទី១)</label>
                                        <input type="text"
                                            value="@if (isset($caseCompany->log5_company_phone_number)) {{ $caseCompany->log5_company_phone_number }} @endif"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="mb-2">លេខទូរស័ព្ទ (ខ្សែទី២)</label>
                                        <input type="text" value="{{ $caseCompany->log5_company_phone_number2 }}"
                                            class="form-control" disabled />
                                    </div>
                                @else
                                    <div class="form-group col-sm-10 mt-3">
                                        <label class="mb-2">ឈ្មោះសហគ្រាស គ្រឹះស្ថាន</label>
                                        <input type="text" name="company_name_khmer" id="company_name_khmer"
                                            value="{{ $company->company_name_khmer }} {{ $company->company_name_latin }}"
                                            class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="mb-2">លេខទូរស័ព្ទ</label>
                                        <input type="text"
                                            value="@if (isset($caseCompany->log5_company_phone_number)) {{ $caseCompany->log5_company_phone_number }} @endif"
                                            class="form-control" disabled />
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6 mt-3">
                                    <label for="case_type_id"
                                        class="fw-bold text-danger">***ដែនការិយាល័យទី{{ number2KhmerNumber($adata['domainID']) }}***</label>
                                </div>
                            </div>
                            <form name="frm2" action="{{ url('cases/' . $row->id) }}" method="GET">
                                <div class="row">
                                    <div class="form-group col-sm-6 mt-3">
                                        <label for="case_type_id"
                                            class="fw-bold text-info">*បញ្ចូលពាក្យបណ្តឹងដោយ៖</label>
                                        <label class="form-label fw-bold text-danger">{{ $entryUser->k_fullname }}
                                            @if (!empty($entryUser->officerRole))
                                                <span
                                                    class="blue fw-bold">({{ $entryUser->officerRole->officer_role }})</span>
                                            @endif
                                        </label>

                                    </div>
                                    <div class="form-group col-sm-4 mt-3">
                                        {!! showSelect(
                                            'in_out_domain',
                                            [1 => 'ក្នុងដែនការិយាល័យ', 2 => 'ក្រៅដែនការិយាល័យ'],
                                            old('in_out_domain', $inOrOutDomain),
                                            ' select2',
                                            "onchange='this.form.submit()'",
                                            '',
                                            'required',
                                        ) !!}
                                    </div>
                                </div>
                            </form>
                            <br>
                            <table class="table text-hanuman-18">
                                <thead class="table-light">
                                    <tr>
                                        <th width="24%" class="text-hanuman-22 text-danger">ជំហាននៃដំណើរការបណ្ដឹង
                                        </th>
                                        <th></th>
                                        <th width="17%" class="text-hanuman-20 text-danger"
                                            style="text-align: center;">ឯកសារ</th>
                                        <th width="10%" class="text-hanuman-20 text-danger"
                                            style="text-align: center;">ស្ថានភាព</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><label
                                                class="form-label fw-bold blue">{{ Num2Unicode(1) }}.ពាក្យបណ្ដឹង</label>
                                        </td>
                                        <td>
                                            @php
                                                $imageUrl = rurl('assets/images');
                                            @endphp
                                            <div class="row mt-2">
                                                <div class="form-group col-sm-4">
                                                    @if ($hasFullAccess)
                                                        <a class="btn btn-success custom form-control fw-bold"
                                                            href="{{ url('cases/' . $row->id . '/edit') }}"
                                                            title="កែប្រែពាក្យបណ្តឹង"
                                                            target="_blank">កែសម្រួលពាក្យបណ្ដឹង</a>
                                                    @endif
                                                </div>
                                                <div class="form-group col-sm-4"></div>
                                                <div class="form-group col-sm-4 text-end">
                                                    <a class="btn btn-info custom form-control fw-bold"
                                                        href="{{ url('export/word/case/' . $row->id) }}"
                                                        title="ទាញយកពាក្យបណ្ដឹង" target="_blank">ទាញយកពាក្យបណ្ដឹង</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                if ($hasFullAccess) {
                                                    $caseFile = showFile(
                                                        1,
                                                        $row->case_file,
                                                        pathToDeleteFile('case_doc/form1/' . $caseYear . '/'),
                                                        'delete',
                                                        'tbl_case',
                                                        'id',
                                                        $row->id,
                                                        'case_file',
                                                        '',
                                                        '',
                                                    );
                                                    if ($caseFile) {
                                                        echo $caseFile;
                                                        $imageStatus = '/check.png';
                                                        $button = '';
                                                    } else {
                                                        //echo '<button id="uploadButtonInvitationEmployee" class="btn btn-success uploadButton" value="'.$row->id.'" data-url="'.url('case/upload/case_file3').'">Upload លិខិត</button>';
                                                        echo '<a href="' .
                                                            url('uploads/all/1/' . $row->id . '/' . $row->id) .
                                                            '" class="btn btn-success form-control mt-2 fw-bold">' .
                                                            'Upload លិខិត</a>';
                                                    }
                                                }
                                            @endphp
                                        </td>
                                        <td class="text-center">
                                            @if (!empty($imageStatus))
                                                <img width="30" height="30"
                                                    src="{{ $imageUrl }}/check.png" />
                                            @endif
                                        </td>
                                    </tr>
@php
    $caseYear= !empty($row->case_date) ? date('Y', strtotime($row->case_date)) : null;
@endphp

                                    <tr>
                                        <td>
                                            @php
                                                $officerName = '';
                                                if (!empty($lastOfficer)) {
                                                    $officerName = $adata['lastOfficerInfo']->officer_name_khmer;
                                                    $step2_status = true;
                                                } else {
                                                    $step2_status = false;
                                                }
                                            @endphp

                                            <label
                                                class="form-label fw-bold blue">{{ Num2Unicode(2) }}.អ្នកផ្សះផ្សារ:</label>
                                            <span class="red fw-bold">{{ $officerName }}</span>
                                        </td>

                                        <td colspan="2">
                                            @if (!empty($imageStatus) && ($caseYear >= 2025 && !empty($row->case_file)) || ($caseYear < 2025))
                                                <form action="{{ url('assign/officer') }}" method="POST">
                                                    @method('PUT')
                                                    @csrf

                                                    <input type="hidden" name="case_id"
                                                        value="{{ $row->id }}">

                                                    <div class="row">
                                                        <div class="form-group col-sm-3">
                                                            @if ($hasFullAccess)
                                                                <button id="btnShowSelectOfficer" type="button"
                                                                    class="btn btn-success form-control fw-bold">
                                                                    កំណត់ ឬ ផ្លាស់ប្តូរ
                                                                </button>
                                                            @endif
                                                        </div>

                                                        <div id="div_select_officer" class="form-group col-sm-6"
                                                            style="display:none">
                                                            {!! showSelect('officer_id', $adata['caseOfficerList'], old('officer_id'), ' select2', '', '', 'required') !!}
                                                        </div>

                                                        <div id="div_btn_change_officer" class="form-group col-sm-3"
                                                            style="display:none">
                                                            <button type="submit"
                                                                class="btn btn-success form-control fw-bold">បញ្ជូន</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if (!empty($step2_status) && !empty($imageStatus))
                                                <img width="30" height="30"
                                                    src="{{ $imageUrl }}/check.png">
                                            @endif
                                        </td>


                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="form-label fw-bold blue">
                                                {{ Num2Unicode(3) }}.អញ្ជើញភាគីដើមចោទមកផ្ដល់ព័ត៌មាន
                                            </label>
                                        </td>

                                        <td>
                                            <div class="row">
                                                @if (!empty($lastOfficer) && !empty($imageStatus) && ($caseYear >= 2025) || ($caseYear < 2025))
                                                    {!! $invitationEmployee['info'] !!}
                                                    {!! $invitationEmployee['export'] !!}
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            @php
                                                if ($hasFullAccess && $invitationEmployee['invitation_id'] > 0 && !empty($imageStatus)) {
                                                    $showFile = showFile(
                                                        1,
                                                        $invitationEmployee['invitation_file'],
                                                        pathToDeleteFile('invitation/' . $caseYear . '/'),
                                                        'delete',
                                                        'tbl_case_invitation',
                                                        'id',
                                                        $invitationEmployee['invitation_id'],
                                                        'invitation_file',
                                                    );

                                                    if ($showFile) {
                                                        echo $showFile;
                                                        $step3_status = true;
                                                    } else {
                                                        echo '<a href="' .
                                                            url(
                                                                'uploads/all/3/' .
                                                                    $row->id .
                                                                    '/' .
                                                                    $invitationEmployee['invitation_id'],
                                                            ) .
                                                            '" class="btn btn-success form-control fw-bold">Upload លិខិត</a>';
                                                        $step3_status = false;
                                                    }
                                                }
                                            @endphp
                                        </td>

                                        <td class="text-center">
                                            @if (!empty($step3_status) && !empty($imageStatus))
                                                <img width="30" height="30"
                                                    src="{{ $imageUrl }}/check.png">
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($hasFullAccess)
                                        @if (!empty($row->invitationDisputant))
                                            @if (count($row->invitationDisputant->nextTime) > 0)
                                                @foreach ($row->invitationDisputant->nextTime as $next)
                                                    <tr>
                                                        <td></td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="form-group col-sm-12 fw-bold">
                                                                    <span class="text-info"><span
                                                                            class="blue">[{{ $next->status->status_name }}]</span>
                                                                        ជួបលើកក្រោយថ្ងៃទី <span
                                                                            class="text-danger">{{ date2Display($next->next_date) }}</span>
                                                                        ម៉ោង <span
                                                                            class="text-danger">{{ $next->next_time }}</span>
                                                                        មូលហេតុ <span
                                                                            class="text-danger">{{ $next->reason }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td>
                                                            @php
                                                                $showFile = showFile(
                                                                    1,
                                                                    $next->letter,
                                                                    pathToDeleteFile(
                                                                        'invitation/next/' . $caseYear . '/',
                                                                    ),
                                                                    'delete',
                                                                    'tbl_case_invitation_next_time',
                                                                    'id',
                                                                    $next->id,
                                                                    'letter',
                                                                    '',
                                                                );
                                                                if ($showFile) {
                                                                    echo $showFile;
                                                                } else {
                                                                    echo '<a href="' .
                                                                        url(
                                                                            'uploads/all/33/' .
                                                                                $row->id .
                                                                                '/' .
                                                                                $next->id,
                                                                        ) .
                                                                        '" class="btn btn-success form-control fw-bold">' .
                                                                        'Upload លិខិត</a>';
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endif
                                    @endif

                                    <tr>
                                        <td><label
                                                class="form-label fw-bold blue">{{ Num2Unicode(4) }}.សាកសួរព័ត៌មានដើមចោទ</label>
                                        </td>

                                        <td>
                                            @if (!empty($lastOfficer))
                                                <div class="row">
                                                    @if (!empty($showFile) && ($invitationEmployee['invitation_id'] > 0) && !empty($imageStatus) && ($caseYear >= 2025) || ($caseYear < 2025))
                                                        
                                                        {!! $showCaseLog34['info'] !!}
                                                        {!! $showCaseLog34['export'] !!}
                                                    @endif

                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                if ($hasFullAccess) {
                                                    if (!empty($showFile) && ($showCaseLog34['log34_id'] > 0)) {
                                                        $showFile = showFile(
                                                            1,
                                                            $showCaseLog34['log_file'],
                                                            pathToDeleteFile('case_doc/log34/' . $caseYear . '/'),
                                                            'delete',
                                                            'tbl_case_log34',
                                                            'id',
                                                            $showCaseLog34['log34_id'],
                                                            'log_file',
                                                            '',
                                                        );
                                                        if ($showFile) {
                                                            echo $showFile;
                                                        } else {
                                                            //echo '<button id="uploadButtonInvitationEmployee" class="btn btn-success form-control uploadButton" value="'.$showCaseLog34['log34_id'].'" data-title="Upload កំណត់ហេតុដែលបានចុះហត្ថលេខា ផ្ដិតមេដៃ និងវាយត្រាឈ្មោះ" data-url="'.url('log34/upload/file').'">Upload កំណត់ហេតុ</button>';
                                                            echo '<a href="' .
                                                                url(
                                                                    'uploads/all/4/' .
                                                                        $row->id .
                                                                        '/' .
                                                                        $showCaseLog34['log34_id'],
                                                                ) .
                                                                '" class="btn btn-success form-control fw-bold">' .
                                                                'Upload កំណត់ហេតុ</a>';
                                                        }
                                                    }
                                                }
                                            @endphp
                                        </td>
                                        <td class="text-center">
                                            @if (!empty($showFile) &&($showCaseLog34['log34_id'] > 0))
                                                <img width="30" height="30"
                                                    src="{{ $imageUrl }}/check.png" />
                                            @endif
                                        </td>


                                    </tr>
                                    <tr>
                                        <td><label
                                                class="form-label fw-bold blue">{{ Num2Unicode(5) }}.អញ្ជើញភាគីចុងចោទមកផ្ដល់ព័ត៌មាន</label>
                                        </td>

                                        <td>
                                           
                                            @if (!empty($lastOfficer))
                                                <div class="row">
                                                    @php
                                                        $step1_completed = !empty($row->case_file);
                                                        $step2_completed = !empty($adata['lastOfficer']);
                                                        $step4_completed = !empty($showCaseLog34['log34_id']); // FIXED
                                                        $hasStep4File    = !empty($showCaseLog34['log_file']); // <-- semicolon added

                                                        // Check Step 4 file in log5
                                                        if (!empty($row->log5)) {
                                                            foreach ($row->log5 as $log5) {
                                                                if (!empty($log5->detail5) && !empty($log5->detail5->log_file)) {
                                                                    $hasStep4File = true;
                                                                    $step4_completed = true;
                                                                    break;
                                                                }
                                                            }
                                                        }

                                                        $invitationCompany = showInvitationCompany(
                                                            $row,
                                                            $step1_completed,
                                                            $step2_completed,
                                                            $step4_completed,
                                                            $hasStep4File
                                                        );
                                                    @endphp

                                                    {{-- @php
                                                        // Step completion checks
                                                    $step1_completed = !empty($row->case_file); // Step 1: Case file exists
                                                    $step2_completed = !empty($adata['lastOfficer']); // Step 2: Last officer selected
                                                    $step4_completed = !empty($showCaseLog34['log34_id']); // Step 4: Some log exists

                                                    // Disable button if step4 completed but no file (or any other logic)
                                                    $disabled = !$step4_completed;

                                                    // Get invitation info
                                                    $invitationCompany = showInvitationCompany(
                                                        $row,
                                                        $disabled,
                                                        $step2_completed,
                                                        $step1_completed,
                                                        $step4_completed
                                                    );
                                                    @endphp --}}

                                                    {!! $invitationCompany['info'] !!}
                                                    {!! $invitationCompany['export'] !!}
                                                    {{-- @if (!empty($imageStatus) && ($caseYear >= 2025) || ($caseYear < 2025))
                                                        {!! $invitationCompany['info'] !!}
                                                        {!! $invitationCompany['export'] !!}
                                                    @endif                                     --}}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                if ($hasFullAccess) {
                                                    if ($invitationCompany['invitation_id'] > 0) {
                                                        $showFile = showFile(
                                                            1,
                                                            $invitationCompany['invitation_file'],
                                                            pathToDeleteFile('invitation/' . $caseYear . '/'),
                                                            'delete',
                                                            'tbl_case_invitation',
                                                            'id',
                                                            $invitationCompany['invitation_id'],
                                                            'invitation_file',
                                                            '',
                                                        );
                                                        if ($showFile) {
                                                            echo $showFile;
                                                        } else {
                                                            echo '<a href="' .
                                                                url(
                                                                    'uploads/all/5/' .
                                                                        $row->id .
                                                                        '/' .
                                                                        $invitationCompany['invitation_id'],
                                                                ) .
                                                                '" class="btn btn-success form-control fw-bold">' .
                                                                'Upload លិខិត</a>';
                                                        }
                                                    }
                                                }
                                            @endphp
                                        </td>
                                        <td class="text-center">
                                            @if ($invitationCompany['invitation_id'] > 0 && !empty($showFile))
                                                <img width="30" height="30"
                                                    src="{{ $imageUrl }}/check.png" />
                                            @endif
                                        </td>

                                    </tr>
                                    @if (!empty($row->invitationCompany))
                                        @if (count($row->invitationCompany->nextTime) > 0)
                                            @foreach ($row->invitationCompany->nextTime as $next)
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="form-group col-sm-12 fw-bold">
                                                                <span class="text-info"><span
                                                                        class="pink">[{{ $next->status->status_name }}]</span>
                                                                    ជួបលើកក្រោយថ្ងៃទី <span
                                                                        class="text-danger">{{ date2Display($next->next_date) }}</span>
                                                                    ម៉ោង <span
                                                                        class="text-danger">{{ $next->next_time }}</span>
                                                                    មូលហេតុ <span
                                                                        class="text-danger">{{ $next->reason }}</span>
                                                                </span>
                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        @php
                                                            if ($hasFullAccess) {
                                                                $showFile = showFile(
                                                                    1,
                                                                    $next->letter,
                                                                    pathToDeleteFile(
                                                                        'invitation/next/' . $caseYear . '/',
                                                                    ),
                                                                    'delete',
                                                                    'tbl_case_invitation_next_time',
                                                                    'id',
                                                                    $next->id,
                                                                    'letter',
                                                                    '',
                                                                );
                                                                if ($showFile) {
                                                                    echo $showFile;
                                                                } else {
                                                                    echo '<a href="' .
                                                                        url(
                                                                            'uploads/all/55/' .
                                                                                $row->id .
                                                                                '/' .
                                                                                $next->id,
                                                                        ) .
                                                                        '" class="btn btn-success form-control fw-bold">' .
                                                                        'Upload លិខិត</a>';
                                                                }
                                                            }
                                                        @endphp
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    {{-- <tr>
                                        <td>
                                            @php
                                                $officerName = '';
                                                $select = showSelect(
                                                    'officer_id',
                                                    $adata['caseOfficerList'],
                                                    old('officer_id'),
                                                    ' select2',
                                                    '',
                                                    '',
                                                    'required',
                                                );
                                                $button =
                                                    '<button type="submit" class="btn btn-success form-control fw-bold">បញ្ជូន</button>';
                                                $btnChangeOfficer =
                                                    '<button id="btnShowSelectOfficer" value="0" type="button" class="btn btn-success form-control fw-bold">កំណត់ ឬផ្លាស់ប្តូរ</button>';
                                                if (!empty($lastOfficer)) {
                                                    $officerName = $adata['lastOfficerInfo']->officer_name_khmer;
                                                    $imageStatus = '/check.png';
                                                } else {
                                                    $imageStatus = '';
                                                }
                                            @endphp
                                            <label
                                                class="form-label fw-bold blue">{{ Num2Unicode(2) }}.អ្នកផ្សះផ្សារ:</label>
                                            <span class="red fw-bold">{{ $officerName }}</span>
                                        </td>

                                      
                                            <td colspan="2">
                                                <form id="frm_change_officer" name="frm_change_officer"
                                                    action="{{ url('assign/officer') }}" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <input type="hidden" name="case_id"
                                                        value="{{ $row->id }}">
                                                    <div class="row">
                                                        <div class="form-group col-sm-3">
                                                            @if ($hasFullAccess)
                                                                {!! $btnChangeOfficer !!}
                                                            @endif
                                                        </div>
                                                        <div id="div_select_officer" class="form-group col-sm-6"
                                                            style="display: none">
                                                            {!! $select !!}
                                                        </div>
                                                        <div id="div_btn_change_officer" class="form-group col-sm-3"
                                                            style="display: none">
                                                            {!! $button !!}
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                       
                                        <td class="text-center">
                                            @if (!empty($imageStatus))
                                                <img width="30" height="30"
                                                    src="{{ $imageUrl }}/check.png" />
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label
                                                class="form-label fw-bold blue">{{ Num2Unicode(3) }}.អញ្ជើញភាគីដើមចោទមកផ្ដល់ព័ត៌មាន</label>
                                        </td>
                                            <td>
                                                <div class="row">
                                                    @if (!empty($lastOfficer))
                                                        {!! $invitationEmployee['info'] !!}
                                                        {!! $invitationEmployee['export'] !!}
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    if ($hasFullAccess) {
                                                        if ($invitationEmployee['invitation_id'] > 0) {
                                                            $showFile = showFile(
                                                                1,
                                                                $invitationEmployee['invitation_file'],
                                                                pathToDeleteFile('invitation/' . $caseYear . '/'),
                                                                'delete',
                                                                'tbl_case_invitation',
                                                                'id',
                                                                $invitationEmployee['invitation_id'],
                                                                'invitation_file',
                                                                '',
                                                            );
                                                            if ($showFile) {
                                                                echo $showFile;
                                                            } else {
                                                                // echo '<button id="uploadButtonInvitationEmployee" class="btn btn-success form-control uploadButton" value="'.$invitationEmployee['invitation_id'].'" data-title="Upload លិខិតអញ្ជើញដែលមានចុះហត្ថលេខាទទួល" data-url="'.url('invitation/upload/file').'">Upload លិខិត</button>';
                                                                echo '<a href="' .
                                                                    url(
                                                                        'uploads/all/3/' .
                                                                            $row->id .
                                                                            '/' .
                                                                            $invitationEmployee['invitation_id'],
                                                                    ) .
                                                                    '" class="btn btn-success form-control fw-bold">' .
                                                                    'Upload លិខិត</a>';
                                                            }
                                                        }
                                                    }
                                                @endphp
                                            </td>
                                            <td class="text-center">
                                                @if ($invitationEmployee['invitation_id'] > 0)
                                                    <img width="30" height="30"
                                                        src="{{ $imageUrl }}/check.png" />
                                                @endif
                                            </td>
                                    </tr> --}}
                                    {{-- @if ($hasFullAccess)
                                        @if (!empty($row->invitationDisputant))
                                            @if (count($row->invitationDisputant->nextTime) > 0)
                                                @foreach ($row->invitationDisputant->nextTime as $next)
                                                    <tr>
                                                        <td></td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="form-group col-sm-12 fw-bold">
                                                                    <span class="text-info"><span
                                                                            class="blue">[{{ $next->status->status_name }}]</span>
                                                                        ជួបលើកក្រោយថ្ងៃទី <span
                                                                            class="text-danger">{{ date2Display($next->next_date) }}</span>
                                                                        ម៉ោង <span
                                                                            class="text-danger">{{ $next->next_time }}</span>
                                                                        មូលហេតុ <span
                                                                            class="text-danger">{{ $next->reason }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td>
                                                            @php
                                                                $showFile = showFile(
                                                                    1,
                                                                    $next->letter,
                                                                    pathToDeleteFile(
                                                                        'invitation/next/' . $caseYear . '/',
                                                                    ),
                                                                    'delete',
                                                                    'tbl_case_invitation_next_time',
                                                                    'id',
                                                                    $next->id,
                                                                    'letter',
                                                                    '',
                                                                );
                                                                if ($showFile) {
                                                                    echo $showFile;
                                                                } else {
                                                                    echo '<a href="' .
                                                                        url(
                                                                            'uploads/all/33/' .
                                                                                $row->id .
                                                                                '/' .
                                                                                $next->id,
                                                                        ) .
                                                                        '" class="btn btn-success form-control fw-bold">' .
                                                                        'Upload លិខិត</a>';
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endif
                                    @endif --}}
                                    {{-- <tr>
                                        <td><label
                                                class="form-label fw-bold blue">{{ Num2Unicode(4) }}.សាកសួរព័ត៌មានដើមចោទ</label>
                                        </td>

                                        <td>
                                            @if (!empty($lastOfficer))
                                                <div class="row">
                                                    @if ($invitationEmployee['invitation_id'] > 0)
                                                        {!! $showCaseLog34['info'] !!}
                                                        {!! $showCaseLog34['export'] !!}
                                                    @endif

                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                if ($hasFullAccess) {
                                                    if ($showCaseLog34['log34_id'] > 0) {
                                                        $showFile = showFile(
                                                            1,
                                                            $showCaseLog34['log_file'],
                                                            pathToDeleteFile('case_doc/log34/' . $caseYear . '/'),
                                                            'delete',
                                                            'tbl_case_log34',
                                                            'id',
                                                            $showCaseLog34['log34_id'],
                                                            'log_file',
                                                            '',
                                                        );
                                                        if ($showFile) {
                                                            echo $showFile;
                                                        } else {
                                                            //echo '<button id="uploadButtonInvitationEmployee" class="btn btn-success form-control uploadButton" value="'.$showCaseLog34['log34_id'].'" data-title="Upload កំណត់ហេតុដែលបានចុះហត្ថលេខា ផ្ដិតមេដៃ និងវាយត្រាឈ្មោះ" data-url="'.url('log34/upload/file').'">Upload កំណត់ហេតុ</button>';
                                                            echo '<a href="' .
                                                                url(
                                                                    'uploads/all/4/' .
                                                                        $row->id .
                                                                        '/' .
                                                                        $showCaseLog34['log34_id'],
                                                                ) .
                                                                '" class="btn btn-success form-control fw-bold">' .
                                                                'Upload កំណត់ហេតុ</a>';
                                                        }
                                                    }
                                                }
                                            @endphp
                                        </td>
                                        <td class="text-center">
                                            @if ($showCaseLog34['log34_id'] > 0)
                                                <img width="30" height="30"
                                                    src="{{ $imageUrl }}/check.png" />
                                            @endif
                                        </td>


                                    </tr> --}}
                                    {{-- <tr>
                                        <td><label
                                                class="form-label fw-bold blue">{{ Num2Unicode(5) }}.អញ្ជើញភាគីចុងចោទមកផ្ដល់ព័ត៌មាន</label>
                                        </td>

                                        <td>
                                            @php

                                            @endphp
                                            @if (!empty($lastOfficer))
                                                <div class="row">
                                                    {!! $invitationCompany['info'] !!}
                                                    {!! $invitationCompany['export'] !!}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                if ($hasFullAccess) {
                                                    if ($invitationCompany['invitation_id'] > 0) {
                                                        $showFile = showFile(
                                                            1,
                                                            $invitationCompany['invitation_file'],
                                                            pathToDeleteFile('invitation/' . $caseYear . '/'),
                                                            'delete',
                                                            'tbl_case_invitation',
                                                            'id',
                                                            $invitationCompany['invitation_id'],
                                                            'invitation_file',
                                                            '',
                                                        );
                                                        if ($showFile) {
                                                            echo $showFile;
                                                        } else {
                                                            echo '<a href="' .
                                                                url(
                                                                    'uploads/all/5/' .
                                                                        $row->id .
                                                                        '/' .
                                                                        $invitationCompany['invitation_id'],
                                                                ) .
                                                                '" class="btn btn-success form-control fw-bold">' .
                                                                'Upload លិខិត</a>';
                                                        }
                                                    }
                                                }
                                            @endphp
                                        </td>
                                        <td class="text-center">
                                            @if ($invitationCompany['invitation_id'] > 0)
                                                <img width="30" height="30"
                                                    src="{{ $imageUrl }}/check.png" />
                                            @endif
                                        </td>

                                    </tr> --}}
                                    {{-- @if (!empty($row->invitationCompany))
                                        @if (count($row->invitationCompany->nextTime) > 0)
                                            @foreach ($row->invitationCompany->nextTime as $next)
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="form-group col-sm-12 fw-bold">
                                                                <span class="text-info"><span
                                                                        class="pink">[{{ $next->status->status_name }}]</span>
                                                                    ជួបលើកក្រោយថ្ងៃទី <span
                                                                        class="text-danger">{{ date2Display($next->next_date) }}</span>
                                                                    ម៉ោង <span
                                                                        class="text-danger">{{ $next->next_time }}</span>
                                                                    មូលហេតុ <span
                                                                        class="text-danger">{{ $next->reason }}</span>
                                                                </span>
                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        @php
                                                            if ($hasFullAccess) {
                                                                $showFile = showFile(
                                                                    1,
                                                                    $next->letter,
                                                                    pathToDeleteFile(
                                                                        'invitation/next/' . $caseYear . '/',
                                                                    ),
                                                                    'delete',
                                                                    'tbl_case_invitation_next_time',
                                                                    'id',
                                                                    $next->id,
                                                                    'letter',
                                                                    '',
                                                                );
                                                                if ($showFile) {
                                                                    echo $showFile;
                                                                } else {
                                                                    echo '<a href="' .
                                                                        url(
                                                                            'uploads/all/55/' .
                                                                                $row->id .
                                                                                '/' .
                                                                                $next->id,
                                                                        ) .
                                                                        '" class="btn btn-success form-control fw-bold">' .
                                                                        'Upload លិខិត</a>';
                                                                }
                                                            }
                                                        @endphp
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif --}}
                                    <tr>
                                        <td><label
                                                class="form-label fw-bold blue">{{ Num2Unicode(6) }}.សាកសួរព័ត៌មានភាគីចុងចោទ</label>
                                        </td>

                                        <td>
                                            <div class="row">
                                                @if(!empty($showFile) && ($invitationCompany['invitation_id'] > 0))

                                                    {!! $showCaseLog5['info'] !!}
                                                    {!! $showCaseLog5['export'] !!}
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                if ($hasFullAccess) {
                                                    if ($showCaseLog5['log5_id'] > 0) {
                                                        $showFile = showFile(
                                                            1,
                                                            $showCaseLog5['log_file'],
                                                            pathToDeleteFile('case_doc/log5/' . $caseYear . '/'),
                                                            'delete',
                                                            'tbl_case_log5',
                                                            'id',
                                                            $showCaseLog5['log5_id'],
                                                            'log_file',
                                                            '',
                                                        );
                                                        if ($showFile) {
                                                            echo $showFile;
                                                        } else {
                                                            echo '<a href="' .
                                                                url(
                                                                    'uploads/all/6/' .
                                                                        $row->id .
                                                                        '/' .
                                                                        $showCaseLog5['log5_id'],
                                                                ) .
                                                                '" class="btn btn-success form-control fw-bold">' .
                                                                'Upload កំណត់ហេតុ</a>';
                                                        }
                                                    }
                                                }
                                            @endphp
                                        </td>
                                        <td class="text-center">
                                            @if ($showCaseLog5['log5_id'] > 0)
                                                <img width="30" height="30"
                                                    src="{{ $imageUrl }}/check.png" />
                                            @endif
                                        </td>

                                    </tr>
                                    <tr>
                                        <td><label
                                                class="form-label fw-bold blue">{{ Num2Unicode(7) }}.អញ្ជើញភាគីទាំង២មកផ្សះផ្សា</label>
                                        </td>

                                        <td>
                                            @php
                                                if ($invitationBoth['invitation_id1'] == 0) {
                                                    echo $invitationBoth['info1'];
                                                }
                                            @endphp
                                        </td>
                                        <td></td>
                                        <td class="text-center">
                                            @if ($invitationBoth['invitation_id1'] > 0)
                                                <img width="30" height="30"
                                                    src="{{ $imageUrl }}/check.png" />
                                            @endif
                                        </td>

                                    </tr>

                                    @if ($invitationBoth['invitation_id1'] > 0)
                                        <tr>
                                            <td></td>
                                            <td>
                                                <div class="row mt-2">
                                                    {!! $invitationBoth['info1'] !!}
                                                    {!! $invitationBoth['export1'] !!}
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    if ($hasFullAccess) {
                                                        $showFile = showFile(
                                                            1,
                                                            $invitationBoth['invitation_file1'],
                                                            pathToDeleteFile('invitation/' . $caseYear . '/'),
                                                            'delete',
                                                            'tbl_case_invitation',
                                                            'id',
                                                            $invitationBoth['invitation_id1'],
                                                            'invitation_file',
                                                            '',
                                                        );
                                                        if ($showFile) {
                                                            echo $showFile;
                                                        } else {
                                                            echo '<a href="' .
                                                                url(
                                                                    'uploads/all/7/' .
                                                                        $row->id .
                                                                        '/' .
                                                                        $invitationBoth['invitation_id1'],
                                                                ) .
                                                                '" class="btn btn-success form-control fw-bold">' .
                                                                'Upload លិខិត</a>';
                                                        }
                                                    }
                                                @endphp
                                            </td>
                                            <td></td>
                                        </tr>
                                    @endif
                                    @if ($invitationBoth['invitation_id2'] > 0)
                                        <tr>
                                            <td></td>export2
                                            <td>
                                                <div class="row">
                                                    {!! $invitationBoth['info2'] !!}
                                                    {!! $invitationBoth['export2'] !!}
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    if ($hasFullAccess) {
                                                        $showFile = showFile(
                                                            1,
                                                            $invitationBoth['invitation_file2'],
                                                            pathToDeleteFile('invitation/' . $caseYear . '/'),
                                                            'delete',
                                                            'tbl_case_invitation',
                                                            'id',
                                                            $invitationBoth['invitation_id2'],
                                                            'invitation_file',
                                                            '',
                                                        );
                                                        if ($showFile) {
                                                            echo $showFile;
                                                        } else {
                                                            echo '<a href="' .
                                                                url(
                                                                    'uploads/all/7/' .
                                                                        $row->id .
                                                                        '/' .
                                                                        $invitationBoth['invitation_id2'],
                                                                ) .
                                                                '" class="btn btn-success form-control fw-bold">' .
                                                                'Upload លិខិត</a>';
                                                        }
                                                    }
                                                @endphp
                                            </td>
                                            <td></td>
                                        </tr>
                                    @endif

                                    @if (!empty($row->invitationForConcilationEmployee))
                                        @if (count($row->invitationForConcilationEmployee->nextTime) > 0)
                                            @foreach ($row->invitationForConcilationEmployee->nextTime as $next)
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="form-group col-sm-12 text-info fw-bold">
                                                                <span class="blue">*ដើមចោទ
                                                                    [{{ $next->status->status_name }}]</span>
                                                                ជួបលើកក្រោយថ្ងៃទី <span
                                                                    class="text-danger">{{ date2Display($next->next_date) }}</span>
                                                                ម៉ោង <span
                                                                    class="text-danger">{{ $next->next_time }}</span><br />
                                                                មូលហេតុ <span
                                                                    class="text-danger">{{ $next->reason }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @php
                                                            if ($hasFullAccess) {
                                                                $showFile = showFile(
                                                                    1,
                                                                    $next->letter,
                                                                    pathToDeleteFile(
                                                                        'invitation/next/' . $caseYear . '/',
                                                                    ),
                                                                    'delete',
                                                                    'tbl_case_invitation_next_time',
                                                                    'id',
                                                                    $next->id,
                                                                    'letter',
                                                                    '',
                                                                );
                                                                if ($showFile) {
                                                                    echo $showFile;
                                                                } else {
                                                                    echo '<a href="' .
                                                                        url(
                                                                            'uploads/all/77/' .
                                                                                $row->id .
                                                                                '/' .
                                                                                $next->id,
                                                                        ) .
                                                                        '" class="btn btn-success form-control fw-bold">' .
                                                                        'Upload លិខិត</a>';
                                                                }
                                                            }
                                                        @endphp
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    @if (!empty($row->invitationForConcilationCompany))
                                        @if (count($row->invitationForConcilationCompany->nextTime) > 0)
                                            @foreach ($row->invitationForConcilationCompany->nextTime as $next)
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="form-group col-sm-12 text-info fw-bold">
                                                                <span class="pink">*ចុងចោទ
                                                                    [{{ $next->status->status_name }}]</span>
                                                                ជួបលើកក្រោយថ្ងៃទី <span
                                                                    class="text-danger">{{ date2Display($next->next_date) }}</span>
                                                                ម៉ោង <span
                                                                    class="text-danger">{{ $next->next_time }}</span><br />
                                                                មូលហេតុ <span
                                                                    class="text-danger">{{ $next->reason }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @php
                                                            if ($hasFullAccess) {
                                                                $showFile = showFile(
                                                                    1,
                                                                    $next->letter,
                                                                    pathToDeleteFile(
                                                                        'invitation/next/' . $caseYear . '/',
                                                                    ),
                                                                    'delete',
                                                                    'tbl_case_invitation_next_time',
                                                                    'id',
                                                                    $next->id,
                                                                    'letter',
                                                                    '',
                                                                );
                                                                if ($showFile) {
                                                                    echo $showFile;
                                                                } else {
                                                                    echo '<a href="' .
                                                                        url(
                                                                            'uploads/all/77/' .
                                                                                $row->id .
                                                                                '/' .
                                                                                $next->id,
                                                                        ) .
                                                                        '" class="btn btn-success form-control fw-bold">' .
                                                                        'Upload លិខិត</a>';
                                                                }
                                                            }
                                                        @endphp
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    <tr>
                                        <td><label
                                                class="form-label blue fw-bold">{{ Num2Unicode(8) }}.ការផ្សះផ្សា</label>
                                        </td>

                                        <td>
                                            @if ($showCaseLog6All['num_log6'] > 0)
                                            @else
                                                {!! $showCaseLog6All['log6_data']['info'] !!}
                                            @endif
                                        </td>
                                        <td>
                                        </td>
                                        <td class="text-center">
                                            @if ($showCaseLog6All['num_log6'] > 0)
                                                <img width="30" height="30"
                                                    src="{{ $imageUrl }}/check.png" />
                                            @endif
                                        </td>

                                    </tr>
                                    @if ($showCaseLog6All['num_log6'] > 0)
                                        @php $j=1; @endphp
                                        @foreach ($showCaseLog6All['log6_data'] as $showCaseLog6)
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <div class="row">
                                                        <div class="form-group col-sm-8" style="line-height: 40px;">
                                                            {!! $showCaseLog6['info'] !!}
                                                            @if ($j == $showCaseLog6All['num_log6'])
                                                                @if ($showCaseLog6['detail']->status_id == 2)
                                                                    @if ($showCaseLog6['detail']->reopen_status == 1)
                                                                        <div class="row mt-2">
                                                                            <div
                                                                                class="form-group col-sm-12 fw-bold text-info">
                                                                                @if ($hasFullAccess)
                                                                                    <a href="{{ url('uploads/all/84/' . $row->id . '/' . $showCaseLog6['log6_id']) }}"
                                                                                        class="btn btn-success form-control fw-bold">កែប្រែព័ត៌មានសុំផ្សះផ្សាឡើងវិញ</a>
                                                                                @endif
                                                                                <br>
                                                                                <span
                                                                                    class="fw-bold blue">ផ្សះផ្សាឡើងវិញថ្ងៃទី
                                                                                    <span
                                                                                        class="text-danger">{{ date2Display($showCaseLog6['detail']->status_date) }}</span>
                                                                                    ម៉ោង <span
                                                                                        class="text-danger">{{ date2Display($showCaseLog6['detail']->status_time, 'H:i') }}</span>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        @if ($hasFullAccess)
                                                                            <a href='#'
                                                                                class='btn btn-success form-control fw-bold'
                                                                                style='margin-bottom: 3px;'
                                                                                onClick="comfirm_sweetalert2('{{ url('log6/generate/new/log/' . $showCaseLog6['log6_id'] . '/' . $showCaseLog6['detail']->status_id) }}', 'ចង់បង្កើតកំណត់ហេតុផ្សះផ្សាឡើងវិញ មែនឫ?')">បង្កើតកំណត់ហេតុសុំផ្សះផ្សាឡើងវិញ</a>
                                                                        @endif
                                                                    @else
                                                                        @if ($hasFullAccess)
                                                                            <div class="form-group col-sm-6"><a
                                                                                    href="{{ url('uploads/all/82/' . $row->id . '/' . $showCaseLog6['log6_id']) }}"
                                                                                    class="btn btn-success form-control fw-bold">សុំផ្សះផ្សាឡើងវិញ</a>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                @elseif($showCaseLog6['detail']->status_id == 3)
                                                                    <div class="row mt-3">
                                                                        <div class="form-group col-sm-12">
                                                                            @if ($hasFullAccess)
                                                                                <a href="{{ url('uploads/all/83/' . $row->id . '/' . $showCaseLog6['log6_id']) }}"
                                                                                    class="btn btn-success form-control fw-bold">កែប្រែព័ត៌មានសុំលើកពេលផ្សះផ្សា</a>
                                                                            @endif
                                                                            <br>
                                                                            <label
                                                                                class="form-label fw-bold text-info">លើកពេលផ្សះផ្សាទៅថ្ងៃទី
                                                                                <span
                                                                                    class="text-danger">{{ date2Display($showCaseLog6['detail']->status_date) }}</span>
                                                                                ម៉ោង <span
                                                                                    class="text-danger">{{ date2Display($showCaseLog6['detail']->status_time, 'H:i') }}</span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    @if ($hasFullAccess)
                                                                        <a href='#'
                                                                            class='btn btn-success form-control fw-bold'
                                                                            style='margin-bottom: 3px;'
                                                                            onClick="comfirm_sweetalert2('{{ url('log6/generate/new/log/' . $showCaseLog6['log6_id'] . '/' . $showCaseLog6['detail']->status_id) }}', 'ចង់បង្កើតកំណត់ហេតុលើកពេលផ្សះផ្សា មែនឫ?')">បង្កើតកំណត់ហេតុលើកពេលផ្សះផ្សា</a>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                <div class="row mt-2">
                                                                    <div
                                                                        class="form-group col-sm-12 text-info fw-bold">
                                                                        @if ($showCaseLog6['detail']->status_id == 2)
                                                                            <span
                                                                                class="pink">[បានស្នើសុំផ្សះផ្សាឡើងវិញ]</span>
                                                                        @elseif($showCaseLog6['detail']->status_id == 3)
                                                                            <span
                                                                                class="text-danger">{{ date2Display($showCaseLog6['detail']->status_date) }}</span>
                                                                            ម៉ោង <span
                                                                                class="text-danger">{{ date2Display($showCaseLog6['detail']->status_time, 'H:i') }}</span><br />
                                                                            <span
                                                                                class="blue">[កាលបរិច្ឆេទលើកពេលផ្សះផ្សា]</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="form-group col-sm-4">
                                                            {!! $showCaseLog6['export'] !!}
                                                            @if ($j == $showCaseLog6All['num_log6'])
                                                                @if ($showCaseLog6['detail']->status_id == 2 && $showCaseLog6['detail']->reopen_status == 1)
                                                                    <div class="mt-3">
                                                                        @if ($hasFullAccess)
                                                                            <a href="#"
                                                                                onClick="comfirm_sweetalert2('{{ url('log6/reopen/request/cancel/' . $showCaseLog6['detail']->case_id . '/' . $showCaseLog6['detail']->id) }}', 'តើអ្នកពិតជាចង់លុប មែនឫ?')"
                                                                                class="btn btn-danger form-control">
                                                                                លុបការសុំផ្សះផ្សាឡើងវិញ
                                                                        @endif
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        if ($hasFullAccess) {
                                                            if ($showCaseLog6['log6_id'] > 0) {
                                                                $showFile = showFile(
                                                                    1,
                                                                    $showCaseLog6['log_file'],
                                                                    pathToDeleteFile(
                                                                        'case_doc/log6/' . $caseYear . '/',
                                                                    ),
                                                                    'delete',
                                                                    'tbl_case_log6',
                                                                    'id',
                                                                    $showCaseLog6['log6_id'],
                                                                    'log_file',
                                                                    '',
                                                                );
                                                                if ($showFile) {
                                                                    echo $showFile;
                                                                } else {
                                                                    echo '<a href="' .
                                                                        url(
                                                                            'uploads/all/8/' .
                                                                                $row->id .
                                                                                '/' .
                                                                                $showCaseLog6['log6_id'],
                                                                        ) .
                                                                        '" class="btn btn-success form-control fw-bold">' .
                                                                        'Upload កំណត់ហេតុ</a>';
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    @if ($showCaseLog6['detail']->status_id == 2)
                                                        <div class="mt-3">
                                                            @php
                                                                if ($hasFullAccess) {
                                                                    if ($showCaseLog6['detail']->reopen_status == 1) {
                                                                        $showFile = showFile(
                                                                            1,
                                                                            $showCaseLog6['detail']->status_letter,
                                                                            pathToDeleteFile(
                                                                                'case_doc/log6/status_letter/' .
                                                                                    $caseYear .
                                                                                    '/',
                                                                            ),
                                                                            'delete',
                                                                            'tbl_case_log6',
                                                                            'id',
                                                                            $showCaseLog6['detail']->id,
                                                                            'status_letter',
                                                                            '',
                                                                        );
                                                                        if ($showFile) {
                                                                            echo $showFile;
                                                                        } else {
                                                                            echo '<button id="uploadButtonInvitationEmployee" class="btn btn-success form-control uploadButton fw-bold mb-1" value="' .
                                                                                $showCaseLog6['detail']->id .
                                                                                '" data-title="Upload លិខិតស្នើសុំផ្សះផ្សាឡើងវិញ ដែលបានចុះហត្ថលេខា" data-url="' .
                                                                                url('log6/reopen/upload/file') .
                                                                                '">Upload លិខិត</button>';
                                                                            echo '<a href="' .
                                                                                url(
                                                                                    'uploads/all/81/' .
                                                                                        $row->id .
                                                                                        '/' .
                                                                                        $showCaseLog6['detail']->id,
                                                                                ) .
                                                                                '" class="btn btn-success form-control fw-bold">' .
                                                                                'Upload កំណត់ហេតុ</a>';
                                                                        }
                                                                    }
                                                                }
                                                            @endphp
                                                        </div>
                                                    @elseif($showCaseLog6['detail']->status_id == 3)
                                                        <div class="mt-3">
                                                            @php
                                                                if ($hasFullAccess) {
                                                                    $showFile = showFile(
                                                                        1,
                                                                        $showCaseLog6['detail']->status_letter,
                                                                        pathToDeleteFile(
                                                                            'case_doc/log6/status_letter/' .
                                                                                $caseYear .
                                                                                '/',
                                                                        ),
                                                                        'delete',
                                                                        'tbl_case_log6',
                                                                        'id',
                                                                        $showCaseLog6['detail']->id,
                                                                        'status_letter',
                                                                        '',
                                                                    );
                                                                    if ($showFile) {
                                                                        echo $showFile;
                                                                    } else {
                                                                        echo '<a href="' .
                                                                            url(
                                                                                'uploads/all/81/' .
                                                                                    $row->id .
                                                                                    '/' .
                                                                                    $showCaseLog6['detail']->id,
                                                                            ) .
                                                                            '" class="btn btn-success form-control fw-bold">' .
                                                                            'Upload កំណត់ហេតុ</a>';
                                                                    }
                                                                }
                                                            @endphp
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-purple text-center fw-bold">
                                                    {{ $showCaseLog6['log6']->status->status_name }}
                                                    @if ($showCaseLog6['log6']->status->id == 3)
                                                        <br>
                                                        <span class="text-danger fw-bold"
                                                            style="color:#000000; font-size: 12px;">
                                                            [ទៅថ្ងៃ
                                                            {{ date2Display($showCaseLog6['log6']->status_date) }}]
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @php $j++; @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if ($hasFullAccess)
                            <div class="card-block row text-center mt-4">
                                <div class="form-group  col-sm-12">
                                    <a class="btn btn-danger text-hanuman-22"
                                        href="{{ url('close/case/' . $row->id) }}" title="ចុចបិទបញ្ចប់សំណុំរឿង"
                                        target="">ការបិទបញ្ចប់សំណុំរឿង</a>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
    <x-slot name="moreAfterScript">
        {{--        @include('case.script.case_script') --}}
        <script src="{{ rurl('assets/js/jquery.ui.min.js') }}"></script>
        <!-- Plugins Select2-->
        <script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>
        @include('script.my_sweetalert2')
        <script type="text/javascript">
            // Telegram notify config available to upload handler
            window.telegramNotify = {
                url: '{{ route('telegram.notify-upload') }}',
                case_id: '{{ $row->id }}',
                case_num: '{{ $row->case_num_str ?? $row->case_number ?? "" }}',
                csrf: '{{ csrf_token() }}'
            };

            $(document).ready(function() {
                $('#in_out_domain').select2();
                $('#officer_id').select2();
                $('#test_date').datepicker({});
                $("#btnShowSelectOfficer").click(function() {
                    $("#div_select_officer").show();
                    $("#div_btn_change_officer").show();
                    var val = $("#btnShowSelectOfficer").val();
                    if (val == 0) {
                        $("#btnShowSelectOfficer").val(1);
                        $("#div_select_officer").show();
                        $("#div_btn_change_officer").show();
                    } else if (val == 1) {
                        $("#btnShowSelectOfficer").val(0);
                        $("#div_select_officer").hide();
                        $("#div_btn_change_officer").hide();
                    }
                    //alert(val);

                });

                $(".uploadButton").click(function() {
                    var id = $(this).val();
                    var title = $(this).data('title');
                    var url = $(this).data('url'); //"invitation/upload/file"
                    alert(id);
                    Swal.fire({
                        title: title,
                        html: `
                            <input type="hidden" name="id" id="id" value="` + id + `" >
                            <input type="hidden" name="url" id="url" value="` + url + `" >
                            <input type="file" id="fileInput" name="fileInput">
                            <div id="error-message" style="color: red;"></div>
                        `,
                        width: '800px',
                        showCancelButton: true,
                        confirmButtonText: 'Upload',
                        preConfirm: () => {
                            const id = document.getElementById('id').value.trim();
                            const url = document.getElementById('url').value.trim();
                            //const file = $('.swal2-file')[0].files[0];
                            //const file = document.getElementById('fileInput').files[0];
                            let file = $('#fileInput')[0].files[0];

                            if (!file) {
                                document.getElementById('error-message').innerText =
                                    'Please select a file.';
                                return false;
                            }
                            return {
                                id: id,
                                url: url,
                                file: file
                            };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            uploadPopupForm(result.value);
                        }
                    });
                });


                function uploadPopupForm(data) {
                    let formData = new FormData();
                    formData.append('id', data.id);
                    formData.append('file', data.file);
                    console.log(data.file);
                    fetch(data.url, {
                            method: 'POST',
                            //processData: false,
                            //contentType: false,
                            body: formData,
                            headers: {
                                //'Access-Control-Allow-Origin': '*',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                        })
                        .then(response => {
                            console.log(response);
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Handle success response
                            console.log(data.message);
                            Swal.fire('Success', data.message, 'success');

                            // Notify telegram about successful upload (best-effort)
                            try {
                                if (window.telegramNotify && window.telegramNotify.url) {
                                    fetch(window.telegramNotify.url, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': window.telegramNotify.csrf,
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            case_id: window.telegramNotify.case_id,
                                            case_num: window.telegramNotify.case_num
                                        }),
                                        keepalive: true
                                    }).then(function(res) {
                                        return res.json();
                                    }).then(function(json) {
                                        console.log('telegram notify after upload', json);
                                    }).catch(function(err) {
                                        console.warn('telegram notify failed', err);
                                    });
                                }
                            } catch (e) {
                                console.warn('telegram notify exception', e);
                            }

                            location.reload(); // Reload the page upon successful upload
                        })
                        .catch(error => {
                            // Handle error
                            console.error('There was an error!', error);
                            Swal.fire('Error', 'There was an error uploading file' + error, 'error');
                        });
                }
            });

            // Notify telegram when user clicks any upload link on this page
            (function() {
                var CASE_ID = '{{ $row->id }}';
                var CASE_NUM = '{{ $row->case_num_str ?? $row->case_number ?? "" }}';
                var notifyUrl = '{{ route('telegram.notify-upload') }}';

                document.querySelectorAll('a[href*="/uploads/all/"]').forEach(function(el) {
                    el.addEventListener('click', function(evt) {
                        try {
                            var payload = JSON.stringify({ case_id: CASE_ID, case_num: CASE_NUM });
                            // Use keepalive so the request can continue during navigation
                            fetch(notifyUrl, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: payload,
                                keepalive: true
                            }).then(function(res) {
                                // optional: handle response
                                return res.json();
                            }).then(function(json) {
                                console.log('telegram notify', json);
                            }).catch(function(err) {
                                console.warn('telegram notify failed', err);
                            });
                        } catch (e) {
                            console.warn('telegram notify exception', e);
                        }
                        // allow the link to proceed to upload page
                    });
                });
            })();
        </script>
    </x-slot>
</x-admin.layout-main>
