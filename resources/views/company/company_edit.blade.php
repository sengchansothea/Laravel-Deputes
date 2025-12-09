@php
$com = $adata['company'];
$comAPI = $adata['companyAPI'];

$comProID = $com->province_id;
$comDisID = $com->district_id;
$comComID = $com->commune_id;

$nssfNumber = $com->nssf_number ?? $comAPI->nssf_number ?? "";
$articleOfCompany = !empty($com->article_of_company) ? $com->article_of_company : $comAPI->article_of_company ?? 0;
$busActivity = !empty($com->business_activity) ? $com->business_activity : $comAPI->business_activity ?? "";
$firstBusAct = $com->first_business_act ?? $comAPI->first_business_act ?? "";
$companyTypeID = !empty($com->company_type_id) ? $com->company_type_id : $comAPI->company_type_id ?? 0 ;
$companyRegNum = $com->company_register_number ?? $comAPI->company_register_number ?? "";
$companyTin = $com->company_tin ?? $comAPI->company_tin ?? "";
$openDate = !empty($com->open_date) ? $com->open_date : $comAPI->open_date ?? "";
$regDate = !empty($com->registration_date) ? $com->registration_date : $comAPI->registration_date ?? "";




$arrComDistrict = $comProID > 0 ? arrayDistrict($comProID, 1) : array('0' => 'សូមជ្រើសរើស');
$arrComCommune = $comDisID > 0 ? arrayCommune($comDisID, 1) : array('0' => 'សូមជ្រើសរើស');
$arrComVillage = $comComID > 0 ? arrayVillage($comComID, 1) : array('0' => 'សូមជ្រើសរើស');


$csic1 = $com->csic_1 ?? $comAPI->csic_1 ?? $com->business_activity1 ?? $comAPI->business_activity1 ?? "";
$csic2 = $com->csic_2 ?? $comAPI->csic_2 ?? $com->business_activity2 ?? $comAPI->business_activity2 ?? "";
$csic3 = $com->csic_3 ?? $comAPI->csic_3 ?? $com->business_activity3 ?? $comAPI->business_activity3 ?? "";
$csic4 = $com->csic_4 ?? $comAPI->csic_4 ?? $com->business_activity4 ?? $comAPI->business_activity4 ?? "";
$csic5 = $com->csic_5 ?? $comAPI->csic_5 ?? "";

