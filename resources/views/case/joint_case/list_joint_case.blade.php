<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
    </x-slot>
    {{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">--}}
    <style>
        a:hover {
            color: red;
            text-decoration: underline;
        }
    </style>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-hanuman">
                            @if($adata['opt_search'] == "quick")
                                @include("case.quick-search")
                            @else
                                @include("case.advance-search")
                            @endif
                        </div>
                        <div class="card-block row">
                            <div class="col-sm-12 col-lg-12 col-xl-12">
                                <div class="table-responsive">
                                    <div class="bg-secondary text-center div_number text-hanuman-22">
                                        {{ $adata['pagetitle'] }} : {{ number_format($adata['totalRecord']) }}
                                    </div>
                                    <table class="table">
                                        <thead class="table-primary">
                                        <tr>
                                            <th scope="col">លរ</th>
                                            <th scope="col" class="text-center">ឈ្មោះសហគ្រាស គ្រឹះស្ថាន</th>
                                            <th scope="col" class="text-center">អាស័យដ្ឋាន</th>
                                            <th scope="col" class="text-center">អំពីកម្មករ</th>
                                            <th scope="col" class="text-center">អង្គភាពទទួលបន្ទុក</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach( $adata['jointCases'] as $row )
                                            <tr>
                                                <td scope="row">
                                                    {{ $adata['jointCases']->firstItem() + $loop->iteration - 1  }}
                                                </td>
                                                <td class="center text-nowrap">
                                                    <div class="purple fw-bold">
                                                        {{ $row->company->company_name_khmer }}
                                                        <span class="red">@if(!empty($row->company->company_name_latin))<br>({{ $row->company->company_name_latin }})@endif</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-nowrap">
                                                        - ខេត្ត/រាជធានី៖ <span class="red fw-bold">{{ $row->company->province->pro_khname }}</span><br/>
                                                        - ស្រុក/ខណ្ឌ/ក្រុង៖ <span class="red fw-bold">{{ $row->company->district->dis_khname }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-nowrap">
                                                        កម្មករពាក់ព័ន្ធវិវាទ៖ <span class="red fw-bold">[{{ $row->total_disputed_emp }}]</span><br/>
                                                        @if(!empty($row->total_emp))
                                                        កម្មករសរុប៖ <span class="red fw-bold">({{ $row->total_emp }})</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td  class="center text-nowrap">
                                                    <div class="purple fw-bold">
                                                        {{ $row->unit->unit_name }}<br/>
                                                        <span class="red">@if(!empty($row->responsible_person)) ({{ $row->responsible_person }}) @endif</span>
                                                    </div>
                                                </td>
                                                <td style="line-height: 40px;">
                                                    <a class="btn btn-success custom" href="{{ url('joint_cases/'.$row->id.'/edit') }}" title="កែប្រែពាក្យបណ្តឹង" target="_blank"><i data-feather="edit"></i>
                                                    </a>
                                                    <br>
                                                    <form action="{{ url('joint_cases/'.$row->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" title="លុបពាក្យបណ្តឹង" class="btn btn-danger delete-btn">
                                                            <i data-feather="trash"></i>
                                                        </button>
                                                    </form>
                                                    <a class="btn btn-primary custom" href="#" title="ទាញយកពាក្យបណ្ដឹង" target="_blank"><i data-feather="download"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    <div class="pagination" >
                                        @if( $adata['jointCases']->hasPages() )
                                            {!! $adata['jointCases']->links('pagination::bootstrap-5') !!}
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
            document.addEventListener('DOMContentLoaded', function () {
                // Attach SweetAlert2 confirmation to delete button
                const deleteButtons = document.querySelectorAll('.delete-btn');

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        Swal.fire({
                            title: 'តើអ្នកពិតជាចង់លុប មែនឫ?',
                            //text: 'You will not be able to recover this data!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'លុបចោល',
                            cancelButtonText: 'អត់ទេ'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // If user confirms, submit the form
                                button.closest('form').submit();
                            }
                        });
                    });
                });
            });
        </script>
        <script>
            function comfirm_delete_steetalert2(my_url, message){
                Swal.fire({
                    title: 'Are you sure?',
                    // text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        alert(my_url);
                        window.location.href=my_url;
                    }
                });
            }

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
        @include('script.my_sweetalert2')
    </x-slot>
</x-admin.layout-main>
