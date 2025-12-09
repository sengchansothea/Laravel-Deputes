@php
    $case = $adata['case'];
    $com = $adata['company'];
    $caseCom = $adata['caseCom'];
    $comAPI = $adata['companyAPI'];
    $caseYear = date2Display($case->case_date, "Y");
    $arrOfficersInHand = arrayOfficerCaseInHand(0, 1);

    $csic1 = !empty($caseCom->log5_csic_1) ? $caseCom->log5_csic_1
            : (!empty($com->csic_1) ? $com->csic_1
            : (!empty($comAPI->csic_1) ? $comAPI->csic_1 : ""));

    $csic2 = $caseCom->log5_csic_2 ?? $com->csic_2 ?? $comAPI->csic_2 ?? "";
    $csic3 = $caseCom->log5_csic_3 ?? $com->csic_3 ?? $comAPI->csic_3 ?? "";
    $csic4 = $caseCom->log5_csic_4 ?? $com->csic_4 ?? $comAPI->csic_4 ?? "";
    $csic5 = $caseCom->log5_csic_5 ?? $com->csic_5 ?? $comAPI->csic_5 ?? "";

    $busActivity = !empty($caseCom->log5_business_activity) ? $caseCom->log5_business_activity : $comAPI->business_activity ?? "";

    $arrCSIC2 = !empty($csic1) ? arrCSIC2($csic1) : array('0' => 'សូមជ្រើសរើស');
    $arrCSIC3 = !empty($csic2) ? arrCSIC3($csic1, $csic2) : array('0' => 'សូមជ្រើសរើស');
    $arrCSIC4 = !empty($csic3) ? arrCSIC4($csic1, $csic2, $csic3) : array('0' => 'សូមជ្រើសរើស');
    $arrCSIC5 = !empty($csic4) ? arrCSIC5($csic1, $csic2, $csic3, $csic4) : array('0' => 'សូមជ្រើសរើស');
