<!-- Plugins Sweetalert2-->
<script src="{{ rurl('assets/myjs/sweetalert2.10.10.1.all.min.js') }}"></script>
<style>
    .swal2-popup {
        font-size: 1rem !important;
        font-family: "Hanuman", Georgia, serif;
    }

    /** Related to Upload File Style */
    .label_upload {
        background-color: #f2d700; /* #D3D3D3 */
        color: #000000;
        padding: 0.2rem;
        font-family: Hanuman;

        border-radius: 0.3rem;
        cursor: pointer;
        /*margin-top: 1rem;*/
    }
    .visuallyhidden {
        border: 0;
        clip: rect(0 0 0 0);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
    }

    #file-chosen{
        margin-left: 0.3rem;
        font-family: sans-serif;
    }
</style>
<script>
    /** ======== Confirm Delete Form Button (Resource Route) ================ */
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
    /** ======== Confirm Delete with Normal Button (Normal Route) =========== */
    function comfirm_delete_steetalert2(my_url, message = "តើអ្នកពិតជាចង់លុប មែនឫ?"){
        //alert(my_url);
        Swal.fire({
            title: message,
            // text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'លុបចោល',
            cancelButtonText: 'អត់ទេ'

        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href=my_url;
            }
        });
    }
</script>
<!-- Plugins AutoCompleted Input Text-->
{{--        <script src="https://code.jquery.com/jquery-3.6.4.js"></script>--}}
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        var counter_disputant = 1;
        var counter_union = 2;
        /**  =============== Select2 ================ */
        $('#represent_company_nationality').select2();
        $('#represent_company_pob_country_id').select2();
        $('#log5_owner_nationality_id').select2();
        $('#log5_director_nationality_id').select2();
        $('#log5_article_of_company').select2();
        $('#log5_company_type_id').select2();
        $('#log5_sector_id').select2();
        $('#head_meeting').select2();
        $('#represent_company_gender1').select2();
        $('#noter').select2();

        $('#meeting_place_id').select2();

        /** Triggered Nationality and Pob_Country */
        var nationalityValue = $('#nationality').val();

        //if(nationalityValue > 0){
        //    $('#pob_country_id').val(nationalityValue).trigger('change'); // Update and trigger change event
        //}

        $('#represent_company_nationality').on('change', function() {
            var selectedValue = $(this).val();
            $('#represent_company_pob_country_id').val(selectedValue).trigger('change'); // Update and trigger change event
        });

        /** ==============Event Union Number Onchange ====================== */
        $('#log5_union1_number').on('blur change', function(e) {
            // e.type is the type of event fired
            var i = 0;
            //var union1_number = $("#log5_union1_number").val();
            if($("#log5_union1_number").val() < 1){
                for(i=2; i< counter_union; i++){

                    $('#union_' + i).remove();
                }
                $("#union_1").hide();
            }
            else{
                $("#union_1").show();
            }
        });
        /** ==============Event Button Add/Remove Union ====================== */
        $("#btn_add_union").click(function () {
            if(counter_union > 30){
                let timerInterval;
                Swal.fire({
                    title: "មិនអាចបន្ថែមបានទៀតទេ!",
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
                class: 'row',
                id: 'union_'+ counter_union,
                html: `
                       <div class="form-group col-sm-6 mt-3">
                            <div class="row py-1">
                                <div style="width:2%" class="col-sm-1 mt-1">`+ counter_union + `</div>
                                <div class="col-sm-11" style="width:96%">
                                    <input type="hidden" name="union1_id[]" value="0">
                                    <input type="text" name="union1_name[]" value="{{ old('union1_name[]') }}" class="form-control">
                                </div>
                            </div>
                       </div>

                      `
            });

            // Append the HTML code to the div with id "disputant_emp"
            $('#union_' + (counter_union - 1)).after(html);
            counter_union ++;
        });
        //Remove  អ្នកដែលអមកម្មករនិយោជិត និង/ឫ តំណាងកម្មករនិយោជិត
        $("#btn_remove_union").on("click", function() {
            if(counter_union == 2){
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
            $('#union_' + (counter_union - 1)).remove();
            counter_union--;
        });
        /**  =============== Onchange of Business_Activity 1,2,3 ================ */
        $('#business_activity1').on('change', function() {
            $("#business_activity2 > option").remove(); //first of all clear select items
            $("#business_activity3 > option").remove();
            $("#business_activity4 > option").remove();
            var business_activity1 = $(this).val();
            // Empty the dropdown
            //$('#province_id').find('option').not(':first').remove();
            // AJAX request
            $.ajax({
                url: "{{ url('ajaxGetBusinessActivity2') }}/"+ business_activity1,
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
                        $("#business_activity2").append("<option value=''>សូមជ្រើសរើស</option>");
                        for(var i=0; i<len; i++){
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var option = "<option value='"+id+"'>"+name+"</option>";
                            $("#business_activity2").append(option);
                        }
                    }

                }
            });
        });
        $('#business_activity2').on('change', function() {
            $("#business_activity3 > option").remove();
            $("#business_activity4 > option").remove();
            var business_activity1 = $("#business_activity1").val();
            var business_activity2 = $(this).val();

            //alert(":" + business_activity1 + ", " + business_activity2);
            // Empty the dropdown
            //$('#province_id').find('option').not(':first').remove();
            // AJAX request
            $.ajax({
                url: "{{ url('ajaxGetBusinessActivity3') }}/" + business_activity1 + "/" + business_activity2,
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
                        $("#business_activity3").append("<option value=''>សូមជ្រើសរើស</option>");
                        for(var i=0; i<len; i++){
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var option = "<option value='"+id+"'>"+name+"</option>";
                            $("#business_activity3").append(option);
                        }
                    }

                }
            });
        });
        $('#business_activity3').on('change', function() {
            $("#business_activity4 > option").remove();
            var business_activity1 = $("#business_activity1").val();
            var business_activity2 = $("#business_activity2").val();
            var business_activity3 = $(this).val();
            //alert(business_activity3);
            // Empty the dropdown
            //$('#province_id').find('option').not(':first').remove();
            // AJAX request
            $.ajax({
                url: "{{ url('ajaxGetBusinessActivity4') }}/"+ business_activity1 + "/" + business_activity2 + "/" + business_activity3,
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
                        $("#business_activity4").append("<option value=''>សូមជ្រើសរើស</option>");
                        for(var i=0; i<len; i++){
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var option = "<option value='"+id+"'>"+name+"</option>";
                            $("#business_activity4").append(option);
                        }
                    }
                }
            });
        });

        /**  =============== Onchange of Head Office of Company ================ */
        eventChangeAddress("", "log5_head_province_id", "log5_head_district_id", "log5_head_commune_id", "log5_head_village_id");
        /**  =============== Onchange of Company Address ================ */
        eventChangeAddress("", "log5_province_id", "log5_district_id", "log5_commune_id", "log5_village_id");


        /**  ===============Load Default-Represent Company: Event AutoComplete, Event POB, DOB ============ */
        eventChangePob("", "represent_company_pob_province_id", "represent_company_pob_district_id", "represent_company_pob_commune_id"); // (1)
        eventChangeAddress("", "represent_company_province", "represent_company_district", "represent_company_commune", "represent_company_village"); // (2)
        eventAutocomplete(
            "find_represent_company_autocomplete", "response_message_represent_company",
            "",
            "represent_company_name", "represent_company_gender", "represent_company_dob", "represent_company_nationality",
            "represent_company_id_number", "represent_company_phone_number", 'represent_company_phone2_number', "represent_company_occupation",
            "represent_company_pob_province_id", "represent_company_pob_district_id", "represent_company_pob_commune_id",
            "represent_company_province", "represent_company_district", "represent_company_commune", "represent_company_village",
            "represent_company_addr_street","represent_company_addr_house_no"
        ); // (3)

        /**  ===============Load Default: Event AutoComplete, Event DOB and Address ================ */
        // eventChangePob("1", "represent_company_pob_province_id", "represent_company_pob_district_id", "represent_company_pob_commune_id");
        // eventChangeAddress("1", "represent_company_province", "represent_company_district", "represent_company_commune", "represent_company_village");
        // eventAutocomplete(
        //     "find_represent_company_autocomplete", "response_message_company",
        //     "1",
        //     "represent_company_name", "represent_company_gender", "represent_company_dob", "represent_company_nationality", "represent_company_id_number", "represent_company_phone_number", "represent_company_occupation",
        //     "represent_company_pob_province_id", "represent_company_pob_district_id", "represent_company_pob_commune_id",
        //     "represent_company_province", "represent_company_district", "represent_company_commune", "represent_company_village",
        //     "represent_company_addr_house_no", "represent_company_addr_street"
        // );

        /**  =============== Date Picker and Time Picker ================ */
        var maxDate = new Date();
        maxDate.setDate(maxDate.getDate() - 5475);
        $('#represent_company_dob').datepicker({
            maxDate: maxDate,
        });
        $('#meeting_date, #open_date, #registration_date').datepicker({
            maxDate: new Date(),
        });

        $('#represent_company_dob').keydown(function(event) {
            if (event.keyCode != 8) { // Allow only Backspace
                event.preventDefault();
                return false;
            }
            // if ((event.charCode >= 48 && event.charCode <= 57) || // 0-9
            //     (event.charCode >= 65 && event.cha rCode <= 90) || // A-Z
            //     (event.charCode >= 97 && event.charCode <= 122))  // a-z
        });

        $("#represent_company_phone_number, #represent_company_phone2_number, #log5_head_phone, #log5_company_phone_number, #log5_total_employee, #log5_total_employee_female, #log5_union1_number").keypress(function(event){
            if (!(event.charCode >= 48 && event.charCode <= 57)){ // 0-9
                event.preventDefault();
                return false;
            }
            // if ((event.charCode >= 48 && event.charCode <= 57) || // 0-9
            //     (event.charCode >= 65 && event.charCode <= 90) || // A-Z
            //     (event.charCode >= 97 && event.charCode <= 122))  // a-z
        });
        $("#represent_company_id_number, #company_tin, #company_register_number").keypress(function(event){
            if ( (event.charCode >= 6016 && event.charCode <= 6121) ){ //except ០-៩
                event.preventDefault();
                return false; //alert(event.charCode); 6016 - 6121
            }
        });

        $("#company_register_number, #company_tin").keypress(function(event){
            if ( (event.charCode >= 6112 && event.charCode <= 6121) ){ //except ០-៩
                 event.preventDefault();
                 return false;
            }
        });
        $("#open_date, #registration_date, #meeting_date, #meeting_stime, #meeting_etime").keydown(function(event) {
            return false;
        });

        /**  =============== Load Event Related to Disputant ================ */
        $('#find_employee_autocomplete').on('blur' , function() {
            // var str = $('#find_employee_autocomplete').val();
            // alert(str);
        });
        $("#find_employee_autocomplete").autocomplete({
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
                        //$("#sub_disputant_id").val(data[0].id);
                        $("#name"+counter_disputant).val(data[0].name); // Replace 'details' with the actual field name
                        var gender = 1;
                        if(data[0].gender == "ស្រី")
                            gender = 2;
                        $("#gender"+counter_disputant).select2().val(gender).trigger("change");
                        $("#dob"+counter_disputant).val(data[0].dob);
                        //$("#nationality_id").select2().val(data[0].nationality).trigger("change");
                        {{--var tmp = data[0].nationality;--}}
                        {{--var nat = "{{ getNationalityID("+tmp+") }}" ;--}}
                        {{--alert(nat);--}}
                        //$("#nationality_id").val(nat);
                        $("#occupation" + counter_disputant).val(data[0].occupation).trigger("change");

                        $("#nationality"+counter_disputant).select2().val(data[0].nationality).trigger("change");
                        $("#id_number"+counter_disputant).val(data[0].id_number);
                        $("#phone_number"+counter_disputant).val(data[0].phone_number);

                        var pob_provinc_id = data[0].pob_province_id;
                        var pob_district_id = data[0].pob_district_id;
                        var pob_commune_id = data[0].pob_commune_id;
                        $("#pob_province_id"+counter_disputant).select2().val(pob_provinc_id).trigger("change");
                        ajaxGetPobDistrict(pob_provinc_id, pob_district_id, counter_disputant);
                        ajaxGetPobCommnue(pob_district_id, pob_commune_id, counter_disputant);



                        var province = data[0].province;
                        var district = data[0].district;
                        var commune = data[0].commune;
                        var village = data[0].village;
                        $("#province"+counter_disputant).select2().val(province).trigger("change");
                        ajaxGetAddressDistrict(province, district, counter_disputant);
                        ajaxGetAddressCommnue(district, commune, counter_disputant);
                        ajaxGetAddressVillage(commune, village, counter_disputant);



                        $("#addr_street").val(data[0].street);
                        $("#addr_house_no").val(data[0].house_no);

                        // eventChangePobProvince(counter_disputant);
                        // eventChangePobDistrict(counter_disputant);
                        // eventChangeAddressProvince(counter_disputant);
                        // eventChangeAddressDistrict(counter_disputant);
                        // eventChangeAddressCommune(counter_disputant);
                        allAllDisputantEvent(counter_disputant);
                    }
                });
            }
        });


        /**  =============== Function Get Address Data ================ */
        function ajaxGetAddressDistrictxx(province_id = 0, district_id = 0, my_id=""){
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
                        $("#district"+my_id).append("<option value=''>Please Select</option>");
                        for(var i=0; i<len; i++){
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var option = "<option value='"+id+"'>"+name+"</option>";
                            $("#district"+my_id).append(option);

                        }
                    }
                    //$("#district"+my_id).val(district_id);
                    //$("#district_id").select2().trigger('change');
                    $("#district"+my_id).select2().val(district_id).trigger("change");
                }
            });
        }
        function ajaxGetAddressCommnuexx(district_id = 0, commune_id = 0, my_id = ""){
            $.ajax({
                url: "{{ url('ajaxGetCommune') }}/"+ district_id,
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
                        $("#commune"+my_id).append("<option value=''>Please Select</option>");
                        for(var i=0; i<len; i++){
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var option = "<option value='"+id+"'>"+name+"</option>";
                            $("#commune"+my_id).append(option);

                        }
                    }
                    //$("#commune"+my_id).val(commune_id);
                    $("#commune"+my_id).select2().val(commune_id).trigger("change");
                }
            });
        }
        function ajaxGetAddressVillagexx(commune_id = 0, village_id = 0, my_id = ""){
            $.ajax({
                url: "{{ url('ajaxGetVillage') }}/"+ commune_id,
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
                        $("#village"+my_id).append("<option value=''>Please Select</option>");
                        for(var i=0; i<len; i++){
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var option = "<option value='"+id+"'>"+name+"</option>";
                            $("#village"+my_id).append(option);
                        }
                    }
                    //$("#village"+my_id).val(village_id);
                    //$("#district_id").select2().trigger('change');
                    $("#village"+my_id).select2().val(village_id).trigger("change");
                }
            });
        }
        function ajaxGetPobDistrictxx(province_id = 0, district_id = 0, my_id=""){
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
                        $("#pob_district_id"+my_id).append("<option value=''>Please Select</option>");
                        for(var i=0; i<len; i++){
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var option = "<option value='"+id+"'>"+name+"</option>";
                            $("#pob_district_id"+my_id).append(option);

                        }
                    }
                    $("#pob_district_id"+my_id).val(district_id);
                    //$("#district_id").select2().trigger('change');
                    //$("#district_id").select2().val(district_id).trigger("change");
                }
            });
        }
        function ajaxGetPobCommnuexx(district_id = 0, commune_id = 0, my_id=""){
            $.ajax({
                url: "{{ url('ajaxGetCommune') }}/"+ district_id,
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
                        $("#pob_commune_id"+my_id).append("<option value=''>Please Select</option>");
                        for(var i=0; i<len; i++){
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var option = "<option value='"+id+"'>"+name+"</option>";
                            $("#pob_commune_id"+my_id).append(option);

                        }
                    }
                    $("#pob_commune_id"+my_id).val(commune_id);
                }
            });
        }

        /**  =============== Function Related to Event ================ */
        // function allDisputantEvent(my_id = ""){
        //     eventChangePobProvince(counter_disputant);
        //     eventChangePobDistrict(counter_disputant);
        //     eventChangeAddressProvince(counter_disputant);
        //     eventChangeAddressDistrict(counter_disputant);
        //     eventChangeAddressCommune(counter_disputant);
        // }
        /** Pob Address Province On Change */
        function eventChangePobProvincexx(my_id=""){
            $('#pob_province_id'+my_id).on('change', function() {
                $("#pob_district_id"+my_id+" > option").remove(); //first of all clear select items
                $("#pob_commune_id"+my_id+" > option").remove();
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
                            $("#pob_district_id"+my_id).append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#pob_district_id"+my_id).append(option);
                            }
                        }

                    }
                });
            });
        }
        /** Pob Address District On Change to Get Commune */
        function eventChangePobDistrictxx(my_id=""){
            $('#pob_district_id'+my_id).on('change', function() {
                $("#pob_commune_id"+my_id+" > option").remove();
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
                            $("#pob_commune_id"+my_id).append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#pob_commune_id"+my_id).append(option);
                            }
                        }

                    }
                });
            });
        }
        /** Current Address Province On Change */
        function eventChangeAddressProvincexx(my_id=""){
            $('#province'+my_id).on('change', function() {
                $("#district"+my_id+" > option").remove(); //first of all clear select items
                $("#commune"+my_id+" > option").remove();
                $("#village"+my_id+" > option").remove();
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
                            $("#district"+my_id).append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#district"+my_id).append(option);
                            }
                        }

                    }
                });
            });
        }
        /** Current Address District On Change to Get Commune */
        function eventChangeAddressDistrictxx(my_id=""){
            $('#district'+my_id).on('change', function() {
                $("#commune"+my_id+" > option").remove();
                $("#village"+my_id+" > option").remove();
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
                            $("#commune"+my_id).append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#commune"+my_id).append(option);
                            }
                        }

                    }
                });
            });
        }
        /** Current Address Commune On Change to Get Village */
        function eventChangeAddressCommunexx(my_id=""){
            $('#commune'+my_id).on('change', function() {
                $("#village"+my_id+" > option").remove();
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
                            $("#village"+my_id).append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#village"+my_id).append(option);
                            }
                        }
                    }
                });
            });
        }

    });
</script>
@include('case.script.event_address_script')
<!-- Plugins Datepicker-->
<script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
<script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
<!-- Plugins Timepicker-->
<script src="{{ rurl('assets/js/time-picker/jquery-clockpicker.min.js') }}"></script>
<script src="{{ rurl('assets/js/time-picker/highlight.min.js') }}"></script>
<script src="{{ rurl('assets/js/time-picker/clockpicker.js') }}"></script>
<!-- Plugins Select2-->
<script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>
