<script type="text/javascript">
    // ========== UNIFIED ADDRESS MANAGEMENT SYSTEM ==========
    $(document).ready(function() {
        console.log('Document ready - Initializing unified system');
        console.log('jQuery version:', $.fn.jquery);
        console.log('jQuery UI version:', $.ui ? $.ui.version : 'Not loaded');
        console.log('jQuery UI datepicker exists:', typeof $.fn.datepicker !== 'undefined');

        // ========== INITIALIZE ALL SELECT2 ==========
        initAllSelect2();

        // ========== INITIALIZE DATEPICKERS ==========
        initAllDatepickers();

        // ========== INITIALIZE CLOCKPICKER ==========
        initClockPicker();

        // ========== INITIALIZE BASIC EVENT HANDLERS ==========
        initBasicEventHandlers();

        // ========== INITIALIZE ADDRESS CASCADE ==========
        setTimeout(function() {
            initAddressCascades();
        }, 500);

        // ========== INITIALIZE AUTOCOMPLETE ==========
        initCompanyAutocomplete();

        // ========== INITIALIZE SECTION NAVIGATION ==========
        initSectionNavigation();

        // ========== CHECK DOB FIELD ==========
        console.log('DOB field exists:', $('#dob').length > 0);
        console.log('DOB field value:', $('#dob').val());
        console.log('DOB field classes:', $('#dob').attr('class'));
    });

    // ========== INITIALIZATION FUNCTIONS ==========

    function initAllSelect2() {
        console.log('Initializing Select2');

        if (typeof $.fn.select2 === 'undefined') {
            console.error('Select2 not loaded!');
            return;
        }

        // Company Address Selects - with error handling
        try {
            $('#province_id, #district_id, #commune_id, #village_id').select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $(document.body),
                placeholder: 'សូមជ្រើសរើស',
                allowClear: true
            });
            console.log('Company address Select2 initialized');
        } catch (e) {
            console.error('Error initializing company address Select2:', e);
        }

        // Employee Current Address (ប្រសិនបើមាន)
        setTimeout(function() {
            try {
                $('#province, #district, #commune, #village').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownParent: $(document.body),
                    placeholder: 'សូមជ្រើសរើស',
                    allowClear: true
                });
                console.log('Employee address Select2 initialized');
            } catch (e) {
                console.error('Error initializing employee address Select2:', e);
            }

            // Place of Birth Address
            try {
                $('#pob_country_id, #pob_province_id, #pob_district_id, #pob_commune_id').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownParent: $(document.body),
                    placeholder: 'សូមជ្រើសរើស',
                    allowClear: true
                });
                console.log('POB address Select2 initialized');
            } catch (e) {
                console.error('Error initializing POB address Select2:', e);
            }
        }, 1000);

        // Other Select2 elements
        try {
            $('#case_type_id, #sector_id, #company_type_id, #gender, #nationality, #case_objective_id, #disputant_contract_type, #disputant_night_work, #disputant_holiday_week, #disputant_holiday_year, #disputant_terminated_contract, #officer_id8, #officer_id')
                .select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownParent: $(document.body),
                    placeholder: 'សូមជ្រើសរើស',
                    allowClear: true
                });
            console.log('Other Select2 elements initialized');
        } catch (e) {
            console.error('Error initializing other Select2 elements:', e);
        }

        console.log('Select2 initialization complete');
    }

    function initAllDatepickers() {
        console.log('Initializing Datepickers');

        // ពិនិត្យមើលថា jQuery UI datepicker មានឬអត់
        if (typeof $.fn.datepicker !== 'undefined') {
            try {
                // កំណត់ format ឲ្យត្រូវនឹង jQuery UI datepicker
                $('#dob, #terminated_contract_date, #disputant_sdate_work, #case_date, #case_date_entry').datepicker({
                    dateFormat: 'dd-mm-yy', // jQuery UI ប្រើ yy សម្រាប់ 4 ខ្ទង់
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1900:2100'
                });
                console.log('Datepickers initialized successfully');
            } catch (e) {
                console.error('Error initializing datepicker:', e);
                // ប្រើ HTML5 date input ជំនួស
                $('#dob, #terminated_contract_date, #disputant_sdate_work, #case_date, #case_date_entry').attr('type',
                    'date');
            }
        } else {
            console.warn('jQuery UI datepicker not found, using HTML5 date input');
            // ប្រើ HTML5 date input ជំនួស
            $('#dob, #terminated_contract_date, #disputant_sdate_work, #case_date, #case_date_entry').attr('type',
                'date');
        }
    }

    function initClockPicker() {
        if ($.fn.clockpicker) {
            $('.clockpicker').clockpicker({
                autoclose: true,
                twelvehour: false
            });
        }
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
    }

    // ========== ADDRESS LOADING FUNCTIONS ==========

    function loadDistricts(provinceId, districtElementId, selectedDistrictId, callback) {
        console.log('loadDistricts:', provinceId, districtElementId, selectedDistrictId);

        if (!provinceId || provinceId == 0 || provinceId == '0' || provinceId == '') {
            $('#' + districtElementId).empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');
            if (callback) callback();
            return;
        }

        $('#' + districtElementId).empty().append('<option value="">កំពុងផ្ទុក...</option>').trigger('change');

        $.ajax({
            url: "{{ url('ajaxGetDistrict') }}/" + provinceId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var $select = $('#' + districtElementId);
                $select.empty().append('<option value="">សូមជ្រើសរើស</option>');

                if (response && response.data && response.data.length > 0) {
                    $.each(response.data, function(i, item) {
                        $select.append('<option value="' + item.id + '">' + item.name +
                            '</option>');
                    });
                }

                if (selectedDistrictId && selectedDistrictId != 0 && selectedDistrictId != '0') {
                    $select.val(selectedDistrictId);
                }

                $select.trigger('change');
                if (callback) callback();
            },
            error: function(xhr, status, error) {
                console.error('Error loading districts:', error);
                $('#' + districtElementId).empty().append('<option value="">សូមជ្រើសរើស</option>').trigger(
                    'change');
                if (callback) callback();
            }
        });
    }

    function loadCommunes(districtId, communeElementId, selectedCommuneId, callback) {
        console.log('loadCommunes:', districtId, communeElementId, selectedCommuneId);

        if (!districtId || districtId == 0 || districtId == '0') {
            $('#' + communeElementId).empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');
            if (callback) callback();
            return;
        }

        $('#' + communeElementId).empty().append('<option value="">កំពុងផ្ទុក...</option>').trigger('change');

        $.ajax({
            url: "{{ url('ajaxGetCommune') }}/" + districtId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var $select = $('#' + communeElementId);
                $select.empty().append('<option value="">សូមជ្រើសរើស</option>');

                if (response && response.data && response.data.length > 0) {
                    $.each(response.data, function(i, item) {
                        $select.append('<option value="' + item.id + '">' + item.name +
                            '</option>');
                    });
                }

                if (selectedCommuneId && selectedCommuneId != 0 && selectedCommuneId != '0') {
                    $select.val(selectedCommuneId);
                }

                $select.trigger('change');
                if (callback) callback();
            },
            error: function(xhr, status, error) {
                console.error('Error loading communes:', error);
                $('#' + communeElementId).empty().append('<option value="">សូមជ្រើសរើស</option>').trigger(
                    'change');
                if (callback) callback();
            }
        });
    }

    function loadVillages(communeId, villageElementId, selectedVillageId, callback) {
        console.log('loadVillages:', communeId, villageElementId, selectedVillageId);

        if (!communeId || communeId == 0 || communeId == '0') {
            $('#' + villageElementId).empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');
            if (callback) callback();
            return;
        }

        $('#' + villageElementId).empty().append('<option value="">កំពុងផ្ទុក...</option>').trigger('change');

        $.ajax({
            url: "{{ url('ajaxGetVillage') }}/" + communeId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var $select = $('#' + villageElementId);
                $select.empty().append('<option value="">សូមជ្រើសរើស</option>');

                if (response && response.data && response.data.length > 0) {
                    $.each(response.data, function(i, item) {
                        $select.append('<option value="' + item.id + '">' + item.name +
                            '</option>');
                    });
                }

                if (selectedVillageId && selectedVillageId != 0 && selectedVillageId != '0') {
                    $select.val(selectedVillageId);
                }

                $select.trigger('change');
                if (callback) callback();
            },
            error: function(xhr, status, error) {
                console.error('Error loading villages:', error);
                $('#' + villageElementId).empty().append('<option value="">សូមជ្រើសរើស</option>').trigger(
                    'change');
                if (callback) callback();
            }
        });
    }

    // ========== ADDRESS CASCADE FUNCTIONS ==========

    function initAddressCascades() {
        console.log('Initializing address cascades');

        // Remove all existing event handlers first
        $('#province_id, #district_id, #commune_id').off('change');
        $('#province, #district, #commune').off('change');
        $('#pob_province_id, #pob_district_id').off('change');

        // ===== COMPANY ADDRESS =====
        $('#province_id').on('change', function() {
            var provinceId = $(this).val();
            console.log('Company province changed:', provinceId);

            $('#district_id').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');
            $('#commune_id').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');
            $('#village_id').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');

            if (provinceId && provinceId != 0 && provinceId != '0') {
                loadDistricts(provinceId, 'district_id', null, function() {
                    console.log('Districts loaded for province:', provinceId);
                });
            }
        });

        $('#district_id').on('change', function() {
            var districtId = $(this).val();
            console.log('Company district changed:', districtId);

            $('#commune_id').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');
            $('#village_id').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');

            if (districtId && districtId != 0 && districtId != '0') {
                loadCommunes(districtId, 'commune_id', null, function() {
                    console.log('Communes loaded for district:', districtId);
                });
            }
        });

        $('#commune_id').on('change', function() {
            var communeId = $(this).val();
            console.log('Company commune changed:', communeId);

            $('#village_id').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');

            if (communeId && communeId != 0 && communeId != '0') {
                loadVillages(communeId, 'village_id', null, function() {
                    console.log('Villages loaded for commune:', communeId);
                });
            }
        });

        // ===== EMPLOYEE CURRENT ADDRESS (ត្រូវដាក់ពេលដែល step 2 បង្ហាញ) =====
        function initEmployeeAddressCascades() {
            console.log('Initializing employee address cascades');

            $('#province').off('change').on('change', function() {
                var provinceId = $(this).val();
                console.log('Employee province changed:', provinceId);

                $('#district').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');
                $('#commune').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');
                $('#village').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');

                if (provinceId && provinceId != 0 && provinceId != '0') {
                    loadDistricts(provinceId, 'district', null, function() {});
                }
            });

            $('#district').off('change').on('change', function() {
                var districtId = $(this).val();
                console.log('Employee district changed:', districtId);

                $('#commune').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');
                $('#village').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');

                if (districtId && districtId != 0 && districtId != '0') {
                    loadCommunes(districtId, 'commune', null, function() {});
                }
            });

            $('#commune').off('change').on('change', function() {
                var communeId = $(this).val();
                console.log('Employee commune changed:', communeId);

                $('#village').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');

                if (communeId && communeId != 0 && communeId != '0') {
                    loadVillages(communeId, 'village', null, function() {});
                }
            });

            // ===== PLACE OF BIRTH ADDRESS =====
            $('#pob_province_id').off('change').on('change', function() {
                var provinceId = $(this).val();
                console.log('POB province changed:', provinceId);

                $('#pob_district_id').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');
                $('#pob_commune_id').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');

                if (provinceId && provinceId != 0 && provinceId != '0') {
                    loadDistricts(provinceId, 'pob_district_id', null, function() {});
                }
            });

            $('#pob_district_id').off('change').on('change', function() {
                var districtId = $(this).val();
                console.log('POB district changed:', districtId);

                $('#pob_commune_id').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger('change');

                if (districtId && districtId != 0 && districtId != '0') {
                    loadCommunes(districtId, 'pob_commune_id', null, function() {});
                }
            });
        }

        // រក្សាទុក function ដើម្បីហៅពេលក្រោយ
        window.initEmployeeAddressCascades = initEmployeeAddressCascades;

        console.log('Address cascades initialized');
    }

    // ========== COMPANY AUTOCOMPLETE ==========

    function initCompanyAutocomplete() {
        if ($("#find_company_autocomplete").length > 0) {
            $("#find_company_autocomplete").autocomplete({
                source: function(request, response) {
                    $("#response_message_company").fadeIn();
                    $.ajax({
                        url: "{{ url('/find_company_autocomplete') }}",
                        dataType: "json",
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            $("#response_message_company").fadeOut();
                            response(data);
                        },
                        error: function() {
                            $("#response_message_company").fadeOut();
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    loadCompanyDetails(ui.item.value);
                }
            });
        }
    }

    function loadCompanyDetails(companyName) {
        $("#response_message_company").fadeIn();

        $.ajax({
            url: "{{ url('/get-details') }}",
            dataType: "json",
            data: {
                name: companyName
            },
            success: function(data) {
                fillCompanyBasicInfo(data);

                if (data.province_id && data.province_id != 0) {
                    $("#province_id").val(data.province_id).trigger('change');

                    setTimeout(function() {
                        loadDistricts(data.province_id, 'district_id', data.district_id,
                            function() {
                                setTimeout(function() {
                                    loadCommunes(data.district_id, 'commune_id', data
                                        .commune_id,
                                        function() {
                                            setTimeout(function() {
                                                loadVillages(data
                                                    .commune_id,
                                                    'village_id', data
                                                    .village_id,
                                                    function() {
                                                        $("#response_message_company")
                                                            .fadeOut();
                                                        updateCompanyResultDisplay
                                                            (data);
                                                        showSuccess(
                                                            'បានទាញយកព័ត៌មានក្រុមហ៊ុនដោយជោគជ័យ'
                                                        );
                                                    });
                                            }, 500);
                                        });
                                }, 500);
                            });
                    }, 500);
                } else {
                    $("#response_message_company").fadeOut();
                    updateCompanyResultDisplay(data);
                    showSuccess('បានទាញយកព័ត៌មានក្រុមហ៊ុនដោយជោគជ័យ');
                }
            },
            error: function() {
                $("#response_message_company").fadeOut();
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
        $("#street_no").val(data.street_no || '');
        $("#building_no").val(data.building_no || '');
        $("#company_phone_number").val(data.company_phone_number);
        $("#company_phone_number2").val(data.company_phone_number2);
        $("#single_id").val(data.single_id);
        $("#operation_status").val(data.operation_status);
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

        var strResult = "";
        strResult += "លេខអត្តសញ្ញាណ LACMS: " + (data.company_id || '') + "\n";
        strResult += "លេខសម្គាល់អត្តសញ្ញាណសហគ្រាស: " + (data.single_id || '') + "\n";
        strResult += "ឈ្មោះខ្មែរ: " + (data.company_name_khmer || '') + "\n";
        strResult += "ឈ្មោះអង់គ្លេស: " + (data.company_name_latin || '') + "\n";
        strResult += "ស្ថានភាព: " + operationStatus + "\n";
        strResult += "លេខចុះបញ្ជី: " + (data.company_register_number || '') + "\n";
        strResult += "កាលបរិច្ឆេទចុះបញ្ជី: " + (data.registration_date || '') + "\n";
        strResult += "TIN: " + (data.company_tin || '') + "\n";
        strResult += "NSSF: " + (data.nssf_number || '') + "\n";
        strResult += "សកម្មភាពអាជីវកម្ម: " + (data.business_activity || '') + "\n";
        strResult += "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី១: " + (data.csic_1 || '') + "\n";
        strResult += "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី២: " + (data.csic_2 || '') + "\n";
        strResult += "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៣: " + (data.csic_3 || '') + "\n";
        strResult += "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៤: " + (data.csic_4 || '') + "\n";
        strResult += "សកម្មភាពសេដ្ឋកិច្ចកម្រិតទី៥: " + (data.csic_5 || '');

        $("#company_result").val(strResult);
    }

    // ========== EVENT CHANGE FUNCTIONS FOR ADDRESS CASCADES ==========
    function eventChangePob(my_id = "", province_html_id = "pob_province_id", district_html_id = "pob_district_id",
        commune_html_id = "pob_commune_id") {
        console.log('Initializing eventChangePob with:', province_html_id, district_html_id, commune_html_id);

        $('#' + province_html_id + my_id).on('change', function() {
            $("#" + district_html_id + my_id).empty().append('<option value="">សូមជ្រើសរើស</option>');
            $("#" + commune_html_id + my_id).empty().append('<option value="">សូមជ្រើសរើស</option>');

            var province_id = $(this).val();
            if (province_id && province_id != 0 && province_id != '0') {
                loadDistricts(province_id, district_html_id + my_id, null, function() {
                    $('#' + district_html_id + my_id).trigger('change');
                });
            }
        });

        $('#' + district_html_id + my_id).on('change', function() {
            $("#" + commune_html_id + my_id).empty().append('<option value="">សូមជ្រើសរើស</option>');

            var district_id = $(this).val();
            if (district_id && district_id != 0 && district_id != '0') {
                loadCommunes(district_id, commune_html_id + my_id, null, function() {});
            }
        });
    }

    function eventChangeAddress(my_id = "", province_html_id = "province", district_html_id = "district",
        commune_html_id = "commune", village_html_id = "village") {
        console.log('Initializing eventChangeAddress with:', province_html_id, district_html_id, commune_html_id,
            village_html_id);

        $('#' + province_html_id + my_id).on('change', function() {
            $("#" + district_html_id + my_id).empty().append('<option value="">សូមជ្រើសរើស</option>');
            $("#" + commune_html_id + my_id).empty().append('<option value="">សូមជ្រើសរើស</option>');
            $("#" + village_html_id + my_id).empty().append('<option value="">សូមជ្រើសរើស</option>');

            var province_id = $(this).val();
            if (province_id && province_id != 0 && province_id != '0') {
                loadDistricts(province_id, district_html_id + my_id, null, function() {
                    $('#' + district_html_id + my_id).trigger('change');
                });
            }
        });

        $('#' + district_html_id + my_id).on('change', function() {
            $("#" + commune_html_id + my_id).empty().append('<option value="">សូមជ្រើសរើស</option>');
            $("#" + village_html_id + my_id).empty().append('<option value="">សូមជ្រើសរើស</option>');

            var district_id = $(this).val();
            if (district_id && district_id != 0 && district_id != '0') {
                loadCommunes(district_id, commune_html_id + my_id, null, function() {
                    $('#' + commune_html_id + my_id).trigger('change');
                });
            }
        });

        $('#' + commune_html_id + my_id).on('change', function() {
            $("#" + village_html_id + my_id).empty().append('<option value="">សូមជ្រើសរើស</option>');

            var commune_id = $(this).val();
            if (commune_id && commune_id != 0 && commune_id != '0') {
                loadVillages(commune_id, village_html_id + my_id, null, function() {});
            }
        });
    }

    // ========== EMPLOYEE FEATURES INITIALIZATION ==========
    function initEmployeeFeatures() {
        console.log('Initializing employee features');

        // Get company ID from step 1
        var companyId = $('#company_id').val() || 0;
        console.log('Company ID for employee search:', companyId);

        if (companyId == 0) {
            console.warn('Warning: Company ID is 0. Please select a company first.');
            showError('សូមជ្រើសរើសសហគ្រាសជាមុនសិន');
            return;
        }

        // First, destroy any existing autocomplete to prevent conflicts
        if ($("#find_employee_autocomplete").data("ui-autocomplete")) {
            $("#find_employee_autocomplete").autocomplete("destroy");
        }

        // Initialize address cascades for employee section
        eventChangePob("", "pob_province_id", "pob_district_id", "pob_commune_id");
        eventChangeAddress("", "province", "district", "commune", "village");

        // Re-initialize datepicker for DOB field
        if ($.fn.datepicker) {
            try {
                $('#dob').datepicker('destroy').datepicker({
                    dateFormat: 'dd-mm-yy',
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '1900:2100'
                });
                console.log('DOB datepicker re-initialized');
            } catch (e) {
                console.warn('Could not re-initialize datepicker');
                $('#dob').attr('type', 'date');
            }
        }

        // Now initialize autocomplete with the CORRECT company ID
        initEmployeeAutocomplete(companyId);
    }

    // ========== EMPLOYEE AUTOCOMPLETE ==========
    function initEmployeeAutocomplete(companyId) {
        console.log('Setting up employee autocomplete with company ID:', companyId);

        $("#find_employee_autocomplete").autocomplete({
            source: function(request, response) {
                $("#response_message_employee").fadeIn();
                $.ajax({
                    url: "{{ url('/find_employee_autocomplete') }}/" + companyId,
                    dataType: "json",
                    data: {
                        query: request.term
                    },
                    success: function(data) {
                        $("#response_message_employee").fadeOut();
                        response(data);
                    },
                    error: function(xhr, status, error) {
                        $("#response_message_employee").fadeOut();
                        console.error('Error searching employees:', error);
                        response([]);
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                console.log('Employee selected:', ui.item);
                $("#response_message_employee").fadeIn();

                $.ajax({
                    url: "{{ url('/autocomplete/get_employee_detail') }}/" + companyId,
                    dataType: "json",
                    data: {
                        name: ui.item.value
                    },
                    success: function(data) {
                        $("#response_message_employee").fadeOut();

                        if (data && data.length > 0) {
                            fillEmployeeForm(data[0]);
                        }
                    },
                    error: function(xhr, status, error) {
                        $("#response_message_employee").fadeOut();
                        console.error('Error fetching employee details:', error);
                        showError('មិនអាចទាញយកព័ត៌មានកម្មករនិយោជិតបានទេ');
                    }
                });
            }
        });
    }

    // ========== FILL EMPLOYEE FORM ==========
    function fillEmployeeForm(employee) {
        console.log('Filling employee form with data:', employee);

        // Basic Info
        $("#name").val(employee.name || '');

        // Gender
        var gender = 1; // Default male
        if (employee.gender == "ស្រី" || employee.gender == "Female" || employee.gender == 2) {
            gender = 2;
        }
        $("#gender").val(gender).trigger('change');

        // Date of Birth - FIXED VERSION
        if (employee.dob) {
            console.log('Setting DOB to:', employee.dob);
            $("#dob").val(employee.dob);

            // If using jQuery UI datepicker, update it
            if ($("#dob").data("ui-datepicker")) {
                try {
                    // Parse the date (assuming format dd-mm-yyyy or yyyy-mm-dd)
                    var dateStr = employee.dob;
                    var dateParts;

                    if (dateStr.includes('-')) {
                        dateParts = dateStr.split('-');
                        if (dateParts[0].length === 4) {
                            // Format: yyyy-mm-dd
                            var dateObj = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
                            $("#dob").datepicker("setDate", dateObj);
                        } else if (dateParts[2].length === 4) {
                            // Format: dd-mm-yyyy
                            var dateObj = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
                            $("#dob").datepicker("setDate", dateObj);
                        }
                    }
                    console.log('Datepicker updated');
                } catch (e) {
                    console.warn('Could not set datepicker date:', e);
                }
            }
        } else {
            console.warn('No DOB data for employee');
        }

        // Nationality
        if (employee.nationality) {
            $("#nationality").val(employee.nationality).trigger('change');
        }

        // ID Number
        $("#id_number").val(employee.id_number || '');

        // Phone Numbers
        $("#phone_number").val(employee.phone_number || '');
        $("#phone_number2").val(employee.phone_number2 || '');

        // Occupation
        $("#occupation").val(employee.occupation || '');

        // Place of Birth
        if (employee.pob_province_id && employee.pob_province_id != 0) {
            $("#pob_province_id").val(employee.pob_province_id).trigger('change');

            setTimeout(function() {
                if (employee.pob_district_id && employee.pob_district_id != 0) {
                    // Load districts
                    loadDistricts(employee.pob_province_id, 'pob_district_id', employee.pob_district_id,
                        function() {
                            if (employee.pob_commune_id && employee.pob_commune_id != 0) {
                                setTimeout(function() {
                                    loadCommunes(employee.pob_district_id, 'pob_commune_id',
                                        employee.pob_commune_id,
                                        function() {});
                                }, 500);
                            }
                        });
                }
            }, 500);
        }

        // Current Address
        if (employee.province && employee.province != 0) {
            $("#province").val(employee.province).trigger('change');

            setTimeout(function() {
                if (employee.district && employee.district != 0) {
                    loadDistricts(employee.province, 'district', employee.district, function() {
                        if (employee.commune && employee.commune != 0) {
                            setTimeout(function() {
                                loadCommunes(employee.district, 'commune', employee.commune,
                                    function() {
                                        if (employee.village && employee.village != 0) {
                                            setTimeout(function() {
                                                loadVillages(employee.commune,
                                                    'village', employee.village,
                                                    function() {});
                                            }, 500);
                                        }
                                    });
                            }, 500);
                        }
                    });
                }
            }, 500);
        }

        // Street and House Number
        $("#addr_street").val(employee.street || '');
        $("#addr_house_no").val(employee.house_no || '');

        showSuccess('បានទាញយកព័ត៌មានកម្មករនិយោជិតដោយជោគជ័យ');
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
        } else {
            alert(message);
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
        } else {
            alert('Error: ' + message);
        }
    }

    // ========== SECTION NAVIGATION ==========
    function initSectionNavigation() {
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

        function getFieldLabel(field) {
            var id = field.attr('id');
            var label = $('label[for="' + id + '"]').text().trim();
            return label || field.attr('name') || id;
        }

        function validateSection(section, sectionName) {
            let valid = true;
            let errorMessages = [];

            section.find('input[required], textarea[required]').each(function() {
                const field = $(this);
                if (!field.val() || field.val().trim() === '') {
                    field.addClass('is-invalid');
                    valid = false;
                    var label = getFieldLabel(field);
                    errorMessages.push(`- ${label} ត្រូវការបំពេញ`);
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
                    var label = getFieldLabel(select);
                    errorMessages.push(`- ${label} ត្រូវការជ្រើសរើស`);
                } else {
                    select.removeClass('is-invalid');
                    select.next('.select2-container').find('.select2-selection').removeClass('is-invalid');
                }
            });

            if (!valid && typeof Swal !== 'undefined') {
                var sectionNames = ['១', '២', '៣', '៤'];
                var errorHtml = `
                <div class="error-list">
                    <h5 style="color: #dc3545; margin-bottom: 15px;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        សូមបំពេញព័ត៌មានខាងក្រោមក្នុងដំណាក់កាលទី ${sectionNames[sectionName-1]}:
                    </h5>
                    <ul style="list-style-type: none; padding-left: 0;">
            `;

                errorMessages.forEach(msg => {
                    errorHtml +=
                        `<li style="margin-bottom: 8px;"><i class="fas fa-times-circle text-danger me-2"></i>${msg}</li>`;
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
            }

            return valid;
        }

        // Remove all existing click handlers first
        $('#btn_next_to_defendant, #btn_back_to_plantiff, #btn_next_to_objective, #btn_back_to_defendant, #btn_next_to_contract, #btn_back_to_objective')
            .off('click');

        // Navigation events
        $('#btn_next_to_defendant').click(function() {
            if (validateSection(sections.plantiff, 1)) {
                sections.plantiff.hide();
                sections.defendant.show();
                updateProgressSteps('defendant');
                $('html, body').animate({
                    scrollTop: 0
                }, 500);

                // Initialize employee features when step 2 is shown
                setTimeout(function() {
                    if (typeof initEmployeeFeatures === 'function') {
                        initEmployeeFeatures();
                    } else {
                        console.error('initEmployeeFeatures function not found');
                    }
                }, 500);
            }
        });

        $('#btn_back_to_plantiff').click(function() {
            sections.defendant.hide();
            sections.plantiff.show();
            updateProgressSteps('plantiff');
            $('html, body').animate({
                scrollTop: 0
            }, 500);
        });

        $('#btn_next_to_objective').click(function() {
            if (validateSection(sections.defendant, 2)) {
                sections.defendant.hide();
                sections.objective.show();
                updateProgressSteps('objective');
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
            }
        });

        $('#btn_back_to_defendant').click(function() {
            sections.objective.hide();
            sections.defendant.show();
            updateProgressSteps('defendant');
            $('html, body').animate({
                scrollTop: 0
            }, 500);
        });

        $('#btn_next_to_contract').click(function() {
            if (validateSection(sections.objective, 3)) {
                sections.objective.hide();
                sections.contract.show();
                updateProgressSteps('contract');
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
            }
        });

        $('#btn_back_to_objective').click(function() {
            sections.contract.hide();
            sections.objective.show();
            updateProgressSteps('objective');
            $('html, body').animate({
                scrollTop: 0
            }, 500);
        });

        updateProgressSteps('plantiff');
    }

    // ========== ADDITIONAL FIX FOR COMPANY ADDRESS ==========
    // This ensures that when province is selected, districts load properly
    $(document).on('change', '#province_id', function() {
        var provinceId = $(this).val();
        console.log('Company province changed (direct):', provinceId);

        if (provinceId && provinceId != 0 && provinceId != '0') {
            loadDistricts(provinceId, 'district_id', null, function() {
                console.log('Districts loaded successfully');
            });
        }
    });

    $(document).on('change', '#district_id', function() {
        var districtId = $(this).val();
        console.log('Company district changed (direct):', districtId);

        if (districtId && districtId != 0 && districtId != '0') {
            loadCommunes(districtId, 'commune_id', null, function() {
                console.log('Communes loaded successfully');
            });
        }
    });

    $(document).on('change', '#commune_id', function() {
        var communeId = $(this).val();
        console.log('Company commune changed (direct):', communeId);

        if (communeId && communeId != 0 && communeId != '0') {
            loadVillages(communeId, 'village_id', null, function() {
                console.log('Villages loaded successfully');
            });
        }
    });

    // ========== FORCE LOAD PROVINCE DATA ==========
    // This function will force load the province data
    function forceLoadProvinceData() {
        console.log('Force loading province data...');

        // Check if province select exists
        if ($('#province_id').length > 0) {
            console.log('Province select found');

            // Get the current selected value
            var currentValue = $('#province_id').val();
            console.log('Current province value:', currentValue);

            // If no value selected, try to select the first option if available
            if (!currentValue || currentValue === '' || currentValue === '0') {
                var options = $('#province_id option');
                console.log('Number of province options:', options.length);

                if (options.length > 1) {
                    // Select the first non-empty option
                    var firstOption = options.eq(1).val();
                    if (firstOption) {
                        console.log('Selecting first province:', firstOption);
                        $('#province_id').val(firstOption).trigger('change');
                    }
                }
            }
        } else {
            console.error('Province select not found!');
        }
    }

    // ========== FIX FOR PROVINCE SELECT ==========
    $(document).ready(function() {
        console.log('Running province fix...');

        // Force load province data after a short delay
        setTimeout(function() {
            forceLoadProvinceData();
        }, 1000);

        // Also try after Select2 is fully initialized
        setTimeout(function() {
            forceLoadProvinceData();
        }, 2000);

        // Monitor changes to province select
        $('#province_id').on('change', function() {
            var provinceId = $(this).val();
            console.log('Province changed to:', provinceId);

            if (provinceId && provinceId != 0 && provinceId != '0') {
                // Clear district and commune
                $('#district_id').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger(
                    'change');
                $('#commune_id').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger(
                    'change');
                $('#village_id').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger(
                    'change');

                // Load districts
                loadDistricts(provinceId, 'district_id', null, function() {
                    console.log('Districts loaded for province:', provinceId);
                });
            }
        });

        // Monitor district changes
        $('#district_id').on('change', function() {
            var districtId = $(this).val();
            console.log('District changed to:', districtId);

            if (districtId && districtId != 0 && districtId != '0') {
                // Clear commune and village
                $('#commune_id').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger(
                    'change');
                $('#village_id').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger(
                    'change');

                // Load communes
                loadCommunes(districtId, 'commune_id', null, function() {
                    console.log('Communes loaded for district:', districtId);
                });
            }
        });

        // Monitor commune changes
        $('#commune_id').on('change', function() {
            var communeId = $(this).val();
            console.log('Commune changed to:', communeId);

            if (communeId && communeId != 0 && communeId != '0') {
                // Clear village
                $('#village_id').empty().append('<option value="">សូមជ្រើសរើស</option>').trigger(
                    'change');

                // Load villages
                loadVillages(communeId, 'village_id', null, function() {
                    console.log('Villages loaded for commune:', communeId);
                });
            }
        });
    });

    // ========== FIX FOR SELECT2 INITIALIZATION ==========
    // Make sure Select2 is properly initialized for province
    function initProvinceSelect2() {
        console.log('Initializing province Select2...');

        if ($('#province_id').length > 0) {
            // Destroy existing Select2 if any
            if ($('#province_id').data('select2')) {
                $('#province_id').select2('destroy');
            }

            // Re-initialize Select2
            $('#province_id').select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $(document.body),
                placeholder: 'សូមជ្រើសរើស',
                allowClear: true
            });

            console.log('Province Select2 re-initialized');
        }
    }

    // Call this after page load
    $(document).ready(function() {
        setTimeout(function() {
            initProvinceSelect2();
        }, 1500);
    });
</script>

<!-- Include helper scripts -->
@include('script.my_sweetalert2')

<!-- jQuery UI - ប្រើ URL ផ្ទាល់ មិនត្រូវប្រើ rurl() -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<!-- Plugins - រក្សា rurl() សម្រាប់ local files -->
<script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
<script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
<script src="{{ rurl('assets/js/time-picker/jquery-clockpicker.min.js') }}"></script>
<script src="{{ rurl('assets/js/time-picker/clockpicker.js') }}"></script>
<script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>
