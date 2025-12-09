@php
    $case = $adata['case'];
    $invNumEmp = sprintf('%03d', $adata['invCountPlus1']);
    $invNumCom = sprintf('%03d', $adata['invCountPlus1'] + 1);
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
                    <form name="formCreateBothInv" action="{{ url('collectives_invitation/store_both') }}" method="POST" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <input type="hidden" name="case_id" value="{{ $adata['case_id'] }}" >
                        <input type="hidden" name="invitation_type_employee" value="{{ $adata['invitation_type_employee'] }}" >
                        <input type="hidden" name="invitation_type_company" value="{{ $adata['invitation_type_company'] }}" >
                        <input type="hidden" name="disputant_id" value="{{ $case->disputant_id }}" >
                        <input type="hidden" name="company_id" value="{{ $case->company_id }}" >

                        <div class="card-body text-hanuman-17">
                            <div class="card-block">
                                <div class="row">
                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="text-purple text-hanuman-24 required mb-1">លេខលិខិតអញ្ជើញដើមចោទ</label>
                                        {{--                                            <input for="invitation_number" type="text" name="invitation_number" value="{{ old('invitation_number', $invNum) }}" class="form-control" required>--}}
                                        <div class="d-flex">
                                            <input  type="text" name="inv_num_emp" id="inv_num_emp" value="{{ old('inv_num_emp', $invNumEmp) }}" class="form-control col-sm-2" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="text-purple text-hanuman-24 required mb-1">លេខលិខិតអញ្ជើញចុងចោទ</label>
                                        {{--                                            <input for="invitation_number" type="text" name="invitation_number" value="{{ old('invitation_number', $invNum) }}" class="form-control" required>--}}
                                        <div class="d-flex">
                                            <input  type="text" name="inv_num_com" id="inv_num_com" value="{{ old('inv_num_com', $invNumCom) }}" class="form-control col-sm-2" required>
                                        </div>
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

                                <div class="row">
                                    <div class="form-group col-sm-5 mt-3">
                                        <label class="fw-bold mb-1">ឈ្មោះសហគ្រាស គ្រឹះស្ថាន</label>
                                        <input type="text" name="company_name_khmer" id="company_name_khmer" value="{{ $case->company->company_name_khmer }} {{ $case->company->company_name_latin }}" class="form-control" disabled />
                                    </div>

                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">លេខទូរស័ព្ទ</label>
                                        <input type="text" value="{{ $case->caseCompany->log5_company_phone_number }}" class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-2 mt-3">
                                        <label class="fw-bold mb-1">អាសយដ្ឋាន</label>
                                        <input type="text" value="{{ $case->caseCompany->province->pro_khname }}" class="form-control" disabled />
                                    </div>
                                    <div class="form-group col-sm-3 mt-3">
                                        <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                        <input type="text" value="{{ $case->caseCompany->district->dis_khname }}" class="form-control" disabled />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="fw-bold mb-1 required">កាលបរិច្ឆេទមកផ្សះផ្សា</label>
                                        <input type="text"  name="meeting_date" id="meeting_date" value="{{ old('meeting_date') }}" class="form-control"  data-language="en" required >
                                    </div>
                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="fw-bold mb-1 required">ម៉ោងជួប</label>
                                        <div class="input-group clockpicker" data-autoclose="true">
                                            <input name="meeting_time" id="meeting_time" value="{{ old("meeting_time") }}"  class="form-control" type="text" data-bs-original-title="" required >
                                        </div>
                                    </div>
{{--                                        <div class="form-group col-sm-4 mt-3">--}}
{{--                                            <label class="fw-bold required">ប្រភេទលិខិតអញ្ជើញ</label>--}}
{{--                                            {!! showSelect('invitation_type_id', arrayInvitationType($row->case_type_id, $adata['employee_or_company']), old('invitation_type_id'), " select2") !!}--}}
{{--                                        </div>--}}
                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="fw-bold mb-1 required">ឯកសារពាក់ព័ន្ធសម្រាប់កម្មកនិយោជិតមាន</label>
                                        <input type="text" name="invitation_required_doc_employee" id="invitation_required_doc_employee" class="form-control" />
                                    </div>
                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="fw-bold mb-1 required">ឯកសារពាក់ព័ន្ធសម្រាប់សហគ្រាស គ្រឹះស្ថានមាន</label>
                                        <input type="text" name="invitation_required_doc_company" id="invitation_required_doc_company" class="form-control" />
                                    </div>
                                    <div class="form-group col-sm-4 mt-3">
                                        <label  for="letter_date" class="fw-bold mb-1 required">ថ្ងៃខែឆ្នាំបង្កើតលិខិតអញ្ជើញ</label>
                                        <input type="text" name="letter_date" id="letter_date" value="{{ old('letter_date', myDate("d-m-Y")) }}" class="form-control"  data-language="en"  >
                                    </div>
                                    <div class="form-group col-sm-4 mt-3">
                                        <label class="fw-bold mb-1" for="contact_phone">លេខទូរស័ព្ទទំនាក់ទំនង</label>
                                        <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}" class="form-control" id="contact_phone" >
                                    </div>
                                    <div class="form-group col-sm-4 mt-3">
                                        <label class="fw-bold mb-1" style="visibility: hidden" for="contact_phone">រក្សាទុក</label>
                                        <button type="submit" class="btn btn-success form-control fw-bold">រក្សាទុក</button>
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
        @include('case.script.invitation_script')

    </x-slot>
</x-admin.layout-main>
