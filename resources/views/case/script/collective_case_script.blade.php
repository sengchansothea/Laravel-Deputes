{{--setTimeout(function() {--}}
<script type="text/javascript">
    $(document).ready(function() {
        $('#case_type_id').select2();
        $('#business_activity').select2();
        $('#sector_id').select2();
        $('#company_type_id').select2();
        $('#province_id').select2();
        $('#district_id').select2();
        $('#commune_id').select2();
        $('#village_id').select2();
        $('#gender').select2();
        $('#nationality').select2();
        $('#officer_id').select2();

        var counterRepre = 2;
        var counterIssue = 2;


        // $('#province').select2();
        // $('#district').select2();
        // $('#commune').select2();
        // $('#village').select2();


        /** បន្ថែមតំណាងប្រតិភូចរចា (តំណាងកម្មករនិយោជិត) */
        $("#btn-add-representative").click(function () {
            if(counterRepre > 10){
                return false;
            }
            // Create a jQuery object from the HTML code for Sub Disputant

            var html = $('<div>', {
                class: 'row',
                id: 'collectiveRepre_'+ counterRepre,
                html : `
                    <div class="form-group col-7 mt-3 mb-3 dynamic-form-group">
                        <label class="fw-bold pink mb-1" for="repName1">ឈ្មោះតំណាងកម្មករនិយោជិត</label>
                        <div class="d-flex align-items-center">
                            <input type="hidden" name="repID[]" value="0">
                            <input type="text" name="repName[]" id="repName1" class="form-control" style="flex: 1;">
                        </div>
                    </div>

                `
            });

            // Append the HTML code to the div with id "disputant_emp"
            $('#collectiveRepre_' + (counterRepre - 1)).after(html);
            counterRepre ++;
        });
        /** លុបតំណាងប្រតិភូចរចា (តំណាងកម្មករនិយោជិត) */
        $("#btn-remove-representative").on("click", function() {
            if(counterRepre == 2){
                return false;
            }
            // Remove the last added input element
            $('#collectiveRepre_' + (counterRepre - 1)).remove();
            counterRepre--;
        });


        /** បន្ថែមចំណុចទាមទារ (Issues) */
        $("#btn-add-issue").click(function () {
            if(counterIssue > 10){
                return false;
            }
            // Create a jQuery object from the HTML code for Sub Disputant

            var html = $('<div>', {
                class: 'row',
                id: 'collectiveIssue_'+ counterIssue,
                html : `
                        <div class="form-group col-12 mt-3 mb-3">
                            <label class="fw-bold text-danger mb-1" for="issues1">ចំណុចទាមទារ</label>
                            <div class="d-flex align-items-center">
                                <input type="hidden" name="issueID[]" value="0">
                                <textarea name="issues[]" id="issues1" class="form-control" rows="4" style="flex: 1;"></textarea>
                            </div>
                        </div>

                    `
                  });

            // Append the HTML code to the div with id "disputant_emp"
            $('#collectiveIssue_' + (counterIssue - 1)).after(html);
            counterIssue ++;
        });
        /** លុបតំណាងប្រតិភូចរចា (តំណាងកម្មករនិយោជិត) */
        $("#btn-remove-issue").on("click", function() {
            if(counterIssue == 2){
                return false;
            }
            // Remove the last added input element
            $('#collectiveIssue_' + (counterIssue - 1)).remove();
            counterIssue--;
        });


        /** បន្ថែមចំណុចទាមទារ (Issues) */
        let issuesCount = 1;

        // Add new issues section on button click
        $('#btn-issues').on('click', function() {
            issuesCount++;

            // Clone the first issues div
            let newIssuesDiv = $('#issues-container .form-group:first').clone();

            // Update the label 'for' attribute and textarea 'id' with the new count
            newIssuesDiv.find('label').attr('for', 'issues' + issuesCount);
            newIssuesDiv.find('textarea').attr('id', 'issues' + issuesCount).val('');

            // Update the label to show the correct number
            newIssuesDiv.find('label').text('ចំណុចទាមទារទី' + issuesCount);

            // Append the new representative section
            $('#issues-container').append(newIssuesDiv);
        });

        // Remove issues section on delete button click
        $(document).on('click', '.delete-btn', function() {
            if ($('#issues-container .form-group').length > 1) {
                $(this).closest('.form-group').remove();
                // Update the count and IDs after deletion
                updateIssuesCount();
            }
        });

        // Function to update issue counts
        function updateIssuesCount() {
            $('#issues-container .form-group').each(function(index) {
                const newCount = index + 1; // start from 1
                $(this).find('label').attr('for', 'issues' + newCount).text('ចំណុចទាមទារទី ' + newCount);
                $(this).find('textarea').attr('id', 'issues' + newCount);
            });

            // Update the global issuesCount to the current count
            issuesCount = $('#issues-container .form-group').length;
        }


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
        // $('#case_type_id').trigger('change');



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

                            $("#first_business_act").val(data.first_business_act);
                            $("#article_of_company").val(data.article_of_company);
                            $("#business_activity").select2().val(data.business_activity).trigger("change");
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

                            var strResult = "លេខអត្តសញ្ញាណ LACMS: " + data.company_id + "\n"
                                        + "លេខសម្គាល់អត្តសញ្ញាណសហគ្រាស (Single ID): " + data.single_id + "\n"
                                        + "នាមករណ៍សហគ្រាស ជាភាសាខ្មែរ: " + data.company_name_khmer + "\n"
                                        + "នាមករណ៍សហគ្រាស ជាភាសាអង់គ្លេស: " + data.company_name_latin + "\n"
                                        + "លេខចុះបញ្ជីពាណិជ្ជកម្ម: " + data.company_register_number + "\n"
                                        + "កាលបរិច្ឆេទចុះបញ្ជី: " + data.registration_date + "\n"
                                        + "លេខអត្តសញ្ញាណកម្មសារពើពន្ធ (TIN): " + data.company_tin + "\n";


                            $("#company_result").text(strResult);
                            // $('#company_result').html(strResult);  // For HTML content





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
            "name", "gender", "dob", "nationality", "id_number", "represent_company_phone_number", "occupation",
            "pob_province_id", "pob_district_id", "pob_commune_id",
            "province", "district", "commune", "village",
            "addr_house_no", "addr_street"
        ); // (3)


        $('#case_date').datepicker({});
        $('#case_date_entry').datepicker({});
        $('#collectives_cause_id').select2();
        $('#collectives_assigned_officer_date').datepicker({});


        $("#case_date, #case_date_entry").keydown(function(event) {
            return false;
        });
        $("#company_phone_number, #phone_number").keypress(function(event){
            if (!(event.charCode >= 48 && event.charCode <= 57)){ // 0-9
                event.preventDefault();
                return false;
            }
            // if ((event.charCode >= 48 && event.charCode <= 57) || // 0-9
            //     (event.charCode >= 65 && event.charCode <= 90) || // A-Z
            //     (event.charCode >= 97 && event.charCode <= 122))  // a-z
        });
        $("#id_numberXXX").keypress(function(event){
            if ( (event.charCode >= 6016 && event.charCode <= 6121) ){ //except ០-៩
                event.preventDefault();
                return false;
                //alert(event.charCode); 6016 - 6121
            }
        });

    });
</script>
@include('script.my_sweetalert2')
@include('case.script.collective_case_address_script')

<!-- Plugins Datepicker-->
<script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
<script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
<!-- Plugins Timepicker-->
{{--<script src="{{ rurl('assets/js/time-picker/jquery-clockpicker.min.js') }}"></script>--}}
{{--<script src="{{ rurl('assets/js/time-picker/highlight.min.js') }}"></script>--}}
{{--<script src="{{ rurl('assets/js/time-picker/clockpicker.js') }}"></script>--}}
<!-- Plugins Select2-->
<script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>
