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
    function comfirm_sweetalert2(my_url, message = "Are you sure?"){
        //alert(my_url);
        Swal.fire({
            title: message,
            // text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href=my_url;
            }
        });
    }
    function comfirm_delete_steetalert2(my_url, message = "Are you sure?"){
        //alert(my_url);
        Swal.fire({
            title: message,
            // text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ពិតមែនហើយ',
            cancelButtonText: 'អត់ទេ'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href=my_url;
            }
        });
    }
</script>


<script type="text/javascript">
    $(document).ready(function(){
        var counter_disputant = 1;
        var counter_officer = 2;
        var counter_log620 = 2;
        var counter_log621 = 2;

        /**  =============== Onclick Button Show/Hide Information ================ */
        $("#btn_employee").click(function(){
           if($("#btn_employee").val() == 1 ){
               $("#r1Employee").show();
               $("#r2Employee").show();
               $("#btn_employee").val(0);
               $("#btn_employee").text("បិទព័ត៌មានលម្អិត");
           }
           else{
               $("#r1Employee").hide();
               $("#r2Employee").hide();
               $("#btn_employee").val(1);
               $("#btn_employee").text("បង្ហាញព័ត៌មានលម្អិត");
           }

        });
        $("#btn_company").click(function(){
            if($("#btn_company").val() == 1 ){
                $("#r1Company").show();
                $("#r2Company").show();
                $("#btn_company").val(0);
                $("#btn_company").text("បិទព័ត៌មានលម្អិត");
            }
            else{
                $("#r1Company").hide();
                $("#r2Company").hide();
                $("#btn_company").val(1);
                $("#btn_company").text("បង្ហាញព័ត៌មានលម្អិត");
            }

        });


        var current_status_id = $("#current_status_id").val();
        $(".radio_status_id").click(function(){
            //alert("click" + current_status_id);
            var radio_status_id = $(this).val();
            showHideUploadStatusLetter(current_status_id, radio_status_id);
        });

        showHideUploadStatusLetter(current_status_id, current_status_id);
        function showHideUploadStatusLetter(current_status_id, radio_status_id){
            //alert("status id: " + radio_status_id);
            if(radio_status_id == 1){
                $("#div_reopen_upload_file").hide();
                $('#status_date').prop('required', false); // remove required attr
                $('#status_time').prop('required', false); // remove required attr
                $('#status_letter').prop('required', false); // remove required attr
            }
            else if(radio_status_id == 2){
                if(current_status_id == 2){
                    $("#div_reopen_upload_file").show();
                    $("#check_reopen_status").show();

                    $("#show_upload_status_letter").hide();
                    $('#status_date').prop('required', false); // remove required attr
                    $('#status_time').prop('required', false); // remove required attr
                    $('#status_letter').prop('required', false); // remove required attr
                }
                else{
                    $("#div_reopen_upload_file").hide();
                    $('#status_date').prop('required', false); // remove required attr
                    $('#status_time').prop('required', false); // remove required attr
                    $('#status_letter').prop('required', false); // remove required attr
                }
                //$("#div_btn_renew_log6").show();
                //$("#show_upload_status_letter").hide();
            }
            else if(radio_status_id == 3){
                $("#div_reopen_upload_file").show();
                $("#check_reopen_status").hide();
                $("#show_upload_status_letter").show();
                $('#status_date').prop('required', true); //add required attr
                $('#status_letter').prop('required', true); //add required attr

            }
        }


        $("#reopen_status").click(function(){
            var isChecked = $('#reopen_status').prop('checked');
            showHideReopenLog(isChecked);
        });

        var reopen_status = $("#reopen_status").val();
        showHideReopenLog(reopen_status, current_status_id);

        function showHideReopenLog(isChecked, status_id){
            //alert(isChecked);
            if (isChecked == 1) {
                //$("#div_reopen_upload_file").show();
                $("#show_upload_status_letter").show();
                $("#reopen_status").val(1);
                $('#status_date').prop('required', true); //add required attr
                $('#status_letter').prop('required', true); //add required attr
            }
            else {
                $("#reopen_status").val(0);
                $("#show_upload_status_letter").hide();
                $('#status_date').prop('required', false); // remove required attr
                $('#status_letter').prop('required', false); // remove required attr
            }
            if(status_id == 3){
                $("#div_reopen_upload_file").show();
                $("#check_reopen_status").hide();
                $("#show_upload_status_letter").show();
                $('#status_date').prop('required', true); //add required attr
                $('#status_letter').prop('required', true); //add required attr
            }

        }
        /**  =============== Select2 ================ */
        $('#log6_meeting_place_id').select2();
        $('#sub_employee_nationality').select2();
        $('#represent_company_gender').select2();
        $('#represent_company_nationality').select2();
        $('#sub_company_nationality').select2();
        $('#sub_officer').select2();
        $('#sub_officer1').select2();
        $('#sub_company_gender').select2();
        $('#sub_employee_gender').select2();
        $('#log624_cause_id').select2();
        $('#log625_solution_id').select2();
        $('#noter').select2();

        /** ==============Event Button Add/Remove Officer, Log620, Log621 ====================== */
        $("#btn_add_officer").click(function () {
            if(counter_officer > 9){
                let timerInterval;
                // Swal.fire({
                //     title: "មិនអាចបន្ថែមបានទៀតទេ!",
                //     timer: 800,
                //     timerProgressBar: true,
                //     didOpen: () => {
                //         Swal.showLoading();
                //         const timer = Swal.getPopup().querySelector("b");
                //         timerInterval = setInterval(() => {
                //             timer.textContent = `${Swal.getTimerLeft()}`;
                //         }, 100);
                //     },
                //     willClose: () => {
                //         clearInterval(timerInterval);
                //     }
                // });
                return false;
            }

            // Create a jQuery object from the HTML code for Sub Disputant
            var html = $('<div>', {
                class: 'row',
                id: 'officer_'+ counter_officer,
                html: `
                       <div class="form-group col-sm-6 mt-3">
                            <div class="row py-1">
                                <div style="width:2%" class="mt-1">`+ counter_officer + `</div>
                                <div style="width:96%">
                                   {!! showSelect('sub_officer[]', myArrOfficerExcept($arrExcludedOfficerID, 1, 0), old('sub_officer[]'), " select2", "", "sub_officer` + counter_officer + `", "") !!}
                                </div>
                            </div>
                        </div>
`
            });
            // Append the HTML code to the div with id "disputant_emp"
            $('#officer_' + (counter_officer - 1)).after(html);
            $('#sub_officer' + counter_officer).select2();
            counter_officer ++;
        });
        //Remove Log620
        $("#btn_remove_officer").on("click", function() {
            if(counter_officer == 2){
                let timerInterval;
                // Swal.fire({
                //     title: "លុបលែងបានហើយ",
                //     timer: 800,
                //     timerProgressBar: true,
                //     didOpen: () => {
                //         Swal.showLoading();
                //         const timer = Swal.getPopup().querySelector("b");
                //         timerInterval = setInterval(() => {
                //             timer.textContent = `${Swal.getTimerLeft()}`;
                //         }, 100);
                //     },
                //     willClose: () => {
                //         clearInterval(timerInterval);
                //     }
                // });
                return false;
            }
            // Remove the last added input element
            $('#officer_' + (counter_officer - 1)).remove();
            counter_officer--;
        });

        $("#btn_add_log620").click(function () {
            if(counter_log620 > 20){
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
                id: 'log620_'+ counter_log620,
                html: `
                       <div class="form-group col-sm-5">
                            <label></label>
                            <div class="row py-1">
                                <div style="width:96%">
                                    <input type="hidden" name="log620_id[]" value="0">
                                    <textarea rows="4" name="log620_agree_point[]" class="form-control">{{ old('log620_agree_point[]') }}</textarea>
                                </div>
                            </div>
                       </div>
                       <div class="form-group col-sm-5">
                            <label></label>
                            <div class="row py-1">
                                <div style="width:96%">
                                    <textarea rows="4" name="log620_solution[]" class="form-control">{{ old('log620_solution[]') }}</textarea>
                                </div>
                            </div>
                       </div>

                      `
            });

            // Append the HTML code to the div with id "disputant_emp"
            $('#log620_' + (counter_log620 - 1)).after(html);
            counter_log620 ++;
        });
        //Remove Log620
        $("#btn_remove_log620").on("click", function() {
            if(counter_log620 == 2){
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
            $('#log620_' + (counter_log620 - 1)).remove();
            counter_log620--;
        });

        $("#btn_add_log621").click(function () {
            if(counter_log621 > 20){
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
                id: 'log621_'+ counter_log621,
                html: `
                        <div class="form-group col-sm-5">
                            <label></label>
                            <div class="row py-1">
                                <div style="width:96%">
                                    <input type="hidden" name="log621_id[]" value="0">
                                    <textarea rows="4" name="log621_disagree_point[]" class="form-control">{{ old('log621_disagree_point[]') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-5">
                            <label></label>
                            <div class="row py-1">
                                <div style="width:96%">
                                    {{--<input type="text" name="log621_solution[]" value="{{ old('log620_solution[]') }}" class="form-control">--}}
                                    <textarea rows="4" name="log621_solution[]" class="form-control">{{ old('log621_solution[]') }}</textarea>
                                </div>
                            </div>
                        </div>

                      `
            });

            // Append the HTML code to the div with id "disputant_emp"
            $('#log621_' + (counter_log621 - 1)).after(html);
            counter_log621 ++;
        });
        //Remove Log621
        $("#btn_remove_log621").on("click", function() {
            if(counter_log621 == 2){
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
            $('#log621_' + (counter_log621 - 1)).remove();
            counter_log621--;
        });

        /**  ===============Load Default-Sub Employee: Event AutoComplete, Event POB, DOB ================ */
        eventChangePob("", "sub_employee_pob_province_id", "sub_employee_pob_district_id", "sub_employee_pob_commune_id"); // (1)
        eventChangeAddress("", "sub_employee_province", "sub_employee_district", "sub_employee_commune", "sub_employee_village"); // (2)
        eventAutocomplete(
            "find_sub_employee_autocomplete", "response_message_sub_employee",
            "",
            "sub_employee_name", "sub_employee_gender", "sub_employee_dob", "sub_employee_nationality",
            "sub_employee_id_number", "sub_employee_phone_number","sub_employee_phone2_number" , "sub_employee_occupation",
            "sub_employee_pob_province_id", "sub_employee_pob_district_id", "sub_employee_pob_commune_id",
            "sub_employee_province", "sub_employee_district", "sub_employee_commune", "sub_employee_village",
            "sub_employee_addr_street", "sub_employee_addr_house_no"
        ); // (3)

        /**  ===============Load Default-Represent Company: Event AutoComplete, Event POB, DOB ============ */
        eventChangePob("", "represent_company_pob_province_id", "represent_company_pob_district_id", "represent_company_pob_commune_id"); // (1)
        eventChangeAddress("", "represent_company_province", "represent_company_district", "represent_company_commune", "represent_company_village"); // (2)
        eventAutocomplete(
            "find_represent_company_autocomplete", "response_message_company",
            "",
            "represent_company_name", "represent_company_gender", "represent_company_dob", "represent_company_nationality",
            "represent_company_id_number", "represent_company_phone_number", "represent_company_phone2_number", "represent_company_occupation",
            "represent_company_pob_province_id", "represent_company_pob_district_id", "represent_company_pob_commune_id",
            "represent_company_province", "represent_company_district", "represent_company_commune", "represent_company_village",
            "represent_company_addr_street", "represent_company_addr_house_no"
        ); // (3)


        /**  ===============Load Default-Sub Represent Company: Event AutoComplete, Event POB, DOB ========= */
        eventChangePob("", "sub_company_pob_province_id", "sub_company_pob_district_id", "sub_company_pob_commune_id"); // (1)
        eventChangeAddress("", "sub_company_province", "sub_company_district", "sub_company_commune", "sub_company_village"); // (2)
        eventAutocomplete(
            "find_sub_company_autocomplete", "response_message_sub_company",
            "",
            "sub_company_name", "sub_company_gender", "sub_company_dob", "sub_company_nationality",
            "sub_company_id_number", "sub_company_phone_number", "sub_company_phone2_number", "sub_company_occupation",
            "sub_company_pob_province_id", "sub_company_pob_district_id", "sub_company_pob_commune_id",
            "sub_company_province", "sub_company_district", "sub_company_commune", "sub_company_village",
            "sub_company_addr_street","sub_company_addr_house_no"
        ); // (3)

        /** ============Load Event Date Picker and Other =============================*/
        // var maxDate = new Date();
        // maxDate.setDate(maxDate.getDate() - 5475);
        $('#log6_date').datepicker({
            // maxDate: new Date(),
        });
        $('#sub_employee_dob').datepicker({
            // changeYear: true,
            // maxDate: maxDate,
        });
        $('#represent_company_dob').datepicker({
            // maxDate: maxDate,
        });
        $('#sub_company_dob').datepicker({
            // maxDate: maxDate,
        });
        // var maxDate2 = new Date();
        // maxDate2.setDate(maxDate2.getDate() + 30);
        $('#status_date').datepicker({
            // minDate:  new Date(),
            // maxDate: maxDate2,
        });

        $("#log6_date, #sub_employee_dob, #represent_company_dob, #sub_company_dob, #log6_stime, #log6_etime").keydown(function(event) {
            return false;
        });
        $("#sub_employee_phone_number1, #represent_company_phone_number, #sub_company_phone_number1").keypress(function(event){
            if (!(event.charCode >= 48 && event.charCode <= 57)){ //allow number only 0-9
                event.preventDefault();
                return false;
            }
            //#company_register_number, #nssf_number, #company_tin
            // if ((event.charCode >= 48 && event.charCode <= 57) || // 0-9
            //     (event.charCode >= 65 && event.charCode <= 90) || // A-Z
            //     (event.charCode >= 97 && event.charCode <= 122))  // a-z
            //     (event.charCode >= 6112 && event.charCode <= 6121))  // ០-៩
            //     (event.charCode >= 6016 && event.charCode <= 6121))  //all khmer កខ and ០-៩
            //     alert("0-9, a-z or A-Z");
        });
        $("#sub_employee_id_number1, #represent_company_id_number, #sub_company_id_number1").keypress(function(event){
            if ( (event.charCode >= 6016 && event.charCode <= 6121) ){ //except ០-៩
                event.preventDefault();
                return false;
                //alert(event.charCode); 6016 - 6121
            }

        });
        $("#meeting_date, #meeting_stime, #meeting_etime").keydown(function(event) {
            return false;
        });

    });
</script>
@include('case.script.event_address_script')
<!-- Plugins Datepicker-->
<script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
<script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
<!-- Plugins Timepicker-->
<script src="{{ rurl('assets/js/time-picker/jquery-clockpicker.min.js') }}"></script>
<script src="{{ rurl('assets/js/time-picker/clockpicker.js') }}"></script>
<!-- Plugins Select2-->
<script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>
