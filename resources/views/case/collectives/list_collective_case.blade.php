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
                                    <div class="bg-primary text-center div_number text-hanuman-22">
                                        {{ $adata['pagetitle'] }} : {{ number_format($adata['totalRecord']) }}
                                    </div>
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                        <tr>
                                            <th width="1%">ល.រ</th>
                                            <th width="13%" class="text-center text-nowrap">ប្រភេទពាក្យបណ្ដឹង</th>
                                            <th width="25%">តំណាងប្រតិភូចរចា</th>
                                            <th width="25%">សហគ្រាស គ្រឹះស្ថាន</th>
                                            {{--                                            <th width="8%">ឯកសារពាក្យបណ្ដឹង</th>--}}
                                            <th width="13%">មន្ត្រីទទួលបន្ទុក</th>
                                            <th width="25%" class="center">ដំណើរការបណ្តឹង</th>
                                            <th width="3%">សកម្មភាព</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach( $adata['collectives'] as $row )
                                            <tr>
                                                <td scope="row">
                                                    {{ $adata['collectives']->firstItem() + $loop->iteration - 1  }}
                                                </td>
                                                <td class="center">
                                                    {{ $row->caseType->case_type_name }}
                                                    @if(!empty($row->case_date))
                                                        <span class="text-danger fw-bold"><br>[{{ date2Display($row->case_date) }}]</span>
                                                    @endif
                                                    <br/><br/>

                                                    @php
                                                        $caseYear = !empty($row->case_date) ? date2Display($row->case_date,'Y') : myDate('Y');
                                                        $show_file= showFile(1, $row->collectives_case_file, pathToDeleteFile('case_doc/collectives/'.$caseYear."/"), "notdelete", "tbl_case", "id", $row->id,  "case_file", "");
                                                        if($show_file){
                                                            echo '<span class="fw-bold">ឯកសារពាក្យបណ្ដឹង</span>'."<br/".$show_file;
                                                        }
                                                    @endphp
                                                </td>
                                                <td>
                                                    <div class="purple">
                                                        @if(count($row->collectivesRepresentatives) > 0)
                                                            @foreach($row->collectivesRepresentatives as $cRepre)
                                                                <label class="form-label fw-bold blue">- {{ $cRepre->fullname }}</label><br/>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="purple fw-bold">
                                                        {!! $row->company->company_name_khmer !!}
                                                        @if(!empty($row->company->company_name_latin))
                                                            <br>{{ $row->company->company_name_latin }}
                                                        @endif
                                                    </div>

                                                </td>
                                                {{--                                                <td>--}}
                                                {{--                                                    @php--}}
                                                {{--                                                        $caseYear = !empty($row->case_date) ? date2Display($row->case_date,'Y') : myDate('Y');--}}
                                                {{--                                                        $show_file= showFile(1, $row->case_file, pathToDeleteFile('case_doc/form1/'.$caseYear."/"), "notdelete", "tbl_case", "id", $row->id,  "case_file", "");--}}
                                                {{--                                                        if($show_file){--}}
                                                {{--                                                            echo $show_file;--}}
                                                {{--                                                        }--}}
                                                {{--                                                    @endphp--}}
                                                {{--                                                </td>--}}
                                                <td class="text-nowrap">
                                                    <div style="line-height: 28px;">
                                                        អ្នកផ្សះផ្សារ:<br>
                                                        <label class="form-label fw-bold text-danger">{!! getCaseOfficerName($row->id, 0, 6) !!}</label>
{{--                                                        <br>--}}
{{--                                                        អ្នកកត់ត្រា:<br>--}}
{{--                                                        <span class="text-danger">{!! getCaseOfficer($row->id, 0, 8) !!}</span>--}}
                                                    </div>
                                                </td>
                                                <td  class="center" style="line-height: 28px">
                                                    @php
                                                        $caseStatus = generateCollectivesCaseStatus($row);
                                                        echo displayCaseStatus($caseStatus);
                                                    @endphp
                                                    <br>
                                                    <a class="btn btn-success-gradien custom" href="{{ url('collective_cases/'.$row->id) }}" title="មើលដំណើរការបណ្ដឹង" target="_blank">មើលដំណើរការបណ្ដឹង</a>
                                                </td>
                                                <td style="line-height: 40px;">
                                                    <a class="btn btn-warning-gradien custom" href="{{ url('collective_cases/'.$row->id.'/edit') }}" title="Edit" target="_blank"><i data-feather="edit"></i>
                                                    </a>
                                                    <br>
                                                    <form action="{{ url('collective_cases/'.$row->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" title="Delete" class="btn btn-danger-gradien delete-btn">
                                                            <i data-feather="trash"></i>
                                                        </button>
                                                    </form>
                                                    <a class="btn btn-primary custom" href="{{ url('export/word/case/'.$row->id) }}" title="ទាញយកពាក្យបណ្ដឹង" target="_blank"><i data-feather="download"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    <div class="pagination" >
                                        @if( $adata['collectives']->hasPages() )
                                            {!! $adata['collectives']->links('pagination::bootstrap-5') !!}
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
                $('#domainID').select2();
                $('#statusID').select2();

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
