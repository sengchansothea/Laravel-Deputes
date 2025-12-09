@php
    $case = $adata['cases'];
    $caseYear = !empty($case->case_date) ? date2Display($case->case_date,'Y') : myDate('Y');
    $caseNumber = !empty($case->case_number) ? $case->case_number : 0;
    $casePre = "";
    $cYear = "";
    $lastOfficer = $adata['lastOfficer'];
    $officerInDomain = $adata['officerInDomain'];
@endphp
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/date-picker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/timepicker.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <style>
            .swal2-html-container .pop-content{
                text-align: left !important;
                overflow: hidden;
                box-sizing: border-box !important;
                padding: 10px !important;
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
                    <input type="hidden" name="company_id" value="{{ $case->company_id }}" />
                    <div class="card-body text-hanuman-17">
                        <div class="card-block">
                            <div class="row">
                                @if(!empty($case->caseCompany->log5_company_phone_number2))
                                    <div class="form-group col-sm-4 mt-3">
                                        <label class="mb-2">ឈ្មោះសហគ្រាស គ្រឹះស្ថាន ជាភាសាខ្មែរ</label>
                                        <input type="text" name="company_name_khmer" id="company_name_khmer" value="{{ $case->company->company_name_khmer }} {{ $case->company->company_name_latin }}" class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-4 mt-3">
                                        <label class="mb-2">ឈ្មោះសហគ្រាស គ្រឹះស្ថាន ជាភាសាឡាតាំង</label>
                                        <input type="text" name="company_name_latin" id="company_name_latin" value="{{ $case->company->company_name_latin }} {{ $case->company->company_name_latin }}" class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="mb-2">លេខទូរស័ព្ទ (ខ្សែទី១)</label>
                                        <input type="text" value="@if(isset($case->caseCompany->log5_company_phone_number)) {{ $case->caseCompany->log5_company_phone_number }} @endif" class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="mb-2">លេខទូរស័ព្ទ (ខ្សែទី២)</label>
                                        <input type="text" value="{{ $case->caseCompany->log5_company_phone_number2 }}" class="form-control" disabled />
                                    </div>
                                @else
                                    <div class="form-group col-sm-5 mt-3">
                                        <label class="mb-2">ឈ្មោះសហគ្រាស គ្រឹះស្ថាន ជាភាសាខ្មែរ</label>
                                        <input type="text" name="company_name_khmer" id="company_name_khmer" value="{{ $case->company->company_name_khmer }} {{ $case->company->company_name_latin }}" class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-5 mt-3">
                                        <label class="mb-2">ឈ្មោះសហគ្រាស គ្រឹះស្ថាន ជាភាសាឡាតាំង</label>
                                        <input type="text" name="company_name_latin" id="company_name_latin" value="{{ $case->company->company_name_latin }} {{ $case->company->company_name_latin }}" class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="mb-2">លេខទូរស័ព្ទ</label>
                                        <input type="text" value="@if(isset($case->caseCompany->log5_company_phone_number)) {{ $case->caseCompany->log5_company_phone_number }} @endif" class="form-control" disabled />
                                    </div>
                                @endif

                            </div>
                            <div class="row col-12 mt-3">
                                <label class="text-purple text-hanuman-26" for="contact_phone">
                                    <button type="button" id="btn_cRepre" value="1" class="btn btn-info">បង្ហាញឈ្មោះ តំណាងប្រតិភូចរចា (តំណាងកម្មករនិយោជិត)</button>
                                </label>

                            </div>
                            @php
                                $listRepresentatives = !empty($case->collectivesCaseDisputantsEmp) ? $case->collectivesCaseDisputantsEmp : $case->collectivesRepresentatives;
                            @endphp
                            @if(count($listRepresentatives) > 0)
                                <div id="collectives-representatives" class="row" style="display: none">
                                @foreach($case->collectivesRepresentatives as $cRepre)
                                    <div class="form-group col-sm-3 mt-3">
{{--                                        <label class="mb-1">ឈ្មោះកម្មករ</label>--}}
                                        <input type="text" value="{{ $cRepre->fullname }}" class="form-control" disabled />
                                    </div>
                                @endforeach
                                </div>
                            @endif
                            <div class="row">
                                <div class="form-group col-sm-6 mt-3">
                                    <label for="case_type_id" class="fw-bold text-info">*បញ្ចូលពាក្យបណ្តឹងដោយ៖</label>
                                    <label class="form-label fw-bold text-danger">{{ $case->entryUser->k_fullname }}</label>
                                </div>
                            </div>
                            <br>
                            <table class="table text-hanuman-18">
                                <thead class="table-light">
                                <tr>
                                    <th width="24%" class="text-hanuman-22 text-danger" >ជំហាននៃដំណើរការបណ្ដឹង</th>
                                    <th ></th>
                                    <th width="17%" class="text-hanuman-20 text-danger" style="text-align: center;">ឯកសារ</th>
                                    <th width="10%" class="text-hanuman-20 text-danger" style="text-align: center;">ស្ថានភាព</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><label class="form-label fw-bold blue">{{Num2Unicode(1)}}.ពាក្យបណ្ដឹង</label></td>
                                    <td>
                                        @php
                                            $imageUrl = rurl('assets/images');
                                        @endphp
                                        <div class="row mt-2">
                                            <div class="form-group col-sm-4">
                                                <a class="btn btn-success custom form-control fw-bold" href="{{ url('collective_cases/'.$case->id.'/edit') }}" title="កែប្រែពាក្យបណ្តឹង" target="_blank">កែសម្រួលពាក្យបណ្ដឹង</a>
                                            </div>
                                            <div class="form-group col-sm-4"></div>
                                            <div class="form-group col-sm-4 text-end" >
{{--                                                <a class="btn btn-info custom form-control fw-bold" href="{{ url('export/word/case/'.$case->id) }}" title="ទាញយកពាក្យបណ្ដឹង" target="_blank">ទាញយកពាក្យបណ្ដឹង</a>--}}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $case_file = showFile(1, $case->collectives_case_file, pathToDeleteFile('case_doc/collectives/'.$caseYear."/"), "delete", "tbl_case", "id", $case->id,  "collectives_case_file", "", "");
                                            if($case_file){
                                                echo $case_file;
//                                                dd($case_file);
                                                $imageStatus = "/check.png";
                                                $button = '';
                                            }
                                            else{
                                                //echo '<button id="uploadButtonInvitationEmployee" class="btn btn-success uploadButton" value="'.$case->id.'" data-url="'.url('case/upload/case_file3').'">Upload លិខិត</button>';
                                                echo '<a href="'.url('uploads/all/1/'.$case->id.'/'.$case->id).'" class="fw-bold btn btn-success form-control mt-2">'."Upload លិខិត</a>";
                                            }
                                        @endphp

                                    </td>
                                    <td class="text-center">
                                        @if(!empty($imageStatus))
                                            <img  width="30" height="30" src="{{ $imageUrl }}/check.png" />
                                        @endif

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @php

                                            $officerName ="";
//                                            $arrOfficersInHand = arrayOfficerCaseInHand(0, 1);

//                                            dd($tmp2);
//                                            dd($arrOfficersInHand);
                                            //arrayOfficer(0,1, "")
//                                            $select = showSelect('officer_id', $tmp2, old('officer_id'), " select2", "", "", "required");
                                            $select = showSelect('officer_id', $officerInDomain, old('officer_id'), " select2", "", "", "required");
                                            $button = '<button type="submit" class="btn btn-success form-control">បញ្ជូន</button>';
                                            $btnChangeOfficer = '<button id="btnShowSelectOfficer" value="0" type="button" class="btn btn-secondary form-control fw-bold">កំណត់ ឬផ្លាស់ប្តូរ</button>';
                                            if(!empty($lastOfficer)){
                                                $officerName =  $lastOfficer->officer->officer_name_khmer;
                                                $imageStatus = "/check.png";
                                            }
                                            else{
                                                $imageStatus= "";
                                            }
                                        @endphp
                                        <label class="form-label fw-bold blue">{{ Num2Unicode(2) }}.អ្នកផ្សះផ្សា:</label>
                                        <span class="red fw-bold">{{ $officerName }}</span>
                                    </td>
                                    <td colspan="2">
                                        <form id="frm_change_officer" name="frm_change_officer" action="{{ url('assign/officer') }}" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <input type="hidden" name="case_id" value="{{ $case->id }}" >
                                            <div class="row">
                                                <div class="form-group col-sm-3">
                                                    {!! $btnChangeOfficer !!}
                                                </div>
                                                <div id="div_select_officer" class="form-group col-sm-6" style="display: none">
                                                    {!! $select !!}
                                                </div>
                                                <div id="div_btn_change_officer" class="form-group col-sm-3" style="display: none">
                                                    {!! $button !!}
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        @if(!empty($imageStatus))
                                            <img  width="30" height="30" src="{{ $imageUrl }}/check.png" />
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><label class="form-label fw-bold blue">{{ Num2Unicode(3) }}.អញ្ជើញភាគីដើមចោទមកផ្ដល់ព័ត៌មាន</label></td>
                                    <td>
                                    @php
                                        $invitationEmployee = showCollectivesInviationEMP($case);
                                        //dd($invitationEmployee);
                                    @endphp
                                    <div class="row">
                                        @if(!empty($lastOfficer))
                                            {!! $invitationEmployee['info'] !!}
                                            {!! $invitationEmployee['export'] !!}
                                        @endif
                                    </div>
                                    </td>
                                    <td>
                                    @php
                                        if($invitationEmployee['invitation_id'] > 0){
                                            $show_file = showFile(1, $invitationEmployee['invitation_file'], pathToDeleteFile('collectives_invitation/'.$caseYear."/"), "delete", "tbl_case_invitation", "id", $invitationEmployee['invitation_id'],  "invitation_file", "", "");
                                            if($show_file){
                                                echo $show_file;
                                            }
                                            else{
//                                                        echo '<button id="uploadButtonInvitationEmployee" class="btn btn-success form-control uploadButton" value="'.$invitationEmployee['invitation_id'].'" data-title="Upload លិខិតអញ្ជើញដែលមានចុះហត្ថលេខាទទួល" data-url="'.url('invitation/upload/file').'">Upload លិខិត</button>';
                                                echo '<a href="'.url('uploads/all/3/'.$case->id.'/'.$invitationEmployee['invitation_id']).'" class="fw-bold btn btn-success form-control">'."Upload លិខិត</a>";
                                            }
                                        }
                                    @endphp
                                    </td>
                                    <td class="text-center">
                                        @if($invitationEmployee['invitation_id'] > 0)
                                            <img width="30" height="30" src="{{ $imageUrl }}/check.png" />
                                        @endif

                                    </td>
                                </tr>
                                @if(!empty($case->invitationCollectivesDisputants))
                                    @if(count($case->invitationCollectivesDisputants->nextTime) > 0)
                                        @foreach($case->invitationCollectivesDisputants->nextTime as $next)
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <div class="row">
                                                        <div class="form-group col-sm-12 blue fw-bold">
                                                            <span class="fw-bold text-danger">*{{ $next->status->status_name }}៖</span>
                                                            ជួបលើកក្រោយថ្ងៃទី <span class="text-danger">{{ date2Display($next->next_date) }}</span>
                                                            ម៉ោង <span class="text-danger">{{ $next->next_time }}</span>
                                                            មូលហេតុ <span class="text-danger">{{ $next->reason }}</span>
                                                        </div>
                                                    </div>

                                                </td>
                                                <td>
                                                @php
                                                    $show_file= showFile(1, $next->letter, pathToDeleteFile('collectives_invitation/next/'.$caseYear."/"), "delete", "tbl_case_invitation_next_time", "id", $next->id,  "letter", "", "");
                                                    if($show_file){
                                                        echo $show_file;
                                                    }
                                                    else{
    //                                                                echo '<button id="" class="btn btn-success form-control uploadButton" value="'.$next->id.'" data-title="Upload លិខិតអញ្ជើញដែលមានចុះហត្ថលេខាទទួល" data-url="'.url('invitation/upload/next/file').'">Upload លិខិតអញ្ជើញ</button>';
                                                        echo '<a href="'.url('uploads/all/33/'.$case->id.'/'.$next->id).'" class="btn btn-success form-control fw-bold">'."Upload លិខិត</a>";
                                                    }
                                                @endphp
                                                </td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif

                                <tr>
                                    <td><label class="form-label fw-bold blue">{{ Num2Unicode(4) }}.សាកសួរព័ត៌មានដើមចោទ</label></td>
                                    <td>
                                        @php
                                            $showCaseLog34 = showCollectivesLog34($case);
                                        @endphp
                                        @if(!empty($lastOfficer))
                                            <div class="row">
                                                {!! $showCaseLog34['info'] !!}
                                                {!! $showCaseLog34['export'] !!}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                    @php
                                        if($showCaseLog34['log34_id'] > 0){
                                            $show_file= showFile(1, $showCaseLog34['log_file'], pathToDeleteFile('case_doc/collectives/log34/'.$caseYear."/"), "delete", "tbl_case_log34", "id", $showCaseLog34['log34_id'],  "log_file", "", "");
                                            if($show_file){
                                                echo $show_file;
                                            }
                                            else{
                                                //echo '<button id="uploadButtonInvitationEmployee" class="btn btn-success form-control uploadButton" value="'.$showCaseLog34['log34_id'].'" data-title="Upload កំណត់ហេតុដែលបានចុះហត្ថលេខា ផ្ដិតមេដៃ និងវាយត្រាឈ្មោះ" data-url="'.url('log34/upload/file').'">Upload កំណត់ហេតុ</button>';
                                                echo '<a href="'.url('uploads/all/4/'.$case->id.'/'.$showCaseLog34['log34_id']).'" class="btn btn-success form-control fw-bold">'."Upload កំណត់ហេតុ</a>";
                                            }
                                        }
                                    @endphp
                                    </td>
                                    <td class="text-center">
                                        @if($showCaseLog34['log34_id'] > 0)
                                            <img width="30" height="30" src="{{ $imageUrl }}/check.png" />
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><label class="form-label fw-bold blue">{{ Num2Unicode(5) }}.អញ្ជើញភាគីចុងចោទមកផ្ដល់ព័ត៌មាន</label></td>
                                    <td>
                                        @php
                                            $invitationCompany = showCollectivesInvitationCompany($case);
//                                            dd($invitationCompany);
                                        @endphp
                                        @if(!empty($lastOfficer))
                                            <div class="row">
                                                {!! $invitationCompany['info'] !!}
                                                {!! $invitationCompany['export'] !!}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                    @php
                                        if($invitationCompany['invitation_id'] > 0){
                                            $show_file= showFile(1, $invitationCompany['invitation_file'], pathToDeleteFile('collectives_invitation/'.$caseYear."/"), "delete", "tbl_case_invitation", "id", $invitationCompany['invitation_id'],  "invitation_file", "", "");
                                            if($show_file){
                                                echo $show_file;
                                            }
                                            else{
                                                //echo '<button id="uploadButtonInvitationCompany" class="btn btn-success form-control uploadButton" value="'.$invitationCompany['invitation_id'].'" data-title="Upload លិខិតអញ្ជើញដែលមានចុះហត្ថលេខាទទួល" data-url="'.url('invitation/upload/file').'">Upload លិខិត</button>';
                                                echo '<a href="'.url('uploads/all/5/'.$case->id.'/'.$invitationCompany['invitation_id']).'" class="btn btn-success form-control fw-bold">'."Upload លិខិត</a>";
                                            }
                                        }
                                    @endphp
                                    </td>
                                    <td class="text-center">
                                        @if($invitationCompany['invitation_id'] > 0)
                                            <img width="30" height="30" src="{{ $imageUrl }}/check.png" />
                                        @endif
                                    </td>
                                </tr>
                                @if(!empty($case->invitationCollectivesCompany))
                                    @if(count($case->invitationCollectivesCompany->nextTime) > 0)
                                        @foreach($case->invitationCollectivesCompany->nextTime as $next)
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <div class="row">
                                                        <div class="form-group col-sm-12 fw-bold blue">
                                                            <span class="text-danger">*{{ $next->status->status_name }}</span>:
                                                            ជួបលើកក្រោយថ្ងៃទី <span class="text-danger">{{ date2Display($next->next_date) }}</span>
                                                            ម៉ោង <span class="text-danger">{{ $next->next_time }}</span>
                                                            មូលហេតុ <span class="text-danger">{{ $next->reason }}</span>
                                                        </div>
                                                    </div>

                                                </td>
                                                <td>
                                                    @php
                                                        //dd($next);
                                                            $show_file= showFile(1, $next->letter, pathToDeleteFile('collectives_invitation/next/'.$caseYear."/"), "delete", "tbl_case_invitation_next_time", "id", $next->id, "letter", "", "");
//                                                            dd($show_file);
                                                            if($show_file){
                                                                echo $show_file;
                                                            }
                                                            else{
                                                                //echo '<button id="" class="btn btn-success form-control uploadButton" value="'.$next->id.'" data-title="Upload លិខិតអញ្ជើញដែលមានចុះហត្ថលេខាទទួល" data-url="'.url('invitation/upload/next/file').'">Upload លិខិតអញ្ជើញ</button>';
                                                                echo '<a href="'.url('uploads/all/55/'.$case->id.'/'.$next->id).'" class="btn btn-success form-control fw-bold">'."Upload លិខិត</a>";
                                                            }
                                                    @endphp
                                                </td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif
                                <tr>
                                    <td><label class="form-label fw-bold blue">{{ Num2Unicode(6) }}.សាកសួរព័ត៌មានភាគីចុងចោទ</label></td>
                                    <td>
                                        @php
                                            $showCaseLog5 = showCollectivesLog5($case);
//                                            dd($showCaseLog5);
                                        @endphp
                                        <div class="row">
                                            @if($invitationCompany['invitation_id'] > 0)
                                                {!! $showCaseLog5['info'] !!}
                                                {!! $showCaseLog5['export'] !!}
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            if($showCaseLog5['log5_id'] > 0){
                                                $show_file= showFile(1, $showCaseLog5['log_file'], pathToDeleteFile('case_doc/collectives/log5/'.$caseYear."/"), "delete", "tbl_case_log5", "id", $showCaseLog5['log5_id'],  "log_file", "", "");
                                                if($show_file){
                                                    echo $show_file;
                                                }
                                                else{
                                                    //echo '<button id="uploadButtonInvitationEmployee" class="btn btn-success form-control uploadButton" value="'.$showCaseLog5['log5_id'].'" data-title="Upload កំណត់ហេតុដែលបានចុះហត្ថលេខា ផ្ដិតមេដៃ និងវាយត្រាឈ្មោះ" data-url="'.url('log5/upload/file').'">Upload កំណត់ហេតុ</button>';
                                                    echo '<a href="'.url('uploads/all/6/'.$case->id.'/'.$showCaseLog5['log5_id']).'" class="btn btn-success form-control fw-bold">'."Upload កំណត់ហេតុ</a>";
                                                }
                                            }
                                        @endphp
                                    </td>
                                    <td class="text-center">
                                        @if($showCaseLog5['log5_id'] > 0)
                                            <img width="30" height="30" src="{{ $imageUrl }}/check.png" />
                                        @endif
                                        @php
                                            //                                                            $imageStatus = $showCaseLog5['log5_id'] > 0?
                                            //                                                            "/check.png" : "/delete.png";
                                        @endphp
                                    </td>
                                </tr>
                                <tr>
                                    <td><label class="form-label fw-bold blue">{{ Num2Unicode(7) }}.អញ្ជើញភាគីទាំង២មកផ្សះផ្សា</label></td>
                                    <td>
                                    @php
                                        $invitationBoth = showCollectivesInvitationBoth($case);
//                                            dd($invitationBoth);
                                        if($invitationBoth['invitation_id1'] == 0){
                                            echo $invitationBoth['info1'];
                                        }
                                    @endphp
                                    </td>
                                    <td></td>
                                    <td class="text-center">
                                        @if($invitationBoth['invitation_id1'] > 0)
                                            <img width="30" height="30" src="{{ $imageUrl }}/check.png" />
                                        @endif
                                    </td>
                                </tr>
                                @if($invitationBoth['invitation_id1'] > 0)
                                    <tr>
                                        <td></td>
                                        <td>
                                            <div class="row">
                                                {!! $invitationBoth['info1'] !!}
                                                {!! $invitationBoth['export1'] !!}
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $show_file= showFile(1, $invitationBoth['invitation_file1'], pathToDeleteFile('collectives_invitation/'.$caseYear."/"), "delete", "tbl_case_invitation", "id", $invitationBoth['invitation_id1'],  "invitation_file", "", "");
                                                if($show_file){
                                                    echo $show_file;
                                                }
                                                else{
                                                    //echo '<button id="uploadButtonInvitationCompany" class="btn btn-success form-control uploadButton" value="'.$invitationBoth['invitation_id1'].'" data-title="Upload លិខិតអញ្ជើញដែលមានចុះហត្ថលេខាទទួល" data-url="'.url('invitation/upload/file').'">Upload លិខិត</button>';
                                                    echo '<a href="'.url('uploads/all/7/'.$case->id.'/'.$invitationBoth['invitation_id1']).'" class="btn btn-success form-control fw-bold">'."Upload លិខិត</a>";
                                                }
                                            @endphp
                                        </td>
                                        <td></td>
                                    </tr>
                                @endif
                                @if($invitationBoth['invitation_id2'] > 0)
                                    <tr>
                                        <td></td>
                                        <td>
                                            <div class="row mt-2">
                                                {!! $invitationBoth['info2'] !!}
                                                {!! $invitationBoth['export2'] !!}
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $show_file= showFile(1, $invitationBoth['invitation_file2'], pathToDeleteFile('collectives_invitation/'.$caseYear."/"), "delete", "tbl_case_invitation", "id", $invitationBoth['invitation_id2'],  "invitation_file", "", "");
                                                if($show_file){
                                                    echo $show_file;
                                                }
                                                else{
                                                    //echo '<button id="uploadButtonInvitationCompany" class="btn btn-success form-control uploadButton" value="'.$invitationBoth['invitation_id2'].'" data-title="Upload លិខិតអញ្ជើញដែលមានចុះហត្ថលេខាទទួល" data-url="'.url('invitation/upload/file').'">Upload លិខិត</button>';
                                                    echo '<a href="'.url('uploads/all/7/'.$case->id.'/'.$invitationBoth['invitation_id2']).'" class="btn btn-success form-control fw-bold">'."Upload លិខិត</a>";
                                                }
                                            @endphp
                                        </td>
                                        <td></td>
                                    </tr>
                                @endif
                                @if(!empty($case->collectivesInvForConcilationEmp))
                                    @if(count($case->collectivesInvForConcilationEmp->nextTime) > 0)
                                        @foreach($case->collectivesInvForConcilationEmp->nextTime as $next)
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <div class="row">
                                                        <div class="form-group col-sm-12 fw-bold blue">
                                                            <span class="text-info">*កម្មករនិយោជិត ({{ $next->status->status_name }}):</span>
                                                            ជួបលើកក្រោយថ្ងៃទី <span class="text-danger">{{ date2Display($next->next_date) }}</span>
                                                            ម៉ោង <span class="text-danger">{{ $next->next_time }}</span>
                                                            មូលហេតុ <span class="text-danger">{{ $next->reason }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        //dd($next);
                                                            $show_file= showFile(1, $next->letter, pathToDeleteFile('collectives_invitation/next/'.$caseYear."/"), "delete", "tbl_case_invitation_next_time", "id", $next->id,  "letter", "", "");
                                                            if($show_file){
                                                                echo $show_file;
                                                            }
                                                            else{
                                                                //echo '<button id="" class="btn btn-success form-control uploadButton" value="'.$next->id.'" data-title="Upload លិខិតអញ្ជើញដែលមានចុះហត្ថលេខាទទួល" data-url="'.url('invitation/upload/next/file').'">Upload លិខិត</button>';
                                                                 echo '<a href="'.url('uploads/all/77/'.$case->id.'/'.$next->id).'" class="btn btn-success form-control fw-bold">'."Upload លិខិត</a>";
                                                            }
                                                    @endphp
                                                </td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif

                                @if(!empty($case->collectivesInvForConcilationCom))
                                    @if(count($case->collectivesInvForConcilationCom->nextTime) > 0)
                                        @foreach($case->collectivesInvForConcilationCom->nextTime as $next)
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <div class="row">
                                                        <div class="form-group col-sm-12 fw-bold blue">
                                                            <span class="text-info">*សហគ្រាស គ្រឹះស្ថាន ({{ $next->status->status_name }}):</span>
                                                            ជួបលើកក្រោយថ្ងៃទី <span class="text-danger">{{ date2Display($next->next_date) }}</span>
                                                            ម៉ោង <span class="text-danger">{{ $next->next_time }}</span>
                                                            មូលហេតុ <span class="text-danger">{{ $next->reason }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                @php
                                                    //dd($next);
                                                        $show_file= showFile(1, $next->letter, pathToDeleteFile('collectives_invitation/next/'.$caseYear."/"), "delete", "tbl_case_invitation_next_time", "id", $next->id,  "letter", "", "");
                                                        if($show_file){
                                                            echo $show_file;
                                                        }
                                                        else{
                                                            echo '<a href="'.url('uploads/all/77/'.$case->id.'/'.$next->id).'" class="btn btn-success form-control fw-bold fw-bold">'."Upload លិខិត</a>";
                                                        }
                                                @endphp
                                                </td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif

                                @php
                                    $showCaseLog6All = showCollectivesLog6($case);
                                    //echo $showCaseLog6All['num_log6'];
                                    //dd($showCaseLog6All);
                                @endphp
                                <tr>
                                    <td><label class="form-label fw-bold blue">{{ Num2Unicode(8) }}.ការផ្សះផ្សា</label></td>
                                    <td>
                                        {{--                                                        {{ dd($showCaseLog6All) }}--}}
                                        @if($showCaseLog6All['num_log6'] > 0)

                                            {{--                                                            {!! $showCaseLog6All['log6_data'][0]['info'] !!}--}}
                                        @else
                                            {!! $showCaseLog6All['log6_data']['info'] !!}
                                        @endif
                                    </td>
                                    <td>
                                    </td>
                                    <td class="text-center">
                                        @if($showCaseLog6All["num_log6"] > 0)
                                            <img width="30" height="30" src="{{ $imageUrl }}/check.png" />
                                        @endif
                                    </td>
                                </tr>
                                @if($showCaseLog6All['num_log6'] > 0)
                                    @php $j=1; @endphp
                                    @foreach($showCaseLog6All['log6_data'] as $showCaseLog6)
                                        <tr>
                                            <td></td>
                                            <td>
                                                <div class="row">
                                                    <div class="form-group col-sm-8" style="line-height: 40px">
                                                        {!! $showCaseLog6['info'] !!}
                                                        @if($j == $showCaseLog6All['num_log6'])
                                                            @if($showCaseLog6['detail']->status_id == 2)
                                                                @if($showCaseLog6['detail']->reopen_status == 1)
                                                                    <div class="row mt-2">
                                                                        <div class="form-group col-sm-12">
                                                                            {{--                                                                        <button id="reopenLog6Update" class="btn btn-success form-control" value="{{ $showCaseLog6['log6_id'] }}" data-title="ការសុំផ្សះផ្សាឡើងវិញ" data-url="{{ url('log6/reopen/update') }}" data-status_date="{{ date2Display($showCaseLog6['detail']->status_date) }}" data-status_time="{{ $showCaseLog6['detail']->status_time }}" data-status_letter="{{ $showCaseLog6['detail']->status_letter }}">--}}
                                                                            {{--                                                                            កែប្រែព័ត៌មានសុំផ្សះផ្សាឡើងវិញ--}}
                                                                            {{--                                                                        </button>--}}
                                                                            <a href="{{ url('uploads/all/84/'.$case->id.'/'.$showCaseLog6['log6_id']) }}" class="btn btn-success form-control fw-bold">កែប្រែព័ត៌មានសុំផ្សះផ្សាឡើងវិញ</a>
                                                                            <br>
                                                                            <span class="fw-bold blue">ផ្សះផ្សាឡើងវិញថ្ងៃទី <span class="text-danger">{{ date2Display($showCaseLog6['detail']->status_date) }}</span>
                                                                            ម៉ោង <span class="text-danger">{{ date2Display($showCaseLog6['detail']->status_time, 'H:i') }}</span>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <a href='#' class='btn btn-success form-control fw-bold' style='margin-bottom: 3px;'
                                                                       onClick="comfirm_sweetalert2('{{ url("collectives/log6/generate/new/log/".$showCaseLog6['log6_id']."/".$showCaseLog6['detail']->status_id) }}', 'ចង់បង្កើតកំណត់ហេតុផ្សះផ្សាឡើងវិញ មែនឫ?')" >បង្កើតកំណត់ហេតុសុំផ្សះផ្សាឡើងវិញ</a>
                                                                @else
                                                                    {{--                                                                <button id="reopenLog6Insert" class="btn btn-success form-control" value="{{ $showCaseLog6['log6_id'] }}" data-title="ការសុំផ្សះផ្សាឡើងវិញ" data-url="{{ url('log6/reopen/insert') }}" data-status_date="{{ date2Display($showCaseLog6['detail']->status_date) }}" data-status_time="{{ $showCaseLog6['detail']->status_time }}" data-status_letter="{{ $showCaseLog6['detail']->status_letter }}">--}}
                                                                    {{--                                                                    សុំផ្សះផ្សាឡើងវិញ--}}
                                                                    {{--                                                                </button>--}}
                                                                    <div class="form-group col-sm-6"><a href="{{ url('uploads/all/82/'.$case->id.'/'.$showCaseLog6['log6_id']) }}" class="btn btn-success form-control fw-bold">សុំផ្សះផ្សាឡើងវិញ</a></div>
                                                                @endif
                                                            @elseif($showCaseLog6['detail']->status_id == 3)
                                                                <div class="row mt-3">
                                                                    <div class="form-group col-sm-12">
                                                                        {{--                                                                    <button id="reopenLog6Update" class="btn btn-success form-control" value="{{ $showCaseLog6['log6_id'] }}" data-title="ការសុំលើកពេលផ្សះផ្សា" data-url="{{ url('log6/reopen/update') }}" data-status_date="{{ date2Display($showCaseLog6['detail']->status_date) }}" data-status_time="{{ $showCaseLog6['detail']->status_time }}" data-status_letter="{{ $showCaseLog6['detail']->status_letter }}">--}}
                                                                        {{--                                                                        កែប្រែព័ត៌មានសុំលើកពេលផ្សះផ្សា--}}
                                                                        {{--                                                                    </button>--}}
                                                                        <a href="{{ url('uploads/all/83/'.$case->id.'/'.$showCaseLog6['log6_id']) }}" class="btn btn-success form-control fw-bold">កែប្រែព័ត៌មានសុំលើកពេលផ្សះផ្សា</a>
                                                                        <br>
                                                                        <span class="fw-bold blue">លើកពេលផ្សះផ្សាទៅថ្ងៃទី <span class="text-danger">{{ date2Display($showCaseLog6['detail']->status_date) }}</span>
                                                                        ម៉ោង <span class="text-danger">{{ date2Display($showCaseLog6['detail']->status_time, 'H:i') }}</span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <a href='#' class='btn btn-success form-control fw-bold' style='margin-bottom: 3px;'
                                                                   onClick="comfirm_sweetalert2('{{ url("collectives/log6/generate/new/log/".$showCaseLog6['log6_id']."/".$showCaseLog6['detail']->status_id) }}', 'ចង់បង្កើតកំណត់ហេតុលើកពេលផ្សះផ្សា មែនឫ?')" >បង្កើតកំណត់ហេតុលើកពេលផ្សះផ្សា</a>
                                                            @endif
                                                        @else
                                                            <div class="row mt-2">
                                                                <div class="form-group col-sm-12 text-info fw-bold">
                                                                    @if($showCaseLog6['detail']->status_id == 2)
                                                                        <span class="pink">[បានស្នើសុំផ្សះផ្សាឡើងវិញ]</span>
                                                                    @elseif($showCaseLog6['detail']->status_id == 3)
                                                                        <span class="text-danger">{{ date2Display($showCaseLog6['detail']->status_date) }}</span>
                                                                        ម៉ោង <span class="text-danger">{{ date2Display($showCaseLog6['detail']->status_time, 'H:i') }}</span><br/>
                                                                        <span class="blue">[កាលបរិច្ឆេទលើកពេលផ្សះផ្សា]</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="form-group col-sm-4">
                                                        {!! $showCaseLog6['export'] !!}
                                                        @if($j == $showCaseLog6All['num_log6'])
                                                            @if($showCaseLog6['detail']->status_id == 2 && $showCaseLog6['detail']->reopen_status == 1)
                                                                <div class="mt-3">
                                                                    <a href="#" onClick="comfirm_sweetalert2('{{ url("collectives/log6/reopen/request/cancel/".$showCaseLog6['detail']->case_id.'/'.$showCaseLog6['detail']->id) }}', 'តើអ្នកពិតជាចង់លុប មែនឫ?')" class="btn btn-danger form-control">
                                                                        លុបការសុំផ្សះផ្សាឡើងវិញ
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    //dd($showCaseLog6);
                                                    if($showCaseLog6['log6_id'] > 0){
                                                        $show_file = showFile(1, $showCaseLog6['log_file'], pathToDeleteFile('case_doc/collectives/log6/'.$caseYear."/"), "delete", "tbl_case_log6", "id", $showCaseLog6['log6_id'],  "log_file", "");
                                                        if($show_file){
                                                            echo $show_file;
                                                        }
                                                        else{
                                                            //echo '<button id="uploadButtonInvitationEmployee" class="btn btn-success form-control uploadButton" value="'.$showCaseLog6['log6_id'].'" data-title="Upload កំណត់ហេតុដែលបានចុះហត្ថលេខា ផ្ដិតមេដៃ និងវាយត្រាឈ្មោះ" data-url="'.url('log6/upload/file').'">Upload កំណត់ហេតុ</button>';
                                                             echo '<a href="'.url('uploads/all/8/'.$case->id.'/'.$showCaseLog6['log6_id']).'" class="btn btn-success form-control fw-bold">'."Upload កំណត់ហេតុ</a>";
                                                        }
                                                    }
                                                @endphp
                                                @if($showCaseLog6['detail']->status_id == 2)
                                                    <div class="mt-3">
                                                        @php
                                                            //dd($showCaseLog6);
                                                            //dd($showCaseLog6['detail']);
                                                        if($showCaseLog6['detail']->reopen_status == 1){
                                                            $show_file= showFile(1, $showCaseLog6['detail']->status_letter, pathToDeleteFile('case_doc/collectives/log6/status_letter/'.$caseYear."/"), "delete", "tbl_case_log6", "id", $showCaseLog6['detail']->id,  "status_letter", "");
                                                                if($show_file){
                                                                    echo $show_file;
                                                                }
                                                                else{
                                                                    echo '<button id="uploadButtonInvitationEmployee" class="btn btn-success form-control uploadButton" value="'.$showCaseLog6['detail']->id.'" data-title="Upload លិខិតស្នើសុំផ្សះផ្សាឡើងវិញ ដែលបានចុះហត្ថលេខា" data-url="'.url('log6/reopen/upload/file').'">Upload លិខិត</button>';
                                                                    echo '<a href="'.url('uploads/all/81/'.$case->id.'/'.$showCaseLog6['detail']->id).'" class="btn btn-success form-control">'."Upload កំណត់ហេតុ</a>";
                                                                }
                                                        }
                                                        @endphp
                                                    </div>
                                                @elseif($showCaseLog6['detail']->status_id == 3)
                                                    <div class="mt-3">
                                                        @php
                                                            //dd($showCaseLog6);
                                                            //dd($showCaseLog6['detail']);
                                                            $show_file= showFile(1, $showCaseLog6['detail']->status_letter, pathToDeleteFile('case_doc/collectives/log6/status_letter/'.$caseYear."/"), "delete", "tbl_case_log6", "id", $showCaseLog6['detail']->id,  "status_letter", "");
                                                                if($show_file){
                                                                    echo $show_file;
                                                                }
                                                                else{
                                                                    //echo '<button id="uploadButtonInvitationEmployee" class="btn btn-success form-control uploadButton" value="'.$showCaseLog6['detail']->id.'" data-title="Upload កំណត់ហេតុដែលបានចុះហត្ថលេខា ផ្ដិតមេដៃ និងវាយត្រាឈ្មោះ" data-url="'.url('log6/reopen/upload/file').'">Upload លិខិត</button>';
                                                                    echo '<a href="'.url('uploads/all/81/'.$case->id.'/'.$showCaseLog6['detail']->id).'" class="btn btn-success form-control fw-bold">'."Upload កំណត់ហេតុ</a>";
                                                                }
                                                        @endphp
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-purple text-center fw-bold">
                                                {{ $showCaseLog6['log6']->status->status_name }}
                                                @if( $showCaseLog6['log6']->status->id == 3)
                                                <br>
                                                <span style="font-size: 12px;" class="text-danger">
                                                    [ទៅថ្ងៃ {{ date2Display($showCaseLog6['log6']->status_date) }}]
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
                        <div class="card-block row text-center mt-4">
                            <div class="form-group  col-sm-12">
                                <a class="btn btn-danger text-hanuman-22" href="{{ url('close/case/'.$case->id) }}" title="ចុចបិទបញ្ចប់សំណុំរឿង" target="">ការបិទបញ្ចប់សំណុំរឿង</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <x-slot name="moreAfterScript">
{{--        @include('case.script.collective_case_script')--}}
        @include('case.script.collective_show_case_script')
        @include('script.my_sweetalert2')
    </x-slot>
</x-admin.layout-main>
