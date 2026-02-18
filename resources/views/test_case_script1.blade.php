<script type="text/javascript">
// ========== COMPLETE ENHANCED ADDRESS MANAGEMENT SYSTEM ==========
// Full script for case creation form with complete address handling
// Maintains original functionality with improved organization

$(document).ready(function() {
    // ========== INITIALIZE ALL SELECT2 ==========
    initAllSelect2();
    
    // ========== INITIALIZE DATEPICKERS ==========
    initAllDatepickers();
    
    // ========== INITIALIZE CLOCKPICKER ==========
    initClockPicker();
    
    // ========== INITIALIZE BASIC EVENT HANDLERS ==========
    initBasicEventHandlers();
    
    // ========== INITIALIZE ADDRESS CASCADE (OLD STYLE) ==========
    initAddressCascades();
    
    // ========== INITIALIZE AUTOCOMPLETE ==========
    initCompanyAutocomplete();
    initEmployeeAutocomplete();
    
    // ========== INITIALIZE SECTION NAVIGATION ==========
    initSectionNavigation();
});

// ========== INITIALIZATION FUNCTIONS ==========

function initAllSelect2() {
    // Case Type and Basic Info
    $('#case_type_id, #sector_id, #company_type_id').select2({
        theme: 'bootstrap-5',
        width: '100%',
        dropdownParent: $(document.body)
    });

    // Company Address Selects
    $('#province_id, #district_id, #commune_id, #village_id').select2({
        theme: 'bootstrap-5',
        width: '100%',
        dropdownParent: $(document.body)
    });

    // Employee Basic Info
    $('#gender, #nationality').select2({
        theme: 'bootstrap-5',
        width: '100%',
        dropdownParent: $(document.body)
    });

    // Place of Birth Address
    $('#pob_country_id, #pob_province_id, #pob_district_id, #pob_commune_id').select2({
        theme: 'bootstrap-5',
        width: '100%',
        dropdownParent: $(document.body)
    });

    // Current Address
    $('#province, #district, #commune, #village').select2({
        theme: 'bootstrap-5',
        width: '100%',
        dropdownParent: $(document.body)
    });

    // Case Objectives and Contract
    $('#case_objective_id, #disputant_contract_type, #disputant_night_work, #disputant_holiday_week, #disputant_holiday_year, #disputant_terminated_contract, #officer_id8, #officer_id')
        .select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $(document.body)
        });
}

function initAllDatepickers() {
    $('#dob, #terminated_contract_date, #disputant_sdate_work, #case_date, #case_date_entry').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });
}

function initClockPicker() {
    $('.clockpicker').clockpicker({
        autoclose: true,
        twelvehour: false
    });
}

