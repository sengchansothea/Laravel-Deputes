<div class="section-title">២. ព័ត៌មានកម្មករនិយោជិត</div>

<!-- Search Employee -->
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="text-primary">ស្វែងរកឈ្មោះកម្មករនិយោជិត</label>
            <div id="response_message_employee" class="response-message">កំពុងស្វែងរក...</div>
            <input type="text" name="find_employee_autocomplete" id="find_employee_autocomplete" 
                class="form-control" placeholder="វាយបញ្ចូលឈ្មោះកម្មករ...">
        </div>
    </div>
</div>

<div class="subsection-title">- ព័ត៌មានទូទៅ</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group required-field">
            <label for="name">ឈ្មោះអ្នកប្ដឹង</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group required-field">
            <label for="gender">ភេទ</label>
            {!! showSelect('gender', ['1' => 'ប្រុស', '2' => 'ស្រី'], old('gender'), 'select2', '', '', 'required') !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group required-field">
            <label for="nationality">សញ្ជាតិ</label>
            {!! showSelect('nationality', $arrNationality, old('nationality'), 'select2', '', '', 'required') !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group required-field">
            <label for="dob">ថ្ងៃខែឆ្នាំកំណើត</label>
            <input type="text" name="dob" id="dob" value="{{ old('dob') }}" class="form-control datepicker" 
                placeholder="DD-MM-YYYY" required>
            @error('dob')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="id_number">លេខអត្តសញ្ញាណប័ណ្ណ/លិខិតឆ្លងដែន</label>
            <input type="text" name="id_number" id="id_number" value="{{ old('id_number') }}" class="form-control">
            @error('id_number')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group required-field">
            <label for="occupation">មុខងារ</label>
            <input type="text" name="occupation" id="occupation" value="{{ old('occupation') }}" class="form-control" required>
            @error('occupation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required-field">
            <label for="phone_number">លេខទូរស័ព្ទ (ខ្សែទី១)</label>
            <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" 
                class="form-control number_only" minlength="9" maxlength="10" required>
            @error('phone_number')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="phone_number2">លេខទូរស័ព្ទ (ខ្សែទី២)</label>
            <input type="tel" name="phone_number2" id="phone_number2" value="{{ old('phone_number2') }}" 
                class="form-control number_only">
            @error('phone_number2')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="subsection-title">- ទីកន្លែងកំណើត</div>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="pob_country_id">ប្រទេស</label>
            {!! showSelect('pob_country_id', $arrNationality, old('pob_country_id'), 'select2') !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="pob_province_id">រាជធានី-ខេត្ត</label>
            {!! showSelect('pob_province_id', $arrProvince, old('pob_province_id'), 'select2') !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="pob_district_id">ក្រុង-ស្រុក-ខណ្ឌ</label>
            <select name="pob_district_id" id="pob_district_id" class="form-control select2">
                <option value="">-- សូមជ្រើសរើស --</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="pob_commune_id">ឃុំ-សង្កាត់</label>
            <select name="pob_commune_id" id="pob_commune_id" class="form-control select2">
                <option value="">-- សូមជ្រើសរើស --</option>
            </select>
        </div>
    </div>
</div>

<div class="subsection-title">- អាសយដ្ឋានបច្ចុប្បន្ន</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group required-field">
            <label for="province">រាជធានី-ខេត្ត</label>
            {!! showSelect('province', $arrProvince, old('province'), 'select2', '', '', 'required') !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group required-field">
            <label for="district">ក្រុង-ស្រុក-ខណ្ឌ</label>
            <select name="district" id="district" class="form-control select2" required>
                <option value="">-- សូមជ្រើសរើស --</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group required-field">
            <label for="commune">ឃុំ-សង្កាត់</label>
            <select name="commune" id="commune" class="form-control select2" required>
                <option value="">-- សូមជ្រើសរើស --</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="village">ភូមិ</label>
            <select name="village" id="village" class="form-control select2">
                <option value="">-- សូមជ្រើសរើស --</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="addr_house_no">ផ្ទះលេខ</label>
            <input type="text" name="addr_house_no" id="addr_house_no" value="{{ old('addr_house_no') }}" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="addr_street">ផ្លូវ</label>
            <input type="text" name="addr_street" id="addr_street" value="{{ old('addr_street') }}" class="form-control">
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-6 text-start">
        <button type="button" id="btn_back_to_step1" class="btn btn-secondary btn-lg">
            <i class="fa fa-arrow-left"></i> ត្រឡប់ទៅដំណាក់កាលទី ១
        </button>
    </div>
    <div class="col-6 text-end">
        <button type="button" id="btn_next_to_step3" class="btn btn-primary btn-lg">
            បន្តទៅដំណាក់កាលទី ៣ <i class="fa fa-arrow-right"></i>
        </button>
    </div>
</div>