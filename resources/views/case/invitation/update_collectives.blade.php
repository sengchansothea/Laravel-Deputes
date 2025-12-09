@php
    $row = $adata['letter'];
    $caseYear = date2Display($row->case->case_date, "Y");
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
                            $label = $row->invitationType->employee_or_company == 1? "employee":"employee";
                            $id = $row->id;
                            if($adata['id_pair'] > 0){
                                $id = $row->id."_".$adata['id_pair'];
                            }
                        @endphp
{{--                        <div class="form-group col-sm-4"></div>--}}
                        @if($row->invitation_type_id == 7)
                            @if(count($row->case->log34) == 0)
                                <div class="form-group col-sm-4">
                                    <a class="btn btn-info custom form-control mb-1" href="{{ url('export/word/collectives/invitation/'.$row->id) }}" title="Download" target="_blank">ទាញយកលិខិតសាកសួរ</a>
                                </div>
                                <div class="form-group col-sm-4">
                                    <a class="btn btn-info custom form-control mb-1" href="{{ url('export/word/collectives/invitation/'.$row->id."/2") }}" title="Download" target="_blank">ទាញយកលិខិតដោះស្រាយ</a>
                                </div>
                                <div class="form-group col-sm-4">
                                    <form name="frmDelete" action = "{{ url('collectives_invitations'.'/'.$id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="form-control btn btn-danger delete-btn">
                                            លុបលិខិតអញ្ជើញ
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="form-group col-sm-6">
                                    <a class="btn btn-info custom form-control mb-1" href="{{ url('export/word/collectives/invitation/'.$row->id) }}" title="Download" target="_blank">ទាញយកលិខិតសាកសួរ</a>
                                </div>
                                <div class="form-group col-sm-6">
                                    <a class="btn btn-info custom form-control mb-1" href="{{ url('export/word/collectives/invitation/'.$row->id."/2") }}" title="Download" target="_blank">ទាញយកលិខិតដោះស្រាយ</a>
                                </div>
                            @endif
                        @elseif($row->invitation_type_id == 8)
                            @if(count($row->case->log5) == 0)
                                <div class="form-group col-sm-4">
                                    <a class="btn btn-info custom form-control mb-1" href="{{ url('export/word/collectives/invitation/'.$row->id) }}" title="Download" target="_blank">ទាញយកលិខិតសាកសួរ</a>
                                </div>
                                <div class="form-group col-sm-4">
                                    <a class="btn btn-info custom form-control mb-1" href="{{ url('export/word/collectives/invitation/'.$row->id."/2") }}" title="Download" target="_blank">ទាញយកលិខិតដោះស្រាយ</a>
                                </div>
                                <div class="form-group col-sm-4">
                                    <form name="frmDelete" action = "{{ url('collectives_invitations'.'/'.$id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        {{--                                ."_".$adata['id_pair']--}}
                                        <button type="button" class="form-control btn btn-danger delete-btn">
                                            លុបលិខិតអញ្ជើញ
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="form-group col-sm-6">
                                    <a class="btn btn-info custom form-control mb-1" href="{{ url('export/word/collectives/invitation/'.$row->id) }}" title="Download" target="_blank">ទាញយកលិខិតសាកសួរ</a>
                                </div>
                                <div class="form-group col-sm-6">
                                    <a class="btn btn-info custom form-control mb-1" href="{{ url('export/word/collectives/invitation/'.$row->id."/2") }}" title="Download" target="_blank">ទាញយកលិខិតដោះស្រាយ</a>
                                </div>
                            @endif
                        @else
                            fsdafsadf
                        @endif
                    </div>

                    <form name="formCreateCase" action="{{ url('collectives_invitations/'.$row->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="case_id" value="{{ $row->case_id }}" >
                        <input type="hidden" name="case_year" value="{{ $caseYear }}">
{{--                        <input type="hidden" name="disputant_id" value="{{ $row->disputant_id }}" >--}}
                        <input type="hidden" name="company_id" value="{{ $row->company_id }}" >
                        <input type="hidden" name="invitation_type" value="{{ $row->invitation_type_id }}" >
                        <div class="card-body text-hanuman-17">
                            <div class="card-block row">
                                <div class="">
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="text-purple text-hanuman-24 required mb-1">លេខលិខិតអញ្ជើញ</label>
                                            {{--                                            <input for="invitation_number" type="text" name="invitation_number" value="{{ old('invitation_number', $invNum) }}" class="form-control" required>--}}
                                            <div class="d-flex">
                                                <input  type="text" name="inv_number" id="inv_number" value="{{ old('inv_number', $row->invitation_number) }}" class="form-control col-sm-2" required>
                                                {!! nbs(3) !!}
                                                <input  type="text"  id="inv_num_str" value="{{ $row->invitation_number." ក.ប/អ.ក/វ.ក"  }}" class="form-control col-sm-2" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    @if($row->invitationType->employee_or_company == 31)
                                        <div class="row col-12 mt-4">
                                            <label class="text-purple text-hanuman-24">
                                                1. តំណាងប្រតិភូចរចា (តំណាងកម្មករនិយោជិត)
                                            </label>
                                        </div>
                                        @if(count($row->case->collectivesRepresentatives) > 0)
                                            <div class="row">
                                                @foreach($row->case->collectivesRepresentatives as $cRepre)
                                                    <div class="form-group col-sm-3 mt-3">
                                                        {{--                                        <label class="mb-1">ឈ្មោះកម្មករ</label>--}}
                                                        <input type="text" value="{{ $cRepre->fullname }}" class="form-control" disabled />
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @else
                                        <div class="row">
                                            <div class="form-group col-sm-6 mt-3">
                                                <label class="fw-bold mb-1">ឈ្មោះសហគ្រាស គ្រឹះស្ថាន</label>
                                                <input type="text" name="company_name_khmer" id="company_name_khmer" value="{{ $row->company->company_name_khmer }}" class="form-control" disabled />
                                            </div>
                                            <div class="form-group col-sm-6 mt-3">
                                                <label class="fw-bold mb-1" for="company_name_latin">ឈ្មោះជាភាសាឡាតាំង</label>
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
                                    @endif
                                    <div class="row col-12 mt-4">
                                        <label class="text-purple text-hanuman-24">
                                            2. កាលបរិច្ឆេទអញ្ជើញមក (ផ្តល់ព័ត៌មាន, ផ្សះផ្សា និងដោះស្រាយ)
                                        </label>
                                    </div>
                                        <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold required mb-1">ថ្ងៃខែឆ្នាំជួប</label>
                                            <input type="text"  name="meeting_date" id="meeting_date" value="{{ old('meeting_date', date2Display($row->meeting_date)) }}" class="form-control"  data-language="en" required >
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold required mb-1">ម៉ោងជួប</label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input name="meeting_time" id="meeting_time" value="{{ old("meeting_time", date2Display($row->meeting_time, 'H:i')) }}"  class="form-control" type="text" data-bs-original-title="" required >
                                            </div>
                                        </div>
                                        </div>
                                    <div class="row col-12 mt-4">
                                        <label class="text-purple required text-hanuman-24">
                                            3. ប្រភេទលិខិតអញ្ជើញ
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            {!! showSelect('invitation_type_id', arrayInvitationType($row->case->case_type_id, $row->invitationType->employee_or_company, 1), old('invitation_type_id', $row->invitation_type_id), " select2") !!}
                                        </div>
                                    </div>
                                    @if($row->case->case_type_id == 3)
                                        <div class="row col-12 mt-4">
                                            <label class="text-purple text-hanuman-24">
                                                4. ចំណុចទាមទារ (Issues)
                                            </label>
                                        </div>
                                        @php $counterIssues = 1  @endphp
                                        @if(count($row->case->collectivesIssues) > 0)
                                            @foreach($row->case->collectivesIssues as $cIssue)
                                                <div class="row mt-1">
                                                    <div class="form-group col-12 mt-3 mb-3">
                                                        <label class="fw-bold mb-1 text-danger" for="issues1">* ចំណុចទាមទារទី<span>{{ Num2Unicode($counterIssues) }}</span></label>
                                                        <div class="d-flex align-items-center">
                                                            <textarea class="form-control" rows="4" style="flex: 1;" readonly >{{ $cIssue->issue }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                @php $counterIssues++ @endphp
                                            @endforeach
                                        @endif

                                    @else
                                        <div class="row col-12 mt-4">
                                            <label class="text-purple text-hanuman-24">
                                                4. មូលហេតុនៃវិវាទ
                                            </label>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-12 mt-3">
                                                {{--                                            <input type="text" name="reason" value="{{ $row->case_objective_des }}" class="form-control" disabled />--}}
                                                {!! showTextarea("reason", $row->case_objective_des, 4, "readonly") !!}
                                            </div>

                                        </div>
                                    @endif

                                    <div class="row col-12 mt-4">
                                        <label class="text-purple text-hanuman-24">
                                            5. ឯកសារភ្ជាប់
                                        </label>
                                    </div>
                                    <div class="row">

                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="fw-bold required mb-1">ឯកសារពាក់ព័ន្ធមាន</label>
                                            <input type="text" name="invitation_required_doc" id="invitation_required_doc" value="{{ old("invitation_required_doc", $row->invitation_required_doc) }}" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="row col-12 mt-4">
                                        <label class="text-purple required text-hanuman-24">
                                            6. កាលបរិច្ឆេទធ្វើលិខិតអញ្ជើញ
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-4 mt-3">
                                            <input type="text" name="letter_date" id="letter_date" value="{{ old('letter_date', date2Display($row->letter_date)) }}" class="form-control"  data-language="en"  >
                                        </div>
                                    </div>
                                    <div class="row col-12 mt-4">
                                        <label class="text-purple text-hanuman-24">
                                            7. លេខទូរស័ព្ទសម្រាប់ទំនាក់ទំនង
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-4 mt-3 mb-1">
                                            <label for="contact_phone" class="fw-bold mb-1">លេខទូរស័ព្ទទំនាក់ទំនង</label>
                                            <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $row->contact_phone) }}" class="form-control" >
                                        </div>
                                        <div class="form-group col-sm-4 mt-3 align-self-end">
                                            <input type="hidden" name="next_val" id="next_val" value="0" >
                                            <button id="btn_next" type="button" class="form-control btn btn-info">លើកពេលណាត់ជួប</button>
                                        </div>
                                    </div>
                                       @php
                                           $display = count($row->nextTime) > 0? "":"display: none;";
                                       @endphp
                                        <div id="div_next_label" class="row mt-3" style="{{ $display }}">
                                            <input type="hidden" id="count_next" value="{{ $display }}" >
                                            <div class="form-group col-sm-2">
                                                <label class="fw-bold">STATUS</label>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label class="fw-bold">មូលហេតុ</label>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label class="fw-bold">កាលបរិច្ឆេទជួប</label>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="fw-bold">ម៉ោងជួប</label>
                                            </div>
                                        </div>
                                        @php
                                            $arrayStatus = [1 => "សុំលើកពេល", 2 => "អវត្តមាន"];
                                            $j = 0;
                                            $nextTime = $row->nextTime->count();
                                        @endphp
                                            <div id="" class="row">
                                        @foreach($row->nextTime as $next)
{{--                                            @if($next->id !=27)--}}
{{--                                                {{ dd($next) }}--}}
{{--                                            @endif--}}
                                                <div class="form-group col-sm-2 mb-2">
                                                    <input type="hidden" name="next_id_old[]" id="{{ "next_id_old_".$j }}" value="{{ $next->id }}" >
                                                    {!! showSelect('status_old[]', $arrayStatus, old('status[]', $next->status_id),"status_class") !!}
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
                                                <div class="form-group col-sm-3">
{{--                                                    <label class="fw-bold">លិខិតសុំលើកពេល: </label>--}}
                                                    <input type="hidden" name="letter_old_old[]" value="{{ $next->letter }}" >
                                                    @php
                                                        $show_file= showFile(1, $next->letter, pathToDeleteFile('collectives_invitation/next/'.$caseYear."/"), "delete", "tbl_case_invitation_next_time", "id", $next->id,  "letter", "");
                                                        if($show_file){
                                                            echo $show_file;
                                                        }
                                                        else{
                                                            echo upload_file("letter_old[".$j."]", "សូមជ្រើសរើសឯកសារ (មានទំហំធំបំផុត 15MB)", "", "letter_old_".$next->id);
                                                        }
                                                    @endphp
                                                </div>
                                                <div class="form-group col-sm-1">
                                                    @php
                                                        $deleteUrl = url('invitation/delete/next/'.$row->id.'_'.$next->id);
                                                            $onClick = "comfirm_delete_steetalert2('".$deleteUrl."','តើអ្នកពិតជាចង់លុប មែនឫ?')";
                                                        $str2='<button type="button" class="btn btn-danger" onClick="'.$onClick.'" title="លុបចោល ការលើកពេល"><i data-feather="trash"></i></button>';
                                                        echo $str2;
                                                    @endphp
                                                </div>
                                            @php
                                                $j++;
                                            @endphp
                                        @endforeach
                                            </div>
                                        <div id="div_next_meeting_1" class="row mt-2" style="display: none;">
                                            <div class="form-group col-sm-2">
                                                {!! showSelect('status', $arrayStatus, old('status', 1),"status_class") !!}
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
                                            <div class="form-group col-sm-12 mt-4">
                                                <label class="fw-bold">លិខិតសុំលើកពេល: </label>
                                               {!! upload_file("letter", "សូមជ្រើសរើសឯកសារ (មានទំហំធំបំផុត 15MB)", "") !!}
                                            </div>
{{--                                            <div class="form-group col-sm-2 mt-1">--}}
{{--                                                <button type="button" id="btn_add_next_meeting" class="btn btn-info form-control">លើកពេលណាត់ជួប</button>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group col-sm-1 mt-1">--}}
{{--                                                <button type="button" id="btn_remove_next_meeting" class="btn btn-danger form-control">លុប</button>--}}
{{--                                            </div>--}}
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <button type="submit" name="btnSubmit" value="save" class="form-control btn btn-success">{{ __("btn.button_save") }}</button>

                                            </div>
                                            <div class="form-group col-sm-4">
                                                <button type="submit" name="btnSubmit" value="next" class="form-control btn btn-success">{{ __("btn.button_save2") }}</button>
                                            </div>
                                        </div>

                                        <div>

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
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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

        @include('script.sweetalert2')
        @include('script.my_sweetalert2')
        <!-- Plugins Datepicker-->
        <script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <!-- Plugins Timepicker-->
        <script src="{{ rurl('assets/js/time-picker/jquery-clockpicker.min.js') }}"></script>
        <script src="{{ rurl('assets/js/time-picker/highlight.min.js') }}"></script>
        <script src="{{ rurl('assets/js/time-picker/clockpicker.js') }}"></script>
        <!-- Plugins Select2-->
        <script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                var counter_next_meeting = 2;
                $('#case_type_id').select2();
                $('#company_type_id').select2();
                $('#case_objective_id').select2();

                $('#inv_number').on('change onkeypress', function() {
                    generateAutoInvNumber();
                });
                function generateAutoInvNumber(){
                    let invNum = $('#inv_number').val();

                    $("#inv_num_str").val(invNum + " ក.ប/អ.ក/វ.ក");
                }

                $('#dob').datepicker({
                    //language: 'en',
                    //dateFormat: 'dd-mm-yyyy',
                    // minDate: minDate // Now can select only dates, which goes after today
                    // ,maxDate: maxDate /// new Date("10/01/2023")
                });
                $("#contact_phone, #phone_number, #inv_number").keypress(function(event){
                    if (!(event.charCode >= 48 && event.charCode <= 57)){ // 0-9
                        event.preventDefault();
                        return false;
                    }
                    // if ((event.charCode >= 48 && event.charCode <= 57) || // 0-9
                    //     (event.charCode >= 65 && event.charCode <= 90) || // A-Z
                    //     (event.charCode >= 97 && event.charCode <= 122))  // a-z
                    //     alert("0-9, a-z or A-Z");
                });
                $('.next_date').datepicker({});
                $('#meeting_date').datepicker({});
                // $('#next_date_1').datepicker({});
                $('#receive_date').datepicker({});
                $('#letter_date').datepicker({});
                $('.status_class').select2();

                $("#dob, #meeting_date, #meeting_time, #receive_date, #receive_time, #letter_date").keydown(function(event) {
                    return false;
                });
                // alert($("#next_val").val());
                $("#btn_next").click(function() {
                    //alert("xx");
                    //var next_val = parseInt($("#next_val").val());
                    var nextTime = {{ $nextTime }};

                    if($("#next_val").val() == 0){
                        $("#next_val").val(1);
                        $("#div_next_meeting_1").show("fast");
                        $("#div_next_label").show("fast");
                    }else if($("#next_val").val() == 1){
                        $("#next_val").val(0);
                        $("#div_next_meeting_1").hide("fast");
                        if(nextTime === 0){
                            $("#div_next_label").hide("fast");
                        }

                        // if($("#count_next") == ""){
                        //     $("#div_next_label").hide("fast");
                        // }

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