function initBasicEventHandlers() {
    // Prevent multiple submits
    $('#frmCaseCreated').on('submit', function() {
        const btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>កំពុងរក្សារទុក...');
    });

    // Toggle Company Result
    $("#btn_search_company").click(function() {
        if ($(this).val() == 1) {
            $("#div_company_result").show();
            $(this).val(0).html('<i class="fas fa-eye-slash me-2"></i>បិទព័ត៌មានលម្អិតរបស់សហគ្រាស');
        } else {
            $("#div_company_result").hide();
            $(this).val(1).html('<i class="fas fa-eye me-2"></i>បង្ហាញព័ត៌មានលម្អិតរបស់សហគ្រាស');
        }
    });

    // Number only inputs
    $(".number_only").keypress(function(event) {
        if (!(event.charCode >= 48 && event.charCode <= 57)) {
            event.preventDefault();
            return false;
        }
    });

    $(".number_only_d").keypress(function(event) {
        if (!((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)) {
            event.preventDefault();
            return false;
        }
    });

    // Case Objective auto-fill
    $('#case_objective_id').on('change', function() {
        var selectedVal = $(this).find("option:selected").text();
        $('#case_ojective_other').val(selectedVal);
    });

    // Nationality to POB Country sync
    $('#nationality').on('change', function() {
        var selectedValue = $(this).val();
        $('#pob_country_id').val(selectedValue).trigger('change');
    });

    // Auto Case Number Generation
    const cYear = '{{ $cYear ?? date('y') }}';

    function generateAutoCaseNumber() {
        let caseNumberLabel;
        let caseTypeID = $('#case_type_id').val();
        let casePre = $('#case_number').val();

        if (caseTypeID == 1) {
            caseNumberLabel = casePre + "/" + cYear + "/វប";
        } else if (caseTypeID == 2) {
            caseNumberLabel = casePre + "/" + cYear + "/វស";
        } else if (caseTypeID == 3) {
            caseNumberLabel = casePre + "/" + cYear + "/វរ";
        }
        $('#case_num_str').val(caseNumberLabel);
    }

    $('#case_type_id, #case_number').on('change keyup', generateAutoCaseNumber);
    generateAutoCaseNumber();

    // Prevent typing in date fields
    $("#dob, #terminated_contract_date, #terminated_contract_time, #disputant_sdate_work, #case_date, #case_date_entry")
        .on('keydown', function(e) {
            if (e.keyCode != 8 && e.keyCode != 46) {
                e.preventDefault();
                return false;
            }
        });
}

// ========== ADDRESS CASCADE FUNCTIONS (ORIGINAL STYLE) ==========

function initAddressCascades() {
    // Company address cascade
    eventChangeAddress("", "province_id", "district_id", "commune_id", "village_id");
    
    // Employee current address cascade
    eventChangeAddress("", "province", "district", "commune", "village");
    
    // Place of birth address cascade
    eventChangePob("", "pob_province_id", "pob_district_id", "pob_commune_id");
}

// ========== COMPANY AUTOCOMPLETE FUNCTIONS ==========

function initCompanyAutocomplete() {
    $("#find_company_autocomplete").autocomplete({
        source: function(request, response) {
            $("#response_message").fadeIn();
            $.ajax({
                url: "{{ url('/find_company_autocomplete') }}",
                dataType: "json",
                data: { query: request.term },
                success: function(data) {
                    $("#response_message").fadeOut();
                    response(data);
                },
                error: function() {
                    $("#response_message").fadeOut();
                    showError('មិនអាចស្វែងរកក្រុមហ៊ុនបានទេ');
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            loadCompanyDetails(ui.item.value);
        }
    });
}

function loadCompanyDetails(companyName) {
    $.ajax({
        url: "{{ url('/get-details') }}",
        dataType: "json",
        data: { name: companyName },
        success: function(data) {
            fillCompanyBasicInfo(data);
            fillCompanyAddressWithDelay(data);
            updateCompanyResultDisplay(data);
            showSuccess('បានទាញយកព័ត៌មានក្រុមហ៊ុនដោយជោគជ័យ');
        },
        error: function(xhr, status, error) {
            console.error("Error fetching company details:", error);
            showError('មិនអាចទាញយកព័ត៌មានក្រុមហ៊ុនបានទេ');
        }
    });
}

function fillCompanyBasicInfo(data) {
    $("#company_id_auto").val(1);
    $("#company_id").val(data.company_id);
    $("#company_id_lacms").val(data.company_id);
    $("#company_option").val(data.company_option);
    $("#company_name_khmer").val(data.company_name_khmer);
    $("#company_name_latin").val(data.company_name_latin);
    $("#company_register_number").val(data.company_register_number);
    $("#registration_date").val(data.registration_date);
    $("#company_tin").val(data.company_tin);
    $("#nssf_number").val(data.nssf_number);
    $("#company_type_id").val(data.company_type_id).trigger("change");
    $("#first_business_act").val(data.first_business_act);
    $("#article_of_company").val(data.article_of_company);
    $("#sector_id").val(data.sector_id).trigger("change");
    $("#csic_1").val(data.csic_1);
    $("#csic_2").val(data.csic_2);
    $("#csic_3").val(data.csic_3);
    $("#csic_4").val(data.csic_4);
    $("#csic_5").val(data.csic_5);
    $("#business_activity").val(data.business_activity);
    $("#business_activity1").val(data.business_activity1);
    $("#business_activity2").val(data.business_activity2);
    $("#business_activity3").val(data.business_activity3);
    $("#business_activity4").val(data.business_activity4);
    $("#street_no").val(data.street_no || '');
    $("#building_no").val(data.building_no || '');
    $("#company_phone_number").val(data.company_phone_number);
    $("#company_phone_number2").val(data.company_phone_number2);
    $("#single_id").val(data.single_id);
    $("#operation_status").val(data.operation_status);
}

function fillCompanyAddressWithDelay(data) {
    if (data.province_id) {
        $("#province_id").val(data.province_id).trigger("change");
        
        setTimeout(function() {
            if (data.district_id) {
                $("#district_id").val(data.district_id).trigger('change');
                
                setTimeout(function() {
                    if (data.commune_id) {
                        $("#commune_id").val(data.commune_id).trigger('change');
                        
                        setTimeout(function() {
                            if (data.village_id) {
                                $("#village_id").val(data.village_id).trigger('change');
                            }
                        }, 2000);
                    }
                }, 1200);
            }
        }, 800);
    }
}

function updateCompanyResultDisplay(data) {
    var OperationStatusMap = {
        0: "ផ្អាកដំណើរការ",
        1: "កំពុងដំណើរការ",
        2: "បិទលែងដំណើរការ",
        3: "ផ្លាស់ប្តូរនាមករណ៍",
        4: "មិនទាន់ដំណើរការ"
    };
    var operationStatus = OperationStatusMap[data.operation_status] || "មិនដឹង";

    var strResult = "លេខអត្តសញ្ញាណ LACMS: " + data.company_id + "\n" +
        "លេខសម្គាល់អត្តសញ្ញាណសហគ្រាស (Single ID): " + data.single_id + "\n" +
        "នាមករណ៍សហគ្រាស ជាភាសាខ្មែរ: " + data.company_name_khmer + "\n" +
        "នាមករណ៍សហគ្រាស ជាភាសាអង់គ្លេស: " + data.company_name_latin + "\n" +
        "ស្ថានភាពសហគ្រាស គ្រឹះស្ថាន: " + operationStatus + "\n" +
        "លេខចុះបញ្ជីពាណិជ្ជកម្ម: " + data.company_register_number + "\n" +
        "កាលបរិច្ឆេទចុះបញ្ជី: " + data.registration_date + "\n" +
        "លេខអត្តសញ្ញាណកម្មសារពើពន្ធ (TIN): " + data.company_tin + "\n" +
        "លេខអត្តសញ្ញាណសហគ្រាសនៃ ប.ស.ស: " + data.nssf_number + "\n" +
        "សកម្មភាពអាជីវកម្ម: " + data.business_activity + "\n" +
        "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី១: " + data.csic_1 + "\n" +
        "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី២: " + data.csic_2 + "\n" +
        "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៣: " + data.csic_3 + "\n" +
        "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៤: " + data.csic_4 + "\n" +
        "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៥: " + data.csic_5;

    $("#company_result").text(strResult);
}

// ========== EMPLOYEE AUTOCOMPLETE FUNCTIONS ==========

function initEmployeeAutocomplete() {
    $("#find_employee_autocomplete").autocomplete({
        source: function(request, response) {
            $("#response_message_employee").fadeIn();
            $.ajax({
                url: "{{ url('/find_employee_autocomplete') }}",
                dataType: "json",
                data: { query: request.term },
                success: function(data) {
                    $("#response_message_employee").fadeOut();
                    response(data);
                },
                error: function() {
                    $("#response_message_employee").fadeOut();
                    showError('មិនអាចស្វែងរកកម្មករនិយោជិតបានទេ');
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            loadEmployeeDetails(ui.item.id);
        }
    });
}

function loadEmployeeDetails(employeeId) {
    $.ajax({
        url: "{{ url('/get-employee-details') }}",
        dataType: "json",
        data: { id: employeeId },
        success: function(data) {
            fillEmployeeBasicInfo(data);
            fillPobAddressWithDelay(data);
            fillEmployeeCurrentAddressWithDelay(data);
            showSuccess('បានទាញយកព័ត៌មានកម្មករនិយោជិតដោយជោគជ័យ');
        },
        error: function(xhr, status, error) {
            console.error("Error fetching employee details:", error);
            showError('មិនអាចទាញយកព័ត៌មានកម្មករនិយោជិតបានទេ');
        }
    });
}

function fillEmployeeBasicInfo(data) {
    $("#name").val(data.name);
    $("#gender").val(data.gender).trigger('change');
    $("#dob").val(data.dob);
    $("#nationality").val(data.nationality).trigger('change');
    $("#id_number").val(data.id_number);
    $("#phone_number").val(data.phone_number);
    $("#phone_number2").val(data.phone_number2);
    $("#occupation").val(data.occupation);
}

function fillPobAddressWithDelay(data) {
    if (data.pob_province_id) {
        $("#pob_province_id").val(data.pob_province_id).trigger('change');
        
        setTimeout(function() {
            if (data.pob_district_id) {
                $("#pob_district_id").val(data.pob_district_id).trigger('change');
                
                setTimeout(function() {
                    if (data.pob_commune_id) {
                        $("#pob_commune_id").val(data.pob_commune_id).trigger('change');
                    }
                }, 800);
            }
        }, 500);
    }
}

function fillEmployeeCurrentAddressWithDelay(data) {
    if (data.province_id) {
        $("#province").val(data.province_id).trigger('change');
        
        setTimeout(function() {
            if (data.district_id) {
                $("#district").val(data.district_id).trigger('change');
                
                setTimeout(function() {
                    if (data.commune_id) {
                        $("#commune").val(data.commune_id).trigger('change');
                        
                        setTimeout(function() {
                            if (data.village_id) {
                                $("#village").val(data.village_id).trigger('change');
                            }
                        }, 800);
                    }
                }, 500);
            }
        }, 500);
    }
    
    $("#addr_street").val(data.street || '');
    $("#addr_house_no").val(data.house_no || '');
}

// ========== UTILITY FUNCTIONS ==========

function showSuccess(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'ជោគជ័យ',
            text: message,
            timer: 2000,
            showConfirmButton: false
        });
    }
}

function showError(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'មានបញ្ហា',
            text: message,
            confirmButtonColor: '#667eea'
        });
    }
}

// ========== SECTION NAVIGATION ==========

function initSectionNavigation() {
    // Check if sections exist
    if ($('#plantiff_block').length === 0) return;
    
    const sections = {
        plantiff: $('#plantiff_block'),
        defendant: $('#defendant_block'),
        objective: $('#objective_block'),
        contract: $('#contract_block')
    };

    const steps = {
        step1: $('#step1'),
        step2: $('#step2'),
        step3: $('#step3'),
        step4: $('#step4')
    };

    // Update progress steps
    function updateProgressSteps(currentSection) {
        steps.step1.removeClass('active completed');
        steps.step2.removeClass('active completed');
        steps.step3.removeClass('active completed');
        steps.step4.removeClass('active completed');

        if (currentSection === 'plantiff') {
            steps.step1.addClass('active');
        } else if (currentSection === 'defendant') {
            steps.step1.addClass('completed');
            steps.step2.addClass('active');
        } else if (currentSection === 'objective') {
            steps.step1.addClass('completed');
            steps.step2.addClass('completed');
            steps.step3.addClass('active');
        } else if (currentSection === 'contract') {
            steps.step1.addClass('completed');
            steps.step2.addClass('completed');
            steps.step3.addClass('completed');
            steps.step4.addClass('active');
        }
    }

    // Validation function
    function validateSection(section, sectionName) {
        let valid = true;
        let errorMessages = [];

        section.find('input[required], textarea[required]').each(function() {
            const field = $(this);
            const value = field.val();

            if (!value || value.trim() === '') {
                field.addClass('is-invalid');
                valid = false;
                const label = section.find(`label[for="${field.attr('id')}"]`).text().trim();
                errorMessages.push(`- ${label || field.attr('name')} ត្រូវការបំពេញ`);
            } else {
                field.removeClass('is-invalid');
            }
        });

        section.find('select[required]').each(function() {
            const select = $(this);
            const value = select.val();

            if (!value || value === '' || value === '0') {
                select.addClass('is-invalid');
                select.next('.select2-container').find('.select2-selection').addClass('is-invalid');
                valid = false;
                const label = section.find(`label[for="${select.attr('id')}"]`).text().trim();
                errorMessages.push(`- ${label || select.attr('name')} ត្រូវការជ្រើសរើស`);
            } else {
                select.removeClass('is-invalid');
                select.next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            }
        });

        if (!valid && typeof Swal !== 'undefined') {
            let errorHtml = `
            <div class="error-list">
                <h5 style="color: #dc3545; margin-bottom: 15px;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    សូមបំពេញព័ត៌មានខាងក្រោមក្នុងដំណាក់កាលទី ${sectionName}:
                </h5>
                <ul style="list-style-type: none; padding-left: 0;">
            `;

            errorMessages.forEach(msg => {
                errorHtml += `<li><i class="fas fa-times-circle text-danger me-2"></i>${msg}</li>`;
            });

            errorHtml += `</ul></div>`;

            Swal.fire({
                icon: 'warning',
                title: 'ព័ត៌មានមិនគ្រប់គ្រាន់',
                html: errorHtml,
                confirmButtonText: 'យល់ព្រម',
                confirmButtonColor: '#667eea',
                width: 600
            });

            const firstInvalid = section.find('.is-invalid').first();
            if (firstInvalid.length) {
                firstInvalid.focus();
                if (firstInvalid.is('select')) {
                    firstInvalid.select2('open');
                }
            }
        }

        return valid;
    }

    // Navigation events
    $('#btn_next_to_defendant').click(function() {
        if (validateSection(sections.plantiff, '1')) {
            sections.plantiff.hide();
            sections.defendant.show();
            updateProgressSteps('defendant');
            $('html, body').animate({ scrollTop: 0 }, 500);
        }
    });

    $('#btn_back_to_plantiff').click(function() {
        sections.defendant.hide();
        sections.plantiff.show();
        updateProgressSteps('plantiff');
        $('html, body').animate({ scrollTop: 0 }, 500);
    });

    $('#btn_next_to_objective').click(function() {
        if (validateSection(sections.defendant, '2')) {
            sections.defendant.hide();
            sections.objective.show();
            updateProgressSteps('objective');
            $('html, body').animate({ scrollTop: 0 }, 500);
        }
    });

    $('#btn_back_to_defendant').click(function() {
        sections.objective.hide();
        sections.defendant.show();
        updateProgressSteps('defendant');
        $('html, body').animate({ scrollTop: 0 }, 500);
    });

    $('#btn_next_to_contract').click(function() {
        if (validateSection(sections.objective, '3')) {
            sections.objective.hide();
            sections.contract.show();
            updateProgressSteps('contract');
            $('html, body').animate({ scrollTop: 0 }, 500);
        }
    });

    $('#btn_back_to_objective').click(function() {
        sections.contract.hide();
        sections.objective.show();
        updateProgressSteps('objective');
        $('html, body').animate({ scrollTop: 0 }, 500);
    });

    // Form submit validation
    $('#frmCaseCreated').on('submit', function(e) {
        // Check if we need to validate sections
        if ($('#plantiff_block').length > 0) {
            e.preventDefault();

            let allValid = true;

            if (!validateSection(sections.plantiff, '1')) allValid = false;
            if (!validateSection(sections.defendant, '2')) allValid = false;
            if (!validateSection(sections.objective, '3')) allValid = false;
            if (!validateSection(sections.contract, '4')) allValid = false;

            if (!allValid && typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'ព័ត៌មានមិនគ្រប់គ្រាន់',
                    html: '<div class="error-list">សូមបំពេញព័ត៌មានដែលត្រូវការក្នុងគ្រប់ដំណាក់កាលទាំងអស់។</div>',
                    confirmButtonText: 'យល់ព្រម',
                    confirmButtonColor: '#667eea'
                });
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'កំពុងរក្សាទុក...',
                        html: 'សូមរង់ចាំបន្តិច',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
                this.submit();
            }
        }
    });

    // Real-time validation removal
    $('input[required], textarea[required], select[required]').on('input change', function() {
        const field = $(this);
        if (field.val() && field.val().trim() !== '' && field.val() !== '0') {
            field.removeClass('is-invalid');
            if (field.is('select')) {
                field.next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            }
        }
    });

    // Initialize first section
    if (steps.step1.length > 0) {
        updateProgressSteps('plantiff');
    }
}
</script>

<!-- Include helper scripts -->
@include('script.my_sweetalert2')
@include('case.script.event_address_script')

<!-- Plugins -->
<script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
<script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
<script src="{{ rurl('assets/js/time-picker/jquery-clockpicker.min.js') }}"></script>
<script src="{{ rurl('assets/js/time-picker/clockpicker.js') }}"></script>
<script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>