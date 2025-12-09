@php
    $case = $adata['case'] ?? null;
    $cYear = myDate('y');
    $arrCaseType = $adata['arrCaseType'];
    $arrSector = $adata['arrSector'];
    $arrCompanyType = $adata['arrCompanyType'];
    $arrProvince = $adata['arrProvince'];
    $arrNationality = $adata['arrNationality'];
    $arrObjectiveCase = $adata['arrObjectiveCase'];
    $arrContractType = $adata['arrContractType'];
    $arrNightWork = $adata['arrNightWork'];
    $arrHolidayWeek = $adata['arrHolidayWeek'];
    $arrHolidayYear = $adata['arrHolidayYear'];
    $arrOfficersInHand = $adata['arrOfficersInHand'];

@endphp
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/date-picker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/timepicker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <style>
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
                    <div class="card-body progress-showcase row">
                        <div class="col">
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar bg-success fw-bold text-hanuman-16" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                    ជំហានទី៣ (ព័ត៌មាន លម្អិតនៃពាក្យបណ្តឹង)
                                </div>
                            </div>
                        </div>
                    </div>
                    <form name="formCreateCase" id="frmCaseCreated" action="{{ url('cases') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                        @method('POST')
                        @csrf
                        <div class="card-body text-hanuman-17">
                            <div class="card-block row">
                                <div class="col-sm-12 col-lg-12 col-xl-12">
                                    <div class="row col-12">
                                        <label class="text-purple text-hanuman-24">
                                            3. កម្មវត្ថុ
                                        </label>
                                    </div>
                                    <div class="row col-12 mt-2">
                                        <label class="text-pink text-hanuman-20">
                                            -កម្មវត្ថុបណ្ដឹង
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold required mb-1">កម្មវត្ថុបណ្ដឹង</label>
                                            {!! showSelect('case_objective_id', $arrObjectiveCase, old('case_objective_id', request('case_objective_id')), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1">កម្មវត្ថុបណ្ដឹងផ្សេងៗ</label>
                                            <input type="text" name="case_ojective_other" id="case_ojective_other" value="{{ old('case_ojective_other') }}" class="form-control" />
                                        </div>

                                    </div>
                                    <div class="row col-12 mt-4">
                                        <label class="text-pink text-hanuman-20">
                                            -ការផ្ដាច់កិច្ចសន្យា
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1">ថ្ងៃខែឆ្នាំផ្ដាច់កិច្ចសន្យាការងារ</label>
                                            <input type="text"  name="terminated_contract_date" id="terminated_contract_date" value="{{ old('terminated_contract_date') }}" class="form-control"  data-language="en">
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1">ម៉ោងផ្ដាច់កិច្ចសន្យាការងារ</label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input name="terminated_contract_time" id="terminated_contract_time" value="{{ old("terminated_contract_time") }}"  class="form-control" type="text" data-bs-original-title="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="text-pink text-hanuman-20 mb-2">
                                                -អង្គហេតុនៃវិវាទ
                                            </label>
                                            {!! showTextarea("case_objective_des", old('case_objective_des')) !!}
                                        </div>
                                    </div>
                                    <div class="row col-12 mt-4">
                                        <label class="text-purple text-hanuman-24">
                                            4. កិច្ចសន្យាការងារ និងលក្ខខណ្ឌការងារ
                                        </label>
                                    </div>
                                    <div class="row col-12 mt-4">
                                        <label class="text-pink text-hanuman-20">
                                            -កិច្ចសន្យាការងារ
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold required mb-1">ថ្ងៃខែឆ្នាំចូលបម្រើការងារ</label>
                                            <input type="text"  name="disputant_sdate_work" id="disputant_sdate_work" value="{{ old('disputant_sdate_work') }}" class="form-control"  data-language="en" required>
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold required mb-1">ប្រភេទកិច្ចសន្យាការងារ</label>
                                            {!! showSelect('disputant_contract_type', $arrContractType, old('disputant_contract_type', request('disputant_contract_type')), " select2", "", "", "required") !!}
                                        </div>
                                    </div>
                                    <div class="row col-12 mt-4">
                                        <label class="text-pink text-hanuman-20">
                                            -ថិរវេលាធ្វើការ និងប្រាក់ឈ្នួល
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="mb-2 fw-bold required">ចំនួនម៉ោងធ្វើការក្នុងមួយថ្ងៃ</label>
                                            <input type="number" step="0.01" name="disputant_work_hour_day" id="disputant_work_hour_day" value="{{ old('disputant_work_hour_day') }}" class="form-control" required/>
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="mb-2 fw-bold required">ចំនួនម៉ោងធ្វើការក្នុងមួយសប្ដាហ៍</label>
                                            <input type="number" step="0.01"  name="disputant_work_hour_week" id="disputant_work_hour_week" value="{{ old('disputant_work_hour_week') }}" class="form-control" required/>
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold mb-2">ប្រាក់ឈ្នួលប្រចាំខែ (ដុល្លារ)</label>{!! myToolTip() !!}
                                            <input type="number" step="0.01" name="disputant_salary" id="disputant_salary" value="{{ old('disputant_salary') }}" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="row col-12 mt-4">
                                        <label class="text-pink text-hanuman-20">
                                            -លក្ខខណ្ឌការងារ
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold required mb-1">ការងារវេនយប់</label>
                                            {!! showSelect('disputant_night_work', $arrNightWork, old('disputant_night_work', request('disputant_night_work')), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold required mb-1">ការឈប់សម្រាកប្រចាំសប្ដាហ៍</label>
                                            {!! showSelect('disputant_holiday_week', $arrHolidayWeek, old('disputant_holiday_week', request('disputant_holiday_week')), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-4 mt-3">
                                            <label class="fw-bold required mb-1">ថ្ងៃបុណ្យជាតិ  និងការឈប់សម្រាកប្រចាំឆ្នាំដោយមានប្រាក់ឈ្នួល</label>
                                            {!! showSelect('disputant_holiday_year', $arrHolidayYear, old('disputant_holiday_year', request('disputant_holiday_year')), " select2", "", "", "required") !!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="text-pink text-hanuman-20 mb-2">
                                                -មូលហេតុចម្បងនៃវិវាទ
                                            </label>
                                            {!! showTextarea("case_first_reason", old('case_first_reason')) !!}
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            5. សំណូមពរ
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-3">
                                            <label class="fw-bold mb-1">សំណូមពររបស់អ្នកប្ដឹង</label>
                                            {!! showTextarea("disputant_request", old('disputant_request')) !!}
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            6. កាលបរិច្ឆេទ
                                        </label>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="form-group col-sm-6">
                                            <label class="fw-bold required mb-1">កាលបរិច្ឆេទធ្វើបណ្ដឹង</label>
                                            <input type="text"  name="case_date" id="case_date" value="{{ old('case_date') }}" class="form-control"  data-language="en" required>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="fw-bold mb-1 required">កាលបរិច្ឆេទប្តឹងទៅអធិការការងារ</label>
                                            <input type="text"  name="case_date_entry" id="case_date_entry" value="{{ old('case_date_entry') }}" class="form-control"  data-language="en" required>
                                        </div>
                                    </div>

                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            7. មន្ត្រីទទួលបន្ទុក
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1 required">អ្នកផ្សះផ្សា</label>
{{--                                            {!! showSelect('officer_id', arrayOfficer(0,1, ""), old('officer_id'), " select2", "", "", "") !!}--}}
                                            {!! showSelect('officer_id', $arrOfficersInHand, old('officer_id'), " select2", "", "", "required") !!}
                                        </div>
                                        <div class="form-group col-sm-6 mt-3">
                                            <label class="fw-bold mb-1">លេខាកត់ត្រា</label>
{{--                                            {!! showSelect('officer_id8', arrayOfficer(0,1, ""), old('officer_id8'), " select2", "", "", "") !!}--}}
                                            {!! showSelect('officer_id8', $arrOfficersInHand, old('officer_id8'), " select2", "", "", "") !!}
                                        </div>
                                    </div>
                                    <div class="row col-12  mt-5">
                                        <label class="text-purple text-hanuman-24">
                                            8. Upload ឯកសារពាក្យបណ្ដឹង
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 mt-4">
                                            {!! upload_file("case_file", "សូមជ្រើសរើសឯកសារ(មានទំហំធំបំផុត 5MB)") !!}
                                        </div>
                                    </div>
                                    <br/><br/>
                                    <div class="row">
                                        @if(!empty($case))
                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-success form-control fw-bold">រក្សាទុកពាក្យបណ្តឹង</button>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <a href="{{ route('cases.create.step2') }}" class="btn btn-primary form-control fw-bold">ត្រលប់ (ជំហ៊ានទី២)</a>
                                        </div>
                                        @endif
                                        <div class="form-group col-md-3">
                                            <a href="{{ route('cases.create.step1') }}" class="btn btn-secondary form-control fw-bold">ត្រលប់ (ជំហ៊ានទី១)</a>
                                        </div>
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
        @include('case.script.case_script')
    </x-slot>
</x-admin.layout-main>
