@php
    $case = $adata['case'] ?? null;
    $company = $adata['company'] ?? null;
    $cYear = myDate('y');
    $arrProvince = $adata['arrProvince'];
    $arrNationality = $adata['arrNationality'];
@endphp
{{--{{ dd($company) }}--}}
{{--{{ dd(ApiAdmin($company->company_id_lacms, "30")) }}--}}
{{--{{ dd(ApiAdmin(341, "30")) }}--}}
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/date-picker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/timepicker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <style>
            #response_message_employee {
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
                                <div class="progress-bar bg-primary fw-bold text-hanuman-16" role="progressbar" style="width: 66.66%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                    ជំហានទី២ (ព័ត៌មាន កម្មករនិយោជិត)
                                </div>
                            </div>
                        </div>
                    </div>
                    <form id="frmStep2" action="{{ route('cases.save.step2') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                        @method('POST')
                        @csrf
                        <input type="hidden" name="company_id"  id="company_id" value="{{ $company->company_id_lacms }}" >
                        <input type="hidden" name="comID"  id="comID" value="{{ $company->company_id }}" >
                        <div class="card-body text-hanuman-17">
                            <div class="card-block row">
                                <div class="col-sm-12 col-lg-12 col-xl-12">
{{--                                    Defendant Block--}}
                                    <div id="defendant_block">
                                        <div class="row col-12">
                                            <label class="text-purple text-hanuman-24">
                                                2. កម្មករនិយោជិត
                                            </label>
                                        </div>
                                        <div class="form-group col-12">
                                            <div id="response_message_employee" class="text-danger mt-2 text-hanuman-16" style="display: none;">កំពុងស្វែងរក.....</div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-12 mt-3">
                                                <label for="case_type" class="text-primary text-hanuman-18 mb-1"> ស្វែងរកឈ្មោះកម្មករនិយោជិត (អ្នកប្តឹង)</label>
                                                <input type="text" name="find_employee_autocomplete" value="{{ old('find_employee_autocomplete') }}" class="form-control" id="find_employee_autocomplete" >
                                            </div>
                                        </div>
                                        <div class="row col-12  mt-4">
                                            <label class="text-pink text-hanuman-20">
                                                -ព័ត៌មានទូទៅ
                                            </label>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="form-group col-sm-4">
                                                <label for="case_type" class="fw-bold required mb-1">ឈ្មោះអ្នកប្ដឹង</label>
{{--                                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="name" placeholder="" required>--}}
                                                <input
                                                        type="text"
                                                        name="name"
                                                        value="{{ old('name') }}"
                                                        class="form-control"
                                                        id="name"
                                                        placeholder=""
                                                        oninvalid="this.setCustomValidity('សូមបញ្ចូលឈ្មោះអ្នកប្ដឹង  (យ៉ាងតិច៥ខ្ទង់)')"
                                                        oninput="this.setCustomValidity('')"
                                                        required
                                                >
                                                @error('name')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="fw-bold mb-1 required">ភេទ</label>
                                                {!! showSelect('gender', array("1" =>"ប្រុស", "2" => "ស្រី"), old('gender'), " select2") !!}
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label for="" class="fw-bold required mb-1">សញ្ជាតិ</label>
                                                {!! showSelect('nationality', $arrNationality, old('nationality'), " select2", "", "", "required") !!}
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold required mb-1">ថ្ងៃខែឆ្នាំកំណើត</label>
                                                <input type="text"  name="dob" id="dob" value="{{ old('dob') }}" class="form-control" placeholder="DD-MM-YYYY" data-language="en" required>
                                                @error('dob')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="id_number" class="fw-bold mb-1"> លេខអត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
                                                <input type="text" name="id_number"   value="{{ old('id_number') }}" class="form-control" id = "id_number" placeholder="">
                                                @error('id_number')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="occupation" class="fw-bold required mb-1">មុខងារ</label>
                                                <input type="text" name="occupation" value="{{ old('occupation') }}" class="form-control" id="occupation" placeholder="" required>
                                                @error('occupation')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-6 mt-3">
                                                <label for="phone_number" class="fw-bold mb-1 required">លេខទូរស័ព្ទ (ខ្សែទី១)</label>
                                                <input type="tel" name="phone_number" value="{{ old('phone_number') }}" class="form-control" id="phone_number" minlength="9"  pattern="[0][1-9][0-9]{7}|[0][1-9][0-9]{8}" required>
                                                @error('phone_number')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-6 mt-3">
                                                <label for="phone_number" class="fw-bold mb-1">លេខទូរស័ព្ទ (ខ្សែទី២)</label>
                                                <input type="tel" name="phone_number2" value="{{ old('phone_number2') }}" class="form-control" id="phone_number2" minlength="9" pattern="[0][1-9][0-9]{7}|[0][1-9][0-9]{8}">
                                                @error('phone_number2')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row col-12  mt-5">
                                            <label class="text-pink text-hanuman-20">
                                                -ទីកន្លែងកំណើត
                                            </label>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="form-group col-sm-3">
                                                <label for="" class="fw-bold mb-1">ប្រទេស</label>
                                                {!! showSelect('pob_country_id', $arrNationality, old('pob_country_id'), " select2", "", "", "required") !!}
                                                @error('pob_country_id')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label class="fw-bold mb-1 required">រាជធានី-ខេត្ត</label>
                                                {!! showSelect('pob_province_id', $arrProvince, old('pob_province_id', request('pob_province_id')), " select2") !!}
                                                @error('pob_province_id')
                                                <div>{!! textRed($message) !!}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label class="fw-bold mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                                {!! showSelect('pob_district_id', array(), old('pob_district_id', request('pob_district_id')), " select2") !!}
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label class="fw-bold mb-1">ឃុំ-សង្កាត់</label>
                                                {!! showSelect('pob_commune_id', array(), old('pob_commune_id', request('pob_commune_id')), " select2") !!}
                                            </div>
                                        </div>
                                        <div class="row col-12  mt-5">
                                            <label class="text-pink text-hanuman-20">
                                                -អាសយដ្ឋានបច្ចុប្បន្ន
                                            </label>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="form-group col-sm-4">
                                                <label class="fw-bold required mb-1">រាជធានី-ខេត្ត</label>
                                                {!! showSelect('province', $arrProvince, old('province', request('province')), " select2", "", "", "required") !!}
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="fw-bold required mb-1">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                                {!! showSelect('district', array(), old('pob_district', request('district')), " select2", "", "", "required") !!}
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="fw-bold mb-1 required">ឃុំ-សង្កាត់</label>
                                                {!! showSelect('commune', array(), old('commune', request('commune')), " select2", "", "", "required") !!}
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold mb-1">ភូមិ</label>
                                                {!! showSelect('village', array(), old('village', request('village')), " select2") !!}
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label for="case_type" class="fw-bold mb-1">ផ្ទះលេខ</label>
                                                <input type="text" name="addr_house_no" value="{{ old('addr_house_no') }}" class="form-control" id="addr_house_no" >
                                            </div>
                                            <div class="form-group col-sm-4 mt-3">
                                                <label class="fw-bold mb-1">ផ្លូវ</label>
                                                <input type="text" name="addr_street" id="addr_street" value="{{ old('addr_street') }}" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <br/>
                                    <br/>
                                    <div class="row">
                                        @if(!empty($case))
                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-success form-control fw-bold">រក្សាទុក</button>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <a href="{{ route('cases.create.step3') }}" class="btn btn-primary form-control fw-bold">បន្ទាប់ (ជំហ៊ានទី៣)</a>
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
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const selectFields = [
                    { id: 'province_id', message: 'សូមជ្រើសរើស រាជធានី-ខេត្ត មុនពេលរក្សាទុក។' },
                    { id: 'district_id', message: 'សូមជ្រើសរើស ក្រុង-ស្រុក-ខណ្ឌ មុនពេលរក្សាទុក។' },
                    { id: 'commune_id', message: 'សូមជ្រើសរើស ឃុំ-សង្កាត់ មុនពេលរក្សាទុក។' },
                    { id: 'nationality', message: 'សូមជ្រើសរើស សញ្ជាតិ មុនពេលរក្សាទុក។' },
                ];

                const form = document.querySelector('#frmStep2');

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