@endphp
{{--{{ dd($comAPI) }}--}}
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/date-picker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/timepicker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <form class="" name="frm_case_closed" id="frm_case_closed" action="{{ url('close/case') }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <input type="hidden" name="case_id" value="{{ $case->id }}"  id="case_id">
                <input type="hidden" name="case_year" value="{{ $caseYear }}"  id="case_year">
                <input type="hidden" name="company_id" value="{{ $caseCom->company_id }}"  id="company_id">
                <div class="card">
                    <div class="card-body">
                        <div class="card-block row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="row col-12  mt-5">
                                    <label class="text-purple text-hanuman-24">
                                        ក. ស្ថានភាពសំណុំរឿង
                                    </label>
                                </div>
                                <div class="col">
                                    <div class="m-checkbox-inline custom-radio-ml text-hanuman-20 blue text-center col-sm-12">
                                        <div class="form-check form-check-inline radio radio-primary">
                                            <input class="form-check-input" id="case_close_0" type="radio" name="case_closed" value="{{ old('case_closed', 0) }}">
                                            <label class="form-check-label" for="case_close_0">កំពុងដំណើរការ</label>
                                        </div>
                                        <div class="form-check form-check-inline radio radio-danger ms-4">
                                            <input class="form-check-input mt-0" id="case_close_1" type="radio" name="case_closed" value="{{ old('case_closed', 1) }}" checked>
                                            <label class="form-check-label text-danger" for="case_close_1">បញ្ចប់សំណុំរឿង</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row col-12  mt-5">
                                    <label class="text-purple text-hanuman-24 required">
                                        ខ. ថ្ងៃខែឆ្នាំបិទបញ្ចប់សំណុំរឿង
                                    </label>
                                </div>
                                <div class="form-group col-sm-3 mt-3">
{{--                                    <label>កាលបរិច្ឆេទបិទបញ្ចប់សំណុំរឿង</label>--}}
                                    <input type="text"  name="case_closed_date" id="case_closed_date" value="{{ old('case_closed_date', !empty($case->case_closed_date) ? date2Display($case->case_closed_date) : "") }}" class="form-control"  data-language="en" required>
                                    @error('case_closed_date')
                                        <div>{!! textRed($message) !!}</div>
                                    @enderror
                                </div>
                                <div class="row col-12  mt-5">
                                    <label class="text-purple text-hanuman-24 required">
                                        គ. សំណុំរឿងបិទបញ្ចប់នៅដំណាក់កាល
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 mt-3">
                                        {!! showSelect('case_closed_step_id', arrayCaseSteps(1, ""), old('case_closed_step_id', $case->case_closed_step_id), "select2", "", "", "required") !!}
                                    </div>
                                </div>

                                <div class="row col-12  mt-5">
                                    <label class="text-purple text-hanuman-24 required">
                                        ឃ. មូលហេតុសំខាន់នៃវិវាទ
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 mt-3">
                                        {!! showSelect('case_cause_id', arrayLog624(1, ""), old('case_cause_id', $case->case_cause_id), " select2", "", "", "required") !!}
                                    </div>
                                    <div class="form-group col-sm-12 mt-5">
                                        <label class="mb-3">បើសិនជ្រើសរើស (បញ្ហាដទៃទៀត) </label>
                                        {!! showTextarea("case_cause_other", $case->case_cause_other) !!}
                                    </div>
                                </div>
                                <div class="row col-12 mt-5">
                                    <label class="text-purple text-hanuman-24 required">
                                        ង. ដំណោះស្រាយនៃវិវាទ
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 mt-3">
                                        {!! showSelect('case_solution_id', arrayLog625(1, ""), old('case_solution_id', $case->case_solution_id), " select2", "", "", "required") !!}
                                    </div>
                                </div>
                                <div class="row col-12  mt-5">
                                    <label class="text-purple text-hanuman-24">
                                        ច. បរិយាយនៃវិវាទ
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 mt-3">
                                        {!! showTextarea("case_closed_description", old('case_closed_description', $case->case_closed_description),10) !!}

                                    </div>
                                </div>
                                <div class="row col-12  mt-5">
                                    <label class="text-purple text-hanuman-24">
                                        ឆ. លទ្ធផល
                                    </label>
                                </div>

                                <div class="col">
                                    <div class="m-checkbox-inline custom-radio-ml text-hanuman-20 blue text-center col-sm-12">
                                        <div class="form-check form-check-inline radio radio-primary">
                                            <input class="form-check-input" id="case_closed_result_1" type="radio" name="case_closed_result" value="{{ old('case_closed_result', 1) }}" @if($case->case_closed_result == 1 || $case->case_closed_result == 0 ) {{ "checked" }} @endif>
                                            <label class="form-check-label" for="case_closed_result_1">សះជា</label>
                                        </div>
                                        <div class="form-check form-check-inline radio radio-danger ms-4">
                                            <input class="form-check-input mt-0" id="case_closed_result_2" type="radio" name="case_closed_result" value="{{ old('case_closed_result', 2) }}" @if($case->case_closed_result == 2) {{ "checked" }} @endif>
                                            <label class="form-check-label text-warning" for="case_closed_result_2">មិនសះជា</label>
                                        </div>
                                        <div class="form-check form-check-inline radio radio-danger ms-4">
                                            <input class="form-check-input mt-0" id="case_closed_result_3" type="radio" name="case_closed_result" value="{{ old('case_closed_result', 3) }}" @if($case->case_closed_result == 3) {{ "checked" }} @endif>
                                            <label class="form-check-label text-danger" for="case_closed_result_3">មោឃៈ</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row col-12  mt-5">
                                    <label class="text-purple text-hanuman-24">
                                        ជ. សកម្មភាពសេដ្ឋកិច្ច
                                    </label>
                                </div>
{{--                                    {{ dd(arrCSIC2('A')) }}--}}
{{--                                {{ dd(arrCSIC3('A', '01')) }}--}}
{{--                                {{ dd(arrayBusinessActivity1()) }}--}}
{{--                                {{ dd(arrCSIC4('A', '01', '013')) }}--}}
{{--                                {{ dd(arrCSIC5('A', '01', '011', '0111')) }}--}}
                                <div class="row mt-2">
{{--                                    <div class="form-group col-sm-6 mt-3">--}}
{{--                                        <label for="sector_id" class="fw-bold required mb-1 blue">សកម្មភាពអាជីវកម្ម</label>--}}
{{--                                        {!! showSelect('business_activity', arrayBusinessActivity(1,0), old('business_activity', $busActivity), " select2","","","") !!}--}}
{{--                                    </div>--}}
                                    <div class="form-group col-sm-12 mt-3">
                                        <label for="sector_id" class="fw-bold required mb-1 blue">សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី១</label>
                                        {!! showSelect('csic_1', arrCSIC1(), old('csic_1', $csic1), " select2", "", "csic1", "") !!}
                                    </div>

                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="fw-bold required mb-1 blue">សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី២</label>
                                        {!! showSelect('csic_2', $arrCSIC2, old('csic_2', $csic2), " select2", "", "csic2", "") !!}

                                    </div>
                                    <div class="form-group col-sm-6 mt-3">
                                        <label for="sector_id" class="fw-bold required mb-1 blue">សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៣</label>
                                        {!! showSelect('csic_3', $arrCSIC3, old('csic_3', $csic3), " select2", "", "csic3", "") !!}

                                    </div>

                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="fw-bold required mb-1 blue">សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៤</label>
                                        {!! showSelect('csic_4', $arrCSIC4, old('csic_4', $csic4), " select2", "", "csic4", "") !!}

                                    </div>
                                    <div class="form-group col-sm-6 mt-3">
                                        <label class="fw-bold required mb-1 blue">សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៥</label>
                                        {!! showSelect('csic_5', $arrCSIC5, old('csic_5', $csic5), " select2", "", "csic5", "") !!}
                                    </div>
                                </div>
                                <div class="row col-12  mt-5">
                                    <label class="text-purple text-hanuman-24">
                                        ឈ. Upload ឯកសារយោង
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-4 mt-4">
                                        <input type="hidden" name="case_closed_file_old" value="{{ $case->case_closed_file }}" >
                                        @php
                                            $show_file = showFile(1, $case->case_closed_file, pathToDeleteFile('case_doc/closed/'.$caseYear."/"), "delete", "tbl_case", "id", $case->id,  "case_closed_file");
                                            if($show_file){
                                                echo $show_file;
                                            }
                                            else{
                                                echo "<div class='py-2'>".upload_file("case_closed_file", "(ប្រភេទឯកសារ pdf មានទំហំធំបំផុត 15MB)")."</div>";
                                            }
                                        @endphp
                                    </div>
                                </div>

                                <div class="row col-12  mt-5">
                                    <label class="text-purple text-hanuman-24">
                                        ញ. Upload ឯកសារយោងរបស់ដើមចោទ
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-4 mt-4">
                                        <input type="hidden" name="plaintiff_file_old" value="{{ $case->case_closed_plaintiff_file }}" >
                                        @php
                                            $show_file = showFile(1, $case->case_closed_plaintiff_file, pathToDeleteFile('case_doc/closed/'.$caseYear."/"), "delete", "tbl_case", "id", $case->id,  "case_closed_plaintiff_file");
                                            if($show_file){
                                                echo $show_file;
                                            }
                                            else{
                                                echo "<div class='py-2'>".upload_file("case_closed_plaintiff_file", "(ប្រភេទឯកសារ pdf មានទំហំធំបំផុត 15MB)")."</div>";
                                            }
                                        @endphp
                                    </div>
                                </div>

                                <div class="row col-12  mt-5">
                                    <label class="text-purple text-hanuman-24">
                                        ដ. Upload ឯកសារយោងរបស់ចុងចោទ
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-4 mt-4">
                                        <input type="hidden" name="defendant_file_old" value="{{ $case->case_closed_defendant_file }}" >
                                        @php
                                            $show_file = showFile(1, $case->case_closed_defendant_file, pathToDeleteFile('case_doc/closed/'.$caseYear."/"), "delete", "tbl_case", "id", $case->id,  "case_closed_defendant_file");
                                            if($show_file){
                                                echo $show_file;
                                            }
                                            else{
                                                echo "<div class='py-2'>".upload_file("case_closed_defendant_file", "(ប្រភេទឯកសារ pdf មានទំហំធំបំផុត 15MB)")."</div>";
                                            }
                                        @endphp
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="form-check checkbox checkbox-solid-danger col-sm-10 p-l-30">
                                        <input class="form-check-input"
                                               id="chkbox_go" name="chkbox_go"
                                               oninvalid ="this.setCustomValidity('សូមចុចធីក ក្នុងប្រអប់ជាមុនសិន ដើម្បីបញ្ជាក់នូវការផ្ទៀងផ្ទាត់')"
                                               oninput = "this.setCustomValidity('')"
                                               type="checkbox" required>
                                        <label class="form-check-label text-danger fw-bold" for="chkbox_go">
                                            ពត៌មានខាងលើ ត្រូវបានពិនិត្យ និងផ្ទៀងផ្ទាត់យ៉ាងត្រឹមត្រូវ រួចរាល់អស់ហើយ
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success fw-bold">រក្សារទុក</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <x-slot name="moreAfterScript">
        @include('script.my_sweetalert2')
        @include('case.script.case_closed_script')
        <script>
            $(document).ready(function () {
                $(".select2").select2({
                    matcher: function (params, data) {
                        if ($.trim(params.term) === '') {
                            return data;
                        }

                        // Convert search term and option text to lowercase for case-insensitive search
                        let term = params.term.toLowerCase();
                        let text = data.text.toLowerCase();
                        let id = data.id.toLowerCase();

                        // Check if search term matches either the text or the ID
                        if (text.includes(term) || id.includes(term)) {
                            return data;
                        }

                        return null;
                    }
                });
            });

        </script>

        <!-- Plugins Datepicker-->
        <script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>

        <script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ rurl('assets/myjs/sweetalert2.10.10.1.all.min.js') }}"></script>

    </x-slot>
</x-admin.layout-main>
