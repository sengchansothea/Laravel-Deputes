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
                    title: 'តើអ្នកពិតជាចង់ លុបមែនឫ?',
                    //text: 'You will not be able to recover this data!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'ពិតមែនហើយ',
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
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href=my_url;
            }
        });
    }
</script>
