@push('childScript')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#article_of_company').select2();
            $('#company_type_id').select2();
            $('#business_activity').select2();
            $('#csic1').select2();
            $('#csic2').select2();
            $('#csic3').select2();
            $('#csic4').select2();
            $('#csic5').select2();
            $('#province_id').select2();
            $('#district_id').select2();
            $('#commune_id').select2();
            $('#village_id').select2();

            // $("#registration_date, #open_date").keydown(function(event) {
            //     return false;
            // });
            $('#csic1').on('change', function() {
                $("#csic2 > option").remove(); // Clear existing options
                $("#csic3 > option").remove();
                $("#csic4 > option").remove();
                $("#csic5 > option").remove();

                var csic1 = $(this).val();

                $.ajax({
                    url: "{{ url('ajaxGetCSIC2') }}/" + csic1,
                    type: 'get',
                    data: { "_token": "{{ csrf_token() }}" },
                    dataType: 'json',
                    success: function(response) {
                        if (response['data'] !== null) {
                            $("#csic2").append("<option value=''>សូមជ្រើសរើស</option>"); // Default option

                            // Loop through JSON object using `for...in`
                            $.each(response['data'], function(id, name) {
                                var option = "<option value='" + id + "'>" + name + "</option>";
                                $("#csic2").append(option);
                            });
                        }
                    }
                });
            });
            $('#csic2').on('change', function() {
                $("#csic3 > option").remove();
                $("#csic4 > option").remove();
                var csic1 = $("#csic1").val();
                var csic2 = $(this).val();

                //alert(":" + business_activity1 + ", " + business_activity2);
                // Empty the dropdown
                //$('#province_id').find('option').not(':first').remove();
                // AJAX request
                $.ajax({
                    url: "{{ url('ajaxGetCSIC3') }}/" + csic1 + "/" + csic2,
                    type: 'get',
                    data : {"_token":"{{ csrf_token() }}"},
                    dataType: 'json',
                    success: function(response){
                        if (response['data'] !== null) {
                            $("#csic3").append("<option value=''>សូមជ្រើសរើស</option>"); // Default option

                            // Loop through JSON object using `for...in`
                            $.each(response['data'], function(id, name) {
                                var option = "<option value='" + id + "'>" + name + "</option>";
                                $("#csic3").append(option);
                            });
                        }
                    }
                });
            });
            $('#csic3').on('change', function() {
                $("#csic4 > option").remove();
                var csic1 = $("#csic1").val();
                var csic2 = $("#csic2").val();
                var csic3 = $(this).val();
                //alert(business_activity3);
                // Empty the dropdown
                //$('#province_id').find('option').not(':first').remove();
                // AJAX request
                $.ajax({
                    url: "{{ url('ajaxGetCSIC4') }}/"+ csic1 + "/" + csic2 + "/" + csic3,
                    type: 'get',
                    data : {"_token":"{{ csrf_token() }}"},
                    dataType: 'json',
                    success: function(response){
                        if (response['data'] !== null) {
                            $("#csic4").append("<option value=''>សូមជ្រើសរើស</option>"); // Default option

                            // Loop through JSON object using `for...in`
                            $.each(response['data'], function(id, name) {
                                var option = "<option value='" + id + "'>" + name + "</option>";
                                $("#csic4").append(option);
                            });
                        }
                    }
                });
            });
            $('#csic4').on('change', function() {
                $("#csic5 > option").remove();
                var csic1 = $("#csic1").val();
                var csic2 = $("#csic2").val();
                var csic3 = $("#csic3").val();
                var csic4 = $(this).val();
                //alert(business_activity3);
                // Empty the dropdown
                //$('#province_id').find('option').not(':first').remove();
                // AJAX request
                $.ajax({
                    url: "{{ url('ajaxGetCSIC5') }}/"+ csic1 + "/" + csic2 + "/" + csic3 + "/" + csic4,
                    type: 'get',
                    data : {"_token":"{{ csrf_token() }}"},
                    dataType: 'json',
                    success: function(response){
                        if (response['data'] !== null) {
                            $("#csic5").append("<option value=''>សូមជ្រើសរើស</option>"); // Default option

                            // Loop through JSON object using `for...in`
                            $.each(response['data'], function(id, name) {
                                var option = "<option value='" + id + "'>" + name + "</option>";
                                $("#csic5").append(option);
                            });
                        }
                    }
                });
            });

            /** Company Province */
            $('#province_id').on('change', function (){
                $("#district_id > option").remove(); //first of all clear select items
                $("#commune_id > option").remove();
                $("#village_id > option").remove();

                var buildProID = $(this).val();
                // var empProName = $(this).find('option:selected').text();

                $.ajax({
                    url: "{{ url('ajaxGetDistrict') }}/"+ buildProID,
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
                            $("#district_id").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i = 0; i < len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#district_id").append(option);
                            }
                        }
                    }
                });
            });
            /** Company District */
            $('#district_id').on('change', function() {
                $("#commune_id > option").remove();
                $("#village_id > option").remove();
                var buildDisID = $(this).val();

                $.ajax({
                    url: "{{ url('ajaxGetCommune') }}/"+ buildDisID,
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
                            $("#commune_id").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#commune_id").append(option);
                            }
                        }
                    }
                });
            });
            /** Company Commune */
            $('#commune_id').on('change', function() {
                $("#village_id > option").remove();
                var buildComID = $(this).val();
                // Empty the dropdown
                //$('#province_id').find('option').not(':first').remove();
                // AJAX request
                $.ajax({
                    url: "{{ url('ajaxGetVillage') }}/"+ buildComID,
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
                            $("#village_id").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#village_id").append(option);
                            }
                        }
                    }
                });
            });
        });
    </script>
@endpush
