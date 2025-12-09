<script>
    function eventChangeAddress(my_id = "", province_html_id = "province_id", district_html_id = "district_id"){
        $('#'+province_html_id+my_id).select2();
        $('#'+district_html_id+my_id).select2();

        $('#'+province_html_id+my_id).on('change', function() {
            $("#"+district_html_id+my_id+" > option").remove(); //first of all clear select items

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

</script>
