
@php
    $row = $adata['case'];
    $row2 =  $row->invitationForConcilationEmployee; //លិខិតអញ្ជើញកម្មករមកផ្សះផ្សារ
    $row3 = $row->invitationForConcilationCompany; //លិខិតអញ្ជើញក្រុមហ៊ុនមកផ្សះផ្សារ
    $caseYear = date2Display($row->case_date, "Y");
    $letter = $adata['letter'];
    $letterPair = $adata['letterPair'];
    if($letter->invitation_type_id == 5){
        $label1 = "ទាញយកលិខិតអញ្ជើញកម្មករនិយោជិត";
        $label2 = "ទាញយកលិខិតអញ្ជើញសហគ្រាស គ្រឹះស្ថាន";
        $invType1 = "លេខលិខិតអញ្ជើញកម្មករនិយោជិត";
        $invType2 = "លេខលិខិតអញ្ជើញសហគ្រាស គ្រឹះស្ថាន";

    }else{
        $label1 = "ទាញយកលិខិតអញ្ជើញសហគ្រាស គ្រឹះស្ថាន";
        $label2 = "ទាញយកលិខិតអញ្ជើញកម្មករនិយោជិត";
        $invType1 = "លេខលិខិតអញ្ជើញសហគ្រាស គ្រឹះស្ថាន";
        $invType2 = "លេខលិខិតអញ្ជើញកម្មករនិយោជិត";
    }
    $user = $adata['user'];
    $userOfficerID = $user->officer_id;
    $chkAllowAccess = $adata['chkAllowAccess'];
    $arrOfficerIDs = $adata['arrOfficerIDs'];
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
        </style>
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body row mt-2">
                        @php
                            //$label = $row->invitationType->employee_or_company == 1? "employee":"employee";
                        @endphp
                        <div class="form-group col-sm-4">
                            <a class="btn btn-info custom form-control" href="{{ url('export/word/invitation/'.$letter->id) }}" title="Download" target="_blank">{{ $label1 }}</a>
                        </div>
                        <div class="form-group col-sm-4">
                            <a class="btn btn-info custom form-control" href="{{ url('export/word/invitation/'.$letterPair->id) }}" title="Download" target="_blank">{{ $label2 }}</a>
                        </div>

                        @if(($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs))  || $user->id == $row->user_created && count($row->log6) == 0)
                            <div class="form-group col-sm-4">
                                <form name="frmDelete" action = "{{ url('invitations'.'/'.$letter->id."_".$adata['id_pair']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    {{--                                ."_".$adata['id_pair']--}}
                                    <button type="button" class="form-control btn btn-danger delete-btn">
                                        លុបលិខិតអញ្ជើញ
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    <form name="formUpdateBothInv" action="{{ url('invitation/update_both') }}" method="POST" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <input type="hidden" name="case_id" value="{{ $adata['case_id'] }}" >
                        <input type="hidden" name="case_year" value="{{ $caseYear }}">
                        <input type="hidden" name="id" value="{{ $adata['id'] }}" >
                        <input type="hidden" name="invitation_type_id" value="{{ $letter->invitation_type_id }}" >
                        <input type="hidden" name="id_pair" value="{{ $adata['id_pair'] }}" >
                        <input type="hidden" name="disputant_id" value="{{ $row->disputant_id }}" >
                        <input type="hidden" name="company_id" value="{{ $row->company_id }}" >

                        <div class="card-body text-hanuman-17">
                            <div class="card-block">
                                <div class="row">
                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="text-purple text-hanuman-24 required mb-1">{{ $invType1 }}</label>
                                        <div class="d-flex">
                                            <input  type="text" name="inv_num_emp" id="inv_num_emp" value="{{ old('inv_num_emp', $letter->invitation_number) }}" class="form-control col-sm-2" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="text-purple text-hanuman-24 required mb-1">{{ $invType2 }}</label>
                                        <div class="d-flex">
                                            <input  type="text" name="inv_num_com" id="inv_num_com" value="{{ old('inv_num_com', $letterPair->invitation_number) }}" class="form-control col-sm-2" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-5 mt-3">
                                        <label class="fw-bold mb-1">ឈ្មោះកម្មករនិយោជិត</label>
                                        <input type="text" value="{{ $row->disputant->name }} {{ $row->disputant->name_latin }}" class="form-control" disabled />
                                    </div>
                                    @php
                                        $gender = $row->disputant->gender == 1? "ប្រុស":"ស្រី"
                                    @endphp
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">ភេទ</label>
                                        <input type="text" value="{{ $gender }}" class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">លេខទូរស័ព្ទ</label>
                                        <input type="text" value="{{ $row->caseDisputant->phone_number }}" class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">មានមុខងារ</label>
                                        <input type="text" value="{{ $row->caseDisputant->occupation }}" class="form-control" disabled />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-5 mt-3">
                                        <label class="fw-bold mb-1">ឈ្មោះសហគ្រាស គ្រឹះស្ថាន</label>
                                        <input type="text" name="company_name_khmer" id="company_name_khmer" value="{{ $row->company->company_name_khmer }} {{ $row->company->company_name_latin }}" class="form-control" disabled />
                                    </div>

                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">លេខទូរស័ព្ទ</label>
                                        <input type="text" value="{{ $row->caseCompany->log5_company_phone_number }}" class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">អាសយដ្ឋាន</label>
                                        <input type="text" value="{{ $row->caseCompany->province->pro_khname }}" class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                        <input type="text" value="{{ $row->caseCompany->district->dis_khname }}" class="form-control" disabled />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="fw-bold mb-1 required">កាលបរិច្ឆេទមកផ្សះផ្សា</label>
                                        <input type="text" name="meeting_date" id="meeting_date" value="{{ old('meeting_date', date2Display($letter->meeting_date)) }}" class="form-control" data-language="en" required >
                                    </div>
                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="fw-bold mb-1 required">ម៉ោងជួប</label>
                                        <div class="input-group clockpicker" data-autoclose="true">
                                            <input name="meeting_time" id="meeting_time" value="{{ old("meeting_time", date2Display($letter->meeting_time, 'H:i')) }}"  class="form-control" type="text" data-bs-original-title="" required >
                                        </div>
                                    </div>
                                    @php
                                        $docEmployee = $letter->invitation_type_id == 5 ? $letter->invitation_required_doc : $letterPair->invitation_required_doc;
                                        $docCompany = $letter->invitation_type_id == 5 ? $letterPair->invitation_required_doc : $letter->invitation_required_doc;
                                    @endphp
                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="fw-bold mb-1 required">ឯកសារពាក់ព័ន្ធសម្រាប់កម្មកនិយោជិតមាន</label>
                                        <input type="text" name="invitation_required_doc_employee" value ="{{ $docEmployee }}" id="invitation_required_doc_employee" class="form-control" />
                                    </div>
                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="fw-bold mb-1 required">ឯកសារពាក់ព័ន្ធសម្រាប់សហគ្រាស គ្រឹះស្ថានមាន</label>
                                        <input type="text" name="invitation_required_doc_company"  value ="{{ $docCompany }}" id="invitation_required_doc_company" class="form-control" />
                                    </div>
                                    <div class="form-group col-sm-4 mt-3">
                                        <label  for="letter_date" class="fw-bold mb-1 required">ថ្ងៃខែឆ្នាំបង្កើតលិខិតអញ្ជើញ</label>
                                        <input type="text" name="letter_date" id="letter_date" value="{{ old('letter_date', date2Display($letter->letter_date)) }}" class="form-control"  data-language="en"  >
                                    </div>
                                    <div class="form-group col-sm-4 mt-3">
                                        <label class="fw-bold mb-1" for="contact_phone">លេខទូរស័ព្ទទំនាក់ទំនង</label>
                                        <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $letter->contact_phone) }}" class="form-control"  >
                                    </div>
                                    <div class="form-group col-sm-4 mt-3 align-self-end">
                                        <input type="hidden" name="next_val" id="next_val" value="0" >
                                        <button id="btn_next" type="button" class="form-control btn btn-info fw-bold">លើកពេលណាត់ជួប</button>
                                    </div>
                                </div>
                                @php
