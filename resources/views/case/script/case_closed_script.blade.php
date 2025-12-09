@push('childScript')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#case_closed_date').datepicker({});
            $('#case_closed_step_id').select2();
            $("#case_cause_id").select2();
            $("#case_solution_id").select2();
            $('#business_activity').select2();
            $('#csic1').select2();
            $('#csic2').select2();
            $('#csic3').select2();
            $('#csic4').select2();
            $('#csic5').select2();

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

            $("#case_closed_date").keydown(function(event){
                // Allow backspace, delete, tab, escape, enter, and arrow keys
                if ($.inArray(event.keyCode, [46, 8, 9, 27, 13, 37, 39]) !== -1 ||
                    // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                    (event.ctrlKey === true && $.inArray(event.keyCode, [65, 67, 86, 88]) !== -1) ||
                    // Allow "-" (dash)
                    event.keyCode === 189 || event.keyCode === 109) {
                    // 189 for the - on the standard keyboard
                    // 109 for the - on the numpad
                    return; // Allow these keys without any checks
                }

                // Ensure that the key pressed is a number (0-9)
                if (event.keyCode < 48 || event.keyCode > 57) {
                    event.preventDefault(); // Prevent the default action for any other keys

                    // if ((event.keyCode >= 48 && event.keyCode <= 57)  // 0-9
                    //     (event.keyCode >= 65 && event.keyCode <= 90)  // A-Z
                    //     (event.keyCode >= 97 && event.keyCode <= 122))  // a-z
                }
            });
        });
    </script>
@endpush
