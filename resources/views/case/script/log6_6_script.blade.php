<script>
$(document).ready(function() {

    // --- Multi-step sections ---
    const sections = {
        plantiff: $('#plantiff_block'),
        defendant: $('#defendant_block'),
        contract: $('#contract_block')
    };

    const scrollToSection = (section) => {
        section[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    const validateInputs = (inputs) => {
        let valid = true;
        inputs.each(function() {
            if ($(this).is(':visible') && !$(this).val().trim()) {
                $(this).addClass('is-invalid');
                valid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        return valid;
    };

    // --- Checkbox show/hide log6 Employee ---
    $('#hidden_employee').change(function() {
        if (this.checked) {
            $('#show_employee').prop('checked', false);
            $('#information6_container').hide();
            $('#btn_next_to_defendant').show();
        }
    });

    $('#show_employee').change(function() {
        if (this.checked) {
            $('#hidden_employee').prop('checked', false);
            $('#information6_container').show();
            $('#btn_next_to_defendant').show();
        }
    });

    // --- Checkbox show/hide logic 6 Company  ---
    $('#hidden_company').change(function() {
        if (this.checked) {
            $('#show_company').prop('checked', false);
            $('#information6_company').hide();
        }
    });

    $('#show_company').change(function() {
        if (this.checked) {
            $('#hidden_company').prop('checked', false);
            $('#information6_company').show();
        }
    });

    // --- Checkbox show/hide logic 6 Sub Company  ---
    $('#hidden_sub_company').change(function() {
        if (this.checked) {
            $('#show_sub_company').prop('checked', false);
            $('#information6_sub_company').hide();
        }
    });

    $('#show_sub_company').change(function() {
        if (this.checked) {
            $('#hidden_sub_company').prop('checked', false);
            $('#information6_sub_company').show();
        }
    });

    // --- Navigation buttons ---
    $('#btn_next_to_defendant').off('click').on('click', function() {
        const requiredFields = sections.plantiff.find('input[required], select[required]');
        if (!validateInputs(requiredFields)) return; // stop if validation fails
        sections.plantiff.hide();
        sections.defendant.show();
        scrollToSection(sections.defendant);
    });

    $('#btn_back_to_plantiff').off('click').on('click', function() {
        sections.defendant.hide();
        sections.plantiff.show();
        scrollToSection(sections.plantiff);
    });

    $('#btn_next_to_contract').off('click').on('click', function() {
        const requiredFields = sections.defendant.find('input[required], select[required]');
        if (!validateInputs(requiredFields)) return;
        sections.defendant.hide();
        sections.contract.show();
        scrollToSection(sections.contract);
    });

    const btnBackFromContract = $('#btn_back_to_plantiff_contract');
    if (btnBackFromContract.length) {
        btnBackFromContract.off('click').on('click', function() {
            sections.contract.hide();
            sections.defendant.show();
            scrollToSection(sections.defendant);
        });
    }

});
</script>
