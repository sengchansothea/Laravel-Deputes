@php
    $user = $adata['user'];
@endphp
{{--{{ dd($user) }};--}}
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
                    <form class="needs-validation" name="formUpdateUser" action="{{ url('user'.'/'.$user->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}" />
                        <input type="hidden" name="original_officer_id" value="{{ $user->officer_id }}" />
                        <div class="card-body">
                            <div class="card-block row">
                                <div class="col-sm-12 col-lg-12 col-xl-12">
                                    <div class="row col-12">
                                        <div class="form-group col-3">
                                            <label class="form-label mb-2 fw-bold" for="fullname">{{ __('general.k_ownername') }}</label>
                                            <input type="text" name="fullname" minlength="4" value="{{ old('fullname',$user->k_fullname) }}" class="form-control" id="fullname" placeholder="Full Name" required>
                                            @error('fullname')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-3">
                                            <label class="form-label mb-2 fw-bold" for="username"><span class="label label-success fw-bold" style="font-size: 15px">ឈ្មោះ Login (Username)</span></label>
                                            <input type="text" name="username" minlength="4" value="{{ old('username', $user->username) }}" class="form-control" id="username" placeholder="Enter Username" autocomplete="off"  required >
                                            @error('username')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-3">
                                            <label class="mb-1 fw-bold" for="fullname">អាសយដ្ឋានអ៊ីមែល</label>
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" id="email" placeholder="example@gmail.com" required>
                                            @error('email')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-3">
                                            <label class="form-label mb-2 fw-bold">នាយកដ្ឋាន</label>
                                            {!! showSelect('k_department_id',arrayDepartment(6), old('k_department_id', $user->department_id)) !!}
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row col-12">
                                        <div class="form-group col-3">
                                            <label class="form-label mb-2 fw-bold">{{ __('general.user_type') }}</label>
                                            {!! showSelect('k_category_id',myArrUserType(0,1), old('k_category_id', $user->k_category)) !!}
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
                                            {!! showSelect('officer_role_id',myArrOfficerRole($user->k_category), old('officer_role_id', $user->officer_role_id)) !!}
                                        </div>
                                        <div id="div_k_province" class="form-group col-3" >
                                            <label for="fullname" class="form-label mb-2">រាជធានី/ខេត្ត</label>
                                            {!! showSelect('k_province_id',arrayProvince(1), old('k_province_id', $user->k_province)) !!}
                                            @error('k_province_id')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
{{--                                        <div id="div_k_role_id" class="col-3 form-group" >--}}
{{--                                            <label class="form-label mb-2">{{ __('general.level') }}</label>--}}
{{--                                            {!! showSelect('k_role_id',arrayUserLevel(), old('k_role_id', $user->k_role_id)) !!}--}}
{{--                                        </div>--}}

                                    </div>

                                    <br/>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-success fw-bold">កែប្រែពត៌មាន</button>
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

                $("#div_k_province").hide("fast");
                $('#div_k_role_id').hide("fast");
                $('#div_insp_group').hide("fast");

                getRoleData();
                getKCategory();

                $('#div_officer_id').change(function (){
                    $("#officer_role_id").select2("val", "");
                    $("#officer_role_id > option").remove(); //first of all clear select items

                    getRoleData();
                });

                $("#k_category_id").change(function() {
                    getKCategory();
                });

                function getKCategory(){
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
                }

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
                        url: "{{ url('ajaxGetRole') }}" +"/"+ officerID ,
                        data: {"_token": "{{ csrf_token() }}"},
                        success: function(result)
                        {
                            $.each(result,function(val,label)
                            {
                                var opt = $('<option />');
                                //alert(opt);
                                opt.val(val);
                                opt.text(label);
                                $('#officer_role_id').append(opt);
                            });//end $.each

                        }//end success
                    });//end $.ajax
                }

            });
        </script>
    </x-slot>
</x-admin.layout-main>
