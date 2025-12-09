{{--setTimeout(function() {--}}
<script type="text/javascript">
    $(document).ready(function() {
        $('#case_type_id').select2();
        $('#sector_id').select2();
        $('#company_type_id').select2();
        $('#province_id').select2();
        $('#district_id').select2();
        $('#commune_id').select2();
        $('#village_id').select2();
        $('#gender').select2();
        $('#nationality').select2();

        $('#pob_country_id').select2();
        $('#pob_province_id').select2();
        $('#pob_district_id').select2();
        $('#pob_commune_id').select2();


        $('#province').select2();
        $('#district').select2();
        $('#commune').select2();
        $('#village').select2();

        $('#case_objective_id').select2();
        $('#disputant_contract_type').select2();
        //$('#disputant_work_hour_day').select2();
        //$('#disputant_work_hour_week').select2();
        $('#disputant_night_work').select2();
        $('#disputant_holiday_week').select2();
        $('#disputant_holiday_year').select2();
        $('#disputant_terminated_contract').select2();
        $('#officer_id8').select2();

        // Preventing Clicking on Submit button many times
        $('#frmCaseCreated').on('submit', function () {
            const btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).text('កំពុងរក្សារទុក...');
        });

        /** Toogle Show Company Result */
        $("#btn_search_company").click(function(){
            if($("#btn_search_company").val() == 1 ){
                $("#div_company_result").show();
                $("#btn_search_company").val(0);
                $("#btn_search_company").text("បិទព័ត៌មានលម្អិតរបស់សហគ្រាស គ្រឹះស្ថាន");
            }
            else{
                $("#div_company_result").hide();
                $("#btn_search_company").val(1);
                $("#btn_search_company").text("បង្ហាញព័ត៌មានលម្អិតរបស់សហគ្រាស គ្រឹះស្ថាន");
            }
        });

        /** Toogle Case Type  */
        // $('#case_type_id').on('change', function() {
        //     var caseTypeId = $(this).val();
        //     // alert(caseTypeId);
        //     if (caseTypeId == '1') {
        //         $("#plantiff_order").text("1");
        //         $("#defendant_order").text("2");
        //         // For case_type_id = 1, show Plantiff Block first, then Defendant Block
        //         $('#plantiff_block').show();
        //         $('#defendant_block').show();
        //         $('#plantiff_block').insertBefore('#defendant_block'); // Reorder
        //
        //     } else if (caseTypeId == '2') {
        //         $("#defendant_order").text("1");
        //         $("#plantiff_order").text("2");
        //         // For case_type_id = 2, show Defendant Block first, then Plantiff Block
        //         $('#plantiff_block').show();
        //         $('#defendant_block').show();
        //         $('#defendant_block').insertBefore('#plantiff_block'); // Reorder
        //     }
        // });

        // Trigger the change event on page load to handle pre-selected option
        $('#case_type_id').trigger('change');


        /** Number Allowed Only */
        $(".number_only").keypress(function(event){
            if (!(event.charCode >= 48 && event.charCode <= 57)){ // 0-9
                event.preventDefault();
                return false;
            }
            // if ((event.charCode >= 48 && event.charCode <= 57)  // 0-9
            //     (event.charCode >= 65 && event.charCode <= 90)  // A-Z
            //     (event.charCode >= 97 && event.charCode <= 122))  // a-z
        });
        $(".number_only_d").keypress(function(event){
            if (!( (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)){ // 0-9
                event.preventDefault();
                return false;
            }
        });


        /** Tiggered Objective and Objective_Other */
        $('#case_objective_id').on('change', function(){
            var selectedVal = $(this).find("option:selected").text();  //Get Value from Selection
            $('#case_ojective_other').val(selectedVal);
        });


        /** Triggered Nationality and Pob_Country */
        var nationalityValue = $('#nationality').val();

        //if(nationalityValue > 0){
        //    $('#pob_country_id').val(nationalityValue).trigger('change'); // Update and trigger change event
        //}

        $('#nationality').on('change', function() {
            var selectedValue = $(this).val();
            $('#pob_country_id').val(selectedValue).trigger('change'); // Update and trigger change event
        });


        /** Let's generate Auto Case Index Number */
        const caseTypeSelect = $('select[name="case_type_id"]');
        const caseNum = $('input[name="case_number"]');
        const caseNumFullStr = $('input[name="case_num_str"]');


        // Embed PHP variables into JavaScript
        const cYear = {!! json_encode($cYear) !!};

        generateAutoCaseNumber();
        caseTypeSelect.on('change', function() {
            generateAutoCaseNumber();

        });

        $('#case_number').on('change onkeypress', function() {
            generateAutoCaseNumber();
        });
        function generateAutoCaseNumber(){
            let caseNumberLabel;
            let caseTypeID = $('#case_type_id').val();
            let casePre = caseNum.val();

            if (caseTypeID == 1) {
                caseNumberLabel = casePre + "/" + cYear + "/វប";
            } else if (caseTypeID == 2) {
                caseNumberLabel = casePre + "/" + cYear + "/វស";
            } else if (caseTypeID == 3) {
                caseNumberLabel = casePre + "/" + cYear + "/វរ";
            }
            caseNumFullStr.val(caseNumberLabel);
        }

        /**  ===============Load Find Company: Event AutoComplete, Event POB, DOB ============ */
        eventChangeAddress("", "province_id", "district_id", "commune_id", "village_id"); // (2)
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
                        ,
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
                            $("#company_id_auto").val(1);
                            $("#company_id").val(data.company_id);
                            $("#company_id_lacms").val(data.company_id);
                            $("#company_option").val(data.company_option);
                            $("#company_name_khmer").val(data.company_name_khmer);
                            $("#company_name_latin").val(data.company_name_latin);

                            //$("#business_activity").select2().val(data.business_activity).trigger("change");
                            $("#company_register_number").val(data.company_register_number);
                            $("#registration_date").val(data.registration_date);
                            $("#company_tin").val(data.company_tin);
                            $("#nssf_number").val(data.nssf_number);
                            $("#company_type_id").select2().val(data.company_type_id).trigger("change");
                            $("#first_business_act").val(data.first_business_act);
                            $("#article_of_company").val(data.article_of_company);
                            $("#sector_id").select2().val(data.sector_id).trigger("change");
                            $("#csic_1").val(data.csic_1);
                            $("#csic_2").val(data.csic_2);
                            $("#csic_3").val(data.csic_3);
                            $("#csic_4").val(data.csic_4);
                            $("#csic_5").val(data.csic_5);
                            $("#business_activity").val(data.business_activity);
                            $("#business_activity1").val(data.business_activity1);
                            $("#business_activity2").val(data.business_activity2);
                            $("#business_activity3").val(data.business_activity3);
                            $("#business_activity4").val(data.business_activity4);

                            $("#street_no").val(data.street_no);
                            $("#building_no").val(data.building_no);
                            $("#company_phone_number").val(data.company_phone_number);
                            $("#company_phone_number2").val(data.company_phone_number2);
                            $("#single_id").val(data.single_id);
                            $("#operation_status").val(data.operation_status);

                            var OperationStatusMap = {
                                0: "ផ្អាកដំណើរការ",
                                1: "កំពុងដំណើរការ",
                                2: "បិទលែងដំណើរការ",
                                3: "ផ្លាស់ប្តូរនាមករណ៍",
                                4: "មិនទាន់ដំណើរការ"
                            };
                            var operationStatus = OperationStatusMap[data.operation_status] || "មិនដឹង"; // Default if not found

                            var strResult = "លេខអត្តសញ្ញាណ LACMS: " + data.company_id + "\n"
                                + "លេខសម្គាល់អត្តសញ្ញាណសហគ្រាស (Single ID): " + data.single_id + "\n"
                                + "នាមករណ៍សហគ្រាស ជាភាសាខ្មែរ: " + data.company_name_khmer + "\n"
                                + "នាមករណ៍សហគ្រាស ជាភាសាអង់គ្លេស: " + data.company_name_latin + "\n"
                                + "ស្ថានភាពសហគ្រាស គ្រឹះស្ថាន: " + operationStatus + "\n"
                                + "លេខចុះបញ្ជីពាណិជ្ជកម្ម: " + data.company_register_number + "\n"
                                + "កាលបរិច្ឆេទចុះបញ្ជី: " + data.registration_date + "\n"
                                + "លេខអត្តសញ្ញាណកម្មសារពើពន្ធ (TIN): " + data.company_tin + "\n"
                                + "លេខអត្តសញ្ញាណសហគ្រាសនៃ ប.ស.ស: " + data.nssf_number + "\n"
                                + "សកម្មភាពអាជីវកម្ម: " + data.business_activity + "\n"
                                + "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី១: " + data.csic_1 + "\n"
                                + "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី២: " + data.csic_2 + "\n"
                                + "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៣: " + data.csic_3 + "\n"
                                + "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៤: " + data.csic_4 + "\n"
                                + "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៥: " + data.csic_5 + "\n"

                            $("#company_result").text(strResult);

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

        /**  ===============Load Find Employee: Event AutoComplete, Event POB, DOB ============ */
        eventChangePob("", "pob_province_id", "pob_district_id", "pob_commune_id"); // (1)
        eventChangeAddress("", "province", "district", "commune", "village"); // (2)
        eventAutocomplete(
            "find_employee_autocomplete", "response_message_employee",
            "",
            "name", "gender", "dob", "nationality", "id_number", "phone_number", "phone_number2", "occupation",
            "pob_province_id", "pob_district_id", "pob_commune_id",
            "province", "district", "commune", "village",
            "addr_street","addr_house_no"
        ); // (3)

        // var minDate = new Date();
        // minDate.setDate(minDate.getDate() - (365*80)); //365*
        // var maxDate = new Date();
        // maxDate.setDate(maxDate.getDate() - (365*15));
        $('#dob').datepicker({
            // view: 'years', //days
            //language: 'en',
            //dateFormat: 'dd-mm-yyyy',
            // minDate: minDate, // Now can select only dates, which goes after today
            // maxDate: maxDate, /// new Date("10/01/2023")
        });
        $('#terminated_contract_date').datepicker({});
        $('#disputant_sdate_work').datepicker({});
        $('#case_date').datepicker({});
        $('#case_date_entry').datepicker({});
        $('#officer_id').select2();
        $("#dob, #terminated_contract_date, #terminated_contract_time, #disputant_sdate_work,#case_date, #case_date_entry").keydown(function(event) {
            if (event.keyCode != 8) { // Allow only Backspace
                event.preventDefault();
                return false;
            }
            // if ((event.charCode >= 48 && event.charCode <= 57) || // 0-9
            //     (event.charCode >= 65 && event.cha rCode <= 90) || // A-Z
            //     (event.charCode >= 97 && event.charCode <= 122))  // a-z
        });
        $("#company_phone_number, #phone_number, #phone_number2").keypress(function(event){
            if (!(event.charCode >= 48 && event.charCode <= 57)){ // 0-9
                event.preventDefault();
                return false;
            }
            // if ((event.charCode >= 48 && event.charCode <= 57) || // 0-9
            //     (event.charCode >= 65 && event.charCode <= 90) || // A-Z
            //     (event.charCode >= 97 && event.charCode <= 122))  // a-z
        });
        $("#dobX").keypress(function(event){
            $("#dobX").keypress(function(event) {
                // Allow backspace (keyCode 8)
                if (event.keyCode != 8) {
                    event.preventDefault();
                    return false;
                }
            });

        });



    });
</script>
@include('script.my_sweetalert2')
@include('case.script.event_address_script')
<!-- Plugins Datepicker-->
<script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
<script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
<!-- Plugins Timepicker-->
<script src="{{ rurl('assets/js/time-picker/jquery-clockpicker.min.js') }}"></script>
{{--<script src="{{ rurl('assets/js/time-picker/highlight.min.js') }}"></script>--}}
<script src="{{ rurl('assets/js/time-picker/clockpicker.js') }}"></script>
<!-- Plugins Select2-->
<script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>
