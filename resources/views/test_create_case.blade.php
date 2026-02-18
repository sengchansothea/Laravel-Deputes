@php
    $casePre = $adata['caseNumber'];
    $cYear = myDate('y');
    $arrCaseType = $adata['arrCaseType'];
    $arrSector = $adata['arrSector'];
    $arrCompanyType = $adata['arrCompanyType'];
    $arrProvince = $adata['arrProvince'];
    $arrNationality = $adata['arrNationality'];
    $arrObjectiveCase = $adata['arrObjectiveCase'];
    $arrContractType = $adata['arrContractType'];
    $arrNightWork = $adata['arrNightWork'];
    $arrHolidayWeek = $adata['arrHolidayWeek'];
    $arrHolidayYear = $adata['arrHolidayYear'];
    $arrOfficersInHand = $adata['arrOfficersInHand'];
@endphp

<x-admin.layout-main :adata="$adata">
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/date-picker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/timepicker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <style>
            /* Modern Form Styling */
            .card {
                border-radius: 20px;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
                border: none;
                overflow: hidden;
            }

            .card-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 25px 30px;
                border-bottom: none;
            }

            .card-header h3 {
                color: white;
                font-size: 28px;
                font-weight: 600;
                margin: 0;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            }

            /* Progress Steps - Simplified to 4 steps */
            .progress-steps {
                display: flex;
                justify-content: space-between;
                margin: 40px 30px 30px;
                position: relative;
                padding: 0 10px;
            }

            .progress-steps:before {
                content: '';
                position: absolute;
                top: 25px;
                left: 60px;
                right: 60px;
                height: 4px;
                background: linear-gradient(90deg, #e0e0e0 0%, #e0e0e0 100%);
                z-index: 1;
                border-radius: 2px;
            }

            .step-item {
                position: relative;
                z-index: 2;
                text-align: center;
                flex: 1;
            }

            .step {
                width: 55px;
                height: 55px;
                background: white;
                border: 4px solid #e0e0e0;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                font-size: 22px;
                color: #999;
                margin: 0 auto 10px;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }

            .step.active {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border-color: white;
                transform: scale(1.15);
                box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            }

            .step.completed {
                background: #28a745;
                color: white;
                border-color: white;
            }

            .step.completed:after {
                content: '\f00c';
                font-family: 'Font Awesome 6 Free';
                font-weight: 900;
                font-size: 20px;
            }

            .step.completed span {
                display: none;
            }

            .step-label {
                font-size: 16px;
                font-weight: 600;
                color: #555;
                margin-top: 8px;
                display: block;
                text-shadow: 0 1px 2px rgba(0,0,0,0.05);
            }

            .step.active + .step-label {
                color: #667eea;
                font-weight: 700;
            }

            /* Section Headers */
            .section-header {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                padding: 20px 25px;
                border-radius: 15px;
                margin: 25px 0 20px;
                border-left: 6px solid #6f42c1;
                box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            }

            .section-header h4 {
                margin: 0;
                font-size: 22px;
                font-weight: 600;
                color: #2c3e50;
            }

            .section-header .badge {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 8px 15px;
                border-radius: 25px;
                font-size: 14px;
                margin-left: 15px;
            }

            .subsection-title {
                color: #d63384;
                font-size: 18px;
                font-weight: 600;
                margin: 20px 0 15px;
                padding-left: 15px;
                border-left: 4px solid #d63384;
            }

            /* Form Controls */
            .form-group {
                margin-bottom: 20px;
            }

            .form-group label {
                font-weight: 600;
                color: #34495e;
                margin-bottom: 8px;
                font-size: 15px;
            }

            .form-group label.required:after {
                content: " *";
                color: #dc3545;
                font-weight: bold;
            }

            .form-control, .form-select {
                border: 2px solid #e9ecef;
                border-radius: 12px;
                padding: 12px 18px;
                font-size: 15px;
                transition: all 0.3s;
                background: #f8f9fa;
            }

            .form-control:focus, .form-select:focus {
                border-color: #667eea;
                background: white;
                box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
                outline: none;
            }

            .form-control.is-invalid, .form-select.is-invalid {
                border-color: #dc3545;
                background: #fff8f8;
            }

            /* Select2 Customization */
            .select2-container--bootstrap-5 .select2-selection {
                border: 2px solid #e9ecef !important;
                border-radius: 12px !important;
                min-height: 48px !important;
                padding: 5px 10px;
                background: #f8f9fa !important;
            }

            .select2-container--bootstrap-5.select2-container--focus .select2-selection {
                border-color: #667eea !important;
                box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1) !important;
                background: white !important;
            }

            .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
                line-height: 34px !important;
                font-size: 15px;
            }

            .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
                height: 46px !important;
                right: 12px !important;
            }

            .select2-container--bootstrap-5 .select2-selection--single.is-invalid {
                border-color: #dc3545 !important;
            }

            /* Buttons */
            .btn {
                padding: 14px 35px;
                font-size: 16px;
                font-weight: 600;
                border-radius: 12px;
                border: none;
                transition: all 0.3s;
                position: relative;
                overflow: hidden;
                letter-spacing: 0.5px;
            }

            .btn:before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 0;
                height: 0;
                border-radius: 50%;
                background: rgba(255,255,255,0.3);
                transform: translate(-50%, -50%);
                transition: width 0.6s, height 0.6s;
            }

            .btn:hover:before {
                width: 300px;
                height: 300px;
            }

            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            }

            .btn-primary:hover {
                transform: translateY(-3px);
                box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
            }

            .btn-secondary {
                background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
                color: white;
                box-shadow: 0 8px 20px rgba(149, 165, 166, 0.3);
            }

            .btn-secondary:hover {
                transform: translateY(-3px);
                box-shadow: 0 15px 30px rgba(149, 165, 166, 0.4);
            }

            .btn-success {
                background: linear-gradient(135deg, #34b1aa 0%, #2c7a6e 100%);
                color: white;
                box-shadow: 0 8px 20px rgba(52, 177, 170, 0.3);
            }

            .btn-success:hover {
                transform: translateY(-3px);
                box-shadow: 0 15px 30px rgba(52, 177, 170, 0.4);
            }

            .btn-danger {
                background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
                color: white;
                box-shadow: 0 8px 20px rgba(231, 76, 60, 0.3);
            }

            .btn-danger:hover {
                transform: translateY(-3px);
                box-shadow: 0 15px 30px rgba(231, 76, 60, 0.4);
            }

            .button-group {
                display: flex;
                justify-content: space-between;
                margin-top: 40px;
                padding: 0 15px;
            }

            /* Company Info Box */
            .company-info-box {
                background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
                border-radius: 15px;
                padding: 20px;
                margin: 20px 0;
                border: 2px dashed #6f42c1;
            }

            #company_result {
                background: white;
                border: 2px solid #dee2e6;
                border-radius: 12px;
                font-size: 14px;
                line-height: 1.8;
                color: #2c3e50;
            }

            /* Loading Messages */
            #response_message_company,
            #response_message_employee {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 15px 25px;
                border-radius: 12px;
                font-size: 18px;
                text-align: center;
                animation: pulse 2s infinite;
                box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
                margin-bottom: 20px;
            }

            @keyframes pulse {
                0%, 100% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.8; transform: scale(1.02); }
            }

            /* Error Messages */
            .error-list {
                background: #fff5f5;
                border-left: 6px solid #dc3545;
                border-radius: 12px;
                padding: 20px;
                margin: 15px 0;
                box-shadow: 0 5px 15px rgba(220, 53, 69, 0.1);
            }

            .error-list ul {
                margin: 10px 0 0;
                padding-left: 25px;
            }

            .error-list li {
                color: #dc3545;
                font-size: 15px;
                margin: 5px 0;
                font-weight: 500;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .progress-steps {
                    margin: 20px 10px;
                }
                
                .step {
                    width: 45px;
                    height: 45px;
                    font-size: 18px;
                }
                
                .step-label {
                    font-size: 12px;
                }
                
                .btn {
                    padding: 12px 25px;
                    font-size: 14px;
                }
            }
        </style>
    </x-slot>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">
                            <i class="fas fa-file-alt me-2"></i>
                            ពាក្យបណ្ដឹង ករណីវិវាទការងារ
                        </h3>
                    </div>

                    <!-- Progress Steps - 4 Steps Only -->
                    <div class="progress-steps">
                        <div class="step-item">
                            <div class="step active" id="step1"><span>1</span></div>
                            <span class="step-label">សហគ្រាស គ្រឹះស្ថាន</span>
                        </div>
                        <div class="step-item">
                            <div class="step" id="step2"><span>2</span></div>
                            <span class="step-label">ព័ត៌មានអ្នកប្ដឹង</span>
                        </div>
                        <div class="step-item">
                            <div class="step" id="step3"><span>3</span></div>
                            <span class="step-label">កម្មវត្ថុ និងអង្គហេតុ</span>
                        </div>
                        <div class="step-item">
                            <div class="step" id="step4"><span>4</span></div>
                            <span class="step-label">កិច្ចសន្យា និងបញ្ចប់</span>
                        </div>
                    </div>

                    <form name="formCreateCase" id="frmCaseCreated" action="{{ url('cases') }}" method="POST"
                        enctype="multipart/form-data" autocomplete="off">
                        @method('POST')
                        @csrf
                        
                        <!-- Hidden Fields -->
                        <input type="hidden" name="first_business_act" value="" id="first_business_act">
                        <input type="hidden" name="article_of_company" value="0" id="article_of_company">
                        <input type="hidden" name="csic_1" value="" id="csic_1">
                        <input type="hidden" name="csic_2" value="" id="csic_2">
                        <input type="hidden" name="csic_3" value="" id="csic_3">
                        <input type="hidden" name="csic_4" value="" id="csic_4">
                        <input type="hidden" name="csic_5" value="" id="csic_5">
                        <input type="hidden" name="business_activity" value="" id="business_activity">
                        <input type="hidden" name="business_activity1" value="" id="business_activity1">
                        <input type="hidden" name="business_activity2" value="" id="business_activity2">
                        <input type="hidden" name="business_activity3" value="" id="business_activity3">
                        <input type="hidden" name="business_activity4" value="" id="business_activity4">
                        <input type="hidden" name="company_register_number" value="" id="company_register_number">
                        <input type="hidden" name="registration_date" value="" id="registration_date">
                        <input type="hidden" name="company_tin" value="" id="company_tin">
                        <input type="hidden" name="nssf_number" value="" id="nssf_number">
                        <input type="hidden" name="single_id" value="" id="single_id">
                        <input type="hidden" name="operation_status" value="" id="operation_status">
                        <input type="hidden" name="case_num_str" id="case_num_str" value="">

                        <div class="card-body px-4 py-3">
                            <!-- Loading Messages -->
                            <div id="response_message_company" style="display: none;">
                                <i class="fas fa-spinner fa-spin me-2"></i>
                                កំពុងស្វែងរកទិន្នន័យ...
                            </div>
                            <div id="response_message_employee" style="display: none;">
                                <i class="fas fa-spinner fa-spin me-2"></i>
                                កំពុងស្វែងរកទិន្នន័យ...
                            </div>

                            <!-- Case Type and Number -->
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="case_type_id" class="required">ប្រភេទពាក្យបណ្ដឹង</label>
                                        {!! showSelect('case_type_id', $arrCaseType, old('case_type_id', 1), ' select2', '', '', 'required') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="case_number" class="required">លេខសំណុំរឿង</label>
                                        <input type="text" name="case_number" id="case_number"
                                            value="{{ old('case_number', $casePre) }}"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <!-- STEP 1: Plaintiff Block -->
                            <div id="plantiff_block">
                                <div class="section-header">
                                    <h4>
                                        <i class="fas fa-building me-2"></i>
                                        ដំណាក់កាលទី 1: ព័ត៌មានសហគ្រាស គ្រឹះស្ថាន
                                        <span class="badge">តម្រូវឲ្យបំពេញ</span>
                                    </h4>
                                </div>

                                <!-- Company Search -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="text-primary">
                                                <i class="fas fa-search me-2"></i>
                                                ស្វែងរកឈ្មោះសហគ្រាស គ្រឹះស្ថាន
                                            </label>
                                            <input type="text" name="find_company" minlength="2"
                                                value="{{ old('find_company') }}" class="form-control"
                                                id="find_company_autocomplete" placeholder="វាយបញ្ចូលឈ្មោះក្រុមហ៊ុន...">
                                        </div>
                                    </div>
                                </div>

                                <!-- Toggle Company Details Button -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <button type="button" id="btn_search_company" value="0"
                                            class="btn btn-danger">
                                            <i class="fas fa-eye-slash me-2"></i>
                                            បិទព័ត៌មានលម្អិតរបស់សហគ្រាស
                                        </button>
                                    </div>
                                </div>

                                <!-- Company Result -->
                                <div class="row mb-4" id="div_company_result">
                                    <div class="col-12">
                                        <div class="company-info-box">
                                            <label class="fw-bold mb-2">
                                                <i class="fas fa-info-circle me-2 text-purple"></i>
                                                ព័ត៌មានលម្អិតរបស់សហគ្រាស
                                            </label>
                                            <textarea rows="8" name="company_result" id="company_result" class="form-control" readonly></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Company Basic Info -->
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_name_khmer" class="required">ឈ្មោះជាភាសាខ្មែរ</label>
                                            <input type="text" name="company_name_khmer"
                                                value="{{ old('company_name_khmer') }}" class="form-control"
                                                id="company_name_khmer" placeholder="វាយបញ្ចូលឈ្មោះក្រុមហ៊ុនជាភាសាខ្មែរ" required>
                                            <input type="hidden" name="company_id_auto" id="company_id_auto" value="0">
                                            <input type="hidden" name="company_id" id="company_id" value="0">
                                            <input type="hidden" name="company_option" id="company_option" value="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_name_latin">ឈ្មោះជាភាសាឡាតាំង</label>
                                            <input type="text" name="company_name_latin"
                                                value="{{ old('company_name_latin') }}" class="form-control"
                                                id="company_name_latin" placeholder="Company Name in Latin" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sector_id" class="required">វិស័យ</label>
                                            {!! showSelect('sector_id', $arrSector, old('sector_id'), ' select2', '', '', 'required') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_type_id" class="required">ប្រភេទសហគ្រាស</label>
                                            {!! showSelect('company_type_id', $arrCompanyType, old('company_type_id'), ' select2', '', '', 'required') !!}
                                        </div>
                                    </div>
                                </div>

                                <!-- Address Section -->
                                <div class="subsection-title">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    អាសយដ្ឋាន
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="province_id" class="required">រាជធានី-ខេត្ត</label>
                                            {!! showSelect('province_id', $arrProvince, old('province_id'), ' select2', '', '', 'required') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="district_id" class="required">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                            {!! showSelect('district_id', [], old('district_id'), ' select2', '', '', 'required') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="commune_id" class="required">ឃុំ-សង្កាត់</label>
                                            {!! showSelect('commune_id', [], old('commune_id'), ' select2', '', '', 'required') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="village_id">ភូមិ</label>
                                            {!! showSelect('village_id', [], old('village_id'), ' select2') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="building_no">អគារលេខ</label>
                                            <input type="text" name="building_no" value="{{ old('building_no') }}" 
                                                class="form-control" id="building_no" placeholder="អគារលេខ">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="street_no">ផ្លូវ</label>
                                            <input type="text" name="street_no" id="street_no"
                                                value="{{ old('street_no') }}" class="form-control" placeholder="ផ្លូវ">
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Section -->
                                <div class="subsection-title">                                    
                                    <i class="fas fa-phone"></i>
                                    ទំនាក់ទំនង
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_phone_number" class="required">លេខទូរស័ព្ទ (ខ្សែទី១)</label>
                                            <input type="text" name="company_phone_number" id="company_phone_number"
                                                value="{{ old('company_phone_number') }}" class="form-control number_only"
                                                minlength="9" maxlength="10" placeholder="012345678" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_phone_number2">លេខទូរស័ព្ទ (ខ្សែទី២)</label>
                                            <input type="text" name="company_phone_number2" id="company_phone_number2"
                                                value="{{ old('company_phone_number2') }}" class="form-control number_only"
                                                minlength="9" maxlength="10" placeholder="012345678">
                                        </div>
                                    </div>
                                </div>

                                <!-- Next Button -->
                                <div class="button-group">
                                    <div></div>
                                    <button type="button" id="btn_next_to_defendant" class="btn btn-primary">
                                        បន្តទៅដំណាក់កាលទី 2
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- STEP 2: Defendant Block -->
                            <div id="defendant_block" style="display:none;">
                                <div class="section-header">
                                    <h4>
                                        <i class="fas fa-users me-2"></i>
                                        ដំណាក់កាលទី 2: ព័ត៌មានអ្នកប្ដឹង (កម្មករនិយោជិត)
                                        <span class="badge">តម្រូវឲ្យបំពេញ</span>
                                    </h4>
                                </div>

                                <!-- Employee Search -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="text-primary">
                                                <i class="fas fa-search me-2"></i>
                                                ស្វែងរកឈ្មោះកម្មករនិយោជិត
                                            </label>
                                            <input type="text" name="find_employee_autocomplete"
                                                value="{{ old('find_employee_autocomplete') }}"
                                                class="form-control" id="find_employee_autocomplete"
                                                placeholder="វាយបញ្ចូលឈ្មោះកម្មករនិយោជិត...">
                                        </div>
                                    </div>
                                </div>

                                <!-- Personal Info -->
                                <div class="subsection-title">
                                    <i class="fas fa-user me-2"></i>
                                    ព័ត៌មានទូទៅ
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name" class="required">ឈ្មោះអ្នកប្ដឹង</label>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                class="form-control" id="name" placeholder="គោត្តនាម នាម" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender" class="required">ភេទ</label>
                                            {!! showSelect('gender', ['1' => 'ប្រុស', '2' => 'ស្រី'], old('gender'), ' select2', '', '', 'required') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nationality" class="required">សញ្ជាតិ</label>
                                            {!! showSelect('nationality', $arrNationality, old('nationality'), ' select2', '', '', 'required') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="dob" class="required">ថ្ងៃខែឆ្នាំកំណើត</label>
                                            <input type="text" name="dob" id="dob"
                                                value="{{ old('dob') }}" class="form-control datepicker"
                                                placeholder="DD-MM-YYYY" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="id_number">អត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
                                            <input type="text" name="id_number" value="{{ old('id_number') }}"
                                                class="form-control" id="id_number" placeholder="លេខអត្តសញ្ញាណប័ណ្ណ">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="occupation" class="required">មុខងារ</label>
                                            <input type="text" name="occupation" value="{{ old('occupation') }}"
                                                class="form-control" id="occupation" placeholder="មុខងារបច្ចុប្បន្ន" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="phone_number" class="required">លេខទូរស័ព្ទ (ខ្សែទី១)</label>
                                            <input type="tel" name="phone_number" value="{{ old('phone_number') }}"
                                                class="form-control number_only" id="phone_number" 
                                                minlength="9" maxlength="10" placeholder="012345678" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="phone_number2">លេខទូរស័ព្ទ (ខ្សែទី២)</label>
                                            <input type="tel" name="phone_number2" value="{{ old('phone_number2') }}"
                                                class="form-control number_only" id="phone_number2"
                                                minlength="9" maxlength="10" placeholder="012345678">
                                        </div>
                                    </div>
                                </div>

                                <!-- Place of Birth -->
                                <div class="subsection-title">
                                    <i class="fas fa-baby me-2"></i>
                                    ទីកន្លែងកំណើត
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="pob_country_id">ប្រទេស</label>
                                            {!! showSelect('pob_country_id', $arrNationality, old('pob_country_id'), ' select2') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="pob_province_id" class="required">រាជធានី-ខេត្ត</label>
                                            {!! showSelect('pob_province_id', $arrProvince, old('pob_province_id'), ' select2', '', '', 'required') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="pob_district_id">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                            {!! showSelect('pob_district_id', [], old('pob_district_id'), ' select2') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="pob_commune_id">ឃុំ-សង្កាត់</label>
                                            {!! showSelect('pob_commune_id', [], old('pob_commune_id'), ' select2') !!}
                                        </div>
                                    </div>
                                </div>

                                <!-- Current Address -->
                                <div class="subsection-title">
                                    <i class="fas fa-home me-2"></i>
                                    អាសយដ្ឋានបច្ចុប្បន្ន
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="province" class="required">រាជធានី-ខេត្ត</label>
                                            {!! showSelect('province', $arrProvince, old('province'), ' select2', '', '', 'required') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="district" class="required">ក្រុង-ស្រុក-ខណ្ឌ</label>
                                            {!! showSelect('district', [], old('district'), ' select2', '', '', 'required') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="commune" class="required">ឃុំ-សង្កាត់</label>
                                            {!! showSelect('commune', [], old('commune'), ' select2', '', '', 'required') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="village">ភូមិ</label>
                                            {!! showSelect('village', [], old('village'), ' select2') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="addr_house_no">ផ្ទះលេខ</label>
                                            <input type="text" name="addr_house_no" value="{{ old('addr_house_no') }}"
                                                class="form-control" id="addr_house_no" placeholder="ផ្ទះលេខ">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="addr_street">ផ្លូវ</label>
                                            <input type="text" name="addr_street" id="addr_street"
                                                value="{{ old('addr_street') }}" class="form-control" placeholder="ផ្លូវ">
                                        </div>
                                    </div>
                                </div>

                                <!-- Navigation Buttons -->
                                <div class="button-group">
                                    <button type="button" id="btn_back_to_plantiff" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        ត្រឡប់ក្រោយ
                                    </button>
                                    <button type="button" id="btn_next_to_objective" class="btn btn-primary">
                                        បន្តទៅដំណាក់កាលទី 3
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- STEP 3: Objective Block -->
                            <div id="objective_block" style="display:none;">
                                <div class="section-header">
                                    <h4>
                                        <i class="fas fa-gavel me-2"></i>
                                        ដំណាក់កាលទី 3: កម្មវត្ថុ និងអង្គហេតុនៃវិវាទ
                                        <span class="badge">តម្រូវឲ្យបំពេញ</span>
                                    </h4>
                                </div>

                                <!-- Case Objective -->
                                <div class="subsection-title">
                                    <i class="fas fa-bullseye me-2"></i>
                                    កម្មវត្ថុបណ្ដឹង
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="case_objective_id" class="required">កម្មវត្ថុបណ្ដឹង</label>
                                            {!! showSelect('case_objective_id', $arrObjectiveCase, old('case_objective_id'), ' select2', '', '', 'required') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="case_ojective_other">កម្មវត្ថុបណ្ដឹងផ្សេងៗ</label>
                                            <input type="text" name="case_ojective_other" id="case_ojective_other"
                                                value="{{ old('case_ojective_other') }}" class="form-control"
                                                placeholder="បញ្ជាក់ប្រសិនបើផ្សេងពីខាងលើ">
                                        </div>
                                    </div>
                                </div>

                                <!-- Contract Termination -->
                                <div class="subsection-title">
                                    <i class="fas fa-file-contract me-2"></i>
                                    ការផ្ដាច់កិច្ចសន្យា
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="terminated_contract_date">ថ្ងៃខែឆ្នាំផ្ដាច់កិច្ចសន្យា</label>
                                            <input type="text" name="terminated_contract_date"
                                                id="terminated_contract_date" value="{{ old('terminated_contract_date') }}"
                                                class="form-control datepicker" placeholder="DD-MM-YYYY">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="terminated_contract_time">ម៉ោងផ្ដាច់កិច្ចសន្យា</label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input name="terminated_contract_time" id="terminated_contract_time"
                                                    value="{{ old('terminated_contract_time') }}"
                                                    class="form-control" type="text" placeholder="ម៉ោង:នាទី">
                                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Facts of Dispute -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="fw-bold mb-2">
                                                <i class="fas fa-file-lines me-2 text-pink"></i>
                                                អង្គហេតុនៃវិវាទ
                                            </label>
                                            {!! showTextarea('case_objective_des', old('case_objective_des')) !!}
                                        </div>
                                    </div>
                                </div>

                                <!-- Navigation Buttons -->
                                <div class="button-group">
                                    <button type="button" id="btn_back_to_defendant" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        ត្រឡប់ក្រោយ
                                    </button>
                                    <button type="button" id="btn_next_to_contract" class="btn btn-primary">
                                        បន្តទៅដំណាក់កាលទី 4
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- STEP 4: Contract and Final Block -->
                            <div id="contract_block" style="display:none;">
                                <div class="section-header">
                                    <h4>
                                        <i class="fas fa-file-signature me-2"></i>
                                        ដំណាក់កាលទី 4: កិច្ចសន្យាការងារ និងបញ្ចប់ពាក្យបណ្ដឹង
                                        <span class="badge">តម្រូវឲ្យបំពេញ</span>
                                    </h4>
                                </div>

                                <!-- Employment Contract -->
                                <div class="subsection-title">
                                    <i class="fas fa-handshake me-2"></i>
                                    កិច្ចសន្យាការងារ
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="disputant_sdate_work" class="required">ថ្ងៃខែឆ្នាំចូលបម្រើការងារ</label>
                                            <input type="text" name="disputant_sdate_work" id="disputant_sdate_work"
                                                value="{{ old('disputant_sdate_work') }}" class="form-control datepicker"
                                                placeholder="DD-MM-YYYY" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="disputant_contract_type" class="required">ប្រភេទកិច្ចសន្យាការងារ</label>
                                            {!! showSelect('disputant_contract_type', $arrContractType, old('disputant_contract_type'), ' select2', '', '', 'required') !!}
                                        </div>
                                    </div>
                                </div>

                                <!-- Work Hours and Salary -->
                                <div class="subsection-title">
                                    <i class="fas fa-clock me-2"></i>
                                    ថិរវេលាធ្វើការ និងប្រាក់ឈ្នួល
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="disputant_work_hour_day" class="required">ម៉ោងធ្វើការ/ថ្ងៃ</label>
                                            <input type="number" step="0.01" name="disputant_work_hour_day"
                                                id="disputant_work_hour_day" value="{{ old('disputant_work_hour_day') }}"
                                                class="form-control number_only_d" placeholder="ម៉ោង" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="disputant_work_hour_week" class="required">ម៉ោងធ្វើការ/សប្ដាហ៍</label>
                                            <input type="number" step="0.01" name="disputant_work_hour_week"
                                                id="disputant_work_hour_week" value="{{ old('disputant_work_hour_week') }}"
                                                class="form-control number_only_d" placeholder="ម៉ោង" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="disputant_salary">ប្រាក់ឈ្នួលប្រចាំខែ ($)</label>
                                            <input type="number" step="0.01" name="disputant_salary" id="disputant_salary"
                                                value="{{ old('disputant_salary') }}" class="form-control number_only_d"
                                                placeholder="0.00">
                                        </div>
                                    </div>
                                </div>

                                <!-- Work Conditions -->
                                <div class="subsection-title">
                                    <i class="fas fa-clipboard-list me-2"></i>
                                    លក្ខខណ្ឌការងារ
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="disputant_night_work" class="required">ការងារវេនយប់</label>
                                            {!! showSelect('disputant_night_work', $arrNightWork, old('disputant_night_work'), ' select2', '', '', 'required') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="disputant_holiday_week" class="required">ការឈប់សម្រាកប្រចាំសប្ដាហ៍</label>
                                            {!! showSelect('disputant_holiday_week', $arrHolidayWeek, old('disputant_holiday_week'), ' select2', '', '', 'required') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="disputant_holiday_year" class="required">ថ្ងៃបុណ្យជាតិ និងឈប់សម្រាកប្រចាំឆ្នាំ</label>
                                            {!! showSelect('disputant_holiday_year', $arrHolidayYear, old('disputant_holiday_year'), ' select2', '', '', 'required') !!}
                                        </div>
                                    </div>
                                </div>

                                <!-- Main Reason -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="fw-bold mb-2">
                                                <i class="fas fa-question-circle me-2 text-pink"></i>
                                                មូលហេតុចម្បងនៃវិវាទ
                                            </label>
                                            {!! showTextarea('case_first_reason', old('case_first_reason')) !!}
                                        </div>
                                    </div>
                                </div>

                                <!-- Requests -->
                                <div class="subsection-title">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    សំណូមពរ
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="disputant_request">សំណូមពររបស់អ្នកប្ដឹង</label>
                                            {!! showTextarea('disputant_request', old('disputant_request')) !!}
                                        </div>
                                    </div>
                                </div>

                                <!-- Dates -->
                                <div class="subsection-title">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    កាលបរិច្ឆេទ
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="case_date" class="required">កាលបរិច្ឆេទធ្វើបណ្ដឹង</label>
                                            <input type="text" name="case_date" id="case_date"
                                                value="{{ old('case_date') }}" class="form-control datepicker"
                                                placeholder="DD-MM-YYYY" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="case_date_entry" class="required">កាលបរិច្ឆេទប្តឹងទៅអធិការការងារ</label>
                                            <input type="text" name="case_date_entry" id="case_date_entry"
                                                value="{{ old('case_date_entry') }}" class="form-control datepicker"
                                                placeholder="DD-MM-YYYY" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Officers -->
                                <div class="subsection-title">
                                    <i class="fas fa-user-tie me-2"></i>
                                    មន្ត្រីទទួលបន្ទុក
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="officer_id" class="required">អ្នកផ្សះផ្សា</label>
                                            {!! showSelect('officer_id', $arrOfficersInHand, old('officer_id'), ' select2', '', '', 'required') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="officer_id8">លេខាកត់ត្រា</label>
                                            {!! showSelect('officer_id8', $arrOfficersInHand, old('officer_id8'), ' select2') !!}
                                        </div>
                                    </div>
                                </div>

                                <!-- File Upload -->
                                <div class="subsection-title">
                                    <i class="fas fa-upload me-2"></i>
                                    ឯកសារពាក្យបណ្ដឹង
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            {!! upload_file('case_file', 'សូមជ្រើសរើសឯកសារ (ទំហំអតិបរមា 5MB)') !!}
                                        </div>
                                    </div>
                                </div>

                                <!-- Final Buttons -->
                                <div class="button-group">
                                    <button type="button" id="btn_back_to_objective" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        ត្រឡប់ក្រោយ
                                    </button>
                                    <button type="submit" id="btn_submit_form" class="btn btn-success">
                                        <i class="fas fa-save me-2"></i>
                                        រក្សាទុក
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="moreAfterScript">
        @include('test_case_script1')
    </x-slot>
</x-admin.layout-main>



