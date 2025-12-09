<!-- Plugins AutoCompleted Input Text-->
{{--        <script src="https://code.jquery.com/jquery-3.6.4.js"></script>--}}
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    /**  ===============Load Default: Onchange of Sub Employee: POB, DOB ================ */
    // eventChangePob("1", "sub_employee_pob_province_id", "sub_employee_pob_district_id", "sub_employee_pob_commune_id");// sub_employee_pob_province_id1
    // eventChangeAddress("1", "sub_employee_province", "sub_employee_district", "sub_employee_commune", "sub_employee_village");
    // /**  ===============Load Default: Event AutoComplete ================ */
    // eventAutocomplete(
    //     "find_sub_employee_autocomplete", "response_message_sub_employee",
    //     "1",
    //     "sub_employee_name", "sub_employee_gender", "sub_employee_dob", "sub_employee_nationality",
    //     "sub_employee_id_number", "sub_employee_phone_number", "sub_employee_occupation",
    //     "sub_employee_pob_province_id", "sub_employee_pob_district_id", "sub_employee_pob_commune_id",
    //     "sub_employee_province", "sub_employee_district", "sub_employee_commune", "sub_employee_village",
    //     "sub_employee_addr_house_no", "sub_employee_addr_street"
    // );

    function eventChangePob(my_id = "", province_html_id = "province_id", district_html_id = "district_id", commune_html_id ="commune_id"){
        $('#'+province_html_id+my_id).select2();
        $('#'+district_html_id+my_id).select2();
        $('#'+commune_html_id+my_id).select2();

        $('#'+province_html_id+my_id).on('change', function() {
            $("#"+district_html_id+my_id+" > option").remove(); //first of all clear select items
            $("#"+commune_html_id+my_id+" > option").remove();
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
                        $("#"+district_html_id+my_id).append("<option value=''>សូមជ្រើសរើស</option>");
                        for(var i=0; i<len; i++){
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var option = "<option value='"+id+"'>"+name+"</option>";
                            $("#"+district_html_id+my_id).append(option);
                        }
                    }

                }
            });
        });
        $('#'+district_html_id+my_id).on('change', function() {
            $("#"+commune_html_id+my_id+" > option").remove();
            var district_id = $(this).val();
//alert("Load Event");
// Empty the dropdown
//$('#province_id').find('option').not(':first').remove();
// AJAX request
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
                        $("#"+commune_html_id+my_id).append("<option value=''>សូមជ្រើសរើស</option>");
                        for(var i=0; i<len; i++){
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var option = "<option value='"+id+"'>"+name+"</option>";
                            $("#"+commune_html_id+my_id).append(option);
                        }
                    }

                }
            });
        });
    }
    function eventChangeAddress(my_id = "", province_html_id = "province_id", district_html_id = "district_id", commune_html_id ="commune_id", village_html_id ="village_id"){
        $('#'+province_html_id+my_id).select2();
        $('#'+district_html_id+my_id).select2();
        $('#'+commune_html_id+my_id).select2();
        $('#'+village_html_id+my_id).select2();

        $('#'+province_html_id+my_id).on('change', function() {
            $("#"+district_html_id+my_id+" > option").remove(); //first of all clear select items
            $("#"+commune_html_id+my_id+" > option").remove();
            $("#"+village_html_id+my_id+" > option").remove();
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
                    var len = 0;
                    if(response['data'] != null){
                        len = response['data'].length;
                    }
                    if(len > 0){
                        // Read data and create <option >
                        $("#"+district_html_id+my_id).append("<option value=''>សូមជ្រើសរើស</option>");
                        for(var i=0; i<len; i++){
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var option = "<option value='"+id+"'>"+name+"</option>";
                            $("#"+district_html_id+my_id).append(option);
                        }
                    }

                }
            });
        });
        $('#'+district_html_id+my_id).on('change', function() {
            $("#"+commune_html_id+my_id+" > option").remove();
            $("#"+village_html_id+my_id+" > option").remove();
            var district_id = $(this).val();
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
                        $("#"+commune_html_id+my_id).append("<option value=''>សូមជ្រើសរើស</option>");
                        for(var i=0; i<len; i++){
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var option = "<option value='"+id+"'>"+name+"</option>";
                            $("#"+commune_html_id+my_id).append(option);
                        }
                    }

                }
            });
        });
        $('#'+commune_html_id+my_id).on('change', function() {
            $("#"+village_html_id+my_id+" > option").remove();
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
                        $("#"+village_html_id+my_id).append("<option value=''>សូមជ្រើសរើស</option>");
                        for(var i=0; i<len; i++){
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var option = "<option value='"+id+"'>"+name+"</option>";
                            $("#"+village_html_id+my_id).append(option);
                        }
                    }

                }
            });
        });
    }

    function eventAutocomplete(autocomplete_id ="find_autocomplete", response_message_id="response_message", counter = "", name = "name", gender = "gender", dob="dob", nationality="nationality", id_number="id_number", phone_number="phone_number", occupation="occupation", pob_province="pob_province_id", pob_district="pob_district_id", pob_commune="pob_commune", addr_province ="province", addr_district="district", addr_commune="commune", addr_village="village", addr_street="addr_street", addr_house = "addr_house_no"){
        $("#"+autocomplete_id).autocomplete({
            source: function(request, response) {
                $("#" + response_message_id).fadeIn(); // Show waiting message
                $.ajax({
                    url: "{{ url('/find_employee_autocomplete') }}" + "/" + $("#company_id").val(),
                    dataType: "json",
                    data: {
                        query: request.term
                    },
                    success: function(data) {
                        $("#" + response_message_id).fadeOut(); // Hide waiting message
                        response(data);
                    }
                });
            },
            minLength: 3, // Minimum characters before triggering autocomplete
            select: function(event, ui) {
                // Fetch and display details when an item is selected
                $("#" + response_message_id).fadeIn(); // Show waiting message
                $.ajax({
                    url: "{{ url('/autocomplete/get_employee_detail') }}" + "/" + $("#company_id").val(),
                    dataType: "json",
                    data: {
                        name: ui.item.value
                    },
                    success: function(data) {
                        $("#" + response_message_id).fadeOut(); // Hide waiting message
                        $("#" + name + counter).val(data[0].name);

                        var sex = 1;
                        if(data[0].gender == "ស្រី" || data[0].gender == 2){
                            sex = 2;
                        }
                        $("#" + id_number + counter).val(data[0].id_number).trigger("change");
                        $("#" + phone_number + counter).val(data[0].phone_number).trigger("change");
                        $("#phone_number").val(data[0].phone_number).trigger("change");
                        $("#" + occupation + counter).val(data[0].occupation).trigger("change");
                        $("#occupation").val(data[0].occupation).trigger("change");
                        $("#" + nationality + counter).select2().val(data[0].nationality).trigger("change");
                        $("#" + gender + counter).select2().val(sex).trigger("change");
                        $("#" + dob + counter).val(data[0].dob).trigger("change");

                        var pobProvince = data[0].pob_province_id;
                        var pobDistrict = data[0].pob_district_id;
                        var pobCommune = data[0].pob_commune_id;
                        $("#" + pob_province + counter).select2().val(pobProvince).trigger("change");
                        setTimeout(function() {
                            // Your code to be executed after 5 seconds
                            $("#" + pob_district + counter).select2().val(pobDistrict).trigger('change');
                            //console.log('After 5 seconds');
                        }, 800);
                        setTimeout(function() {
                            // Your code to be executed after 5 seconds
                            $("#" + pob_commune + counter).select2().val(pobCommune).trigger('change');
                        }, 1500);

                        //$("#"+pob_district+counter).append(pobDistrict).trigger("change");
                        //$("#"+pob_district+counter).select2().val(pobDistrict).trigger("change");
                        //ajaxGetPobDistrictAuto(pobProvince, pobDistrict, pob_district, counter);
                        // ajaxGetPobCommnueAuto(pobDistrict, pobCommune, pob_commune, counter);

                        var addrProvince = data[0].province;
                        var addrDistrict = data[0].district;
                        var addrCommune = data[0].commune;
                        var addrVillage = data[0].village;

                        $("#" + addr_province + counter).select2().val(addrProvince).trigger("change");
                        setTimeout(function() {
                            // Your code to be executed after 5 seconds
                            $("#" + addr_district + counter).select2().val(addrDistrict).trigger('change');
                        }, 1000);
                        setTimeout(function() {
                            // Your code to be executed after 5 seconds
                            $("#" + addr_commune + counter).select2().val(addrCommune).trigger('change');
                            //console.log('After 5 seconds');
                        }, 3000);
                        setTimeout(function() {
                            // Your code to be executed after 5 seconds
                            $("#" + addr_village + counter).select2().val(addrVillage).trigger('change');
                            //console.log('After 5 seconds');
                        }, 6000);

                        // ajaxGetAddressDistrictAuto(addrProvince, addrDistrict, addr_district, counter);
                        // ajaxGetAddressCommnueAuto(addrDistrict, addrCommune, addr_commune, counter);
                        // ajaxGetAddressVillageAuto(addrCommune, addrVillage, addr_village, counter);

                        $("#" + addr_street + counter).val(data[0].street).trigger("change");
                        $("#" + addr_house + counter).val(data[0].house_no).trigger("change");

                        //allAllDisputantEvent(counter_disputant);
                    }
                });
            }
        });
    }


    /**  =============== Function Get Address Data ================ */
    function ajaxGetAddressDistrict(province_id = 0, district_id = 0, my_id=""){
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
    function ajaxGetAddressCommnue(district_id = 0, commune_id = 0, my_id = ""){
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
    function ajaxGetAddressVillage(commune_id = 0, village_id = 0, my_id = ""){
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
    function ajaxGetPobDistrict(province_id = 0, district_id = 0, my_id=""){
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
                //$("#pob_district_id"+my_id).val(district_id);
                //$("#district_id").select2().val(district_id).trigger("change");
                $("#district_id"+my_id).select2().val(commune_id).trigger("change");
            }
        });
    }
    function ajaxGetPobCommnue(district_id = 0, commune_id = 0, my_id=""){
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
                //$("#pob_commune_id"+my_id).val(commune_id);
                $("#pob_commune_id"+my_id).select2().val(commune_id).trigger("change");
            }
        });
    }

    function ajaxGetAddressDistrictAuto(province_id = 0, district_id = 0, district_name="district_id", my_id=""){
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
                    $("#"+district_name+my_id).append("<option value=''>Please Select</option>");
                    for(var i=0; i<len; i++){
                        var id = response['data'][i].id;
                        var name = response['data'][i].name;
                        var option = "<option value='"+id+"'>"+name+"</option>";
                        $("#"+district_name+my_id).append(option);

                    }
                }
                //$("#district"+my_id).val(district_id);
                //$("#district_id").select2().trigger('change');
                $("#"+district_name+my_id).select2().val(district_id).trigger("change");
            }
        });
    }
    function ajaxGetAddressCommnueAuto(district_id = 0, commune_id = 0, commune_name="commune_id", my_id = ""){
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
                    $("#"+commune_name+my_id).append("<option value=''>Please Select</option>");
                    for(var i=0; i<len; i++){
                        var id = response['data'][i].id;
                        var name = response['data'][i].name;
                        var option = "<option value='"+id+"'>"+name+"</option>";
                        $("#"+commune_name+my_id).append(option);

                    }
                }
                //$("#commune"+my_id).val(commune_id);
                $("#"+commune_name+my_id).select2().val(commune_id).trigger("change");
            }
        });
    }
    function ajaxGetAddressVillageAuto(commune_id = 0, village_id = 0, village_name="village_name", my_id = ""){
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
                    $("#"+village_name+my_id).append("<option value=''>Please Select</option>");
                    for(var i=0; i<len; i++){
                        var id = response['data'][i].id;
                        var name = response['data'][i].name;
                        var option = "<option value='"+id+"'>"+name+"</option>";
                        $("#"+village_name+my_id).append(option);
                    }
                }
                //$("#village"+my_id).val(village_id);
                //$("#district_id").select2().trigger('change');
                $("#"+village_name+my_id).select2().val(village_id).trigger("change");
            }
        });
    }
    function ajaxGetPobDistrictAuto(province_id = 0, district_id = 0, district_name="pob_district_id", my_id=""){

        //$("#"+district_name+my_id+" > option").remove();
        //$("#"+district_name+my_id).empty();
        $.ajax({
            url: "{{ url('ajaxGetDistrict') }}/"+ province_id,
            type: 'get',
            data : {"_token":"{{ csrf_token() }}"},
            dataType: 'json',
            success: function(response){
                //$("#"+district_name+my_id).empty();
                //$("#"+commune_name+my_id).empty();
                var len = 0;
                if(response['data'] != null){
                    len = response['data'].length;
                }

                if(len > 0){
                    // Read data and create <option >
                    $("#"+district_name+my_id).append("<option value=''>Please Select</option>");
                    for(var i=0; i<len; i++){
                        var id = response['data'][i].id;
                        var name = response['data'][i].name;
                        var option = "<option value='"+id+"'>"+name+"</option>";
                        $("#"+district_name+my_id).append(option);
                    }
                }
                //$("#pob_district_id"+my_id).val(district_id);
                //$("#"+district_name+my_id).select2().val(district_id).trigger("change");
            }
        });
        $("#"+district_name+my_id).select2().val(district_id).trigger("change");
    }
    function ajaxGetPobCommnueAuto(district_id = 0, commune_id = 0, commune_name="pob_commune_id", my_id=""){
        //$("#"+commune_name+my_id+" > option").remove();
        $.ajax({
            url: "{{ url('ajaxGetCommune') }}/"+ district_id,
            type: 'get',
            data : {"_token":"{{ csrf_token() }}"},
            dataType: 'json',
            success: function(response){
                //alert("success");
                //$("#"+commune_name+my_id).empty();
                var len = 0;
                if(response['data'] != null){
                    len = response['data'].length;
                }
                if(len > 0){
                    // Read data and create <option >
                    $("#"+commune_name+my_id).append("<option value=''>Please Select</option>");
                    for(var i=0; i<len; i++){
                        var id = response['data'][i].id;
                        var name = response['data'][i].name;
                        var option = "<option value='"+id+"'>"+name+"</option>";
                        $("#"+commune_name+my_id).append(option);
                    }

                }
                $("#"+commune_name+my_id).select2().val(commune_id).trigger("change");
            }
        });
    }
    /**  =============== Function Related to Event ================ */
    /** Pob Address Province On Change */
    function eventChangePobProvince(my_id=""){
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
    function eventChangePobDistrict(my_id=""){
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
    function eventChangeAddressProvince(my_id=""){
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
    function eventChangeAddressDistrict(my_id=""){
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
    function eventChangeAddressCommune(my_id=""){
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
    /** ============ Original Function ============= */
    $("#find_employee_autocompletexx").autocomplete({
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
                    var tmp = data[0].nationality;
                    var nat = "{{ getNationalityID("+tmp+") }}" ;
                    alert(nat);
                    //$("#nationality_id").val(nat);
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
                    //allAllDisputantEvent(counter_disputant);
                }
            });
        }
    });

</script>
