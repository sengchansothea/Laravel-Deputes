@php
    $casePre = $adata['caseNumber'];
    $cYear = myDate('y');
    $arrCaseType = $adata['arrCaseType'];
    $arrSector = $adata['arrSector'];
    $arrCompanyType = $adata['arrCompanyType'];
    $arrProvince = $adata['arrProvince'];
    $case = $adata['case'];
    $company = $adata['company'] ?? null;
    $caseCom = $adata['caseCom'] ?? null;
    $caseNumber = !empty($case->case_number) ? $case->case_number : 0;

    $provinceID = $adata['provinceID'] ?? 0;
    $districtID = $adata['districtID'] ?? 0;
    $communeID = $adata['communeID'] ?? 0;
    $villageID = $adata['villageID'] ?? 0;
    $arrayDistrictID = $adata['arrayDistrictID'] ?? array();
    $arrayCommuneID = $adata['arrayCommuneID'] ?? array();
    $arrayVillageID = $adata['arrayVillageID'] ?? array();
@endphp
{{--{{ dd($arrayDistrictID, $arrayCommuneID, $arrayVillageID) }}--}}
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/date-picker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/timepicker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <style>
            #response_message_company {
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
                    <div class="card-body progress-showcase row">
                        <div class="col">
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar bg-primary fw-bold text-hanuman-16" role="progressbar" style="width: 33.33%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                    ជំហានទី១ (ព័ត៌មាន សហគ្រាស គ្រឹះស្ថាន)
                                </div>
                            </div>
                        </div>
                    </div>
                    <form id="frmUpdateCreateCase" action="{{ route('cases.save.step1') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                        @method('POST')
                        @csrf
                        <input type="hidden" name="case_id" value="{{ $case->id ?? 0 }}"  id="case_id">
                        <input type="hidden" name="first_business_act" value="{{ old('first_business_act', $caseCom->log5_first_business_act ?? '') }}"  id="first_business_act">
                        <input type="hidden" name="article_of_company" value="{{ old('article_of_company', $caseCom->log5_article_of_company ?? '') }}"  id="article_of_company">
                        <input type="hidden" name="csic_1" value="{{ old('csic_1', $caseCom->log5_csic_1 ?? '') }}" id="csic_1">
                        <input type="hidden" name="csic_2" value="{{ old('csic_2', $caseCom->log5_csic_2 ?? '') }}" id="csic_2">
                        <input type="hidden" name="csic_3" value="{{ old('csic_3', $caseCom->log5_csic_3 ?? '') }}" id="csic_3">
                        <input type="hidden" name="csic_4" value="{{ old('csic_4', $caseCom->log5_csic_4 ?? '') }}" id="csic_4">
                        <input type="hidden" name="csic_5" value="{{ old('csic_5', $caseCom->log5_csic_5 ?? '') }}" id="csic_5">
                        <input type="hidden" name="business_activity" value="{{ old('business_activity', $caseCom->log5_business_activity ?? '') }}" id="business_activity">
                        <input type="hidden" name="business_activity1" value="{{ old('business_activity1', $caseCom->log5_business_activity1 ?? '') }}" id="business_activity1">
                        <input type="hidden" name="business_activity2" value="{{ old('business_activity2', $caseCom->log5_business_activity3 ?? '') }}" id="business_activity2">
                        <input type="hidden" name="business_activity3" value="{{ old('business_activity3', $caseCom->log5_business_activity3 ?? '') }}" id="business_activity3">
                        <input type="hidden" name="business_activity4" value="{{ old('business_activity4', $caseCom->log5_business_activity4 ?? '') }}" id="business_activity4">
                        <input type="hidden" name="company_register_number" value="{{ old('company_register_number', $company->company_register_number ?? '') }}" id="company_register_number">
                        <input type="hidden" name="registration_date" value="{{ old('registration_date', $company->registration_date ?? null) }}" id="registration_date">
                        <input type="hidden" name="company_tin" value="{{ old('company_tin', $company->company_tin ?? '') }}" id="company_tin">
                        <input type="hidden" name="nssf_number" value="{{ old('nssf_number', $company->nssf_number ?? '') }}" id="nssf_number">
                        <input type="hidden" name="single_id" value="{{ old('single_id', $company->single_id ?? '') }}" id="single_id">
                        <input type="hidden" name="operation_status" value="{{ old('operation_status', $company->operation_status ?? 0) }}" id="operation_status">
                        <div class="card-body text-hanuman-17">
                            <div class="card-block row">
                                <div class="col-sm-12 col-lg-12 col-xl-12">
                                    <div class="form-group col-12">
                                        <div id="response_message_company" style="display: none;">Waiting for response...</div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="case_type_id" class="fw-bold required mb-2"> ប្រភេទពាក្យបណ្ដឹង</label>{!! myToolTip(__("case.case_type")) !!}
                                            {!! showSelect('case_type_id',$arrCaseType, old('case_type_id', 1)) !!}
                                        </div>
                                        @if(empty($case))
                                        <div class="form-group col-sm-6">
                                            <label for="case_type_id" class="fw-bold mb-2 required">លេខសំណុំរឿង</label>
                                            <input type="text" name="case_number" id="case_number" value="{{ old('case_number', $casePre) }}" class="form-control col-sm-2" required>
                                        </div>
                                        @else
                                            <div class="form-group col-sm-6">
                                                <label for="case_type_id" class="fw-bold mb-2 required">លេខសំណុំរឿង</label>
                                                <div class="d-flex">
                                                    <input style="" type="text" name="case_number" id="case_number" value="{{ old('case_number', $caseNumber) }}" class="form-control" required>
                                                    {!! nbs(3) !!}
                                                    <input style="" type="text" name="case_num_str" id="case_num_str" value="{{ old('case_num_str') }}" class="form-control" readonly>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
{{--                                    Plantiff Block--}}
                                    <div id="plantiff_block">
                                        <div class="row col-12  mt-4">
                                            <label class="text-purple text-hanuman-24" for="contact_phone">
                                                1. សហគ្រាស គ្រឹះស្ថាន
                                            </label>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-12 mt-3">
                                                <label for="case_type" class="text-primary text-hanuman-22 mb-1"> ស្វែងរកឈ្មោះសហគ្រាស គ្រឹះស្ថាន</label>
                                                <input type="text" name="find_company" minlength="2" value="{{ old('find_company') }}" class="form-control" id="find_company_autocomplete">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-12 mt-3">
                                                <button type="button" id="btn_search_company" value="0" class="btn btn-danger mb-3 text-hanuman-16">បិទព័ត៌មានលម្អិតរបស់សហគ្រាស គ្រឹះស្ថាន</button>
                                            </div>
                                            <div class="form-group col-sm-12" id="div_company_result">
                                                <textarea rows="5" name="company_result" id="company_result" class="form-control" readonly>
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6 mt-3">
                                                <label for="case_type" class="fw-bold required mb-1">
                                                    ឈ្មោះជាភាសាខ្មែរ</label>
                                                <input
                                                        type="text"
                                                        name="company_name_khmer"
                                                        value="{{ old('company_name_khmer', $company->company_name_khmer ?? '') }}"
                                                        class="form-control"
                                                        id="company_name_khmer"
                                                        minlength="5"
                                                        placeholder="" required
                                                        oninvalid="this.setCustomValidity('សូមបញ្ចូលឈ្មោះសហគ្រាស គ្រឹះស្ថាន ជាភាសាខ្មែរ  (យ៉ាងតិច៥ខ្ទង់)')"
                                                        oninput="this.setCustomValidity('')"
                                                >
                                                @error('company_name_khmer')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-6 mt-3">
                                                <input type="hidden" name="company_id_lacms"  id="company_id_lacms" value="{{ $company->company_id_lacms ?? 0 }}">
                                                <input type="hidden" name="company_id_auto"  id="company_id_auto" value="0">
                                                <input type="hidden" name="company_id"  id="company_id" value="{{ $case->id ?? 0  }}" >
                                                <input type="hidden" name="company_option"  id="company_option" value="{{ $case->company_option ?? 0 }}" >
                                                <label for="case_type" class="fw-bold mb-1">ឈ្មោះជាភាសាឡាតាំង</label>
                                                <input
                                                        type="text"
                                                        name="company_name_latin"
                                                        value="{{ old('company_name_latin', $company->company_name_latin ?? '') }}"
                                                        class="form-control" id="company_name_latin"
                                                        minlength="5"
                                                        placeholder="" required
                                                        oninvalid="this.setCustomValidity('សូមបញ្ចូលឈ្មោះសហគ្រាស គ្រឹះស្ថាន ជាភាសាឡាតាំង (យ៉ាងតិច៥ខ្ទង់)')"
                                                        oninput="this.setCustomValidity('')"
                                                >
                                                @error('company_name_latin')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-6 mt-3">
                                                <label for="sector_id" class="fw-bold required mb-1">វិស័យ</label>
                                                {!! showSelect('sector_id', $arrSector, old('sector_id', $case->sector_id ?? 0), " select2", "", "", "required") !!}
                                            </div>
                                            <div class="form-group col-sm-6 mt-3">
                                                <label class="fw-bold required mb-1">ប្រភេទសហគ្រាស</label>
                                                {!! showSelect('company_type_id', $arrCompanyType, old('company_type_id', $case->company_type_id ?? 0), " select2", "", "", "required") !!}
                                            </div>

                                        </div>
                                        <div class="row col-12  mt-4">
                                            <label class="text-pink text-hanuman-20" for="contact_phone">
                                                -អាសយដ្ឋាន
                                            </label>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold required mb-1">រាជធានី-ខេត្ត</label>
                                                {!! showSelect('province_id', $arrProvince, old('province_id', $provinceID), " select2", "", "", "required") !!}
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold required mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                                {!! showSelect('district_id', $arrayDistrictID, old('district_id', $districtID), " select2", "", "", "required") !!}
                                            </div>

                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold required mb-1">ឃុំ-សង្កាត់</label>
                                                {!! showSelect('commune_id', $arrayCommuneID, old('commune_id', $communeID), " select2", "", "", "required") !!}
                                            </div>

                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold mb-1">ភូមិ</label>
                                                {!! showSelect('village_id', $arrayVillageID, old('village_id', $villageID), " select2") !!}
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="case_type" class="fw-bold mb-1">អគារលេខ</label>
                                                <input type="text" name="building_no" value="{{ old('building_no', $caseCom->log5_building_no ?? '') }}" class="form-control" id="building_no" >
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold mb-1">ផ្លូវ</label>
                                                <input type="text" name="street_no" id="street_no" value="{{ old('street_no', $caseCom->log5_street_no ?? '') }}" class="form-control short2" />
                                            </div>
                                        </div>
                                        <div class="row col-12  mt-4">
                                            <label class="text-pink text-hanuman-20" for="contact_phone">
                                                -ទំនាក់ទំនង
                                            </label>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6 mt-3">
                                                <label for="company_phone_number"  class="fw-bold mb-1 required">លេខទូរស័ព្ទក្រុមហ៊ុន (ខ្សែទី១)</label>
                                                <input type="text" name="company_phone_number" id="company_phone_number" value="{{ old('company_phone_number', $caseCom->log5_company_phone_number ?? '') }}" class="form-control" minlength="9" required>
                                            </div>
                                            <div class="form-group col-sm-6 mt-3">
                                                <label for="company_phone_number2"  class="fw-bold mb-1">លេខទូរស័ព្ទក្រុមហ៊ុន (ខ្សែទី២)</label>
                                                <input type="text" name="company_phone_number2" id="company_phone_number2" value="{{ old('company_phone_number2', $caseCom->log5_company_phone_number2 ?? '') }}" class="form-control" minlength="9">
                                            </div>
                                        </div>
                                    </div><br/>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-success form-control fw-bold">រក្សាទុក</button>
                                        </div>
                                        @if(!empty($case))
                                        <div class="form-group col-md-3">
                                            <a href="{{ route('cases.edit.step2', $case->id) }}" class="btn btn-primary form-control fw-bold">បន្ទាប់ (ជំហ៊ានទី២)</a>
                                        </div>
                                        @endif
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
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const selectFields = [
                    { id: 'province_id', message: 'សូមជ្រើសរើស រាជធានី-ខេត្ត មុនពេលរក្សាទុក។' },
                    { id: 'district_id', message: 'សូមជ្រើសរើស ក្រុង-ស្រុក-ខណ្ឌ មុនពេលរក្សាទុក។' },
                    { id: 'commune_id', message: 'សូមជ្រើសរើស ឃុំ-សង្កាត់ មុនពេលរក្សាទុក។' },
                    { id: 'sector_id', message: 'សូមជ្រើសរើស វិស័យ មុនពេលរក្សាទុក។' },
                    { id: 'company_type_id', message: 'សូមជ្រើសរើស ប្រភេទសហគ្រាស មុនពេលរក្សាទុក។' },
                ];

                const form = document.querySelector('#frmUpdateCreateCase');

                form.addEventListener('submit', function (e) {
                    for (const field of selectFields) {
                        const selectEl = document.getElementById(field.id);
                        if (!selectEl) continue;

                        // Reset previous validation
                        selectEl.setCustomValidity('');

                        // Validate only if still default
                        if (selectEl.value === '0' || !selectEl.value) {
                            e.preventDefault(); // stop submission

                            selectEl.setCustomValidity(field.message);
                            selectEl.reportValidity();
                            selectEl.focus();

                            // If Select2 is used, open dropdown
                            if ($(selectEl).hasClass('select2-hidden-accessible')) {
                                $(selectEl).select2('open');
                            }

                            return false; // stop checking after first invalid dropdown
                        }
                    }
                });

                // Listen to native and Select2 change to clear error dynamically
                selectFields.forEach(field => {
                    const selectEl = document.getElementById(field.id);
                    if (!selectEl) return;

                    // Native select
                    selectEl.addEventListener('change', () => selectEl.setCustomValidity(''));

                    // Select2 change event
                    $(selectEl).on('select2:select', () => selectEl.setCustomValidity(''));
                });
            });
        </script>

    </x-slot>
</x-admin.layout-main>
