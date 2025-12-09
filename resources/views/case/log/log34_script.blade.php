{{--@push('childScript')--}}
<script type="text/javascript">
    $(document).ready(function() {
        var counter_dis_emp = 1;
        $('#noter').select2();
        $('#case_type_id').select2();

        //Add  អ្នកដែលអមកម្មករនិយោជិត និង/ឫ តំណាងកម្មករនិយោជិត
        $("#btn_add_disputant_emp").click(function () {
            if(counter_dis_emp > 5){
                let timerInterval;
                Swal.fire({
                    title: "បន្ថែមបានត្រឹមតែ ៥ នាក់ប៉ុណ្ណោះ!",
                    timer: 800,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        const timer = Swal.getPopup().querySelector("b");
                        timerInterval = setInterval(() => {
                            timer.textContent = `${Swal.getTimerLeft()}`;
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                });
                return false;
            }


            // Create a jQuery object from the HTML code for Sub Disputant
            var html = $('<div>', {
                class: 'pt-3 mb-3 m-l-45 border-top border-warning',
                id: 'disputant_emp_'+ counter_dis_emp,
                html: `
                                <div class="form-group col-12">
                                            <div id="response-message" style="display: none;">Waiting for response...</div>
                                        </div>
                                        <div class="row col-12">
                                            <div class="form-group col-sm-12 mt-3">
                                                <label for="case_type" class="text-primary text-hanuman-20"> ស្វែងរកឈ្មោះអមអ្នកប្តឹង</label>
                                                <input type="text" name="find_employee_autocomplete" minlength="2" value="{{ old('find_employee_autocomplete') }}" class="form-control" id="find_employee_autocomplete`+counter_dis_emp+`" >
                                            </div>
                                        </div>
                                        <div class="row col-12 mt-3">
                                            <div class="form-group col-sm-2 mt-3">
                                                <label for="case_type">ឈ្មោះអមអ្នកប្ដឹង</label>
                                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="name`+counter_dis_emp+`" >
                                            </div>
                                            <div class="form-group col-sm-1 mt-3">
                                                <label>ភេទ</label>
                                                {!! showSelect('gender', array("1" =>"ប្រុស", "2" => "ស្រី"), old('gender'), " select2") !!}
                </div>
                <div class="form-group col-sm-2 mt-3">
                    <label>ថ្ងៃខែឆ្នាំកំណើត</label>
                    <input type="text"  name="dob" id="dob" value="{{ old('dob') }}" class="form-control"  data-language="en" >
                                            </div>
                                            <div class="form-group col-sm-2 mt-3">
                                                <label>សញ្ជាតិ</label>
                                                {!! showSelect('nationality', arrayNationality(1), old('nationality'), " select2") !!}
                </div>
                <div class="form-group col-sm-3 mt-3">
                    <label for="id_number"> លេខអត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
                    <input type="text" name="id_number" value="{{ old('id_number') }}" class="form-control" id="id_number" >
                                            </div>
                                            <div class="form-group col-sm-2 mt-3">
                                                <label for="phone_number">លេខទូរស័ព្ទ</label>
                                                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="row col-12 mt-3">
                                            <div class="form-group col-sm-2 mt-3">
                                                <label for="occupation">មុខងារ</label>
                                                <input type="text" name="occupation" id="occupation" value="{{ old('occupation') }}" class="form-control" >
                                            </div>
                                            <div class="form-group col-sm-3 mt-3">
                                                <label>ទីកន្លែងកំណើត រាជធានី-ខេត្ត</label>
                                                {!! showSelect('pob_province_id', arrayProvince(1,0), old('pob_province_id', request('pob_province_id')), " select2", "", "", "") !!}
                </div>

                <div class="form-group col-sm-2 mt-3">
                    <label>ក្រុង-ស្រុក-ខណ្ឌ</label>
{!! showSelect('pob_district_id', array(), old('pob_district_id', request('pob_district_id')), " select2", "", "", "") !!}
                </div>

                <div class="form-group col-sm-2 mt-3">
                    <label>ឃុំ-សង្កាត់</label>
{!! showSelect('pob_commune_id', array(), old('pob_commune_id', request('pob_commune_id')), " select2", "", "", "") !!}
                </div>
            </div>
            <div class="row col-12">
                <div class="form-group col-sm-3 mt-3">
                    <label>អាសយដ្ឋានបច្ចុប្បន្ន រាជធានី-ខេត្ត</label>
{!! showSelect('province', arrayProvince(1,0), old('province', request('province')), " select2") !!}
                </div>

                <div class="form-group col-sm-2 mt-3">
                    <label>ក្រុង-ស្រុក-ខណ្ឌ</label>
{!! showSelect('district', array(), old('pob_district', request('district')), " select2", "", "", "") !!}
                </div>

                <div class="form-group col-sm-2 mt-3">
                    <label>ឃុំ-សង្កាត់</label>
{!! showSelect('commune', array(), old('commune', request('commune')), " select2", "", "", "") !!}
                </div>
                <div class="form-group col-sm-2 mt-3">
                    <label>ភូមិ</label>
{!! showSelect('village', array(), old('village', request('village')), " select2") !!}
                </div>
                <div class="form-group col-sm-1 mt-3">
                    <label for="case_type">ផ្ទះលេខ</label>
                    <input type="text" name="addr_house_no" id="addr_house_no" value="{{ old('addr_house_no') }}" class="form-control" >
                                            </div>
                                            <div class="form-group col-sm-2 mt-3">
                                                <label>ផ្លូវ</label>
                                                <input type="text" name="addr_street" id="addr_street" value="{{ old('addr_street') }}" class="form-control" />
                                            </div>
                                        </div>
                            `
            });

            // Append the HTML code to the div with id "disputant_emp"
            $('#disputant_emp_' + (counter_dis_emp - 1)).after(html);
            loadDisputantEmpSection(counter_dis_emp);
            $('.disputant_dob').datepicker();
            // $('#disputant_dob_'+ counter_dis_emp +'').datepicker();
            counter_dis_emp ++;
        });
        //Remove  អ្នកដែលអមកម្មករនិយោជិត និង/ឫ តំណាងកម្មករនិយោជិត
        $("#btn_remove_disputant_emp").on("click", function() {
            if(counter_dis_emp == 1){
                let timerInterval;
                Swal.fire({
                    title: "លុបលែងបានហើយ",
                    timer: 800,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        const timer = Swal.getPopup().querySelector("b");
                        timerInterval = setInterval(() => {
                            timer.textContent = `${Swal.getTimerLeft()}`;
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                });
                return false;
            }
            // Remove the last added input element
            $('#disputant_emp_' + (counter_dis_emp - 1)).remove();
            counter_dis_emp--;
        });

        function loadDisputantEmpSection(myid){
            $("#find_employee_autocomplete"+ myid).autocomplete({
                source: function(request, response) {
                    $("#response-message").fadeIn(); // Show waiting message
                    $.ajax({
                        url: "{{ url('/find_employee_autocomplete') }}" + "/" + $("#company_id").val(),
                        dataType: "json",
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            //alert("success 111");
                            $("#response-message").fadeOut(); // Hide waiting message
                            response(data);
                        }
                    });
                },
                minLength: 2, // Minimum characters before triggering autocomplete
                select: function(event, ui) {
                    // Fetch and display details when an item is selected

                    $.ajax({
                        url: "{{ url('/autocomplete/get_employee_detail') }}" + "/" + $("#company_id").val(),
                        dataType: "json",
                        data: {
                            name: ui.item.value
                        },
                        success: function(data) {
                            //alert("success employee"+ data[0].name);
                            $("#sub_disputant_id").val(data[0].id);
                            $("#name"+ myid).val(data[0].name); // Replace 'details' with the actual field name
                            var gender = 1;
                            if(data[0].gender == "ស្រី")
                                gender = 2;
                            $("#gender"+ myid).select2().val(gender).trigger("change");
                            $("#dob"+ myid).val(data[0].dob);
                            //$("#nationality_id").select2().val(data[0].nationality).trigger("change");
                            {{--var tmp = data[0].nationality;--}}
                            {{--var nat = "{{ getNationalityID("+tmp+") }}" ;--}}
                            {{--alert(nat);--}}
                            //$("#nationality_id").val(nat);
                            $("#nationality"+ myid).select2().val(data[0].nationality).trigger("change");
                            $("#id_number"+ myid).val(data[0].id_number);
                            $("#phone_number"+ myid).val(data[0].phone_number);

                            var pob_provinc_id = data[0].pob_province_id;
                            var pob_district_id = data[0].pob_district_id;
                            var pob_commune_id = data[0].pob_commune_id;
                            $("#pob_province_id"+ myid).select2().val(pob_provinc_id).trigger("change");
                            ajaxGetPobDistrict(pob_provinc_id, pob_district_id);
                            ajaxGetPobCommnue(pob_district_id, pob_commune_id);


                            var province = data[0].province;
                            var district = data[0].district;
                            var commune = data[0].commune;
                            var village = data[0].village;
                            $("#province").select2().val(province).trigger("change");
                            ajaxGetAddressDistrict(province, district);
                            ajaxGetAddressCommnue(district, commune);
                            ajaxGetAddressVillage(commune, village);
                            $("#addr_street").val(data[0].street);
                            $("#addr_house_no").val(data[0].house_no);

                            // $("#province").select2().val(data.province).trigger("change");
                            // $("#district").select2().val(data.district).trigger("change");
                            // $("#commune").select2().val(data.commune).trigger("change");
                            // var province_id = data.business_province;
                            // var district_id = data.business_district;
                            // var commune_id = data.business_commune;
                            // var village_id = data.business_village;
                            // $("#province_id").select2().val(province_id).trigger("change");
                            // ajaxGetDistrict(province_id, district_id);
                            // ajaxGetCommnue(district_id, commune_id);
                            // ajaxGetVillage(commune_id, village_id);


                        }
                    });
                }
            });
            /** Pob Address Province On Change */
            $('#pob_province_id'+ myid).on('change', function() {
                $("#pob_district_id > option").remove(); //first of all clear select items
                $("#pob_commune_id > option").remove();
                var province_id = $(this).val();
                // Empty the dropdown
                //$('#province_id').find('option').not(':first').remove();
                // AJAX request
                $.ajax({
                    url: "{{ url('ajaxGetDistrict') }}/"+ province_id,
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
                            $("#pob_district_id").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#pob_district_id").append(option);
                            }
                        }

                    }
                });
            });
            /** Pob Address District On Change to Get Commune */
            $('#pob_district_id'+ myid).on('change', function() {
                $("#pob_commune_id > option").remove();
                //$("#village_id > option").remove();
                var district_id = $(this).val();
                //alert("District id:" + district_id);
                // Empty the dropdown
                //$('#province_id').find('option').not(':first').remove();
                // AJAX request
                $.ajax({
                    url: "{{ url('ajaxGetCommune') }}/"+ district_id,
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
                            $("#pob_commune_id").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#pob_commune_id").append(option);
                            }
                        }

                    }
                });
            });

            /** Current Address Province On Change */
            $('#province'+ myid).on('change', function() {
                $("#district > option").remove(); //first of all clear select items
                $("#commune > option").remove();
                $("#village > option").remove();
                var province_id = $(this).val();
                // Empty the dropdown
                //$('#province_id').find('option').not(':first').remove();
                // AJAX request
                $.ajax({
                    url: "{{ url('ajaxGetDistrict') }}/"+ province_id,
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
                            $("#district").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#district").append(option);
                            }
                        }

                    }
                });
            });
            /** Current Address District On Change to Get Commune */
            $('#district'+ myid).on('change', function() {
                $("#commune > option").remove();
                $("#village > option").remove();
                var district_id = $(this).val();
                //alert("District id:" + district_id);
                // Empty the dropdown
                //$('#province_id').find('option').not(':first').remove();
                // AJAX request
                $.ajax({
                    url: "{{ url('ajaxGetCommune') }}/"+ district_id,
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
                            $("#commune").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#commune").append(option);
                            }
                        }

                    }
                });
            });
            /** Current Address Commune On Change to Get Village */
            $('#commune'+ myid).on('change', function() {
                $("#village > option").remove();
                var commune_id = $(this).val();
                // Empty the dropdown
                //$('#province_id').find('option').not(':first').remove();
                // AJAX request
                $.ajax({
                    url: "{{ url('ajaxGetVillage') }}/"+ commune_id,
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
                            $("#village").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#village").append(option);
                            }
                        }

                    }
                });
            });

            $('#gender'+ myid).select2();
            $('#nationality_id'+ myid).select2();
            $('#pob_province_id'+ myid).select2();
            $('#pob_district_id'+ myid).select2();
            $('#pob_commune_id'+ myid).select2();

            $('#province'+ myid).select2();
            $('#district'+ myid).select2();
            $('#commune'+ myid).select2();
            $('#village'+ myid).select2();
        }




        $('#dob').datepicker({
            //language: 'en',
            //dateFormat: 'dd-mm-yyyy',
            // minDate: minDate // Now can select only dates, which goes after today
            // ,maxDate: maxDate /// new Date("10/01/2023")
        });



    });
</script>
{{--@endpush--}}
