<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- Plugins Datepicker-->
<script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
<script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
<!-- Plugins Timepicker-->
<script src="{{ rurl('assets/js/time-picker/jquery-clockpicker.min.js') }}"></script>
<script src="{{ rurl('assets/js/time-picker/clockpicker.js') }}"></script>
<!-- Plugins Select2-->
<script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#sector_id').select2();
        $('#unit_id').select2();
        $('#company_type_id').select2();
        $('#province_id').select2();
        $('#district_id').select2();

        /**  ===============Load Find Company: Event AutoComplete, Event POB, DOB ============ */
        eventChangeAddress("", "province_id", "district_id"); // (2)
        eventAutocompleteCompany("", "find_company_autocomplete", "response_message", "province_id", "district_id", "commune_id", "village_id");

        function eventAutocompleteCompany(counter ="", autocomplete_id = "find_company_autocomplete",response_message_id="response_message_company", addr_province ="province_id", addr_district="district_id", addr_commune="commune_id", addr_village="village_id"){
            $("#"+autocomplete_id+counter).autocomplete({
                source: function(request, response) {
                    $("#" + response_message_id+counter).fadeIn(); // Show waiting message
                    $.ajax({
                        url: "{{ url('/find_company_autocomplete') }}",
                        dataType: "json",
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 2, // Minimum characters before triggering autocomplete
                select: function(event, ui) {
                    //alert(ui.item.value);
                    // Fetch and display details when an item is selected
                    $.ajax({
                        url: "{{ url('/get-details') }}",
                        dataType: "json",
                        data: {
                            name: ui.item.value
                        },
                        success: function(data) {
                            $("#company_id").val(data.company_id);
                            $("#company_option").val(data.company_option);
                            $("#company_name_khmer").val(data.company_name_khmer);
                            $("#company_name_latin").val(data.company_name_latin);
                            $("#company_phone_number").val(data.company_phone_number);
                            $("#sector_id").select2().val(data.sector_id).trigger("change");
                            //$("#business_activity").select2().val(data.business_activity).trigger("change");

                            $("#first_business_act").val(data.first_business_act);
                            $("#article_of_company").val(data.article_of_company);
                            $("#business_activity").val(data.business_activity);
                            $("#business_activity1").val(data.business_activity1);
                            $("#business_activity2").val(data.business_activity2);
                            $("#business_activity3").val(data.business_activity3);
                            $("#business_activity4").val(data.business_activity4);
                            $("#company_register_number").val(data.company_register_number);
                            $("#registration_date").val(data.registration_date);
                            $("#company_tin").val(data.company_tin);
                            $("#nssf_number").val(data.nssf_number);

                            $("#company_type_id").select2().val(data.company_type_id).trigger("change");
                            $("#street_no").val(data.street_no);
                            $("#building_no").val(data.building_no);
                            $("#vil_id").val(data.village_id);
                            $("#com_id").val(data.commune_id);
                            $("#dis_id").val(data.district_id);
                            $("#pro_id").val(data.province_id);

                            $("#"+addr_province+counter).select2().val(data.province_id).trigger("change");
                            setTimeout(function() {
                                // Your code to be executed after 5 seconds
                                $("#"+addr_district+counter).select2().val(data.district_id).trigger('change');
                                //console.log('After 5 seconds');
                            }, 800);
                            setTimeout(function() {
                                // Your code to be executed after 5 seconds
                                $("#"+addr_commune+counter).select2().val(data.commune_id).trigger('change');
                                //console.log('After 5 seconds');
                            }, 2000);
                            setTimeout(function() {
                                // Your code to be executed after 5 seconds
                                $("#"+addr_village+counter).select2().val(data.village_id).trigger('change');
                                //console.log('After 5 seconds');
                            }, 4000);


                            //$("#province_id").select2().val(province_id).trigger("change");
                            // ajaxGetDistrict(province_id, district_id);
                            // ajaxGetCommnue(district_id, commune_id);
                            // ajaxGetVillage(commune_id, village_id);


                        }
                    });
                }
            });
        }

        var minDate = new Date();
        minDate.setDate(minDate.getDate() - (365*60)); //365*
        var maxDate = new Date();
        maxDate.setDate(maxDate.getDate() - (365*15));
        $('#dob').datepicker({
            view: 'years', //days
            //language: 'en',
            //dateFormat: 'dd-mm-yyyy',
            minDate: minDate // Now can select only dates, which goes after today
            ,maxDate: maxDate, /// new Date("10/01/2023")
        });
        $('#terminated_contract_date').datepicker({});
        $('#disputant_sdate_work').datepicker({});
        $('#case_date').datepicker({});
        $('#case_date_entry').datepicker({});
        $('#officer_id').select2();
        $("#terminated_contract_date, #terminated_contract_time, #disputant_sdate_work,#case_date, #case_date_entry").keydown(function(event) {
            return false;
        });
        $("#company_phone_number, #phone_number, #disputant_salary").keypress(function(event){
            if (!(event.charCode >= 48 && event.charCode <= 57)){ // 0-9
                event.preventDefault();
                return false;
            }
            // if ((event.charCode >= 48 && event.charCode <= 57) || // 0-9
            //     (event.charCode >= 65 && event.charCode <= 90) || // A-Z
            //     (event.charCode >= 97 && event.charCode <= 122))  // a-z
        });
        $("#id_number").keypress(function(event){
            if ( (event.charCode >= 6016 && event.charCode <= 6121) ){ //except ០-៩
                event.preventDefault();
                return false;
                //alert(event.charCode); 6016 - 6121
            }
        });

    });
</script>
