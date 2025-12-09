@php
    $row = $adata['case'];
    $invNum = sprintf('%03d', $adata['invCountPlus1']);
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
                    <form name="formCreateCase" action="{{ url('collectives_invitations') }}" method="POST" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <input type="hidden" name="case_id" value="{{ $adata['case_id'] }}" >
{{--                        <input type="hidden" name="disputant_id" value="{{ $row->disputant_id }}" >--}}
                        <input type="hidden" name="company_id" value="{{ $row->company_id }}" >
                        <input type="hidden" name="invitation_type_id" value="{{ $row->invitation_type_id }}" >
                        <div class="card-body text-hanuman-17">
                            <div class="card-block row">
                                <div class="col-sm-12 col-lg-12 col-xl-12">
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="text-purple text-hanuman-24 required mb-1">លេខលិខិតអញ្ជើញ</label>
{{--                                            <input for="invitation_number" type="text" name="invitation_number" value="{{ old('invitation_number', $invNum) }}" class="form-control" required>--}}
                                            <div class="d-flex">
                                                <input  type="text" name="inv_number" id="inv_number" value="{{ old('inv_number', $invNum) }}" class="form-control col-sm-2" required>
                                                {!! nbs(3) !!}
                                                <input  type="text"  id="inv_num_str" value="{{ $invNum." ក.ប/អ.ក/វ.ក"  }}" class="form-control col-sm-2" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    @if($adata['employee_or_company'] == 1)
                                    <div class="row col-12 mt-4">
                                        <label class="text-purple text-hanuman-24">
                                            1. កម្មករនិយោជិត
                                        </label>
                                    </div>
                                    <div class="row">
                                        @if(!empty($row->caseDisputant->phone_number2))
                                            <div class="form-group col-sm-4 mt-3">
                                                <label>ឈ្មោះកម្មករនិយោជិត</label>
                                                <input type="text" value="{{ $row->disputant->name }} {{ $row->disputant->name_latin }}" class="form-control" disabled />
                                            </div>
                                            @php
                                                $gender = $row->disputant->gender == 1? "ប្រុស":"ស្រី"
                                            @endphp
                                            <div class="form-group col-sm-4 mt-3">
                                                <label>ភេទ</label>
                                                <input type="text" value="{{ $gender }}" class="form-control" disabled />
                                            </div>

                                            <div class="form-group col-sm-4 mt-3">
                                                <label>មុខងារ</label>
                                                <input type="text" value="{{ $row->caseDisputant->occupation }}" class="form-control" disabled />
                                            </div>
                                            <div class="form-group col-sm-6 mt-3">
                                                <label>លេខទូរស័ព្ទ (ខ្សែទី១)</label>
                                                <input type="text" value="{{ $row->caseDisputant->phone_number }}" class="form-control" disabled />
                                            </div>
                                            <div class="form-group col-sm-6 mt-3">
                                                <label>លេខទូរស័ព្ទ (ខ្សែទី២)</label>
                                                <input type="text" value="{{ $row->caseDisputant->phone_number2 }}" class="form-control" disabled />
                                            </div>
                                        @else
                                            <div class="form-group col-sm-3 mt-3">
                                                <label>ឈ្មោះកម្មករនិយោជិត</label>
                                                <input type="text" value="{{ $row->disputant->name }} {{ $row->disputant->name_latin }}" class="form-control" disabled />
                                            </div>
                                            @php
                                                $gender = $row->disputant->gender == 1? "ប្រុស":"ស្រី"
                                            @endphp
                                            <div class="form-group col-sm-3 mt-3">
                                                <label>ភេទ</label>
                                                <input type="text" value="{{ $gender }}" class="form-control" disabled />
                                            </div>

                                            <div class="form-group col-sm-3 mt-3">
                                                <label>មុខងារ</label>
                                                <input type="text" value="{{ $row->caseDisputant->occupation }}" class="form-control" disabled />
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label>លេខទូរស័ព្ទ</label>
                                                <input type="text" value="{{ $row->caseDisputant->phone_number }}" class="form-control" disabled />
                                            </div>
                                        @endif

                                    </div>
                                    @elseif($adata['employee_or_company'] == 2 || $adata['employee_or_company'] == 32)
                                    <div class="row col-12 mt-4">
                                        <label class="text-purple text-hanuman-24">
                                            1. សហគ្រាស គ្រឹះស្ថាន
                                        </label>
                                    </div>
                                        <div class="row">
                                            @if(!empty($row->caseCompany->log5_company_phone_number2))
                                                <div class="form-group col-sm-4 mt-3">
                                                    <label class="fw-bold mb-1">ឈ្មោះសហគ្រាស គ្រឹះស្ថាន</label>
                                                    <input type="text" name="company_name_khmer" id="company_name_khmer" value="{{ $row->company->company_name_khmer }}" class="form-control" disabled />
                                                </div>
                                                <div class="form-group col-sm-4 mt-3">
                                                    <label class="fw-bold mb-1" for="company_name_latin">ឈ្មោះជាភាសាឡាតាំង</label>
                                                    <input type="text" id="company_name_latin" value="{{ $row->company->company_name_latin }}" class="form-control" readonly />
                                                </div>
                                                <div class="form-group col-sm-4 mt-3">
                                                    <label class="fw-bold mb-1">អាសយដ្ឋាន</label>
                                                    <input type="text" value="{{ $row->caseCompany->province->pro_khname }}" class="form-control" readonly />
                                                </div>
                                                <div class="form-group col-sm-3 mt-3">
                                                    <label class="fw-bold mb-1">លេខទូរស័ព្ទ (ខ្សែទី១)</label>
                                                    <input type="text" value="{{ $row->caseCompany->log5_company_phone_number }}" class="form-control" readonly />
                                                </div>
                                                <div class="form-group col-sm-3 mt-3">
                                                    <label class="fw-bold mb-1">លេខទូរស័ព្ទ (ខ្សែទី២)</label>
                                                    <input type="text" value="{{ $row->caseCompany->log5_company_phone_number2 }}" class="form-control" readonly />
                                                </div>
                                                <div class="form-group col-sm-3 mt-3">
                                                    <label class="fw-bold mb-1">លេខ TIN</label>
                                                    <input type="text" value="{{ $row->company->company_tin }}" class="form-control" readonly />
                                                </div>
                                                <div class="form-group col-sm-3 mt-3">
                                                    <label class="fw-bold mb-1">លេខចុះបញ្ជីពាណិជ្ជកម្ម</label>
                                                    <input type="text" value="{{ $row->company->company_register_number }}" class="form-control" readonly />
                                                </div>
                                            @else
                                                <div class="form-group col-sm-6 mt-3">
                                                    <label class="fw-bold mb-1">ឈ្មោះសហគ្រាស គ្រឹះស្ថាន</label>
                                                    <input type="text" name="company_name_khmer" id="company_name_khmer" value="{{ $row->company->company_name_khmer }}" class="form-control" disabled />
                                                </div>
                                                <div class="form-group col-sm-6 mt-3">
                                                    <label class="fw-bold mb-1" for="company_name_latin">ឈ្មោះជាភាសាឡាតាំង</label>
                                                    <input type="text" id="company_name_latin" value="{{ $row->company->company_name_latin }}" class="form-control" readonly />
                                                </div>
                                                <div class="form-group col-sm-3 mt-3">
                                                    <label class="fw-bold mb-1">អាសយដ្ឋាន</label>
                                                    <input type="text" value="{{ $row->caseCompany->province->pro_khname }}" class="form-control" readonly />
                                                </div>
                                                <div class="form-group col-sm-3 mt-3">
                                                    <label class="fw-bold mb-1">លេខទូរស័ព្ទ</label>
                                                    <input type="text" value="{{ $row->caseCompany->log5_company_phone_number }}" class="form-control" readonly />
                                                </div>
                                                <div class="form-group col-sm-3 mt-3">
                                                    <label class="fw-bold mb-1">លេខ TIN</label>
                                                    <input type="text" value="{{ $row->company->company_tin }}" class="form-control" readonly />
                                                </div>
                                                <div class="form-group col-sm-3 mt-3">
                                                    <label class="fw-bold mb-1">លេខចុះបញ្ជីពាណិជ្ជកម្ម</label>
                                                    <input type="text" value="{{ $row->company->company_register_number }}" class="form-control" readonly />
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($adata['employee_or_company'] == 31)
                                        <div class="row col-12 mt-4">
                                            <label class="text-purple text-hanuman-24">
                                                1. តំណាងប្រតិភូចរចា (តំណាងកម្មករនិយោជិត)
                                            </label>
                                        </div>
                                        @if(count($row->collectivesRepresentatives) > 0)
                                            <div class="row">
                                                @foreach($row->collectivesRepresentatives as $cRepre)
                                                    <div class="form-group col-sm-3 mt-3">
                                                        {{--                                        <label class="mb-1">ឈ្មោះកម្មករ</label>--}}
                                                        <input type="text" value="{{ $cRepre->fullname }}" class="form-control" disabled />
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                    <div class="row col-12 mt-4">
                                        <label class="text-purple text-hanuman-24">
                                            2. កាលបរិច្ឆេទអញ្ជើញមក (ផ្តល់ព័ត៌មាន, ផ្សះផ្សា និងដោះស្រាយ)
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1 required">ថ្ងៃខែឆ្នាំជួប</label>
                                            <input type="text"  name="meeting_date" id="meeting_date" value="{{ old('meeting_date') }}" class="form-control"  data-language="en" required >
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1 required">ម៉ោងជួប</label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input name="meeting_time" id="meeting_time" value="{{ old("meeting_time") }}"  class="form-control" type="text" data-bs-original-title="" required >
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
                                            {!! showSelect('invitation_type_id', arrayInvitationType($row->case_type_id, $adata['employee_or_company'], 1), old('invitation_type_id'), " select2") !!}
{{--                                            arrayInvitationType($row->case_type_id, $adata['employee_or_company']--}}
                                        </div>
                                    </div>
                                    @if($row->case_type_id == 3)
                                        <div class="row col-12 mt-4">
                                            <label class="text-purple text-hanuman-24">
                                                4. ចំណុចទាមទារ (Issues)
                                            </label>
                                        </div>
                                        @php $counterIssues = 1  @endphp
                                        @if(count($row->collectivesIssues) > 0)
                                            @foreach($row->collectivesIssues as $cIssue)
                                                <div class="row col-12 mt-1">
                                                    <div class="form-group col-12 mt-3 mb-3">
                                                        <label class="fw-bold mb-2 text-danger" for="issues1">*ចំណុចទាមទារទី<span>{{ Num2Unicode($counterIssues) }}</span></label>
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
                                    <div class="'row">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="fw-bold mb-1 required">ឯកសារពាក់ព័ន្ធមាន</label>
                                            <input type="text" name="invitation_required_doc" id="invitation_required_doc" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="row col-12 mt-4">
                                        <label class="text-purple required text-hanuman-24">
                                            6. កាលបរិច្ឆេទធ្វើលិខិតអញ្ជើញ
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <input type="text" name="letter_date" id="letter_date" value="{{ old('letter_date', myDate("d-m-Y")) }}" class="form-control"  data-language="en"  >
                                        </div>
                                    </div>

                                    <div class="row col-12 mt-4">
                                        <label class="text-purple text-hanuman-24">
                                            7. លេខទូរស័ព្ទសម្រាប់ទំនាក់ទំនង
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}" class="form-control" minlength="9" maxlength="10" >
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="form-group col-sm-6">
                                            <button type="submit" class="btn btn-success form-control">រក្សាទុក</button>
                                        </div>
                                    </div>
                                <div>
                                </div>
                                    <br/>
                                    <div class="row">

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
