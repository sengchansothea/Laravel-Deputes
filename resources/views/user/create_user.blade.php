<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    {{--                    <div class="card-header">--}}
                    {{--                        Header--}}
                    {{--                    </div>--}}
                    <form class="theme-form needs-validation" name="formCreateUser" action="{{ route('user.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="card-block row">
                                <div class="col-sm-12 col-lg-12 col-xl-12">
                                    <div class="row col-12">
                                        <div class="form-group col-3">
                                            <label class="mb-1 fw-bold" for="fullname">{{ __('general.k_ownername') }}</label>
                                            <input type="text" name="fullname" minlength="4" value="{{ old('fullname') }}" class="form-control" id="fullname" placeholder="Full Name" required>
                                            @error('fullname')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-3">
                                            <label class="mb-1 fw-bold " for="username"><span class="label label-success fw-bold p-1" style="font-size: 15px">
                                                    ឈ្មោះ Login (Username)
                                                </span></label>
                                            <input type="text" name="username" minlength="4" value="{{ old('username') }}" class="form-control" id="username" placeholder="Enter Username" autocomplete="off"  required >
                                            @error('username')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-3">
                                            <label class="mb-1 fw-bold" for="fullname">អាសយដ្ឋានអ៊ីមែល</label>
                                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" id="email" placeholder="example@gmail.com" required>
                                            @error('email')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-3">
                                            <label class="mb-1 fw-bold">នាយកដ្ឋាន</label>
                                            {!! showSelect('k_department_id',arrayDepartment(6), old('k_department_id', 1)) !!}
                                        </div>
                                    </div>
{{--                                    <br/>{{ dd(getOfficerRoleName(45)) }}--}}
                                    <div class="row col-12 mt-3">
                                        <div class="form-group col-3">
                                            <label class="fw-bold mb-1">{{ __('general.user_type') }}</label>
                                            {!! showSelect('k_category_id',myArrUserType(0,1), old('k_category_id', request('k_category_id'))) !!}
                                            @error('k_category_id')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div id="div_officer_id" class="col-3 form-group" >
                                            <label class="fw-bold mb-1">ឈ្មោះមន្ត្រី</label>
                                            {!! showSelect('officer_id',arrOfficerWithoutUser(1), old('officer_id')) !!}
                                            @error('officer_id')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <!-- Officer Role (auto-filled) -->
                                        <div id="div_officer_role_id" class="col-3 form-group" >
                                            <label class="fw-bold mb-1">តួនាទី (Role)</label>
                                            {!! showSelect('officer_role_id',array(), old('officer_role_id')) !!}
                                        </div>

{{--                                        <div id="div_k_role_id" class="col-3 form-group" >--}}
{{--                                            <label class="fw-bold mb-1">{{ __('general.level') }}</label>--}}
{{--                                            {!! showSelect('k_role_id',array(), old('k_role_id', 3)) !!}--}}
{{--                                        </div>--}}
                                        <div id="div_k_province" class="form-group col-3" >
                                            <label class="fw-bold mb-1" for="fullname">រាជធានី/ខេត្ត</label>
                                            {!! showSelect('k_province_id',array(), old('k_province_id', request('k_province_id'))) !!}
                                            @error('k_province_id')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>

