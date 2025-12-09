@php
    $arrInOrOutDomain = [
        '0' => 'មិនកំណត់',
        '1' => "ក្នុងដែនការិយាល័យ",
        '2' => "ក្រៅដែនការិយាល័យ",
        ];
    $arrDomain = [
        '0' => 'មិនកំណត់',
        '1' => "ការិយាល័យវិវាទការងារទី១",
        '2' => "ការិយាល័យវិវាទការងារទី២",
        '3' => "ការិយាល័យវិវាទការងារទី៣",
        '4' => "ការិយាល័យវិវាទការងារទី៤",
        ];
    $arrCaseStatus = [
        '0' => 'មិនកំណត់',
        '1' => 'កំពុងដំណើរការ',
        '2' => 'បញ្ចប់',
    ];
    $arrCaseStep = [
        '0' => 'មិនកំណត់',
        '1' => 'បណ្តឹងថ្មី',
        '2' => 'លិខិតអញ្ជើញកម្មករ',
        '3' => 'លិខិតអញ្ជើញក្រុមហ៊ុន',
        '4' => 'កំណត់់ហេតុសួរកម្មករ',
        '5' => 'កំណត់ហេតុសួរក្រុមហ៊ុន',
        '6' => 'លិខិតអញ្ញើញផ្សះផ្សា',
        '7' => 'កំណត់ហេតុផ្សះផ្សា',
        '8' => 'លើកពេលផ្សះផ្សា',
        '9' => 'ផ្សះផ្សារចប់',
        '10' => 'បិទបញ្ចប់'
    ];
    $currentYear = date('Y');
    $yearRange = range($currentYear, 2017);
    $arrYear = array_combine($yearRange, $yearRange); // Generate Associative Array (key,value)
    $arrYear = [0 => 'មិនកំណត់'] + $arrYear;

    $csic1 = old('csic1', request('csic1'));
    $csic2 = old('csic2', request('csic2'));
    $csic3 = old('csic3', request('csic3'));
    $csic4 = old('csic4', request('csic4'));

    $arrCSIC2 = $csic1 ? arrCSIC2($csic1) : ['0' => 'សូមជ្រើសរើស'];
    $arrCSIC3 = ($csic1 && $csic2) ? arrCSIC3($csic1, $csic2) : ['0' => 'សូមជ្រើសរើស'];
    $arrCSIC4 = ($csic1 && $csic2 && $csic3) ? arrCSIC4($csic1, $csic2, $csic3) : ['0' => 'សូមជ្រើសរើស'];
    $arrCSIC5 = ($csic1 && $csic2 && $csic3 && $csic4) ? arrCSIC5($csic1, $csic2, $csic3, $csic4) : ['0' => 'សូមជ្រើសរើស'];
@endphp
<x-slot name="moreCss2">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        a.no-hover {
            text-decoration: none !important; /* remove underline */
            color: inherit !important;        /* keep same color */
        }
        a.no-hover:hover {
            text-decoration: none !important;
            color: inherit !important;
        }
    </style>
</x-slot>
<div class="d-flex justify-content-between align-items-center p-2 rounded shadow-sm mb-2">
    <!-- Left Title -->
    @if($user)
        <div class="mb-0 d-flex align-items-center">
            <label class="form-label fw-bold text-info text-hanuman-18">ឈ្មោះអ្នកប្រើប្រាស់៖
                <span class="text-danger fw-bold text-hanuman-20">
                    {{ $user->k_fullname }}
                </span>
            </label>
        </div>
    @endif

    <!-- Right Button with Icon -->
    <a id="toggleButton" class="btn btn-info-gradien btn-lg no-hover" href="#" title="">
        <span class="fa fa-search-minus me-2 text-white"></span>
        <span class="fw-bold text-white">ស្វែងរកតាមតម្រូវការ</span>
    </a>
</div>

