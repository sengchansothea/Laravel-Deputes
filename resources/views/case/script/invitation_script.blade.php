<script type="text/javascript">
    $(document).ready(function() {
        $('#case_type_id').select2();
        $('#company_type_id').select2();
        $('#province_id').select2();
        $('#district_id').select2();
        $('#commune_id').select2();
        $('#village_id').select2();
        $('#gender').select2();
        $('#nationality_id').select2();

        $('#case_objective_id').select2();
        $('#disputant_contract_type').select2();
        $('#disputant_work_hour_day').select2();
        $('#disputant_work_hour_week').select2();
        $('#disputant_night_work').select2();
        $('#disputant_holiday_week').select2();
        $('#disputant_holiday_year').select2();
        $('#disputant_terminated_contract').select2();
        
        $('#inv_number').on('change onkeypress', function() {
            generateAutoInvNumber();
        });
        function generateAutoInvNumber(){
            let invNum = $('#inv_number').val();

            $("#inv_num_str").val(invNum + " ក.ប/អ.ក/វ.ក");
        }

        $('#dob').datepicker({
            //language: 'en',
            //dateFormat: 'dd-mm-yyyy',
            // minDate: minDate // Now can select only dates, which goes after today
            // ,maxDate: maxDate /// new Date("10/01/2023")
        });
        $("#contact_phone, #phone_number, #inv_number").keypress(function(event){
            if (!(event.charCode >= 48 && event.charCode <= 57)){ // 0-9
                event.preventDefault();
                return false;
            }
            // if ((event.charCode >= 48 && event.charCode <= 57) || // 0-9
            //     (event.charCode >= 65 && event.charCode <= 90) || // A-Z
            //     (event.charCode >= 97 && event.charCode <= 122))  // a-z
            //     alert("0-9, a-z or A-Z");
        });
        $('#meeting_date').datepicker({});
        $('#receive_date').datepicker({});
        $('#letter_date').datepicker({});

        $("#dob, #meeting_date, #meeting_time, #receive_date, #receive_time, #letter_date").keydown(function(event) {
            return false;
        });

        /**  ===============Load Default- Employee: Event AutoComplete, Event POB, DOB ================ */
        eventChangePob("", "pob_province_id", "pob_district_id", "pob_commune_id"); // (1)
        eventChangeAddress("", "province", "district", "commune", "village"); // (2)
        eventAutocomplete(
            "find_employee_autocomplete", "response_message",
            "",
            "name", "gender", "dob", "nationality",
            "id_number", "phone_number", "occupation",
            "pob_province_id", "pob_district_id", "pob_commune_id",
            "province", "district", "commune", "village",
            "addr_house_no", "addr_street"
        ); // (3)

    });
</script>
@include('script.my_sweetalert2')
{{--@include('case.script.event_address_script')--}}
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
