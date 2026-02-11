@php
    $arrInOrOutDomain = [
        '0' => 'មិនកំណត់',
        '1' => 'ក្នុងដែនការិយាល័យ',
        '2' => 'ក្រៅដែនការិយាល័យ',
    ];
    $arrDomain = [
        '0' => 'មិនកំណត់',
        '1' => 'ការិយាល័យវិវាទការងារទី១',
        '2' => 'ការិយាល័យវិវាទការងារទី២',
        '3' => 'ការិយាល័យវិវាទការងារទី៣',
        '4' => 'ការិយាល័យវិវាទការងារទី៤',
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
        '10' => 'បិទបញ្ចប់',
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
    $arrCSIC3 = $csic1 && $csic2 ? arrCSIC3($csic1, $csic2) : ['0' => 'សូមជ្រើសរើស'];
    $arrCSIC4 = $csic1 && $csic2 && $csic3 ? arrCSIC4($csic1, $csic2, $csic3) : ['0' => 'សូមជ្រើសរើស'];
    $arrCSIC5 =
        $csic1 && $csic2 && $csic3 && $csic4 ? arrCSIC5($csic1, $csic2, $csic3, $csic4) : ['0' => 'សូមជ្រើសរើស'];
@endphp
<x-slot name="moreCss2">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        a.no-hover {
            text-decoration: none !important;
            /* remove underline */
            color: inherit !important;
            /* keep same color */
        }

        a.no-hover:hover {
            text-decoration: none !important;
            color: inherit !important;
        }
    </style>
</x-slot>
<div class="d-flex justify-content-between align-items-center p-1 rounded shadow-sm mb-3">
    <!-- Left Title -->
    <h5 class="mb-0 d-flex align-items-center text-danger fw-bold text-hanuman-20">
        {{--        <span class="ms-2">🔍 ការតម្រង និងស្វែងរកបញ្ជីពាក្យបណ្តឹង</span> --}}
    </h5>

    <!-- Right Button with Icon -->
    <a id="toggleButton" class="btn btn-info-gradien btn-lg no-hover" href="#" title="">
        <span class="fa fa-search-minus me-2 text-white"></span>
        <span class="text-white fw-bold">ស្វែងរកតាមតម្រូវការ</span>
    </a>
</div>

<form action="{{ url('cases') }}" method="GET">
    @method('PATCH')
    @csrf
    <input type="hidden" name="opt_search" value="advance" />
    <!-- Hidden section (initially hidden) -->
    <div id="advanceSearch" class="d-none">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-1">
                <label class="form-label fw-bold">ឈ្មោះកម្មករនិយោជិត ឫ រោងចក្រ សហគ្រាស ឬ លេខចុះបញ្ជីពាណិជ្ជកម្ម ឬ
                    លេខTIN:</label>
                <input type="text" name="search"
                    placeholder="សូមវាយឈ្មោះ កម្មករនិយោជិត ឫ រោងចក្រ សហគ្រាស ឬ លេខចុះបញ្ជីពាណិជ្ជកម្ម ឬ លេខTIN ដើម្បីស្វែងរក"
                    value="{{ request('search') }}" class="form-control" />
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-1 mt-3">
                <label class="form-label fw-bold">ឈ្មោះអ្នកផ្សះផ្សា:</label>
                <input type="text" name="caseofficer" value="{{ request('caseofficer') }}" class="form-control" />
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-1 mt-3">
                <label class="form-label fw-bold">លេខសំណុំរឿង:</label>
                <input type="text" name="case_number" value="{{ request('case_number') }}" class="form-control" />
            </div>
            @if (chkUserIdentity() <= 3)
                <div class="form-group col-4 mt-3">
                    <label class="form-label mb-1 fw-bold">នាយកដ្ឋានវិវាទការងារ</label>
                    {!! showSelect('domainID', $arrDomain, old('domainID', request('domainID')), '', '') !!}
                </div>
                <div class="form-group col-4 mt-3">
                    <label class="form-label mb-1 fw-bold">ក្នុងឫក្រៅដែនការិយាល័យ</label>
                    {!! showSelect('inOutDomain', $arrInOrOutDomain, old('inOutDomain', request('inOutDomain')), '', '') !!}
                </div>
                {{-- <div class="form-group col-4 mt-3">
                    <label class="form-label mb-1 fw-bold">ឆ្នាំបណ្តឹង</label>
                    {!! showSelect('year',$arrYear, old('year', request('year')), "", "") !!}
                </div> --}}
                <div class="form-group col-6 mt-3">
                    <label class="form-label mb-1 fw-bold">កាលបរិច្ឆេទចាប់ផ្ដើម</label>
                    <input type="text" id="start_date" name="start_date"
                        value="{{ old('start_date', request('start_date')) }}" class="form-control"
                        placeholder="dd/mm/yyyy" />
                </div>
                <div class="form-group col-6 mt-3">
                    <label class="form-label mb-1 fw-bold">កាលបរិច្ឆេទបញ្ចប់</label>
                    <input type="text" id="end_date" name="end_date"
                        value="{{ old('end_date', request('end_date')) }}" class="form-control"
                        placeholder="dd/mm/yyyy" />
                </div>
                <div class="form-group col-6 mt-3">
                    <label class="form-label mb-1 fw-bold">ស្ថានភាពបណ្តឹង</label>
                    {!! showSelect('statusID', $arrCaseStatus, old('statusID', request('statusID')), '', '') !!}
                </div>
                <div class="form-group col-6 mt-3">
                    <label class="form-label mb-1 fw-bold">ដំណើរការបណ្តឹង</label>
                    {!! showSelect('stepID', $arrCaseStep, old('stepID', request('stepID')), '', '') !!}
                </div>
            @else
                <div class="form-group col-4 mt-3">
                    <label class="form-label mb-1 fw-bold">ក្នុងឫក្រៅដែនការិយាល័យ</label>
                    {!! showSelect('inOutDomain', $arrInOrOutDomain, old('inOutDomain', request('inOutDomain')), '', '') !!}
                </div>
                <div class="form-group col-4 mt-3">
                    <label class="form-label mb-1 fw-bold">ស្ថានភាពបណ្តឹង</label>
                    {!! showSelect('statusID', $arrCaseStatus, old('statusID', request('statusID')), '', '') !!}
                </div>
                <div class="form-group col-4 mt-3">
                    <label class="form-label mb-1 fw-bold">ដំណើរការបណ្តឹង</label>
                    {!! showSelect('stepID', $arrCaseStep, old('stepID', request('stepID')), '', '') !!}
                </div>
                {{-- <div class="form-group col-6 mt-3">
                    <label class="form-label mb-1 fw-bold">ឆ្នាំបណ្តឹង</label>
                    {!! showSelect('year',$arrYear, old('year', request('year')), "", "") !!}
                </div> --}}
                <div class="form-group col-6 mt-3">
                    <label class="form-label mb-1 fw-bold">កាលបរិច្ឆេទចាប់ផ្ដើម</label>
                    <input type="text" id="start_date" name="start_date"
                        value="{{ old('start_date', request('start_date')) }}" class="form-control"
                        placeholder="dd/mm/yyyy" />
                </div>
                <div class="form-group col-6 mt-3">
                    <label class="form-label mb-1 fw-bold">កាលបរិច្ឆេទបញ្ចប់</label>
                    <input type="text" id="end_date" name="end_date"
                        value="{{ old('end_date', request('end_date')) }}" class="form-control"
                        placeholder="dd/mm/yyyy" />
                </div>
            @endif

            <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-1 mt-3">
                <label class="form-label fw-bold">សកម្មភាពសេដ្ឋកិច្ច:</label>
                {!! showSelect(
                    'business_activity',
                    arrayBusinessActivity(1, 0, 'មិនកំណត់'),
                    old('business_activity', request('business_activity')),
                    ' select2',
                ) !!}
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-1 mt-3">
                <label class="form-label fw-bold">ប្រភេទសហគ្រាស:</label>
                {!! showSelect(
                    'company_type_id',
                    arrayCompanyType(1, 0, 'មិនកំណត់'),
                    old('company_type_id', request('company_type_id')),
                    ' select2',
                ) !!}
            </div>
            <div class="col-6 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-1 mt-3">
                <label class="form-label fw-bold">សកម្មភាពសេដ្ឋកិច្ចកម្រិត១</label>
                {!! showSelect('csic1', arrCSIC1(), old('csic1', request('csic1')), ' select2', '', 'csic1', '') !!}
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-1 mt-3">
                <label class="form-label fw-bold">សកម្មភាពសេដ្ឋកិច្ចកម្រិត២</label>
                {!! showSelect('csic2', $arrCSIC2, old('csic2', request('csic2')), ' select2', '', 'csic2', '') !!}
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-1 mt-3">
                <label class="form-label fw-bold">សកម្មភាពសេដ្ឋកិច្ចកម្រិត៣</label>
                {!! showSelect('csic3', $arrCSIC3, old('csic3', request('csic3')), ' select2', '', 'csic3', '') !!}
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-1 mt-3">
                <label class="form-label fw-bold">សកម្មភាពសេដ្ឋកិច្ចកម្រិត៤</label>
                {!! showSelect('csic4', $arrCSIC4, old('csic4', request('csic4')), ' select2', '', 'csic4', '') !!}
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-1 mt-3">
                <label class="form-label fw-bold">សកម្មភាពសេដ្ឋកិច្ចកម្រិត៥</label>
                {!! showSelect('csic5', $arrCSIC5, old('csic5', request('csic5')), ' select2', '', 'csic5', '') !!}
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-1 mt-3">
                <label class="form-label fw-bold">អាសយដ្ឋាន: រាជធានី-ខេត្ត:</label>
                {!! showSelect(
                    'province_id',
                    myArrProvince(0, 1, 'មិនកំណត់', 1),
                    old('province_id', request('province_id')),
                    ' select2',
                ) !!}
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-1 mt-3">
                <label class="form-label fw-bold">ក្រុង-ស្រុក-ខណ្ឌ:</label>
                {!! showSelect(
                    'district_id',
                    arrayDistrict(request('province_id'), 1, 0, 'មិនកំណត់'),
                    old('district_id', request('district_id')),
                    ' select2',
                ) !!}
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-1 mt-3">
                <label class="form-label fw-bold">ឃុំ-សង្កាត់:</label>
                {!! showSelect(
                    'commune_id',
                    arrayCommune(request('district_id'), 1, 0, 'មិនកំណត់'),
                    old('commune_id', request('commune_id')),
                    ' select2',
                ) !!}
            </div>

        </div>
        <div class="row justify-content-end">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-1">
                <label class="form-label" style="visibility: hidden">x</label>
                <div class="input-group justify-content-end">
                    <!-- Existing Search Button -->
                    <button type="submit" class="btn btn-lg btn-success-gradien fw-bold">
                        <span class="fa fa-search me-2 text-white"></span>
                        ស្វែងរកពាក្យបណ្តឹង
                    </button>

                    <!-- ✅ New Export Excel Button -->
                    <button type="submit" name="export_excel" value="1"
                        class="btn btn-lg btn-warning-gradien fw-bold ms-2">
                        <span class="fa fa-download me-2 text-white"></span>
                        ទាញយកបញ្ជីពាក្យបណ្តឹង
                    </button>
                </div>
            </div>
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
            $('#csic1').select2();
            $('#csic2').select2();
            $('#csic3').select2();
            $('#csic4').select2();
            $('#csic5').select2();

            $('#csic1').on('change', function() {
                $("#csic2 > option").remove(); // Clear existing options
                $("#csic3 > option").remove();
                $("#csic4 > option").remove();
                $("#csic5 > option").remove();

                var csic1 = $(this).val();

                $.ajax({
                    url: "{{ url('ajaxGetCSIC2') }}/" + csic1,
                    type: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response['data'] !== null) {
                            $("#csic2").append(
                                "<option value=''>សូមជ្រើសរើស</option>"); // Default option

                            // Loop through JSON object using `for...in`
                            $.each(response['data'], function(id, name) {
                                var option = "<option value='" + id + "'>" + name +
                                    "</option>";
                                $("#csic2").append(option);
                            });
                        }
                    }
                });
            });
            $('#csic2').on('change', function() {
                $("#csic3 > option").remove();
                $("#csic4 > option").remove();
                var csic1 = $("#csic1").val();
                var csic2 = $(this).val();

                //alert(":" + business_activity1 + ", " + business_activity2);
                // Empty the dropdown
                //$('#province_id').find('option').not(':first').remove();
                // AJAX request
                $.ajax({
                    url: "{{ url('ajaxGetCSIC3') }}/" + csic1 + "/" + csic2,
                    type: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response['data'] !== null) {
                            $("#csic3").append(
                                "<option value=''>សូមជ្រើសរើស</option>"); // Default option

                            // Loop through JSON object using `for...in`
                            $.each(response['data'], function(id, name) {
                                var option = "<option value='" + id + "'>" + name +
                                    "</option>";
                                $("#csic3").append(option);
                            });
                        }
                    }
                });
            });
            $('#csic3').on('change', function() {
                $("#csic4 > option").remove();
                var csic1 = $("#csic1").val();
                var csic2 = $("#csic2").val();
                var csic3 = $(this).val();
                //alert(business_activity3);
                // Empty the dropdown
                //$('#province_id').find('option').not(':first').remove();
                // AJAX request
                $.ajax({
                    url: "{{ url('ajaxGetCSIC4') }}/" + csic1 + "/" + csic2 + "/" + csic3,
                    type: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response['data'] !== null) {
                            $("#csic4").append(
                                "<option value=''>សូមជ្រើសរើស</option>"); // Default option

                            // Loop through JSON object using `for...in`
                            $.each(response['data'], function(id, name) {
                                var option = "<option value='" + id + "'>" + name +
                                    "</option>";
                                $("#csic4").append(option);
                            });
                        }
                    }
                });
            });
            $('#csic4').on('change', function() {
                $("#csic5 > option").remove();
                var csic1 = $("#csic1").val();
                var csic2 = $("#csic2").val();
                var csic3 = $("#csic3").val();
                var csic4 = $(this).val();
                //alert(business_activity3);
                // Empty the dropdown
                //$('#province_id').find('option').not(':first').remove();
                // AJAX request
                $.ajax({
                    url: "{{ url('ajaxGetCSIC5') }}/" + csic1 + "/" + csic2 + "/" + csic3 + "/" +
                        csic4,
                    type: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response['data'] !== null) {
                            $("#csic5").append(
                                "<option value=''>សូមជ្រើសរើស</option>"); // Default option

                            // Loop through JSON object using `for...in`
                            $.each(response['data'], function(id, name) {
                                var option = "<option value='" + id + "'>" + name +
                                    "</option>";
                                $("#csic5").append(option);
                            });
                        }
                    }
                });
            });
            $("#province_id").on('change', function() {
                var province_id = $("#province_id").val(); //main level

                //$("#district_id").select2("val", "");
                $("#district_id > option").remove(); //first of all clear select items
                //$("#commune_id").select2("val", "");
                $("#commune_id > option").remove(); //first of all clear select items
                get_district_data(province_id);
            });
            $("#district_id").on('change', function() {
                var district_id = $("#district_id").val(); //main level
                //$("#commune_id").select2("val", "");
                $("#commune_id > option").remove(); //first of all clear select items
                get_commune_data(district_id);
            });

            function get_district_data(province_id) {
                $.ajax({
                    url: "{{ url('ajaxGetDistrict') }}/" + province_id,
                    type: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(response) {
                        // alert("Get Province");
                        // $.each(result,function(val,label)
                        // {
                        //     var opt = $('<option />');
                        //     //alert(opt);
                        //     opt.val(val);
                        //     opt.text(label);
                        //     $('#k_province_id').append(opt);
                        // });//end $.each
                        //alert("success");
                        var len = 0;
                        if (response['data'] != null) {
                            len = response['data'].length;
                        }
                        if (len > 0) {
                            // Read data and create <option >
                            $("#district_id").append("<option value='0'>សូមជ្រើសរើស</option>");
                            for (var i = 0; i < len; i++) {
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='" + id + "'>" + name + "</option>";
                                $("#district_id").append(option);
                            }
                        }
                    } //end success
                }); //end $.ajax
            }

            function get_commune_data(district_id) {
                $.ajax({
                    url: "{{ url('ajaxGetCommune') }}/" + district_id,
                    type: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(response) {
                        var len = 0;
                        if (response['data'] != null) {
                            len = response['data'].length;
                        }
                        if (len > 0) {
                            // Read data and create <option >
                            $("#commune_id").append("<option value='0'>សូមជ្រើសរើស</option>");
                            for (var i = 0; i < len; i++) {
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='" + id + "'>" + name + "</option>";
                                $("#commune_id").append(option);
                            }
                        }

                    } //end success
                }); //end $.ajax
            }
        });
    </script>
    <script>
        $(document).on('click', '#toggleButton', function(e) {
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
    <script>
        $(function() {
            $("#start_date, #end_date").datepicker({
                dateFormat: "dd/mm/yy", // display format
                changeMonth: true,
                changeYear: true
            });

            // Optional: Set min/max relationship
            $("#start_date").on("change", function() {
                $("#end_date").datepicker("option", "minDate", $(this).datepicker("getDate"));
            });
            $("#end_date").on("change", function() {
                $("#start_date").datepicker("option", "maxDate", $(this).datepicker("getDate"));
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <!-- Plugins Select2-->
    <script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>
@endpush