<form action="{{ url('cases') }}" method="GET">
    @method('PATCH')
    @csrf
    <input type="hidden" name="opt_search" value="quick" />
    <!-- Hidden section (initially hidden) -->
    <div id="advanceSearch" class="d-none">
        <div class="row mb-2">
            @if(chkUserIdentity() <= 3)
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-1">
                    <label class="form-label fw-bold">ឈ្មោះកម្មករនិយោជិត ឫ រោងចក្រ សហគ្រាស ឬ លេខចុះបញ្ជីពាណិជ្ជកម្ម ឬ លេខTIN:</label>
                    <input type="text" name="search" placeholder="សូមវាយឈ្មោះ កម្មករនិយោជិត ឫ រោងចក្រ សហគ្រាស ឬ លេខចុះបញ្ជីពាណិជ្ជកម្ម ឬ លេខTIN ដើម្បីស្វែងរក" value="{{ request('search') }}" class="form-control" />
                </div>
                <div class="form-group col-4 mt-3">
                    <label class="form-label mb-1 fw-bold">នាយកដ្ឋានវិវាទការងារ</label>
                    {!! showSelect('domainID',$arrDomain, old('domainID', request('domainID')), "", "") !!}
                </div>
                <div class="form-group col-4 mt-3">
                    <label class="form-label mb-1 fw-bold">ក្នុងឫក្រៅដែនការិយាល័យ</label>
                    {!! showSelect('inOutDomain',$arrInOrOutDomain, old('inOutDomain', request('inOutDomain')), "", "") !!}
                </div>
                <div class="form-group col-4 mt-3">
                    <label class="form-label mb-1 fw-bold">ឆ្នាំបណ្តឹង</label>
                    {!! showSelect('year',$arrYear, old('year', request('year')), "", "") !!}
                </div>
                <div class="form-group col-4 mt-3">
                    <label class="form-label mb-1 fw-bold">ស្ថានភាពបណ្តឹង</label>
                    {!! showSelect('statusID',$arrCaseStatus, old('statusID', request('statusID')), "", "") !!}
                </div>
                <div class="form-group col-4 mt-3">
                    <label class="form-label mb-1 fw-bold">ដំណើរការបណ្តឹង</label>
                    {!! showSelect('stepID',$arrCaseStep, old('stepID', request('stepID')), "", "") !!}
                </div>
                <div class="form-group col-4 mt-2">
                    <label class="form-label" style="visibility: hidden">x</label>
{{--                    <div class="input-group">--}}
{{--                        <button type="submit" class="btn btn-lg btn-success-gradien fw-bold">--}}
{{--                            <span class="fa fa-search me-2 text-white"></span>--}}
{{--                            ស្វែងរកពាក្យបណ្តឹង--}}
{{--                        </button>--}}
{{--                    </div>--}}
                    <div class="input-group justify-content-center">
                        <!-- Existing Search Button -->
                        <button type="submit" class="btn btn-lg btn-success-gradien fw-bold">
                            <span class="fa fa-search me-2 text-white"></span>
                            ស្វែងរកពាក្យបណ្តឹង
                        </button>

                        <!-- ✅ New Export Excel Button -->
                        <button type="submit" name="export_excel" value="1" class="btn btn-lg btn-warning-gradien fw-bold ms-2">
                            <span class="fa fa-download me-2 text-white"></span>
                            ទាញយក Excel
                        </button>
                    </div>
                </div>
            @else
                <div class="form-group col-6">
                    <label class="form-label fw-bold mb-1">ឈ្មោះកម្មករនិយោជិត ឫ រោងចក្រ សហគ្រាស ឬ លេខចុះបញ្ជីពាណិជ្ជកម្ម ឬ លេខTIN:</label>
                    <input type="text" name="search" placeholder="សូមវាយឈ្មោះ កម្មករនិយោជិត ឫ រោងចក្រ សហគ្រាស ឬ លេខចុះបញ្ជីពាណិជ្ជកម្ម ឬ លេខTIN ដើម្បីស្វែងរក" value="{{ request('search') }}" class="form-control" />
                </div>
                <div class="form-group col-3">
                    <label class="form-label mb-1 fw-bold">ឆ្នាំបណ្តឹង</label>
                    {!! showSelect('year',$arrYear, old('year', request('year')), "", "") !!}
                </div>
                <div class="form-group col-3">
                    <label class="form-label mb-1 fw-bold">ក្នុងឫក្រៅដែនការិយាល័យ</label>
                    {!! showSelect('inOutDomain',$arrInOrOutDomain, old('inOutDomain', request('inOutDomain')), "", "") !!}
                </div>
                <div class="form-group col-4 mt-3">
                    <label class="form-label mb-1 fw-bold">ស្ថានភាពបណ្តឹង</label>
                    {!! showSelect('statusID',$arrCaseStatus, old('statusID', request('statusID')), "", "") !!}
                </div>
                <div class="form-group col-4 mt-3">
                    <label class="form-label mb-1 fw-bold">ដំណើរការបណ្តឹង</label>
                    {!! showSelect('stepID',$arrCaseStep, old('stepID', request('stepID')), "", "") !!}
                </div>
                <div class="form-group col-4 mt-2">
                    <label class="form-label" style="visibility: hidden">x</label>
{{--                    <div class="input-group">--}}
{{--                        <button type="submit" class="btn btn-lg btn-success-gradien fw-bold">--}}
{{--                            <span class="fa fa-search me-2 text-white"></span>--}}
{{--                            ស្វែងរកពាក្យបណ្តឹង--}}
{{--                        </button>--}}
{{--                    </div>--}}
                    <div class="input-group justify-content-center">
                        <!-- Existing Search Button -->
                        <button type="submit" class="btn btn-lg btn-success-gradien fw-bold">
                            <span class="fa fa-search me-2 text-white"></span>
                            ស្វែងរកពាក្យបណ្តឹង
                        </button>

                        <!-- ✅ New Export Excel Button -->
                        <button type="submit" name="export_excel" value="1" class="btn btn-lg btn-warning-gradien fw-bold ms-2">
                            <span class="fa fa-download me-2 text-white"></span>
                            ទាញយក Excel
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>


</form>
@push('childScript')
    <script>
        $(document).ready(function() {
            $('#business_activity').select2();
            $('#company_type_id').select2();
            $('#province_id').select2();
            $('#district_id').select2();
            $('#commune_id').select2();
            $('#year').select2();
        });
    </script>
    <script>
        $(document).on('click', '#toggleButton', function (e) {
            e.preventDefault();

            const $icon = $(this).find('.fa');
            const $advanceSearch = $('#advanceSearch');

            // 🔄 Toggle icon
            if ($icon.hasClass('fa-search-minus')) {
                $icon.removeClass('fa-search-minus').addClass('fa-search-plus');
            } else {
                $icon.removeClass('fa-search-plus').addClass('fa-search-minus');
            }

            // 🔽 Toggle the visibility of the advanced search div
            $advanceSearch.toggleClass('d-none');
        });
    </script>
    <!-- Plugins Select2-->
    <script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>
@endpush








