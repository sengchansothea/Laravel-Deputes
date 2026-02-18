<script>
$(document).ready(function (){
    function updateButtonState(){
        if($('#pro_information').is(':checked')){
            $('.btn_created').show();
        } 
        else{
            $('.btn_created').hide();
        }
    }

    $('#pro_information').change(function (){
        if(this.checked){
            $('#npro_information').prop('checked', false);
        }
        updateButtonState();
    });

    $('#npro_information').change(function (){
        if(this.checked){
            $('#pro_information').prop('checked', false);
        }
        updateButtonState();
    });

    // run when page reload
    updateButtonState();
});

</script>


