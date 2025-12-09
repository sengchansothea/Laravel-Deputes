<script src="{{ rurl('assets/js/jquery.ui.min.js') }}"></script>
<!-- Plugins Datepicker-->
<script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
<script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
<!-- Plugins Timepicker-->
<script src="{{ rurl('assets/js/time-picker/jquery-clockpicker.min.js') }}"></script>
<script src="{{ rurl('assets/js/time-picker/highlight.min.js') }}"></script>
<script src="{{ rurl('assets/js/time-picker/clockpicker.js') }}"></script>
<!-- Plugins Select2-->
<script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {

        /** Toogle Collectives Representatives  */
        $("#btn_cRepre").click(function(){
            if($("#btn_cRepre").val() == 1 ){
                $("#collectives-representatives").show();
                $("#btn_cRepre").val(0);
                $("#btn_cRepre").text("បិទឈ្មោះ តំណាងប្រតិភូចរចា (តំណាងកម្មករនិយោជិត)");
            }
            else{
                $("#collectives-representatives").hide();
                $("#btn_cRepre").val(1);
                $("#btn_cRepre").text("បង្ហាញឈ្មោះ តំណាងប្រតិភូចរចា (តំណាងកម្មករនិយោជិត)");
            }

        });



        $("#btnShowSelectOfficer").click(function() {
            $("#div_select_officer").show();
            $("#div_btn_change_officer").show();
            var val = $("#btnShowSelectOfficer").val();
            if(val == 0){
                $("#btnShowSelectOfficer").val(1);
                $("#div_select_officer").show();
                $("#div_btn_change_officer").show();
            }
            else if(val == 1){
                $("#btnShowSelectOfficer").val(0);
                $("#div_select_officer").hide();
                $("#div_btn_change_officer").hide();
            }
            //alert(val);

        });


        $(".uploadButton").click(function() {
            var id = $(this).val();
            var title = $(this).data('title');
            var url = $(this).data('url'); //"invitation/upload/file"
            alert(id);
            Swal.fire({
                title: title,
                html: `
                            <input type="hidden" name="id" id="id" value="`+id+`" >
                            <input type="hidden" name="url" id="url" value="`+url+`" >
                            <input type="file" id="fileInput" name="fileInput">
                            <div id="error-message" style="color: red;"></div>
                        `,
                width: '800px',
                showCancelButton: true,
                confirmButtonText: 'Upload',
                preConfirm: () => {
                    const id = document.getElementById('id').value.trim();
                    const url = document.getElementById('url').value.trim();
                    //const file = $('.swal2-file')[0].files[0];
                    //const file = document.getElementById('fileInput').files[0];
                    let file = $('#fileInput')[0].files[0];

                    if (!file) {
                        document.getElementById('error-message').innerText = 'Please select a file.';
                        return false;
                    }
                    return { id: id, url: url, file: file };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    uploadPopupForm(result.value);
                }
            });
        });


        function uploadPopupForm(data) {
            let formData = new FormData();
            formData.append('id', data.id);
            formData.append('file', data.file);
            console.log(data.file);
            fetch(data.url, {
                method: 'POST',
                //processData: false,
                //contentType: false,
                body: formData,
                headers: {
                    //'Access-Control-Allow-Origin': '*',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
                .then(response => {
                    console.log(response);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Handle success response
                    console.log(data.message);
                    Swal.fire('Success', data.message, 'success');
                    location.reload(); // Reload the page upon successful upload
                })
                .catch(error => {
                    // Handle error
                    console.error('There was an error!', error);
                    Swal.fire('Error', 'There was an error uploading file' + error, 'error');
                });
        }



        $("#reopenLog6Insert").click(function() {
            var id = $(this).val();
            var title = $(this).data('title');
            var url = $(this).data('url'); //"invitation/upload/file"
            var status_date = $(this).data('status_date');
            var status_time = $(this).data('status_time');
            var status_letter = $(this).data('status_letter');
            // var showUpload = "";
            // if(status_letter.length == 0){
            //     showUpload = '<input type="file" id="fileInput" name="fileInput">';
            // }
            Swal.fire({
                title: title,
                html: `
<div class="pop-content">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="fw-bold">កាលបរិច្ឆេទណាត់ជួប</label>
                                <input type="text" name="status_date" id="status_date" value="`+status_date+`" class="form-control" data-language="en" required >
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="fw-bold">ម៉ោង</label>
                                    <div class="input-group clockpicker" data-autoclose="true">
                                        <input name="status_time" id="status_time" value="`+status_time+`" class="form-control" type="text" data-bs-original-title="" required >
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold">លិខិតសុំផ្សះផ្សាឡើងវិញ</label>
                                    <input type="file" id="fileInput" name="fileInput">
                                </div>
                        </div>
                        <div class="row">
                            <div id="error_message" style="color: red;"></div>
                        </div>
                        <br><br>

                        <input type="hidden" name="id" id="id" value="`+id+`" >
                        <input type="hidden" name="url" id="url" value="`+url+`" >
                        <input type="hidden" name="status_letter_old" id="status_letter_old" value="`+status_letter+`" >
</div>
                        `,
                width: '900px',

                showCancelButton: true,
                confirmButtonText: 'Save',

                preConfirm: () => {
                    const id = document.getElementById('id').value.trim();
                    const url = document.getElementById('url').value.trim();
                    const file = document.getElementById('fileInput').files[0];
                    const status_date = document.getElementById('status_date').value.trim();
                    const status_time = document.getElementById('status_time').value.trim();
                    const status_letter_old = document.getElementById('status_letter_old').value.trim();

                    // if (status_date == "") {
                    //     alert("hello");
                    //     document.getElementById('error_message').innerText = 'Please select a file.';
                    //         return false;
                    // }
                    if ((!file) || status_date == "" || status_time == "" ) {
                        document.getElementById('error_message').innerText = 'សូមបំពេញព័ត៌មានទាំងអស់';
                        return false;
                    }

                    return { id: id, url: url, file: file, status_date: status_date, status_time: status_time, status_letter_old: status_letter_old };
                }
                ,onOpen: function() {

                }

                ,didOpen: () => {
                    $('#status_date').datepicker({
                        dateFormat: 'dd-mm-yyyy' // Set the date format
                    });

                    const timepickerScript3 = document.createElement('script');
                    timepickerScript3.src = '{{ rurl('assets/js/time-picker/clockpicker.js') }}';
                    timepickerScript3.onload = () => {
                        // Initialize Datepicker after the script is loaded

                        // Adjust the z-index of the datepicker element
                        $('.ui-datepicker').css('z-index', Swal.zIndex.next());
                        $('.clockpicker-popover').css('z-index', Swal.zIndex.next());

                    };
                    document.head.appendChild(timepickerScript3);
                }

            }).then((result) => {
                if (result.isConfirmed) {
                    reopenLog6InsertPopupForm(result.value);
                }
            });
        });
        function reopenLog6InsertPopupForm(data) {
            let formData = new FormData();
            formData.append('id', data.id);
            formData.append('file', data.file);
            formData.append('status_date', data.status_date);
            formData.append('status_time', data.status_time);
            formData.append('status_letter_old', data.status_letter_old);
            fetch(data.url, { //'{{ url('invitation/upload/file') }}'
                method: 'POST',
                body: formData,
                headers: {
                    // 'Access-Control-Allow-Origin': '*',
                    // 'Content-Type' : 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',

                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Handle success response
                    Swal.fire('Success', data.message, 'success');
                    location.reload(); // Reload the page upon successful upload
                })
                .catch(error => {
                    // Handle error
                    console.error('There was an error!', error);
                    Swal.fire('Error', 'There was an error uploading file', 'error');
                });
        }
        $("#reopenLog6Update").click(function() {
            var id = $(this).val();
            var title = $(this).data('title');
            var url = $(this).data('url'); //"invitation/upload/file"
            var status_date = $(this).data('status_date');
            var status_time = $(this).data('status_time');
            var status_letter = $(this).data('status_letter');
            var showUpload = "";
            if(status_letter.length == 0){
                showUpload = '<input type="file" id="fileInput" name="fileInput">';
            }
            Swal.fire({
                title: title,
                html: `
                            <div class="row">
                                <div class="form-group col-6">
                                    <label class="fw-bold">កាលបរិច្ឆេទណាត់ជួប</label>
                                <input type="text"  name="status_date" id="status_date" value="`+status_date+`" class="form-control"  data-language="en" required >
                                </div>
                                <div class="form-group col-6">
                                    <label class="fw-bold">ម៉ោង</label>
                                    <div class="input-group clockpicker" data-autoclose="true">
                                        <input name="status_time" id="status_time" value="`+status_time+`" class="form-control" type="text" data-bs-original-title="" required >
                                    </div>
                                </div>
                        </div>
                        <div class="row">
                            <div id="error_message" style="color: red;"></div>
                        </div>
                        <br><br>
                        <input type="hidden" name="id" id="id" value="`+id+`" >
                        <input type="hidden" name="url" id="url" value="`+url+`" >

                        `,
                width: '800px',
                height: '800px',
                showCancelButton: true,
                confirmButtonText: 'Save',
                preConfirm: () => {
                    const id = document.getElementById('id').value.trim();
                    const url = document.getElementById('url').value.trim();
                    const status_date = document.getElementById('status_date').value.trim();
                    const status_time = document.getElementById('status_time').value.trim();
                    if (status_date == "" || status_time == "" ) {
                        document.getElementById('error_message').innerText = 'សូមបំពេញព័ត៌មានទាំងអស់';
                        return false;
                    }

                    return { id: id, url: url, status_date: status_date, status_time: status_time };
                }
                ,onOpen: function() {

                }
                ,didOpen: () => {
                    $('#status_date').datepicker({
                        dateFormat: 'dd-mm-yyyy' // Set the date format
                    });

                    const timepickerScript3 = document.createElement('script');
                    timepickerScript3.src = '{{ rurl('assets/js/time-picker/clockpicker.js') }}';
                    timepickerScript3.onload = () => {
                        // Initialize Datepicker after the script is loaded

                        // Adjust the z-index of the datepicker element
                        $('.ui-datepicker').css('z-index', Swal.zIndex.next());
                        $('.clockpicker-popover').css('z-index', Swal.zIndex.next());

                    };
                    document.head.appendChild(timepickerScript3);
                }

            }).then((result) => {
                if (result.isConfirmed) {
                    reopenLog6UpdatePopupForm(result.value);
                }
            });
        });
        function reopenLog6UpdatePopupForm(data) {
            let formData = new FormData();
            formData.append('id', data.id);
            //formData.append('file', data.file);
            formData.append('status_date', data.status_date);
            formData.append('status_time', data.status_time);
            formData.append('status_letter_old', data.status_letter_old);
            fetch(data.url, { //'{{ url('invitation/upload/file') }}'
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Handle success response
                    Swal.fire('Success', data.message, 'success');
                    location.reload(); // Reload the page upon successful upload
                })
                .catch(error => {
                    // Handle error
                    console.error('There was an error!', error);
                    Swal.fire('Error', 'There was an error uploading file', 'error');
                });
        }


        $(".reopenLog6Button").click(function() {
            var id = $(this).val();
            var title = $(this).data('title');
            var url = $(this).data('url'); //"invitation/upload/file"
            var status_date = $(this).data('status_date');
            var status_time = $(this).data('status_time');
            var status_letter = $(this).data('status_letter');
            var showUpload = "";
            if(status_letter.length == 0){
                showUpload = '<input type="file" id="fileInput" name="fileInput">';
            }
            Swal.fire({
                title: title,
                html: `

                            <div class="row">
                                <div class="form-group col-3">
                                    <label class="fw-bold">កាលបរិច្ឆេទណាត់ជួប</label>
                                <input type="text"  name="status_date" id="status_date" value="`+status_date+`" class="form-control"  data-language="en" required >
                                </div>
                                <div class="form-group col-2">
                                    <label class="fw-bold">ម៉ោង</label>
                                    <div class="input-group clockpicker" data-autoclose="true">
                                        <input name="status_time" id="status_time" value="`+status_time+`" class="form-control" type="text" data-bs-original-title="" required >
                                    </div>
                                </div>
                                <div class="form-group col-5">
                                    <label class="fw-bold">លិខិតសុំផ្សះផ្សាឡើងវិញ</label>

@php
                            $show_file= showFile2(1, "`+status_letter+`", pathToDeleteFile('case_doc/log6/status_letter/'.$caseYear."/"), "delete", "tbl_case_log6", "id", "`+id+`",  "status_letter", "", "");
                            echo $show_file['file'];

                        @endphp
                        `+showUpload+`


                        </div>
                        </div>
                        <div class="row">
                            <div id="error_message" style="color: red;"></div>
                        </div>
                        <br><br><br><br>

                        <input type="hidden" name="id" id="id" value="`+id+`" >
                        <input type="hidden" name="url" id="url" value="`+url+`" >
                        <input type="hidden" name="status_letter_old" id="status_letter_old" value="`+status_letter+`" >
                        `,
                width: '800px',
                height: '800px',
                showCancelButton: true,
                confirmButtonText: 'Upload',
                preConfirm: () => {
                    const id = document.getElementById('id').value.trim();
                    const url = document.getElementById('url').value.trim();
                    const file = document.getElementById('fileInput').files[0];
                    const status_date = document.getElementById('status_date').value.trim();
                    const status_time = document.getElementById('status_time').value.trim();
                    const status_letter_old = document.getElementById('status_letter_old').value.trim();

                    // if (status_date == "") {
                    //     alert("hello");
                    //     document.getElementById('error_message').innerText = 'Please select a file.';
                    //         return false;
                    // }
                    if ((!file) || status_date == "" || status_time == "" ) {
                        document.getElementById('error_message').innerText = 'សូមបំពេញព័ត៌មានទាំងអស់';
                        return false;
                    }

                    return { id: id, url: url, file: file, status_date: status_date, status_time: status_time, status_letter_old: status_letter_old };
                }
                ,onOpen: function() {

                }
                ,didOpen: () => {

                    const additionalCss1 = document.createElement('link');
                    additionalCss1.rel = 'stylesheet';
                    additionalCss1.href = '{{ rurl('assets/css/date-picker.css') }}';
                    document.head.appendChild(additionalCss1);
                    // Append jQuery UI script dynamically to the form popup
                    const datepickerScript = document.createElement('script');
                    datepickerScript.src = 'https://code.jquery.com/ui/1.12.1/jquery-ui.js';
                    //datepickerScript.src = '{{ rurl('assets/js/time-picker/clockpicker.js') }}';
                    datepickerScript.onload = () => {
                        // Initialize Datepicker after the script is loaded
                        $('#status_date').datepicker({
                            dateFormat: 'dd-mm-yy' // Set the date format
                        });

                        // Adjust the z-index of the datepicker element
                        //$('.ui-datepicker').css('z-index', Swal.zIndex.next());
                        //$('.clockpicker-popover').css('z-index', Swal.zIndex.next());

                    };
                    document.head.appendChild(datepickerScript);

                    {{--const timepickerScript1 = document.createElement('script');--}}
                    {{--timepickerScript1.src = '{{ rurl('assets/js/time-picker/jquery-clockpicker.min.js') }}';--}}
                    {{--document.head.appendChild(timepickerScript1);--}}
                    {{--const timepickerScript2 = document.createElement('script');--}}
                    {{--timepickerScript2.src = '{{ rurl('assets/js/time-picker/highlight.min.js') }}';--}}
                    {{--document.head.appendChild(timepickerScript2);--}}
                    const timepickerScript3 = document.createElement('script');
                    timepickerScript3.src = '{{ rurl('assets/js/time-picker/clockpicker.js') }}';
                    timepickerScript3.onload = () => {
                        // Initialize Datepicker after the script is loaded

                        // Adjust the z-index of the datepicker element
                        $('.ui-datepicker').css('z-index', Swal.zIndex.next());
                        $('.clockpicker-popover').css('z-index', Swal.zIndex.next());

                    };
                    document.head.appendChild(timepickerScript3);
                }

            }).then((result) => {
                if (result.isConfirmed) {
                    reopenLog6PopupForm(result.value);
                }
            });
        });
        function reopenLog6PopupForm(data) {
            let formData = new FormData();
            formData.append('id', data.id);
            formData.append('file', data.file);
            formData.append('status_date', data.status_date);
            formData.append('status_time', data.status_time);
            formData.append('status_letter_old', data.status_letter_old);
            fetch(data.url, { //'{{ url('invitation/upload/file') }}'
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Handle success response
                    Swal.fire('Success', data.message, 'success');
                    location.reload(); // Reload the page upon successful upload
                })
                .catch(error => {
                    // Handle error
                    console.error('There was an error!', error);
                    Swal.fire('Error', 'There was an error uploading file', 'error');
                });
        }


        function uploadInvitationxxx(data) {
            let formData = new FormData();
            formData.append('invitation_id', data.invitation_id);
            formData.append('file', data.file);

            fetch('{{ url('invitation/upload/file') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            })
        .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
                .then(data => {
                    // Handle success response
                    Swal.fire('Success', data.message, 'success');
                    location.reload(); // Reload the page upon successful upload
                })
                .catch(error => {
                    // Handle error
                    console.error('There was an error!', error);
                    Swal.fire('Error', 'There was an error uploading the image', 'error');
                });
        }
        $("#uploadButtonInvitationEmployeexx").click(function() {
            Swal.fire({
                title: 'Upload លិខិតអញ្ជើញដែលមានវាយត្រា',
                html: `
                <input type="hidden" name="invitation_id" id="invitation_id" value="{{ $invitationEmployee['invitation_id'] }}" >
                <input type="file" id="fileInput" name="fileInput">
                <div id="error-message" style="color: red;"></div>

        `,
                width: '800px',
                showCancelButton: true,
                confirmButtonText: 'Upload',
                preConfirm: () => {
                    const file = document.getElementById('fileInput').files[0];
                    const invitation_id = document.getElementById('invitation_id').value.trim();
                    if (!file) {
                        document.getElementById('error-message').innerText = 'Please select an image.';
                        return false;
                    }
                    return { invitation_id: invitation_id, file: file };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    uploadInvitationEmployee(result.value);
                }
            });
        });
        $("#uploadButtonInvitationCompanyxx").click(function() {
            Swal.fire({
                title: 'Upload លិខិតអញ្ជើញដែលមានវាយត្រា',
                html: `
                <input type="text" name="invitation_id" id="invitation_id" value="{{ $invitationCompany['invitation_id'] }}" >
                <input type="file" id="fileInput" name="fileInput">
                <div id="error-message" style="color: red;"></div>

        `,
                width: '800px',
                showCancelButton: true,
                confirmButtonText: 'Upload',
                preConfirm: () => {
                    const file = document.getElementById('fileInput').files[0];
                    const invitation_id = document.getElementById('invitation_id').value.trim();
                    if (!file) {
                        document.getElementById('error-message').innerText = 'Please select an image.';
                        return false;
                    }
                    return { invitation_id: invitation_id, file: file };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    uploadInvitationCompany(result.value);
                }
            });
        });

        function uploadInvitationEmployee(data) {
            let formData = new FormData();
            formData.append('invitation_id', data.invitation_id);
            formData.append('file', data.file);

            fetch('{{ url('invitation/upload/file') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            })
        .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
                .then(data => {
                    // Handle success response
                    Swal.fire('Success', data.message, 'success');
                    location.reload(); // Reload the page upon successful upload
                })
                .catch(error => {
                    // Handle error
                    console.error('There was an error!', error);
                    Swal.fire('Error', 'There was an error uploading the image', 'error');
                });
        }
        function uploadInvitationCompany(data) {
            let formData = new FormData();
            formData.append('invitation_id', data.invitation_id);
            formData.append('file', data.file);

            fetch('{{ url('invitation/upload/file') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            })
        .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
                .then(data => {
                    // Handle success response
                    Swal.fire('Success', data.message, 'success');
                    location.reload(); // Reload the page upon successful upload
                })
                .catch(error => {
                    // Handle error
                    console.error('There was an error!', error);
                    Swal.fire('Error', 'There was an error uploading the image', 'error');
                });
        }
    });

</script>
