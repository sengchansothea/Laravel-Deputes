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
                    <form class="needs-validation" name="formUpdateUser" action="{{ url('user/sync_sso') }}" method="POST">
                        @method('POST')
                        @csrf
                        <input type="hidden" name="userID" value="{{ $user->id }}" />
                        <div class="card-body">
                            <div class="card-block row">
                                <div class="col-sm-12 col-lg-12 col-xl-12">
                                    <div class="row col-12">
                                        <div class="form-group col-3">
                                            <label class="form-label mb-2 fw-bold" for="fullname">{{ __('general.k_ownername') }}</label>
                                            <input type="text" name="fullname" minlength="4" value="{{ old('fullname',$user->k_fullname) }}" class="form-control" id="fullname" placeholder="Full Name" readonly>
                                        </div>
                                        <div class="form-group col-3">
                                            <label class="form-label mb-2 fw-bold" for="username">ឈ្មោះឡាតាំង</label>
                                            <input type="text" name="name" minlength="4" value="{{ old('name', $user->username) }}" class="form-control" id="username" placeholder="Enter Username" autocomplete="off"  required >
                                            @error('name')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-3">
                                            <label class="form-label mb-2 fw-bold" for="username">ភេទ</label>
                                            {!! showSelect('gender',['male' => 'Male', 'female'=> 'Female'], old('gender'), "select2", "", "", "required") !!}
                                            @error('gender')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row col-12 mt-5">
                                        <div class="form-group col-3">
                                            <label class="mb-1 fw-bold" for="fullname">អាសយដ្ឋានអ៊ីមែល</label>
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" id="email" placeholder="example@gmail.com" required>
                                            @error('email')
                                            <div>{!! textRed($message) !!}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-3">
                                            <label class="fw-bold mb-1" for="password">ពាក្យសម្ងាត់ (តិចបំផុត៨ខ្ទង់)</label>
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
                                    @if(empty($user->sync_sso))
                                    <div class="row mt-5">
                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-danger fw-bold">Sync SSO</button>
                                        </div>
                                    </div>
                                    @else
                                        <div class="row mt-5 text-center">
                                            <label class="text-white fw-bold bg-danger p-3">គណនីមួយនេះ បានភ្ជាប់ជាមួយប្រព័ន្ធគណនីរួម (SSO) រួចរាល់ហើយ!</label>
                                        </div>
                                    @endif
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
