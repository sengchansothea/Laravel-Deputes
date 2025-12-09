<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">






    </x-slot>
    {{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">--}}



    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header text-hanuman">
                            @if($adata['opt_search'] == "quick")
                                @include("company.quick-search")
                            @else
                                @include("company.advance-search")
                            @endif
                        </div>
                        <div class="card-block row">
                            <div class="col-sm-12 col-lg-12 col-xl-12">
                                <div class="table-responsive">
                                    <div class="bg-primary text-center div_number text-hanuman-22">
                                        {{ $adata['pagetitle'] }} : {{ number_format($adata['totalRecord']) }}
                                    </div>


                                    <table class="table">
                                        <thead class="table-primary">
                                        <tr>
                                            <th width="5%">លរ</th>
                                            <th scope="col">ឈ្មោះរោងចក្រ សហគ្រាស</th>
                                            <th width="18%" class="center">សកម្មភាពសេដ្ឋកិច្ច</th>
                                            <th width="12%" class="center">រាជធានី-ខេត្ត</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach( $adata['companys'] as $row )
                                            <tr>
                                                <th scope="row">
                                                    {{ $adata['companys']->firstItem() + $loop->iteration - 1  }}
                                                </th>
                                                <td>
                                                    <div class="purple">{{ $row->company_name_khmer }}</div>
                                                    {{ $row->company_name_latin }}
                                                    <br> លេខចុះបញ្ជី: {{ $row->company_register_number }}
                                                    {!! nbs(5) !!} TIN: {{ $row->company_tin }}
                                                    @php
                                                        $emp_status = "ចំនួនប៉ាន់ស្មាន";
                                                        if($row->latest_service == 7){
                                                        $emp_status = __('general.s18_7').":".date2Display($row->latest_total_emp_date);
                                                        }
                                                        elseif($row->latest_service == 8){
                                                        $emp_status = __('general.s18_8').": ".date2Display($row->latest_total_emp_date);
                                                        }
                                                        elseif($row->latest_service == 21){
                                                        $emp_status = __('general.s18_21').": ".date2Display($row->latest_total_emp_date);
                                                        }
                                                        echo "<br>ចំនួនកម្មករសរុប: ".$row->latest_total_emp.__("general.k_neak"). " (".$emp_status.")";
                                                    @endphp
                                                    <br> ឈ្មោះម្ចាស់: {{ $row->owner_khmer_name }}
                                                </td>
                                                <td class="center">
                                                    {{ $row->businessActivity->bus_khmer_name }}
                                                    <br>
{{--                                                    {!! showCompanyStatus($row->company_status); !!}--}}
{{--                                                    {!! showButtonRefreshCompanyInfoFromLacms($row->company_id) !!}--}}
                                                </td>
                                                <td class="center">
                                                    {{ $row->province->pro_khname }}
                                                    <br>({{ $row->district->dis_khname }})
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    <div class="pagination" >
                                        @if( $adata['companys']->hasPages() )
                                            {!! $adata['companys']->links('pagination::bootstrap-5') !!}
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="moreAfterScript">
        <script>
            function confirm_letter_1(str){
                var answer = confirm ("{{ '              '.__('general.letter_1_generate') }}"+"\n" + "{{ __('general.letter_1_confirm') }}"+"\n"+ "<?php //echo lang('letter_1_generate_no')?>"+"\n"+"");
                if (!answer) return false;
                else{
                    window.location=str;
                    //window.location="http://www.google.com";
                    //opener.open(http://www.google.com, '_blank');
                }
                //window.location.reload(true);
            }
            function confirm_inpsection3(str){
                var answer = confirm ("{{ __('general.inspection_3_confirm') }}");
                if (!answer) return false;
                else{
                    window.location=str;
                    //window.location="http://www.google.com";
                    //opener.open(http://www.google.com, '_blank');
                }
                //window.location.reload(true);
            }
            function required(inputtx)
            {
                if ($('#search').val() == '') {
                    return false;
                }
                else
                    return true;
            }
            function confirm_restore(str){
                var answer = confirm ("Do you want to active this user?\n");
                if (!answer) return false;
                else window.location=str;
            }
            function confirm_delete(str){
                var answer = confirm ("Do you want to delete this data?\n The Data are removed and could not restore.");
                if (!answer) return false;
                else window.location=str;
            }
        </script>
        <script>
            $(document).ready(function() {
                $('#business_activity').select2();
                $('#total_emp').select2();

                $('#insp_status').select2();
                $('#province_id').select2();
                $('#district_id').select2();
                $('#commune_id').select2();

                $("#province_id").change(function() {
                    var province_id= $("#province_id").val();//main level

                    $("#district_id").select2("val", "");
                    $("#district_id > option").remove(); //first of all clear select items
                    $("#commune_id").select2("val", "");
                    $("#commune_id > option").remove(); //first of all clear select items
                    get_district_data(province_id);
                });
                $("#district_id").change(function() {
                    var district_id= $("#district_id").val();//main level

                    $("#commune_id").select2("val", "");
                    $("#commune_id > option").remove(); //first of all clear select items
                    get_commune_data(district_id);
                });
                function get_district_data(province_id){
                    $.ajax({
                        url: "{{ url('ajaxGetDistrict') }}/" + province_id,
                        type: 'get',
                        data : {"_token":"{{ csrf_token() }}"},
                        dataType: 'json',
                        success: function(response)
                        {
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
                            if(response['data'] != null){
                                len = response['data'].length;
                            }
                            if(len > 0){
                                // Read data and create <option >
                                $("#district_id").append("<option value='0'>Please Select</option>");
                                for(var i=0; i<len; i++){
                                    var id = response['data'][i].id;
                                    var name = response['data'][i].name;
                                    var option = "<option value='"+id+"'>"+name+"</option>";
                                    $("#district_id").append(option);
                                }
                            }

                        }//end success
                    });//end $.ajax
                }
                function get_commune_data(district_id){
                    $.ajax({
                        url: "{{ url('ajaxGetCommune') }}/" + district_id,
                        type: 'get',
                        data : {"_token":"{{ csrf_token() }}"},
                        dataType: 'json',
                        success: function(response)
                        {
                            var len = 0;
                            if(response['data'] != null){
                                len = response['data'].length;
                            }
                            if(len > 0){
                                // Read data and create <option >
                                $("#commune_id").append("<option value='0'>Please Select</option>");
                                for(var i=0; i<len; i++){
                                    var id = response['data'][i].id;
                                    var name = response['data'][i].name;
                                    var option = "<option value='"+id+"'>"+name+"</option>";
                                    $("#commune_id").append(option);
                                }
                            }

                        }//end success
                    });//end $.ajax
                }
            });
        </script>
        <!-- Plugins Select2-->
        <script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>
        @include('inspection.normal_garment.script.my_sweetalert2')
    </x-slot>
</x-admin.layout-main>