{{--                                        <div id="div_insp_group" class="col-3 form-group" >--}}
{{--                                            <label>{{ __('general.user_group') }}</label>--}}
{{--                                            {!! showSelect('k_insp_group_id',array(), old('k_insp_group_id', request('k_insp_group_id'))) !!}--}}
{{--                                        </div>--}}
                                    </div>
                                    <br/>
                                    <div class="row col-12">
                                        <div class="form-group col-3">
                                            <label class="fw-bold mb-1" for="password">ពាក្យសម្ងាត់ (តិចបំផុត៤ខ្ទង់)</label>
                                            <input type="password" name="password" minlength="8" value="{{ old('password') }}" class="form-control" id="password" placeholder="Password" required >
                                            @error('password')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-3">
                                            <label class="mb-1 fw-bold" for="confirm_password">ពាក្យសម្ងាត់ម្ដងទៀត</label>
                                            <input type="password" name="password_confirmation" minlength="8" value="" class="form-control" id="confirm_password" placeholder="Confirm Password" oninput="check(this)" required >
                                            @error('password_confirmation')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-success fw-bold">បង្កើតអ្នកប្រើប្រាស់</button>
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
        @include('script.my_sweetalert2')
        <!-- Plugins Select2-->
        <script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('#k_category_id').select2();
                $('#officer_id').select2();
                $('#officer_role_id').select2();
                $('#k_province_id').select2();
                $('#k_department_id').select2();

                // Hide Div on Start Up
                $('#div_officer_id').hide("fast");
                $('#div_officer_role_id').hide('fast');
                $("#div_k_province").hide("fast");
                getRoleData();
                $('#div_officer_id').change(function (){
                    $("#officer_role_id").select2("val", "");
                    $("#officer_role_id > option").remove(); //first of all clear select items

                    getRoleData();
                });

                $("#k_category_id").change(function() {
                    var k_category = $("#k_category_id").val();//main level

                    $("#officer_role_id").select2("val", "");
                    $("#officer_role_id > option").remove(); //first of all clear select items
                    $("#k_province_id").select2("val", "");
                    $("#k_province_id > option").remove(); //first of all clear select items

                    // $("#k_insp_group_id").select2("val", "");
                    // $("#k_insp_group_id > option").remove(); //first of all clear select items
                    if(k_category == 0 || k_category == 1 || k_category == 2){ // User Type:  1=>Super, 2=>Master, 3=>ថ្នាក់កណ្តាលុ, 4=>ថ្នាក់មន្ទីរ
                        province_id = 12;
                        $('#div_officer_role_id').hide("fast");
                        $('#div_officer_id').hide("fast");
                        $('#div_k_role_id').hide("fast");
                        $("#div_k_province").hide("fast");
                        // getRoleData(k_category);
                    }
                    else if(k_category == 3){ // ថ្នាក់កណ្តាល (Ministry)
                        // alert("Ministry");
                        province_id = 12;
                        $('#div_officer_id').show("fast");
                        $("#div_k_province").hide("fast");
                        // getInspGroupData(12);
                        getRoleData();

                    }
                    else if(k_category == 4){ //Province
                        $("#div_k_province").show("fast");
                        $('#div_officer_id').hide("fast");
                        $('#div_officer_role_id').hide("fast");
                        // $('#div_insp_group').hide("fast");
                        getProvinceData();


                    }else{
                        $("#div_k_province").hide("fast");
                        $('#div_k_role_id').hide("fast");
                        $('#div_officer_role_id').hide("fast");
                    }

                });
                function getProvinceData(){
                    $.ajax({
                        type: "GET",
                        url: "{{ url('ajaxGetProvince') }}",
                        data: {"_token": "{{ csrf_token() }}"},
                        success: function(result)
                        {
                            $.each(result,function(val,label)
                            {
                                var opt = $('<option />');
                                // alert(opt);
                                opt.val(val);
                                opt.text(label);
                                $('#k_province_id').append(opt);
                            });//end $.each

                        }//end success
                    });//end $.ajax
                }

                function getRoleData(){
                    var officerID = $("#officer_id").val();
                    if(officerID >0){
                        $('#div_officer_role_id').show("fast");
                    }else{
                        $('#div_officer_role_id').hide("fast");
                    }
                    $.ajax({
                        type: "GET",
                        {{--url: "{{ url('ajaxGetRole') }}",--}}
                        url: "{{ url('ajaxGetRole') }}" +"/"+ officerID ,
                        data: {"_token": "{{ csrf_token() }}"},
                        success: function(result)
                        {
                            $.each(result,function(val,label)
                            {
                                var opt = $('<option />');
                                // alert(opt);
                                opt.val(val);
                                opt.text(label);
                                $('#officer_role_id').append(opt);
                            });//end $.each

                        }//end success
                    });//end $.ajax
                }

                function getInspGroupData( province_id = 12 ){
                    //alert(province_id);
                    $.ajax({
                        type: "GET",
                        url: "{{ url('ajaxGetInspGroup') }}" +"/"+ province_id ,
                        data: {"_token": "{{ csrf_token() }}"},
                        success: function(result)
                        {
                            $.each(result,function(val,label)
                            {
                                var opt = $('<option />');
                                //alert(opt);
                                opt.val(val);
                                opt.text(label);
                                $('#k_insp_group_id').append(opt);
                            });//end $.each

                        }//end success
                    });//end $.ajax
                }//end func

                /* User Level */
                $('#k_role_id, #k_province_id').change(function(){ //any select change on the dropdown with id country trigger this code
                    // $("#k_insp_group_id").select2("val", "");
                    // $("#k_insp_group_id > option").remove(); //first of all clear select items
                    var k_role_id = $('#k_role_id').val();
                    var province_id = $("#k_province_id").val();
                    if(province_id == null)
                        province_id = 12;
                    // $('#div_insp_group').hide("fast");

                    // if(k_role_id == 1){
                    //     $('#div_insp_group').show("fast");
                    //     getInspGroupData(province_id);
                    // }else{
                    //     $('#div_insp_group').hide("fast");
                    // }

                });
            });
        </script>
    </x-slot>
</x-admin.layout-main>
