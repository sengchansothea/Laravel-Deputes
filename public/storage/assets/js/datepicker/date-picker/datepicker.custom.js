"use strict";
(function($) {
    "use strict";
//Minimum and Maxium Date
//     $('#minMaxExample').datepicker({
//         language: 'en',
//         minDate: new Date() // Now can select only dates, which goes after today
//     });
//     $('#insp_date').datepicker({
//         language: 'en',
//         dateFormat: 'dd-mm-yyyy',
//         // minDate: new Date() // Now can select only dates, which goes after today
//         // ,maxDate: new Date("10/01/2023")
//     });
    // $("#insp_date, #insp_start_time, #insp_end_time").keypress(function(event){
    //     if (!(event.charCode >= 48 && event.charCode <= 57)){ // 0-9
    //         event.preventDefault();
    //         return false;
    //     }
    //     // if ((event.charCode >= 48 && event.charCode <= 57) || // 0-9
    //     //     (event.charCode >= 65 && event.charCode <= 90) || // A-Z
    //     //     (event.charCode >= 97 && event.charCode <= 122))  // a-z
    //     //     alert("0-9, a-z or A-Z");
    // });


//Disable Days of week
    var disabledDays = [0, 6];

    $('#disabled-days').datepicker({
        language: 'en',
        onRenderCell: function (date, cellType) {
            if (cellType == 'day') {
                var day = date.getDay(),
                    isDisabled = disabledDays.indexOf(day) != -1;
                return {
                    disabled: isDisabled
                }
            }
        }
    })
})(jQuery);
