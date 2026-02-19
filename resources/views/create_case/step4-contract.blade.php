<div class="section-title">៤. កិច្ចសន្យាការងារ និងលក្ខខណ្ឌការងារ</div>

<div class="subsection-title">- កិច្ចសន្យាការងារ</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required-field">
            <label for="disputant_sdate_work">ថ្ងៃខែឆ្នាំចូលបម្រើការងារ</label>
            <input type="text" name="disputant_sdate_work" id="disputant_sdate_work" 
                value="{{ old('disputant_sdate_work') }}" class="form-control datepicker" 
                placeholder="DD-MM-YYYY" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required-field">
            <label for="disputant_contract_type">ប្រភេទកិច្ចសន្យាការងារ</label>
            {!! showSelect('disputant_contract_type', $arrContractType, old('disputant_contract_type'), 'select2', '', '', 'required') !!}
        </div>
    </div>
</div>

<div class="subsection-title">- ថិរវេលាធ្វើការ និងប្រាក់ឈ្នួល</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group required-field">
            <label for="disputant_work_hour_day">ចំនួនម៉ោងធ្វើការក្នុងមួយថ្ងៃ</label>
            <input type="number" step="0.01" name="disputant_work_hour_day" id="disputant_work_hour_day" 
                value="{{ old('disputant_work_hour_day') }}" class="form-control number_only_d" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group required-field">
            <label for="disputant_work_hour_week">ចំនួនម៉ោងធ្វើការក្នុងមួយសប្ដាហ៍</label>
            <input type="number" step="0.01" name="disputant_work_hour_week" id="disputant_work_hour_week" 
                value="{{ old('disputant_work_hour_week') }}" class="form-control number_only_d" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="disputant_salary">ប្រាក់ឈ្នួលប្រចាំខែ (ដុល្លារ)</label>
            <input type="number" step="0.01" name="disputant_salary" id="disputant_salary" 
                value="{{ old('disputant_salary') }}" class="form-control number_only_d">
        </div>
    </div>
</div>

<div class="subsection-title">- លក្ខខណ្ឌការងារ</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group required-field">
            <label for="disputant_night_work">ការងារវេនយប់</label>
            {!! showSelect('disputant_night_work', $arrNightWork, old('disputant_night_work'), 'select2', '', '', 'required') !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group required-field">
            <label for="disputant_holiday_week">ការឈប់សម្រាកប្រចាំសប្ដាហ៍</label>
            {!! showSelect('disputant_holiday_week', $arrHolidayWeek, old('disputant_holiday_week'), 'select2', '', '', 'required') !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group required-field">
            <label for="disputant_holiday_year">ថ្ងៃបុណ្យជាតិ និងការឈប់សម្រាកប្រចាំឆ្នាំ</label>
            {!! showSelect('disputant_holiday_year', $arrHolidayYear, old('disputant_holiday_year'), 'select2', '', '', 'required') !!}
        </div>
    </div>
</div>

<div class="subsection-title">- មូលហេតុចម្បងនៃវិវាទ</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <textarea name="case_first_reason" id="case_first_reason" rows="4" class="form-control">{{ old('case_first_reason') }}</textarea>
        </div>
    </div>
</div>

<div class="section-title">៥. សំណូមពរ</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="disputant_request">សំណូមពររបស់អ្នកប្ដឹង</label>
            <textarea name="disputant_request" id="disputant_request" rows="4" class="form-control">{{ old('disputant_request') }}</textarea>
        </div>
    </div>
</div>

<div class="section-title">៦. កាលបរិច្ឆេទ</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required-field">
            <label for="case_date">កាលបរិច្ឆេទធ្វើបណ្ដឹង</label>
            <input type="text" name="case_date" id="case_date" value="{{ old('case_date') }}" 
                class="form-control datepicker" placeholder="DD-MM-YYYY" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required-field">
            <label for="case_date_entry">កាលបរិច្ឆេទប្តឹងទៅអធិការការងារ</label>
            <input type="text" name="case_date_entry" id="case_date_entry" value="{{ old('case_date_entry') }}" 
                class="form-control datepicker" placeholder="DD-MM-YYYY" required>
        </div>
    </div>
</div>

<div class="section-title">៧. មន្ត្រីទទួលបន្ទុក</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required-field">
            <label for="officer_id">អ្នកផ្សះផ្សា</label>
            {!! showSelect('officer_id', $arrOfficersInHand, old('officer_id'), 'select2', '', '', 'required') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="officer_id8">លេខាកត់ត្រា</label>
            {!! showSelect('officer_id8', $arrOfficersInHand, old('officer_id8'), 'select2') !!}
        </div>
    </div>
</div>

<div class="section-title">៨. ឯកសារពាក្យបណ្ដឹង</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! upload_file('case_file', 'សូមជ្រើសរើសឯកសារ (ទំហំអតិបរមា 5MB)') !!}
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-6 text-start">
        <button type="button" id="btn_back_to_step3" class="btn btn-secondary btn-lg">
            <i class="fa fa-arrow-left"></i> ត្រឡប់ទៅដំណាក់កាលទី ៣
        </button>
    </div>
    <div class="col-6 text-end">
        <button type="submit" class="btn btn-success btn-lg">
            <i class="fa fa-save"></i> រក្សាទុក
        </button>
    </div>
</div>