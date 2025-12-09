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
                        <div class="text-hanuman">
                            @if($adata['opt_search'] == "quick")
                                @include("case.invitation.quick-search")
                            @else
                                @include("case.advance-search")
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
                                            <th width="2%">លរ</th>
                                            <th width="13%">ប្រភេទពាក្យបណ្ដឹង</th>
                                            <th width="13%">ប្រភេទលិខិតអញ្ជើញ</th>
                                            <th width="30%">ឈ្មោះកម្មករនិយោជិត/ឈ្មោះសហគ្រាស គ្រឹះស្ថាន</th>
                                            <th width="8%" class="center">កាលបរិច្ឆេទចេញលិខិត</th>
                                            <th>កាលបរិច្ឆេទជួប</th>
                                            <th width="10%">ទាញយកលិខិតអញ្ជើញ</th>
                                            <th width="12%" class="center">កែតម្រូវ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach( $adata['letters'] as $row )

                                            <tr>
                                                <td>
                                                    {{ $adata['letters']->firstItem() + $loop->iteration - 1  }}
                                                </td>
                                                <td>{{ $row->case->caseType->case_type_name }}</td>
                                                <td>{{ $row->invitationType->invitation_type_name }}</td>
                                                <td>

                                                    @if($row->invitationType->employee_or_company == 1)
                                                    <div class="purple">{{ $row->disputant->name }}</div>
                                                    <b>លេខទូរស័ព្ទ: </b> {{ $row->case->caseDisputant->phone_number }}
                                                    @else
                                                        <div class="purple">{{ $row->company->company_name_khmer }}</div>
                                                        <b>លេខទូរស័ព្ទ: </b> {{ $row->caseCompany->log5_company_phone_number }}
                                                    @endif
                                                </td>
                                                <td class="center">
                                                    {{ date2Display($row->letter_date) }}
                                                </td>
                                                <td>
                                                    {{ date2Display($row->meeting_date) }}
                                                    <br>ម៉ោង {{ $row->meeting_time }}
                                                </td>
                                                <td class="text-center">
                                                    <a class="btn btn-info custom form-control" href="{{ url('export/word/invitation/'.$row->id) }}" title="Download" target="_blank">ទាញយកលិខិត
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a class="btn btn-success me-2" href="{{ url('invitations/'.$row->id.'/edit') }}" title="Edit" target="_blank"><i data-feather="edit"></i>
                                                        </a>

                                                        {{--                                                    @php--}}
                                                        {{--                                                        $del_url_ajax= url('inspection/delete_inspection/'.$row->id);--}}
                                                        {{--                                                            $onClick2="comfirm_delete_steetalert2('".$del_url_ajax."', '".$row->company_id."','Do you want to delete this data?')";--}}
                                                        {{--                                                        $str2='<button type="submit" class="btn btn-danger custom" onClick="'.$onClick2.'" title="Delete Data"><i data-feather="trash"></i></button>';--}}
                                                        {{--                                                    @endphp--}}
                                                        {{--                                                    {!! OnlySuperAccess($str2) !!}--}}

                                                        <form action="{{ url('invitations/'.$row->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger delete-btn">
                                                                <i data-feather="trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    <div class="pagination" >
                                        @if( $adata['letters']->hasPages() )
                                            {!! $adata['letters']->links('pagination::bootstrap-5') !!}
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
                            title: 'Are you sure?',
                            //text: 'You will not be able to recover this data!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!'
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
