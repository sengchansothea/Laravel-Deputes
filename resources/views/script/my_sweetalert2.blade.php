<script src="{{ rurl('assets/myjs/sweetalert2.10.10.1.all.min.js') }}"></script>
<style>
    .swal2-popup {
        font-size: 1rem !important;
        font-family: "Hanuman", Georgia, serif;
    }

    /** Related to Upload File Style */
    .label_upload {
        background-color: #f2d700;
        /* #D3D3D3 */
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

    #file-chosen {
        margin-left: 0.3rem;
        font-family: sans-serif;
    }
</style>
<script>
    /** ======== Confirm Delete with Normal Button (Normal Route) =========== */
    function comfirm_sweetalert2(my_url, message = "តើអ្នកពិតជាចង់លុបចោល មែនឫ?") {
        //alert(my_url);
        Swal.fire({
            title: message,
            // text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ពិតហើយ',
            cancelButtonText: 'អត់ទេ'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = my_url;
            }
        });
    }


    function comfirm_delete_file_steetalert2(html_id=1, my_url, file_name="", path="", table="", key_find="", key_value="", field="", message='Are You Sure?'){
        var my_data= {"file_name" : file_name, path: path, table: table, key_find: key_find, key_value: key_value, field: field };
         var url= my_url + '/' + file_name + '/' + path + '/' + table + '/' + key_find + '/' + key_value + '/' + field;
         //alert(url);
        //alert(my_data["path"]);
        //alert(table + ", key find:" + key_find + ", key_value:" + key_value + ", Field:" + field);
        Swal.fire({
            title: 'តើអ្នកចង់លុបឯកសារនេះឬ?',
            // text: "You won't be able to revert this!",
            width: 700,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'លុប',
            cancelButtonText: 'ទេ'
        }).then((result) => {
            //alert(my_data);
            if (result.isConfirmed) {
                $.ajax({
                    //url : my_url,
                    url : my_url + '/' + file_name + '/' + path + '/' + table + '/' + key_find + '/' + key_value + '/' + field,
                    //url : "ajaxDeleteFile" + '/' + file_name + '/' + path + '/' + table + '/' + key_find + '/' + key_value + '/' + field,
                    type : 'GET',
                    data: my_data,
                    dataType:'json',
                    beforeSend: function() {
                        swal.fire({
                            title: 'Please Wait..!',
                            text: 'Is working..',
                            onOpen: function() {
                                swal.showLoading()
                            }
                        })
                    },
                    success : function(data) {
                        swal.fire({
                            // position: 'top-right',
                            icon: 'success',
                            type: 'success',
                            title: 'ជោគជ័យ',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        $("#row_file"+html_id).remove();
                        location.reload();
                    },
                    complete: function() {
                        swal.hideLoading();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        //alert(my_url);
                        swal.hideLoading();
                        swal.fire("!Opps ", "Something went wrong, try again later", "error");
                    }
                });
            }
        });
    }
    function comfirmDeleteFileSweetalert2(my_url, file_name = "", path = "", table = "", key_find = "", key_value = "",
        field = "", message = 'Are You Sure?') {
        var my_data = {
            "file_name": file_name,
            path: path,
            table: table,
            key_find: key_find,
            key_value: key_value,
            field: field
        };
        var url = my_url + '/' + file_name + '/' + path + '/' + table + '/' + key_find + '/' + key_value + '/' + field;
        //alert(url);
        Swal.fire({
            title: message,
            // text: "You won't be able to revert this!",
            width: 700,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'លុប',
            cancelButtonText: 'ទេ'
        }).then((result) => {
            //alert(my_data);
            if (result.isConfirmed) {
                $.ajax({
                    //url : my_url,
                    url: my_url + '/' + file_name + '/' + path + '/' + table + '/' + key_find + '/' +
                        key_value + '/' + field,
                    type: 'GET',
                    data: my_data,
                    dataType: 'json',
                    beforeSend: function() {
                        swal.fire({
                            title: 'Please Wait..!',
                            text: 'Is working..',
                            onOpen: function() {
                                swal.showLoading()
                            }
                        })
                    },
                    success: function(data) {
                        swal.fire({
                            // position: 'top-right',
                            icon: 'success',
                            type: 'success',
                            title: 'ជោគជ័យ',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        //$("#row_file"+html_id).remove();
                        location.reload();
                    },
                    complete: function() {
                        swal.hideLoading();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        //alert(my_url);
                        swal.hideLoading();
                        swal.fire("!Opps ", "Something went wrong, try again later", "error");
                    }
                });
            }
        });
    }

    function confirmLetter1(my_url, company_id = 0) {
        var url = my_url + '/' + company_id;
        //alert(url);
        Swal.fire({
            title: 'ចេញលិខិតជូនដំណឹង​អធិការកិច្ចការងារ?',
            // text: "You won't be able to revert this!",
            width: 700,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ចេញលិខិត',
            cancelButtonText: 'ទេ'
        }).then((result) => {
            //alert(url);
            if (result.isConfirmed) {
                window.location = url;
                // Swal.fire(
                //     'Letter Deleted!',
                //     'Letter was Deleted.',
                //     'success',
                // )
            }

        });
    }

    function confirmDoInspection(my_url) {
        //alert(url);
        Swal.fire({
            title: "Are You Sure?",
            // text: "You won't be able to revert this!",
            width: 700,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            //alert(url);
            if (result.isConfirmed) {
                window.location = my_url;
                // Swal.fire(
                //     'Letter Deleted!',
                //     'Letter was Deleted.',
                //     'success',
                // )
            }

        });
    }

    function confirmLetter1Ajax(my_url, company_id = 0, letter_type = 0, inspection_id = 0) {
        var my_data = {
            "company_id": company_id,
            "letter_type": letter_type,
            "inspection_id": inspection_id
        };
        var html_id = 1;
        var url = my_url + '/' + company_id + '/' + letter_type + '/' + inspection_id;
        alert(url);
        Swal.fire({
            title: 'ចេញលិខិតជូនដំណឹង​អធិការកិច្ចការងារ?',
            // text: "You won't be able to revert this!",
            width: 700,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ចេញលិខិត',
            cancelButtonText: 'ទេ'
        }).then((result) => {
            if (result.isConfirmed) {
                //alert(my_data);
                $.ajax({
                    url: my_url + '/' + company_id + '/' + letter_type + '/' + inspection_id,
                    type: 'GET',
                    data: my_data,
                    dataType: 'json',
                    beforeSend: function() {
                        swal.fire({
                            title: 'Please Wait..!',
                            text: 'Is working..',
                            onOpen: function() {
                                swal.showLoading()
                            }
                        })
                    },

                    success: function(data) {
                        swal.fire({
                            // position: 'top-right',
                            icon: 'success',
                            type: 'success',
                            title: 'ជោគជ័យ',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        $("#row_file" + html_id).remove();
                        //location.reload();
                        //window.location = "{{ url('letter/list/1') }}";
                        //location.href("letter/list/1");
                    },
                    complete: function() {
                        swal.hideLoading();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        //alert(textStatus);
                        //alert(my_url + '/' + company_id);
                        swal.hideLoading();
                        swal.fire("!Opps ", "Something went wrong, try again later", "error");
                    }
                });
            }
        });
    }

    function confirmLetter1xx() {
        Swal.fire({
            icon: "warning",
            text: "{{ __('general.k_msg_success_upload') }}",
            showCloseButton: false,
            confirmButtonText: "បិទ",
            showConfirmButton: true // There won't be any confirm button
        });
    }

    function test_confirm() {
        Swal.fire({
            icon: "warning",
            text: "{{ __('general.k_msg_success_upload') }}",
            showCloseButton: false,
            confirmButtonText: "បិទ",
            showConfirmButton: true // There won't be any confirm button
        });
    }
</script>
<script>
    // document.querySelector(".third").addEventListener('click', function(){
    //     Swal.fire("Our First Alert", "With some body text and success icon!", "success");
    // });
    //Swal.fire({
    //    icon: "success",
    //    text: "<?php //echo lang('k_msg_success_upload')
    ?>//",
    //    showCloseButton: false,
    //    confirmButtonText: "បិទ",
    //    showConfirmButton: true // There won't be any confirm button
    //});
</script>