$arrCSIC2 = !empty($csic1) ? arrCSIC2($csic1) : array('0' => 'សូមជ្រើសរើស');
$arrCSIC3 = !empty($csic2) ? arrCSIC3($csic1, $csic2) : array('0' => 'សូមជ្រើសរើស');
$arrCSIC4 = !empty($csic3) ? arrCSIC4($csic1, $csic2, $csic3) : array('0' => 'សូមជ្រើសរើស');
$arrCSIC5 = !empty($csic4) ? arrCSIC5($csic1, $csic2, $csic3, $csic4) : array('0' => 'សូមជ្រើសរើស');
@endphp
{{--{{ dd($comAPI->business_activity) }}--}}
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <style>
            .required::after {
                content: " *";
                color: red;
            }
        </style>
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/date-picker.css') }}">
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <form class="" name="form_company" id="form_company" action="{{ route('company.update', $com->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="company_id_lacms" value="{{ $com->company_id_lacms }}" />
                    <input type="hidden" name="company_option" value="{{ $com->company_option }}" />
                    <div class="card">
    {{--                    <div class="card-header">--}}
    {{--                        Header--}}
    {{--                    </div>--}}
                        <div class="card-body">
                            <div class="card-block row">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="row pb-3">
                                        <div class="form-group col-md-6 col-sm-12 mt-3">
                                            <label class="fw-bold required pb-2">នាមករណ៍សហគ្រាស (ខ្មែរ)</label>
                                            <input class="form-control" value="{{ $com->company_name_khmer }}" name="company_name_khmer" id="company_name_khmer" type="text" aria-describedby="" placeholder="">
                                            @error('company_name_khmer')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12 mt-3">
                                            <label class="fw-bold required pb-2">នាមករណ៍សហគ្រាស (ឡាតាំង)</label>
                                            <input class="form-control" value="{{ $com->company_name_latin }}" name="company_name_latin" id="company_name_latin" type="text" aria-describedby="" placeholder="">
                                            @error('company_name_latin')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row pb-3">
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold pb-2">កាលបរិច្ឆេទបើកសហគ្រាស</label>
                                            <input name="open_date" id="open_date" placeholder="DD/MM/YYYY"  class="datepicker-here form-control digits" type="text" data-language="en" value="{{ old('open_date', date2Display($openDate)) }}">
                                            @error('open_date')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold pb-2">កាលបរិច្ឆេទចុះបញ្ជី</label>
                                            <input name="registration_date" id="registration_date" placeholder="DD/MM/YYYY"  class="datepicker-here form-control digits" type="text" data-language="en" value="{{ old('registration_date', date2Display($regDate)) }}">
                                            @error('registration_date')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold pb-2">លេខចុះបញ្ជីពាណិជ្ជកម្ម</label>
                                            <input class="form-control" value="{{ $companyRegNum }}" name="company_register_number" id="com_registration_number" type="text" aria-describedby="" placeholder="">
                                            @error('company_register_number')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold pb-2">លេខអត្តសញ្ញាណកម្មសារពើពន្ធ (TIN)</label>
                                            <input class="form-control" value="{{ $companyTin }}" name="company_tin" id="company_tin" type="text" aria-describedby="" placeholder="">
                                            @error('company_tin')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold pb-2">លេខអត្តសញ្ញាណ (បសស)</label>

                                            <input class="form-control" value="{{ $nssfNumber }}" name="nssf_number" id="nssf_number" type="text" aria-describedby="" placeholder="">
                                            @error('nssf_number')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold pb-2">លេខទូរសព្ទទំនាក់ទំនង</label>
                                            <input class="form-control" value="{{ $com->company_phone_number }}" name="company_phone_number" id="company_phone_number" type="text" aria-describedby="" placeholder="">
                                            @error('company_phone_number')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold required pb-2">ប្រភេទសហគ្រាស</label>
                                            {!! showSelect('company_type_id', arrayCompanyType(1,0), old('company_type_id', $companyTypeID), " select2","","","") !!}
                                            @error('company_type_id')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold pb-2">សកម្មភាពអាជីវកម្មចម្បង</label>
                                            <input class="form-control" value="{{ $firstBusAct }}" name="first_business_act" id="first_business_act" type="text" aria-describedby="" placeholder="">
                                            @error('first_business_act')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold pb-2">ទ្រង់ទ្រាយសហគ្រាស</label>
                                            {!! showSelect('article_of_company', arrayCompanyArticle(1,0), old('article_of_company', $articleOfCompany), " select2", "","","") !!}
                                            @error('article_of_company')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold required pb-2">សកម្មភាពអាជីវកម្ម</label>
                                            {!! showSelect('business_activity', arrayBusinessActivity(1,0), old('business_activity', $busActivity), " select2","","","") !!}
                                            @error('business_activity')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12 pb-3">
                                            <label class="fw-bold required pb-2">សកម្មភាពសេដ្ឋកិច្ច កម្រិតទី១</label>
                                            {!! showSelect('csic_1', arrCSIC1(), old('csic_1', $csic1), " select2", "", "csic1", "") !!}
                                            @error('csic_1')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold pb-2">សកម្មភាពសេដ្ឋកិច្ច កម្រិតទី២</label>
                                            {!! showSelect('csic_2', $arrCSIC2, old('csic_2', $csic2), " select2", "", "csic2", "") !!}
                                            @error('csic_2')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold pb-2">សកម្មភាពសេដ្ឋកិច្ច កម្រិតទី៣</label>
                                            {!! showSelect('csic_3', $arrCSIC3, old('csic_3', $csic3), " select2", "", "csic3", "") !!}
                                            @error('csic_3')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold pb-2">សកម្មភាពសេដ្ឋកិច្ច កម្រិតទី៤</label>
                                            {!! showSelect('csic_4', $arrCSIC4, old('csic_4', $csic4), " select2", "", "csic4", "") !!}
                                            @error('csic_4')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold pb-2">សកម្មភាពសេដ្ឋកិច្ច កម្រិតទី៥</label>
                                            {!! showSelect('csic_5', $arrCSIC5, old('csic_5', $csic5), " select2", "", "csic5", "") !!}
                                            @error('csic_5')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row pb-3">
                                        <div class="form-group col-xl-4 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold required pb-2">ខេត្ត/រាជធានី</label>
                                            {!! showSelect('province_id', arrayProvince(1), old('province_id', $com->province_id)) !!}
                                            @error('province_id')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-4 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold required pb-2">ស្រុក/ខណ្ឌ</label>
                                            {!! showSelect('district_id', $arrComDistrict, old('district_id', $com->district_id)) !!}
                                            @error('district_id')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-4 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold required pb-2">ឃុំ/សង្កាត់</label>
                                            {!! showSelect('commune_id', $arrComCommune, old('commune_id', $com->commune_id)) !!}
                                            @error('commune_id')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-4 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold pb-2">ក្រុម/ភូមិ</label>
                                            {!! showSelect('village_id', $arrComVillage, old('village_id', $com->village_id)) !!}
                                            @error('village_id')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-4 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold pb-2">ផ្លូវ</label>
                                            <input name="street_no" class="form-control" type="text" value="{{ $com->street_no }}" placeholder="">
                                            @error('street_no')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-xl-4 col-lg-6 col-md-6 col-sm-12 pb-3">
                                            <label class="fw-bold pb-2">ផ្ទះលេខ</label>
                                            <input class="form-control" value="{{ $com->building_no }}" name="building_no" id="building_no" type="text" aria-describedby="" placeholder="">
                                            @error('building_no')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">កែប្រែពត៌មាន</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-slot name="moreAfterScript">
        <script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ rurl('assets/myjs/sweetalert2.10.10.1.all.min.js') }}"></script>
        @include('company.company_script')
    </x-slot>
</x-admin.layout-main>
