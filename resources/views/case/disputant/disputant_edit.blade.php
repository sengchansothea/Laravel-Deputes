@php
    $diputant = $adata['disputant'];

    $disPOBProID = $diputant->pob_province_id;
    $disPOBDisID = $diputant->pob_district_id;
    $arrDiputantDOBDistrict = $disPOBProID > 0 ? arrayDistrict($disPOBProID, 1) : array('0' => 'សូមជ្រើសរើស');
    $arrDiputantDOBCommune = $disPOBDisID > 0 ? arrayCommune($disPOBDisID, 1) : array('0' => 'សូមជ្រើសរើស');

@endphp
{{--{{ dd($diputant) }}--}}
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/date-picker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <form class="" name="frm_disputant" id="frm_disputant" action="{{ route('disputant.update', $diputant->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                <div class="card">
                    <div class="card-header text-danger fw-bold">
                        *** គូវិវាទមានដូចជា៖ កម្មករនិយោជិត / តំណាងកម្មករនិយោជិត / និយោជក / តំណាងនិយោជក / អ្នកបកប្រែ
                    </div>
                    <div class="card-body">
                        <div class="card-block row">
                            <div class="col-sm-12 col-lg-12 col-xl-12">
                                <div class="mb-3 row col-12">
                                    <div class="form-group col-md-4 col-sm-12 mt-3">
                                        <label class="fw-bold required pb-2">ឈ្មោះគូវិវាទ</label>
                                        <input type="text" name="disputant_name" id="officer_name_khmer" value="{{ old('disputant_name', $diputant->name) }}" class="form-control" />
                                        @error('disputant_name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-2 col-sm-12 mt-3">
                                        <label class="fw-bold required pb-2">ភេទ</label>
                                        {!! showSelect('disputant_gender',arrayGender(), old('disputant_gender', $diputant->gender)) !!}
                                        @error('disputant_gender')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12 mt-3">
                                        <label class="fw-bold required pb-2">ថ្ងៃខែឆ្នាំកំណើត</label>
                                        <input name="disputant_dob" id="disputant_dob"  class="datepicker-here form-control digits disputant_dob" type="text" data-language="en" value="{{ old('disputant_dob', date2Display($diputant->dob)) }}">
                                        @error('disputant_dob')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12 mt-3">
                                        <label class="fw-bold required pb-2">សញ្ជាតិ</label>
                                        {!! showSelect('disputant_nationality', arrayNationality(1),old('disputant_nationality', $diputant->nationality),"fw-bold", "", "", "required") !!}
                                        @error('disputant_nationality')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row col-12">
                                    <div class="form-group col-md-4 col-sm-12 mt-3" id="showHideIdNumber">
                                        <label class="fw-bold required pb-2">លេខអត្តសញ្ញាណបណ្ណ/ប៉ាស្ព័រ</label>
                                        <input class="form-control" value="{{ old('disputant_id_number', $diputant->id_number) }}" name="disputant_id_number" id="disputant_id_number" type="text" aria-describedby="" placeholder="">
                                        @error('disputant_id_number')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @php
                                        $display = "";
                                        if($diputant->nationality != 33){
                                            $display = "display: none";
                                        }
                                    @endphp
                                    <div class="form-group col-md-8 col-sm-12 mt-3" style="{{ $display }}" id="add_abroad">
                                        <label class="fw-bold required pb-2">ទីកន្លែងកំណើតក្រៅប្រទេស</label>
                                        <input name="disputant_address_abroad" class="form-control" type="text" value="{{ $diputant->pob_address_abroad }}" placeholder="">
                                        @error('disputant_address_abroad')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                @php
                                    $displayPOB = "";
                                    if($diputant->nationality == 33){
                                        $displayPOB = "display: none";
                                    }
                                @endphp
                                <div class="mb-3 row col-12" style="{{ $displayPOB }}" id="displayPOBAdd">
                                    <div class="form-group col-md-4 col-sm-12 mt-3">
                                        <label class="fw-bold required pb-2">កើតនៅៈ ខេត្ត/រាជធានី</label>
                                        {!! showSelect('disputant_pob_province', arrayProvince(1), old('disputant_pob_province', $diputant->pob_province_id ), "select2", "", "", "required") !!}
                                        @error('disputant_pob_province')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-sm-12 mt-3">
                                        <label class="fw-bold required pb-2">ស្រុក/ខណ្ណ</label>
                                        {!! showSelect('disputant_pob_district', $arrDiputantDOBDistrict, old('disputant_pob_district', $diputant->pob_district_id)) !!}
                                        @error('disputant_pob_district')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-sm-12 mt-3">
                                        <label class="fw-bold required pb-2">ឃុំ/សង្កាត់</label>
                                        {!! showSelect('disputant_pob_commune', $arrDiputantDOBCommune, old('disputant_pob_commune', $diputant->pob_commune_id)) !!}
                                        @error('disputant_pob_commune')
                                        <div class="text-danger mt-2">{{ $message }}</div>
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
        <script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ rurl('assets/js/time-picker/jquery-clockpicker.min.js') }}"></script>
        <script src="{{ rurl('assets/myjs/sweetalert2.10.10.1.all.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#disputant_gender').select2();
                $('#disputant_nationality').select2();
                $('#disputant_pob_province').select2();
                $('#disputant_pob_district').select2();
                $('#disputant_pob_commune').select2();


                $("#disputant_dob").keydown(function(event) {
                    return false;
                });

                // $('#disputant_nationality').trigger("change");
                showHidePOBAddressAbroad();


                /** Show/Hide POB Address Abroad */
                $('#disputant_nationality').on('change', function (){
                    showHidePOBAddressAbroad();
                });
                /** Function Show/Hide POB Address And Abroad */
                function showHidePOBAddressAbroad(){
                    var disputantNationality = $('#disputant_nationality').val();
                    if(disputantNationality == 0){
                        $('#showHideIdNumber').hide('fast');
                        $('#add_abroad').hide('fast');
                        $('#displayPOBAdd').hide('fast');
                    }
                    else if(disputantNationality == 33){
                        $('#showHideIdNumber').show('fast');
                        $('#add_abroad').hide('fast');
                        $('#displayPOBAdd').show('fast');
                    }else{
                        $('#showHideIdNumber').show('fast');
                        $('#add_abroad').show('fast');
                        $('#displayPOBAdd').hide('fast');
                    }
                }
                /** Disputant Employee DOB Province */
                $('#disputant_pob_province').on('change', function (){
                    $("#disputant_pob_district > option").remove(); //first of all clear select items
                    $("#disputant_pob_commune > option").remove();
                    var disDOBProID = $(this).val();
                    // var empProName = $(this).find('option:selected').text();

                    $.ajax({
                        url: "{{ url('ajaxGetDistrict') }}/"+ disDOBProID,
                        type: 'get',
                        data : {"_token":"{{ csrf_token() }}"},
                        dataType: 'json',
                        success: function(response){
                            //alert("success");
                            var len = 0;
                            if(response['data'] != null){
                                len = response['data'].length;
                            }
                            if(len > 0){
                                // Read data and create <option >
                                $("#disputant_pob_district").append("<option value=''>សូមជ្រើសរើស</option>");
                                for(var i = 0; i < len; i++){
                                    var id = response['data'][i].id;
                                    var name = response['data'][i].name;
                                    var option = "<option value='"+id+"'>"+name+"</option>";
                                    $("#disputant_pob_district").append(option);
                                }
                            }
                        }
                    });
                });

                /** Disputant Employee DOB District */
                $('#disputant_pob_district').on('change', function() {
                    $("#disputant_pob_commune > option").remove();

                    var disDOBDisID = $(this).val();

                    $.ajax({
                        url: "{{ url('ajaxGetCommune') }}/"+ disDOBDisID,
                        type: 'get',
                        data : {"_token":"{{ csrf_token() }}"},
                        dataType: 'json',
                        success: function(response){
                            var len = 0;
                            if(response['data'] != null){
                                len = response['data'].length;
                            }
                            if(len > 0){
                                // Read data and create <option >
                                $("#disputant_pob_commune").append("<option value=''>សូមជ្រើសរើស</option>");
                                for(var i=0; i<len; i++){
                                    var id = response['data'][i].id;
                                    var name = response['data'][i].name;
                                    var option = "<option value='"+id+"'>"+name+"</option>";
                                    $("#disputant_pob_commune").append(option);
                                }
                            }
                        }
                    });
                });
            });
        </script>
    </x-slot>
</x-admin.layout-main>
