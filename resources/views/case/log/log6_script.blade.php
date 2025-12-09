@push('childScript')
    <script type="text/javascript">
        $(document).ready(function(){
            var counter_agree = {{ count($log620) > 0 ? count($log620) + 1 : 2 }} ;
            var counter_disagree = {{ count($log621) > 0 ? count($log621) + 1 : 2 }} ;
            var counter_dis_emp = 2;
            var counter_accom_com = 2;
            var counter_sub_officers = 2;
            var divAgreePoints = $("#agree_points");
            var divDisAgreePoints = $("#disagree_points");

            /** Load All Select2 For Employee Address*/
            $('#emp_nationality').select2();
            $('#emp_province').select2();
            $('#emp_district').select2();
            $('#emp_commune').select2();
            $('#emp_village').select2();


            /** Load All Select2 and DropDown Actions For Disputant Employee Section */
            loadDisputantEmpSection(1);



            /** Load All Select2 For Company Address */
            $('#building_province').select2();
            $('#building_district').select2();
            $('#building_commune').select2();
            $('#building_village').select2();

            /** Load All Select2 For Representative Address */
            $('#repre_com_dob_province').select2();
            $('#repre_com_dob_district').select2();
            $('#repre_com_dob_commune').select2();
            $('#repre_com_province').select2();
            $('#repre_com_district').select2();
            $('#repre_com_commune').select2();
            $('#repre_com_village').select2();

            /** Load All Select2 and DropDown Actions For Accompany Company Section */
            loadAccomComSection(1);

            /** Load All Select2 For Translator Address */
            $('#tran_dob_province').select2();
            $('#tran_dob_district').select2();
            $('#tran_dob_commune').select2();
            $('#tran_province').select2();
            $('#tran_distict').select2();
            $('#tran_commune').select2();
            $('#tran_village').select2();

            /**=========================All Actions On DropDown and CheckBox============= */
            /** Employee Province */
            $('#emp_province').on('change', function (){
                $("#emp_district > option").remove(); //first of all clear select items
                $("#emp_commune > option").remove();
                $("#emp_village > option").remove();

                var empProID = $(this).val();
                // var empProName = $(this).find('option:selected').text();

                $.ajax({
                    url: "{{ url('ajaxGetDistrict') }}/"+ empProID,
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
                            $("#emp_district").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i = 0; i < len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#emp_district").append(option);
                            }
                        }
                    }
                });
            });
            /** Employee District */
            $('#emp_district').on('change', function() {
                $("#emp_commune > option").remove();
                $("#emp_village > option").remove();
                var empDisID = $(this).val();
                $.ajax({
                    url: "{{ url('ajaxGetCommune') }}/"+ empDisID,
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
                            $("#emp_commune").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#emp_commune").append(option);
                            }
                        }
                    }
                });
            });
            /** Employee Commune */
            $('#emp_commune').on('change', function() {
                $("#emp_village > option").remove();
                var empComID = $(this).val();
                // Empty the dropdown
                //$('#province_id').find('option').not(':first').remove();
                // AJAX request
                $.ajax({
                    url: "{{ url('ajaxGetVillage') }}/"+ empComID,
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
                            $("#emp_village").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#emp_village").append(option);
                            }
                        }
                    }
                });
            });

            /** Translator DOB Province */
            $('#tran_dob_province').on('change', function (){
                $("#tran_dob_district > option").remove(); //first of all clear select items
                $("#tran_dob_commune > option").remove();

                var tranDOBProID = $(this).val();
                // var empProName = $(this).find('option:selected').text();

                $.ajax({
                    url: "{{ url('ajaxGetDistrict') }}/"+ tranDOBProID,
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
                            $("#tran_dob_district").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i = 0; i < len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#tran_dob_district").append(option);
                            }
                        }
                    }
                });
            });
            /** Translator DOB District */
            $('#tran_dob_district').on('change', function() {
                $("#tran_dob_commune > option").remove();

                var tranDOBDisID = $(this).val();

                $.ajax({
                    url: "{{ url('ajaxGetCommune') }}/"+ tranDOBDisID,
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
                            $("#tran_dob_commune").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#tran_dob_commune").append(option);
                            }
                        }
                    }
                });
            });
            /** Translator Province */
            $('#tran_province').on('change', function (){
                $("#tran_distict > option").remove(); //first of all clear select items
                $("#tran_commune > option").remove();
                $("#tran_village > option").remove();

                var tranProID = $(this).val();
                // var empProName = $(this).find('option:selected').text();

                $.ajax({
                    url: "{{ url('ajaxGetDistrict') }}/"+ tranProID,
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
                            $("#tran_distict").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i = 0; i < len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#tran_distict").append(option);
                            }
                        }
                    }
                });
            });
            /** Translator District */
            $('#tran_distict').on('change', function() {
                $("#tran_commune > option").remove();
                $("#tran_village > option").remove();
                var tranDisID = $(this).val();

                $.ajax({
                    url: "{{ url('ajaxGetCommune') }}/"+ tranDisID,
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
                            $("#tran_commune").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#tran_commune").append(option);
                            }
                        }
                    }
                });
            });
            /** Translator Commune */
            $('#tran_commune').on('change', function() {
                $("#tran_village > option").remove();
                var tranVilID = $(this).val();
                // Empty the dropdown
                //$('#province_id').find('option').not(':first').remove();
                // AJAX request
                $.ajax({
                    url: "{{ url('ajaxGetVillage') }}/"+ tranVilID,
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
                            $("#tran_village").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#tran_village").append(option);
                            }
                        }
                    }
                });
            });


            /** Representative Company Province */
            $('#repre_com_province').on('change', function (){
                $("#repre_com_district > option").remove(); //first of all clear select items
                $("#repre_com_commune > option").remove();
                $("#repre_com_village > option").remove();

                var repreComProID = $(this).val();
                // var empProName = $(this).find('option:selected').text();

                $.ajax({
                    url: "{{ url('ajaxGetDistrict') }}/"+ repreComProID,
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
                            $("#repre_com_district").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i = 0; i < len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#repre_com_district").append(option);
                            }
                        }
                    }
                });
            });
            /** Representative Company District*/
            $('#repre_com_district').on('change', function() {
                $("#repre_com_commune > option").remove();
                $("#repre_com_village > option").remove();
                var repreComDisID = $(this).val();

                $.ajax({
                    url: "{{ url('ajaxGetCommune') }}/"+ repreComDisID,
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
                            $("#repre_com_commune").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#repre_com_commune").append(option);
                            }
                        }
                    }
                });
            });
            /** Representative Company Commune */
            $('#repre_com_commune').on('change', function() {
                $("#repre_com_village > option").remove();
                var repreComComID = $(this).val();
                // Empty the dropdown
                //$('#province_id').find('option').not(':first').remove();
                // AJAX request
                $.ajax({
                    url: "{{ url('ajaxGetVillage') }}/"+ repreComComID,
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
                            $("#repre_com_village").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i < len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#repre_com_village").append(option);
                            }
                        }
                    }
                });
            });
            /** Representative DOB Company Province */
            $('#repre_com_dob_province').on('change', function (){
                $("#repre_com_dob_district > option").remove(); //first of all clear select items
                $("#repre_com_dob_commune > option").remove();

                var repreDOBProID = $(this).val();
                // var empProName = $(this).find('option:selected').text();

                $.ajax({
                    url: "{{ url('ajaxGetDistrict') }}/"+ repreDOBProID,
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
                            $("#repre_com_dob_district").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i = 0; i < len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#repre_com_dob_district").append(option);
                            }
                        }
                    }
                });
            });
            /** Representative DOB Company District */
            $('#repre_com_dob_district').on('change', function() {
                $("#repre_com_dob_commune > option").remove();

                var repreDOBDisID = $(this).val();

                $.ajax({
                    url: "{{ url('ajaxGetCommune') }}/"+ repreDOBDisID,
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
                            $("#repre_com_dob_commune").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#repre_com_dob_commune").append(option);
                            }
                        }
                    }
                });
            });

            /** Company Province */
            $('#building_province').on('change', function (){
                $("#building_district > option").remove(); //first of all clear select items
                $("#building_commune > option").remove();
                $("#building_village > option").remove();

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
                            $("#building_district").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i = 0; i < len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#building_district").append(option);
                            }
                        }
                    }
                });
            });
            /** Company District */
            $('#building_district').on('change', function() {
                $("#building_commune > option").remove();
                $("#building_village > option").remove();
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
                            $("#building_commune").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#building_commune").append(option);
                            }
                        }
                    }
                });
            });
            /** Company Commune */
            $('#building_commune').on('change', function() {
                $("#building_village > option").remove();
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
                            $("#building_village").append("<option value=''>សូមជ្រើសរើស</option>");
                            for(var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#building_village").append(option);
                            }
                        }
                    }
                });
            });



            /** ==================End All Actions ======================*/

            /** Start Adding Dynamic Form  */
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


                // Create a jQuery object from the HTML code
                var html = $('<div>', {
                    class: 'pt-3 mb-3 m-l-45 border-top border-warning',
                    id: 'disputant_emp_'+ counter_dis_emp,
                    html: `
                    <div class="mb-3 row">
                        <div class="col-sm-1 col-form-label fw-bold">
                            <label class="form-label">(`+ counter_dis_emp + `) ឈ្មោះ</label>
                        </div>
                        <div class="col-sm-3">
                            <input name="disputant_name[]" class="form-control" type="text" value="" placeholder="">
                        </div>
                        <div class="col-sm-1 col-form-label">
                            <label class="form-label">ភេទ</label>
                        </div>
                        <div class="col-sm-2">
                            <input name="disputant_gender[]" class="form-control" type="text" value="" placeholder="">
                        </div>
                        <div class="col-sm-2 col-form-label">
                            <label class="form-label">ថ្ងៃខែឆ្នាំកំណើត</label>
                        </div>
                        <div class="col-sm-3">
                            <input name="disputant_dob[]"  class="datepicker-here form-control digits disputant_dob" type="text" data-language="en" value="">
                        </div>
                        <div class="col-sm-1 col-form-label mt-3">
                            <label class="form-label">មុខងារ</label>
                        </div>
                        <div class="col-sm-3 mt-3">
                            <input name="disputant_occupation[]" class="form-control" type="text" value="" placeholder="">
                        </div>
                        <div class="col-sm-2 col-form-label mt-3">
                            <label class="form-label">លេខទូរសព្ទ</label>
                        </div>
                        <div class="col-sm-3 mt-3">
                            <input name="disputant_phone[]" class="form-control m-input digits" type="tel" value="" placeholder="">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-2 col-form-label">
                            <label class="form-label">កើតនៅៈ ខេត្ត/រាជធានី</label>
                        </div>
                        <div class="col-sm-2">
                            {!! showSelect('disputant_dob_province[]', arrayProvince(1), old('disputant_dob_province', request('disputant_dob_province')),"","","disputant_dob_province_` + counter_dis_emp + `" ) !!}
                        </div>
                        <div class="col-sm-2 col-form-label">
                            <label class="form-label">ស្រុក/ខណ្ណ</label>
                        </div>
                        <div class="col-sm-2">
                            {!! showSelect('disputant_dob_district[]', $arrDiputantDOBDistrict, old('disputant_dob_district', request('disputant_dob_district')),"","","disputant_dob_district_` + counter_dis_emp + `" ) !!}
                        </div>
                        <div class="col-sm-2 col-form-label">
                            <label class="form-label">ឃុំ/សង្កាត់</label>
                        </div>
                        <div class="col-sm-2">
                            {!! showSelect('disputant_dob_commune[]', $arrDiputantDOBCommune, old('disputant_dob_commune', request('disputant_dob_commune')),"","", "disputant_dob_commune_` + counter_dis_emp + `" ) !!}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-2 col-form-label">
                            <label class="form-label">អាសយដ្ឋានៈ ផ្ទះលេខ</label>
                        </div>
                        <div class="col-sm-2">
                            <input name="disputant_house[]" class="form-control" type="text" value="" placeholder="">
                        </div>
                        <div class="col-sm-2 col-form-label">
                            <label class="form-label">ផ្លូវ</label>
                        </div>
                        <div class="col-sm-2">
                            <input name="disputant_street[]" class="form-control" type="text" value="" placeholder="">
                        </div>
                        <div class="col-sm-2 col-form-label">
                            <label class="form-label">ខេត្ត/រាជធានី</label>
                        </div>
                        <div class="col-sm-2">
                            {!! showSelect('disputant_province[]', arrayProvince(1), old('disputant_province', request('disputant_province')),"","","disputant_province_` + counter_dis_emp + `" ) !!}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-2 col-form-label">
                            <label class="form-label">ស្រុក/ខណ្ណ</label>
                        </div>
                        <div class="col-sm-2">
                            {!! showSelect('disputant_district[]', $arrDiputantDistrict, old('disputant_district', request('disputant_district')),"","","disputant_district_` + counter_dis_emp + `" ) !!}
                        </div>
                        <div class="col-sm-2 col-form-label">
                            <label class="form-label">ឃុំ/សង្កាត់</label>
                        </div>
                        <div class="col-sm-2">
                            {!! showSelect('disputant_commune[]', $arrDiputantCommune, old('disputant_commune', request('disputant_commune')),"","", "disputant_commune_` + counter_dis_emp + `" ) !!}
                        </div>
                        <div class="col-sm-2 col-form-label">
                            <label class="form-label">ក្រុម/ភូមិ</label>
                        </div>
                        <div class="col-sm-2">
                            {!! showSelect('disputant_village[]', $arrDiputantVillage, old('disputant_village', request('disputant_commune')),"","", "disputant_village_` + counter_dis_emp + `" ) !!}
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
                if(counter_dis_emp == 2){
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

            $("#btn_add_accom_company").click(function () {
                if(counter_accom_com > 5){
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
                // Create a jQuery object from the HTML code
                var html = $('<div>', {
                    class: 'pt-3 mb-3 row m-l-30 border-top border-warning',
                    id: 'accom_company_'+ counter_accom_com,
                    html: `
                        <div class="mb-3 row">
                            <div class="col-sm-2 col-form-label fw-bold">
                                <label class="form-label">(` + counter_accom_com + `) ឈ្មោះ</label>
                            </div>
                            <div class="col-sm-3">
                                <input name="accom_com_name[]" class="form-control" type="text" value="" placeholder="">
                            </div>

                            <div class="col-sm-2 col-form-label">
                                <label class="form-label">ថ្ងៃខែឆ្នាំកំណើត</label>
                            </div>
                            <div class="col-sm-3">
                                <input name="accom_com_dob[]"  class="datepicker-here form-control digits accom_com_dob" type="text" data-language="en" value="">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-2 col-form-label">
                                <label class="form-label">មុខងារ</label>
                            </div>
                            <div class="col-sm-3">
                                <input name="accom_com_occupation[]" class="form-control" type="text" value="" placeholder="">
                            </div>
                            <div class="col-sm-2 col-form-label">
                                <label class="form-label">លេខទូរសព្ទ</label>
                            </div>
                            <div class="col-sm-3">
                                <input name="accom_com_phone[]" class="form-control m-input digits" type="tel" value="" placeholder="">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-2 col-form-label">
                                <label class="form-label">កើតនៅៈ ខេត្ត/រាជធានី</label>
                            </div>
                            <div class="col-sm-2">
                                {!! showSelect('accom_com_dob_province[]', arrayProvince(1), old('accom_com_dob_province', request('accom_com_dob_province')),"","",'accom_com_dob_province_` + counter_accom_com + `' ) !!}
                    </div>
                    <div class="col-sm-2 col-form-label">
                        <label class="form-label">ស្រុក/ខណ្ណ</label>
                    </div>
                    <div class="col-sm-2">
{!! showSelect('accom_com_dob_district[]', $arrAccomComDOBDistrict, old('accom_com_dob_district', request('accom_com_dob_district')),"","","accom_com_dob_district_` + counter_accom_com + `" ) !!}
                    </div>
                    <div class="col-sm-2 col-form-label">
                        <label class="form-label">ឃុំ/សង្កាត់</label>
                    </div>
                    <div class="col-sm-2">
{!! showSelect('accom_com_dob_commune[]', $arrAccomComDOBCommune, old('accom_com_dob_commune', request('accom_com_dob_commune')),"","","accom_com_dob_commune_` + counter_accom_com + `" ) !!}
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-2 col-form-label">
                        <label class="form-label">អាសយដ្ឋានៈ ផ្ទះលេខ</label>
                    </div>
                    <div class="col-sm-2">
                        <input name="accom_com_house[]" class="form-control" type="text" value="" placeholder="">
                    </div>
                    <div class="col-sm-2 col-form-label">
                        <label class="form-label">ផ្លូវ</label>
                    </div>
                    <div class="col-sm-2">
                        <input name="accom_com_street[]" class="form-control" type="text" value="" placeholder="">
                    </div>
                    <div class="col-sm-2 col-form-label">
                        <label class="form-label">ខេត្ត/រាជធានី</label>
                    </div>
                    <div class="col-sm-2">
{!! showSelect('accom_com_province[]', arrayProvince(1), old('accom_com_province', request('accom_com_province')),"","","accom_com_province_` + counter_accom_com + `" ) !!}
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-2 col-form-label">
                        <label class="form-label">ស្រុក/ខណ្ណ</label>
                    </div>
                    <div class="col-sm-2">
{!! showSelect('accom_com_district[]', $arrAccomComDistrict, old('accom_com_district', request('accom_com_district')),"","","accom_com_district_` + counter_accom_com + `" ) !!}
                    </div>
                    <div class="col-sm-2 col-form-label">
                        <label class="form-label">ឃុំ/សង្កាត់</label>
                    </div>
                    <div class="col-sm-2">
{!! showSelect('accom_com_commune[]', $arrAccomComCommune, old('accom_com_commune', request('accom_com_commune')),"","","accom_com_commune_` + counter_accom_com + `" ) !!}
                    </div>
                    <div class="col-sm-2 col-form-label">
                        <label class="form-label">ក្រុម/ភូមិ</label>
                    </div>
                    <div class="col-sm-2">
{!! showSelect('accom_com_village[]', $arrAccomComVillage, old('accom_com_village', request('accom_com_village')),"","","accom_com_village_` + counter_accom_com + `" ) !!}
                    </div>
                </div>
`
                });
                $('#accom_company_' + (counter_accom_com - 1)).after(html);
                $('.accom_com_dob').datepicker();

                loadAccomComSection(counter_accom_com);
                counter_accom_com++;
            });
            $("#btn_remove_accom_company").on("click", function() {
                if(counter_accom_com == 2){
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
                $('#accom_company_' + (counter_accom_com - 1)).remove();
                counter_accom_com--;
            });

            $('#log6_meeting_place').click(function (){

            });
            $('input[name="log6_meeting_place"]').click(function() {
                var value = $(this).val(); // Get the value of the clicked radio button
                if (value == 1) {
                    $('#meeting_place').hide(); // Hide the div element if the radio button value is '1'
                } else {
                    $('#meeting_place').show(); // Show the div element if the radio button value is '2'
                }
            });

            $('#chk_translator').click(function (){
                if ($(this).prop('checked')) {
                    // Perform additional actions when the checkbox is checked
                    $('#translator').show('fast');
                } else {
                    $('#translator').hide('fast');
                    // Perform additional actions when the checkbox is unchecked
                }
            });
            $('input[name="dispute_cause"]').on('click', function() {
                var selectedCause = $('input[name="dispute_cause"]:checked').val();
                if(selectedCause == 11){
                    $('#other_dispute_cause').show('fast');
                }else{
                    $('#other_dispute_cause').hide('fast');
                }
            });
            $('input[name="meeting_place"]').on('click', function() {
                var selectedPlace = $('input[name="meeting_place"]:checked').val();
                if(selectedPlace == 2){
                    $('#log6_meeting_other').show('medium');
                }else if(selectedPlace == 1){
                    $('#log6_meeting_other').hide('medium');
                }
            });

            //Add Agree Points
            $("#btn_add_agree").click(function () {
                if(counter_agree > 10){
                    let timerInterval;
                    Swal.fire({
                        title: "បន្ថែមបានត្រឹមតែ ១០ ចំណុចប៉ុណ្ណោះ!",
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

                var html = $('<div>', {
                    class: 'mb-3 row',
                    id: 'agree_points_'+ counter_agree,
                    html: `
                <div class="col-11 align-items-center m-l-30" >
                    <input type="hidden" name="agrees_id[]" value="0" />
                    <input class="mb-3 form-control"  name="log6_agree[]" type="text" aria-describedby="" placeholder="សូមបំពេញចំនុចព្រមព្រៀងគ្នាត្រង់នេះ">
                </div>
            `});

                $('#agree_points_' + (counter_agree - 1)).after(html);
                counter_agree ++;
            });

            //Remove Agree Points
            $("#btn_remove_agree").on("click", function() {
                if(counter_agree == 2){
                    let timerInterval;
                    Swal.fire({
                        title: "លុបលែងបានហើយ!",
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

                var agreeID = $('#agree_points_'+ (counter_agree - 1) + ' input[type="hidden"]').val();
                if(agreeID != 0){
                    Swal.fire({
                        title: "តើអ្នកពិតជាចង់លុបចំនុចនេះ ចោលមែនឫ?",
                        // text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "ពិតមែនហើយ",
                        cancelButtonText: "អត់ទេ",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ url('ajaxDeleteAgree') }}/"+ agreeID,
                                type: 'GET',
                                data : {"_token":"{{ csrf_token() }}"},
                                dataType: 'json',
                                success: function(response){
                                }
                            });
                            // Swal.fire({
                            //     title: "Deleted!",
                            //     text: "Your file has been deleted.",
                            //     icon: "success"
                            // });
                        }
                    });

                }
                // Remove the last added input element
                $('#agree_points_' + (counter_agree - 1)).remove();
                counter_agree--;
            });

            //Add DisAgree Points
            $("#btn_add_disagree").click(function () {
                if(counter_disagree > 10){
                    let timerInterval;
                    Swal.fire({
                        title: "បន្ថែមបានត្រឹមតែ ១០ ចំណុចប៉ុណ្ណោះ!",
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

                var html = $('<div>', {
                    class: 'mb-3 row',
                    id: 'disagree_points_'+ counter_disagree,
                    html: `
                <div class="col-11 align-items-center m-l-30" >
                    <input type="hidden" name="disagrees_id[]" value="0" />
                    <input class="mb-3 form-control"  name="log6_disagree[]" type="text" aria-describedby="" placeholder="សូមបំពេញចំនុចមិនសះជាគ្នាត្រង់នេះ">
                </div>
            `});

                $('#disagree_points_' + (counter_disagree - 1)).after(html);
                counter_disagree ++;
            });
            //Remove DisAgree Points
            $("#btn_remove_disagree").on("click", function() {
                if(counter_disagree == 2){
                    let timerInterval;
                    Swal.fire({
                        title: "លុបលែងបានហើយ!",
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

                var disAgreeID = $('#disagree_points_'+ (counter_disagree - 1) + ' input[type="hidden"]').val();
                if(disAgreeID != 0){
                    Swal.fire({
                        title: "តើអ្នកពិតជាចង់លុបចំនុចនេះ ចោលមែនឫ?",
                        // text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "ពិតមែនហើយ",
                        cancelButtonText: "អត់ទេ",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ url('ajaxDeleteDisAgree') }}/"+ disAgreeID,
                                type: 'GET',
                                data : {"_token":"{{ csrf_token() }}"},
                                dataType: 'json',
                                success: function(response){
                                }
                            });
                            // Swal.fire({
                            //     title: "Deleted!",
                            //     text: "Your file has been deleted.",
                            //     icon: "success"
                            // });
                        }
                    });

                }
                // Remove the last added input element
                $('#disagree_points_' + (counter_disagree - 1)).remove();
                counter_disagree --;
            });

            //Add Sub Officers
            $('#btn_add_sub_officers').on("click", function (){
                if(counter_sub_officers > 5){
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
                var newSubOfficers = $('<div>', {
                    class: 'mb-3 row m-l-30',
                    id: 'sub_officer_'+ counter_sub_officers,
                    html: `
                        <div class="col-sm-1 col-form-label">
                          <label class="form-label">ឈ្មោះ</label>
                        </div>
                        <div class="col-sm-3">
                          <input class="form-control" name="case_sub_officer_name[]" type="text" aria-describedby="" placeholder="">
                        </div>
                        <div class="col-sm-1 col-form-label">
                          <label class="form-label">មុខងារ</label>
                        </div>
                        <div class="col-sm-3">
                          <input class="form-control" name="case_sub_officer_role[]" type="text" aria-describedby="" placeholder="">
                        </div>
                      `
                });
                $('#sub_officer_' + (counter_sub_officers - 1)).after(newSubOfficers);
                counter_sub_officers ++;
            });
            //Remove Sub Officers
            $('#btn_remove_sub_officers').on("click", function (){
                if(counter_sub_officers == 2){
                    let timerInterval;
                    Swal.fire({
                        title: "លុបលែងបានហើយ!",
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
                $('#sub_officer_' + (counter_sub_officers - 1)).remove();
                counter_sub_officers --;
            });

            /** Ending Adding Dynamic Form */


            /** All Sub Functions Here */
            function loadDisputantEmpSection(myid){
                /** Disputant Address Select2*/
                $('#disputant_dob_province_' + myid).select2();
                $('#disputant_dob_district_' + myid).select2();
                $('#disputant_dob_commune_' + myid).select2();
                $('#disputant_province_' + myid).select2();
                $('#disputant_district_' + myid).select2();
                $('#disputant_commune_' + myid).select2();
                $('#disputant_village_' + myid).select2();

                /** Disputant Employee DOB Province */
                $('#disputant_dob_province_' + myid).on('change', function (){
                    $("#disputant_dob_district_" + myid + " > option").remove(); //first of all clear select items
                    $("#disputant_dob_commune_" + myid + " > option").remove();

                    var disDOBProID = $(this).val();
                    var myID = myid;
                    // var empProName = $(this).find('option:selected').text();

                    $.ajax({
                        url: "{{ url('ajaxGetDistrict') }}/"+ disDOBProID,
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
                                $("#disputant_dob_district_" + myID).append("<option value=''>សូមជ្រើសរើស</option>");
                                for(var i = 0; i < len; i++){
                                    var id = response['data'][i].id;
                                    var name = response['data'][i].name;
                                    var option = "<option value='"+id+"'>"+name+"</option>";
                                    $("#disputant_dob_district_" + myID).append(option);
                                }
                            }
                        }
                    });
                });
                /** Disputant Employee DOB District */
                $('#disputant_dob_district_' + myid).on('change', function() {
                    $("#disputant_dob_commune_" + myid + " > option").remove();

                    var disDOBDisID = $(this).val();
                    var myID = myid;

                    $.ajax({
                        url: "{{ url('ajaxGetCommune') }}/"+ disDOBDisID,
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
                                $("#disputant_dob_commune_" + myID).append("<option value=''>សូមជ្រើសរើស</option>");
                                for(var i=0; i<len; i++){
                                    var id = response['data'][i].id;
                                    var name = response['data'][i].name;
                                    var option = "<option value='"+id+"'>"+name+"</option>";
                                    $("#disputant_dob_commune_" + myID).append(option);
                                }
                            }
                        }
                    });
                });
                /** Disputant Employee Province */
                $('#disputant_province_' + myid).on('change', function (){
                    $("#disputant_district_" + myid + " > option").remove(); //first of all clear select items
                    $("#disputant_commune_" + myid + " > option").remove();
                    $("#disputant_village_" + myid + " > option").remove();

                    var disProID = $(this).val();
                    var myID = myid;
                    // var empProName = $(this).find('option:selected').text();

                    $.ajax({
                        url: "{{ url('ajaxGetDistrict') }}/"+ disProID,
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
                                $("#disputant_district_" + myID).append("<option value=''>សូមជ្រើសរើស</option>");
                                for(var i = 0; i < len; i++){
                                    var id = response['data'][i].id;
                                    var name = response['data'][i].name;
                                    var option = "<option value='"+id+"'>"+name+"</option>";
                                    $("#disputant_district_" + myID).append(option);
                                }
                            }
                        }
                    });
                });
                /** Disputant Employee District */
                $('#disputant_district_' + myid).on('change', function() {
                    $("#disputant_commune_" + myid + " > option").remove();
                    $("#disputant_village_" + myid + " > option").remove();
                    var disDisID = $(this).val();
                    var myID = myid;

                    $.ajax({
                        url: "{{ url('ajaxGetCommune') }}/"+ disDisID,
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
                                $("#disputant_commune_" + myID).append("<option value=''>សូមជ្រើសរើស</option>");
                                for(var i=0; i<len; i++){
                                    var id = response['data'][i].id;
                                    var name = response['data'][i].name;
                                    var option = "<option value='"+id+"'>"+name+"</option>";
                                    $("#disputant_commune_" + myID).append(option);
                                }
                            }
                        }
                    });
                });
                /** Disputant Employee Commune */
                $('#disputant_commune_' + myid).on('change', function() {
                    $("#disputant_village_" + myid + " > option").remove();
                    var disVillageID = $(this).val();
                    var myID = myid;
                    // Empty the dropdown
                    //$('#province_id').find('option').not(':first').remove();
                    // AJAX request
                    $.ajax({
                        url: "{{ url('ajaxGetVillage') }}/"+ disVillageID,
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
                                $("#disputant_village_" + myID).append("<option value=''>សូមជ្រើសរើស</option>");
                                for(var i=0; i<len; i++){
                                    var id = response['data'][i].id;
                                    var name = response['data'][i].name;
                                    var option = "<option value='"+id+"'>"+name+"</option>";
                                    $("#disputant_village_" + myID).append(option);
                                }
                            }
                        }
                    });
                });
            }
            function loadAccomComSection(myid){

                /** Accompany Company Address Select2 */
                $('#accom_com_dob_province_'+myid).select2();
                $('#accom_com_dob_district_'+myid).select2();
                $('#accom_com_dob_commune_'+myid).select2();
                $('#accom_com_province_'+myid).select2();
                $('#accom_com_district_'+myid).select2();
                $('#accom_com_commune_'+myid).select2();
                $('#accom_com_village_'+myid).select2();


                /** Accompany DOB Company Province */
                $('#accom_com_dob_province_'+ myid).on('change', function (){
                    $("#accom_com_dob_district_" + myid + " > option").remove(); //first of all clear select items
                    $("#accom_com_dob_commune_" + myid + " > option").remove();

                    var accomComDOBProID = $(this).val();
                    // var empProName = $(this).find('option:selected').text();
                    var currentID = myid;
                    $.ajax({
                        url: "{{ url('ajaxGetDistrict') }}/"+ accomComDOBProID,
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
                                $("#accom_com_dob_district_"+ currentID).append("<option value=''>សូមជ្រើសរើស</option>");
                                for(var i = 0; i < len; i++){
                                    var id = response['data'][i].id;
                                    var name = response['data'][i].name;
                                    var option = "<option value='"+id+"'>"+name+"</option>";
                                    $("#accom_com_dob_district_" + currentID).append(option);
                                }
                            }
                        }
                    });
                });
                /** Accompany DOB Company District */
                $('#accom_com_dob_district_'+ myid).on('change', function() {
                    $("#accom_com_dob_commune_" + myid + " > option").remove();
                    var accomComDOBDisID = $(this).val();
                    var myID = myid;

                    $.ajax({
                        url: "{{ url('ajaxGetCommune') }}/"+ accomComDOBDisID,
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
                                $("#accom_com_dob_commune_"+ myID).append("<option value=''>សូមជ្រើសរើស</option>");
                                for(var i=0; i<len; i++){
                                    var id = response['data'][i].id;
                                    var name = response['data'][i].name;
                                    var option = "<option value='"+id+"'>"+name+"</option>";
                                    $("#accom_com_dob_commune_" + myID).append(option);
                                }
                            }
                        }
                    });
                });
                /** Accompany Company Province */
                $('#accom_com_province_'+ myid).on('change', function (){
                    $("#accom_com_district_" + myid + " > option").remove(); //first of all clear select items
                    $("#accom_com_commune_" + myid + " > option").remove();
                    $("#accom_com_village_" + myid + " > option").remove();

                    var accomComProID = $(this).val();
                    var myID = myid;

                    $.ajax({
                        url: "{{ url('ajaxGetDistrict') }}/"+ accomComProID,
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
                                $("#accom_com_district_" + myID).append("<option value=''>សូមជ្រើសរើស</option>");
                                for(var i = 0; i < len; i++){
                                    var id = response['data'][i].id;
                                    var name = response['data'][i].name;
                                    var option = "<option value='"+id+"'>"+name+"</option>";
                                    $("#accom_com_district_"+ myID).append(option);
                                }
                            }
                        }
                    });
                });
                /** Accompany Company District */
                $('#accom_com_district_'+ myid).on('change', function() {
                    $("#accom_com_commune_" + myid + " > option").remove();
                    $("#accom_com_village_" + myid + " > option").remove();
                    var accomComDisID = $(this).val();
                    var myID = myid;

                    $.ajax({
                        url: "{{ url('ajaxGetCommune') }}/"+ accomComDisID,
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
                                $("#accom_com_commune_"+myID).append("<option value=''>សូមជ្រើសរើស</option>");
                                for(var i=0; i<len; i++){
                                    var id = response['data'][i].id;
                                    var name = response['data'][i].name;
                                    var option = "<option value='"+id+"'>"+name+"</option>";
                                    $("#accom_com_commune_"+myID).append(option);
                                }
                            }
                        }
                    });
                });
                /** Accompany Company Commune */
                $('#accom_com_commune_'+ myid).on('change', function() {
                    $("#accom_com_village_" + myid + " > option").remove();
                    var accomComComID = $(this).val();
                    var myID = myid;
                    // Empty the dropdown
                    //$('#province_id').find('option').not(':first').remove();
                    // AJAX request
                    $.ajax({
                        url: "{{ url('ajaxGetVillage') }}/"+ accomComComID,
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
                                $("#accom_com_village_" + myID).append("<option value=''>សូមជ្រើសរើស</option>");
                                for(var i=0; i<len; i++){
                                    var id = response['data'][i].id;
                                    var name = response['data'][i].name;
                                    var option = "<option value='"+id+"'>"+name+"</option>";
                                    $("#accom_com_village_" + myID).append(option);
                                }
                            }
                        }
                    });
                });

            }
        });
    </script>
@endpush
