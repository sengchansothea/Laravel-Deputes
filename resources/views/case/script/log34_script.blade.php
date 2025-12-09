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
        Swal.fire({
            title: message,
            // text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'លុបចោល!',
            cancelButtonText: 'អត់ទេ'
        }).then((result) => {
            if (result.isConfirmed) {
                //alert(my_url);
                window.location.href=my_url;
            }
        });
    }
</script>

<!-- Script for Log34-->
<script type="text/javascript">
    $(document).ready(function(){

        $('#collectives_emp_gender').select2();
        $('#collectives_emp_nationality').select2();
        $('#collectives_emp_pob_pro_id').select2();
        $('#collectives_emp_pob_dis_id').select2();
        $('#collectives_emp_pob_com_id').select2();
        $('#collectives_emp_pro_id').select2();
        $('#collectives_emp_dis_id').select2();
        $('#collectives_emp_com_id').select2();
        $('#collectives_emp_vil_id').select2();

        $('#gender').select2();
        $('#nationality').select2();
        $('#pob_province_id').select2();
        $('#pob_district_id').select2();
        $('#pob_commune_id').select2();
        $('#province').select2();
        $('#district').select2();
        $('#commune').select2();
        $('#village').select2();

        $('#case_objective_id').select2();
        $('#disputant_contract_type').select2();
        $('#disputant_work_hour_day').select2();
        $('#disputant_work_hour_week').select2();
        $('#disputant_night_work').select2();
        $('#disputant_holiday_week').select2();
        $('#disputant_holiday_year').select2();
        $('#disputant_terminated_contract').select2();
        $('#head_meeting').select2();
        $('#noter').select2();
        $('#nationality1').select2();

        /** Toogle Collectives Employee Block */
        $("#btnAddCollectivesEmp").on("click", function() {
            if($("#btnAddCollectivesEmp").val() == 0){
                $("#btnAddCollectivesEmp").val(1);
                $("#btnAddCollectivesEmp")
                    .removeClass("btn-info")   // Remove the current class
                    .addClass("btn-danger");   // Add the new class
                // $("#btnAddCollectivesEmp").text("- បន្ថែមឈ្មោះ តំណាងកម្មករនិយោជិត");
                $("#addEmpBlock").show();
            }
            else if($("#btnAddCollectivesEmp").val() == 1){
                $("#btnAddCollectivesEmp").val(0);
                $("#btnAddCollectivesEmp")
                    .removeClass("btn-danger")   // Remove the current class
                    .addClass("btn-info");   // Add the new class
                // $("#btnAddCollectivesEmp").text("បន្ថែមឈ្មោះ តំណាងកម្មករនិយោជិត");
                $("#addEmpBlock").hide();
            }
        });

        /** Toogle Collectives Other Employee Block */

        $("#btnAddCollectivesOtherAttendant").on("click", function() {
            if($("#btnAddCollectivesOtherAttendant").val() == 0){
                $("#btnAddCollectivesOtherAttendant").val(1);
                $("#btnAddCollectivesOtherAttendant")
                    .removeClass("btn-secondary")   // Remove the current class
                    .addClass("btn-danger");   // Add the new class
                // $("#btnAddCollectivesEmp").text("- បន្ថែមឈ្មោះ តំណាងកម្មករនិយោជិត");
                $("#addOtherEmpBlock").show();

            }
            else if($("#btnAddCollectivesOtherAttendant").val() == 1){
                $("#btnAddCollectivesOtherAttendant").val(0);
                $("#btnAddCollectivesOtherAttendant")
                    .removeClass("btn-danger")   // Remove the current class
                    .addClass("btn-secondary");   // Add the new class
                $("#addOtherEmpBlock").hide();
            }
        });



        /** Toogle Collectives Sub Employee Block */
        $("#btnAddCollectivesSubEmp").on("click", function() {
            if($("#btnAddCollectivesSubEmp").val() == 0){
                $("#btnAddCollectivesSubEmp").val(1);
                $("#btnAddCollectivesSubEmp")
                    .removeClass("btn-info")   // Remove the current class
                    .addClass("btn-danger");   // Add the new class
                // $("#btnAddCollectivesEmp").text("- បន្ថែមឈ្មោះ តំណាងកម្មករនិយោជិត");
                $("#addSubEmpBlock").show();
            }
            else if($("#btnAddCollectivesSubEmp").val() == 1){
                $("#btnAddCollectivesSubEmp").val(0);
                $("#btnAddCollectivesSubEmp")
                    .removeClass("btn-danger")   // Remove the current class
                    .addClass("btn-info");   // Add the new class
                $("#addSubEmpBlock").hide();


            }
        });

        $("#btn_add_employee_sub").on("click", function() {

            if($("#btn_add_employee_sub").val() == 0){
                $("#btn_add_employee_sub").val(1);
                $("#btn_add_employee_sub").text("លុបឈ្មោះអមកម្មករ");
                $("#add_employee_blog").show();
            }
            else if($("#btn_add_employee_sub").val() == 1){
                $("#btn_add_employee_sub").val(0);
                $("#btn_add_employee_sub").text("បន្ថែមឈ្មោះអមកម្មករ");
                $("#add_employee_blog").hide();
                $("#name1").val("");
                $("#dob1").val("");
                $("#nationality1").val("");
                $("#id_number1").val("");
            }
            // var btnVal = $("#btn_add_employee_sub").val();
            // alert(btnVal);

        });

        /** Load Event Change Address For Both Current and POB Address For Collectives Employee */
        eventChangePob("", "collectives_emp_pob_pro_id", "collectives_emp_pob_dis_id", "collectives_pob_commune_id"); //
        eventChangeAddress("", "collectives_emp_pro_id", "collectives_emp_dis_id", "collectives_emp_com_id", "collectives_emp_vil_id"); //

        /** Load Event Change Address For Both Current and POB Address For Collectives Employee Other */
        eventChangePob("", "pob_province_id_other", "pob_district_id_other", "pob_commune_id_other"); //
        eventChangeAddress("", "province_other", "district_other", "commune_other", "village_other"); //

        /** Load Event Change Address For Both Current and POB Address For Sub Employee */
        eventChangePob("", "pob_province_id", "pob_district_id", "pob_commune_id"); //
        eventChangeAddress("", "province", "district", "commune", "village"); //

        /** Event AutoCompleted For Searching Collectives Employee */
        eventAutocomplete(
            "collectives_emp_autocompleted", "collectives_response_message",
            "",
            "collectives_emp_name", "collectives_emp_gender", "collectives_emp_dob", "collectives_emp_nationality",
            "collectives_id_number", "collectives_phone_number", "collectives_phone2_number", "collectives_emp_occupation",
            "collectives_emp_pob_pro_id", "collectives_emp_pob_dis_id", "collectives_pob_commune_id",
            "collectives_emp_pro_id", "collectives_emp_dis_id", "collectives_emp_com_id", "collectives_emp_vil_id",
            "collectives_emp_street_no","collectives_emp_house_no"
        );

        /** Event AutoCompleted For Searching Collectives Employee Other */
        eventAutocomplete(
            "find_other_employee_autocomplete", "response_message_other_employee",
            "",
            "name_other", "gender_other", "dob_other", "nationality_other",
            "id_number_other", "phone_number_other", "phone2_number_other", "occupation_other",
            "pob_province_id_other", "pob_district_id_other", "pob_commune_id_other",
            "province_other", "district_other", "commune_other", "village_other",
            "addr_street_other","addr_house_no_other"
        ); // (4)

        /** Event AutoCompleted For Searching Sub Employee */
        eventAutocomplete(
            "find_employee_autocomplete", "response_message_employee",
            "",
            "name", "gender", "dob", "nationality",
            "id_number", "phone_number", "phone2_number", "occupation",
            "pob_province_id", "pob_district_id", "pob_commune_id",
            "province", "district", "commune", "village",
            "addr_street","addr_house_no"
        );

        $("#phone_number1, #phone_number , #collectives_phone_number").keypress(function(event){
            if (!(event.charCode >= 48 && event.charCode <= 57)){ // 0-9
                event.preventDefault();
                return false;
            }
            // if ((event.charCode >= 48 && event.charCode <= 57) || // 0-9
            //     (event.charCode >= 65 && event.charCode <= 90) || // A-Z
            //     (event.charCode >= 97 && event.charCode <= 122))  // a-z
        });
        $("#id_number1 , #id_number, #collectives_id_number").keypress(function(event){
            if ( (event.charCode >= 6016 && event.charCode <= 6121) ){ //except ០-៩
                event.preventDefault();
                return false; //alert(event.charCode); 6016 - 6121
            }
        });
        // var maxDate = new Date();
        // maxDate.setDate(maxDate.getDate() - 5475); //

        $('#meeting_date , #dob1, #collectives_emp_dob').datepicker({
            // maxDate: maxDate,
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