//                                    dd($row2->nextTime);
//                                    $display = count($row2->nextTime) > 0 ? "" : "display: none;";
                                    $display = count($letter->nextTime) > 0 ? "" : "display: none;";
                                @endphp
{{--                                {{ dd($display) }}--}}
                                <div id="div_next_label" class="row col-12 mt-3" style="{{ $display }}">
                                    <input type="hidden" id="count_next" value="{{ $display }}" >
                                    <div class="form-group col-sm-2">
                                        <label class="fw-bold mb-1">ស្ថានភាព</label>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label class="fw-bold mb-1">មូលហេតុ</label>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="fw-bold mb-1">កាលបរិច្ឆេទជួប</label>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label class="fw-bold mb-1">ម៉ោងជួប</label>
                                    </div>
{{--                                    <div class="form-group col-sm-4">--}}
{{--                                    </div>--}}
                                </div>
                                @php
                                    $arrayStatus = [1 => "សុំលើកពេល", 2 => "អវត្តមាន"];
                                    $j = 0;
                                    $nextTime = $letter->nextTime->count();
                                @endphp
                                <div id="" class="row">
                                    @foreach($letter->nextTime as $next)
                                        <div class="form-group col-sm-2 mb-2">
                                            <input type="hidden" name="next_id_old[]" value="{{ $next->id }}" >
                                            {!! showSelect('status_old[]', $arrayStatus, old('status[]', $next->status_id)) !!}
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <input type="text" name="reason_old[]" value="{{ old('reason_old[]', $next->reason) }}" class="form-control">
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <input type="text"  name="next_date_old[]" value="{{ old('next_date_old', date2Display($next->next_date)) }}" class="form-control next_date"  data-language="en" >
                                        </div>
                                        <div class="form-group col-sm-1">
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input name="next_time_old[]" value="{{ old("next_time_old[]", $next->next_time) }}"  class="form-control" type="text" data-bs-original-title="" >
                                            </div>
                                        </div>
                                        @if($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs))
                                        <div class="form-group col-sm-3">
                                            <input type="hidden" name="letter_old_old[]" value="{{ $next->letter }}" >
                                            @php
                                                $show_file = showFile(1, $next->letter, pathToDeleteFile('invitation/next/'.$caseYear."/"), "delete", "tbl_case_invitation_next_time", "id", $next->id,  "letter", "");
                                                if($show_file){
                                                    echo $show_file;
                                                }
                                                else{
                                                    echo "<div class=''>".upload_file("letter_old[".$j."]", "សូមជ្រើសរើសឯកសារ (មានទំហំធំបំផុត 15MB)", "", "letter_old_".$next->id)."</div>";
                                                }
                                            @endphp
                                        </div>
                                        <div class="form-group col-sm-1">
                                            @php
                                                $deleteUrl = url('invitation/delete/next/'.$caseYear.'_'.$next->id);
                                                    $onClick = "comfirm_sweetalert2('".$deleteUrl."','តើអ្នកពិតជាចង់លុបចោល មែនឫ?')";
                                                $str2 = '<button type="button" class="btn btn-danger" onClick="'.$onClick.'" title="លុបចោល ការលើកពេល"><i data-feather="trash"></i></button>';
                                                echo $str2;
                                            @endphp
                                        </div>
                                        @endif
                                        @php $j++; @endphp
                                    @endforeach
                                </div>
                                <div id="div_next_meeting_1" class="row mt-2" style="display: none;">
                                    <div class="form-group col-sm-2">
                                        {!! showSelect('status', $arrayStatus, old('status', 1), "status_class") !!}
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <input type="text" name="reason" value="{{ old('reason') }}" class="form-control">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <input type="text"  name="next_date" value="{{ old('next_date') }}" class="form-control next_date"  data-language="en" >
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <div class="input-group clockpicker" data-autoclose="true">
                                            <input name="next_time" value="{{ old("next_time") }}"  class="form-control" type="text" data-bs-original-title="" >
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12 mt-2">
                                        <label class="fw-bold">លិខិតសុំលើកពេល: </label>
                                        {!! upload_file("letter", "សូមជ្រើសរើសឯកសារ (មានទំហំធំបំផុត 15MB)", "") !!}
                                    </div>
                                </div>

                                <br/>
                                @if($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs) || $user->id == $row->user_created)
                                <div class="row">
                                    <div class="form-group col-sm-2">
                                        <button type="submit" name="btnSubmit" value="save" class="form-control btn btn-success fw-bold">{{ __("btn.button_save") }}</button>

                                    </div>
                                    <div class="form-group col-sm-4">
                                        <button type="submit" name="btnSubmit" value="next" class="form-control btn btn-success fw-bold">{{ __("btn.button_save2") }}</button>
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
    <x-slot name="moreAfterScript">
        @include('script.my_sweetalert2')
        @include('case.script.invitation_script')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Attach SweetAlert2 confirmation to delete button
                const deleteButtons = document.querySelectorAll('.delete-btn');
                deleteButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        Swal.fire({
                            title: 'តើអ្នកពិតជាចង់លុប មែនឫ?',
                            //text: 'You will not be able to recover this data!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'លុបចោល',
                            cancelButtonText: 'អត់ទេ'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // If user confirms, submit the form
                                button.closest('form').submit();
                            }
                        });
                    });
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                var counter_next_meeting = 2;
                $('.status_class').select2();
                $('.next_date').datepicker({});
                $("#btn_next").click(function() {
                    var nextTime = {{ $nextTime }};

                    if($("#next_val").val() == 0){
                        $("#next_val").val(1);
                        $("#div_next_meeting_1").show("fast");
                        $("#div_next_label").show("fast");
                    }
                    else if($("#next_val").val() == 1){
                        $("#next_val").val(0);
                        $("#div_next_meeting_1").hide("fast");
                        if(nextTime === 0){
                            $("#div_next_label").hide("fast");
                        }

                    }
                });
                $("#btn_add_next_meeting").click(function () {
                    if(counter_next_meeting > 10){
                        let timerInterval;
                        Swal.fire({
                            title: "មិនអាចបន្ថែមបានទៀតទេ!",
                            timer: 800,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                                const timer = Swal.getPopup().querySelector("b");
                                timerInterval = setInterval(() => {
                                    timer.textContent = `${Swal.getTimerLeft()}`;
                                }, 100);
                            },
                            willClose: () => {
                                clearInterval(timerInterval);
                            }
                        });
                        return false;
                    }

                    // Create a jQuery object from the HTML code for Sub Disputant
                    //alert(counter_next_meeting);
                    var html = $('<div>', {
                        class: 'col-12',
                        id: 'div_next_meeting_'+ counter_next_meeting,
                        html: `
                            <div id="div_next_meeting_1" class="row mt-2">
                                <div class="form-group col-sm-2">
                                <input type="hidden" name="next_id[]" value="0" >
                                {!! showSelect('status[]', $arrayStatus, old('status', 1)) !!}
                        </div>
                    <div class="form-group col-sm-3">
                        <input type="text" name="reason[]" value="{{ old('reason[]') }}" class="form-control">
                        </div>
                        <div class="form-group col-sm-2">
                            <input type="text"  name="next_date[]" id="next_date_`+counter_next_meeting+`" value="{{ old('next_date') }}" class="form-control"  data-language="en" >
                        </div>
                        <div class="form-group col-sm-2">
                            <div class="input-group clockpicker" data-autoclose="true">
                                <input name="next_time[]" id="next_time_`+counter_next_meeting+`" value="{{ old("next_time") }}"  class="form-control" type="text" data-bs-original-title="" >
                            </div>
                        </div>
                        <div class="form-group col-sm-11 mt-2">
                           {!! upload_file("letter[]", "សូមជ្រើសរើសឯកសារ (មានទំហំធំបំផុត 5MB)", "required") !!}
                        </div>

                        </div>
                    `
                    });

                    // Append the HTML code to the div with id "disputant_emp"
                    $('#div_next_meeting_' + (counter_next_meeting - 1)).after(html);
                    $('#next_date_' + counter_next_meeting).datepicker({});
                    $('.clockpicker').clockpicker({});
                    counter_next_meeting ++;
                });
                //Remove Log620
                $("#btn_remove_next_meeting").on("click", function() {
                    if(counter_next_meeting == 2){
                        // let timerInterval;
                        // Swal.fire({
                        //     title: "លុបលែងបានហើយ",
                        //     timer: 800,
                        //     timerProgressBar: true,
                        //     didOpen: () => {
                        //         Swal.showLoading();
                        //         const timer = Swal.getPopup().querySelector("b");
                        //         timerInterval = setInterval(() => {
                        //             timer.textContent = `${Swal.getTimerLeft()}`;
                        //         }, 100);
                        //     },
                        //     willClose: () => {
                        //         clearInterval(timerInterval);
                        //     }
                        // });
                        return false;
                    }
                    // Remove the last added input element
                    $('#div_next_meeting_' + (counter_next_meeting - 1)).remove();
                    counter_next_meeting--;
                });

            });
        </script>


    </x-slot>
</x-admin.layout-main>
