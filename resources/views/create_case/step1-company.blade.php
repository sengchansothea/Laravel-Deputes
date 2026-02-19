<div class="section-title">១. ព័ត៌មានសហគ្រាស គ្រឹះស្ថាន</div>

<!-- Search Company -->
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="text-primary">ស្វែងរកឈ្មោះសហគ្រាស គ្រឹះស្ថាន</label>
            <div id="response_message_company" class="response-message">កំពុងស្វែងរក...</div>
            <input type="text" name="find_company" class="form-control" id="find_company_autocomplete" placeholder="វាយបញ្ចូលឈ្មោះសហគ្រាស...">
        </div>
    </div>
</div>

<!-- Toggle Company Details -->
<div class="row">
    <div class="col-md-12">
        <button type="button" id="btn_search_company" value="0" class="btn btn-danger mb-3">
            បិទព័ត៌មានលម្អិតរបស់សហគ្រាស គ្រឹះស្ថាន
        </button>
    </div>
</div>

<!-- Company Details -->
<div id="div_company_result">
    <div class="row">
        <div class="col-md-12">
            <textarea rows="6" name="company_result" id="company_result" class="form-control mb-3" readonly></textarea>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required-field">
                <label for="company_name_khmer">ឈ្មោះជាភាសាខ្មែរ</label>
                <input type="text" name="company_name_khmer" id="company_name_khmer" 
                    value="{{ old('company_name_khmer') }}" class="form-control" required>
                @error('company_name_khmer')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="company_name_latin">ឈ្មោះជាភាសាឡាតាំង</label>
                <input type="text" name="company_name_latin" id="company_name_latin" 
                    value="{{ old('company_name_latin') }}" class="form-control" required>
                @error('company_name_latin')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required-field">
                <label for="sector_id">វិស័យ</label>
                {!! showSelect('sector_id', $arrSector, old('sector_id'), 'select2', '', '', 'required') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required-field">
                <label for="company_type_id">ប្រភេទសហគ្រាស</label>
                {!! showSelect('company_type_id', $arrCompanyType, old('company_type_id'), 'select2', '', '', 'required') !!}
            </div>
        </div>
    </div>
    
    <div class="subsection-title">- អាសយដ្ឋាន</div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="form-group required-field">
                <label for="province_id">រាជធានី-ខេត្ត</label>
                {!! showSelect('province_id', $arrProvince, old('province_id'), 'select2', '', '', 'required') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group required-field">
                <label for="district_id">ក្រុង-ស្រុក-ខណ្ឌ</label>
                <select name="district_id" id="district_id" class="form-control select2" required>
                    <option value="">-- សូមជ្រើសរើស --</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group required-field">
                <label for="commune_id">ឃុំ-សង្កាត់</label>
                <select name="commune_id" id="commune_id" class="form-control select2" required>
                    <option value="">-- សូមជ្រើសរើស --</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="village_id">ភូមិ</label>
                <select name="village_id" id="village_id" class="form-control select2">
                    <option value="">-- សូមជ្រើសរើស --</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="building_no">អគារលេខ</label>
                <input type="text" name="building_no" id="building_no" value="{{ old('building_no') }}" class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="street_no">ផ្លូវ</label>
                <input type="text" name="street_no" id="street_no" value="{{ old('street_no') }}" class="form-control">
            </div>
        </div>
    </div>
    
    <div class="subsection-title">- ទំនាក់ទំនង</div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required-field">
                <label for="company_phone_number">លេខទូរស័ព្ទក្រុមហ៊ុន (ខ្សែទី១)</label>
                <input type="text" name="company_phone_number" id="company_phone_number" 
                    value="{{ old('company_phone_number') }}" class="form-control number_only" 
                    minlength="9" maxlength="10" required>
                @error('company_phone_number')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="company_phone_number2">លេខទូរស័ព្ទក្រុមហ៊ុន (ខ្សែទី២)</label>
                <input type="text" name="company_phone_number2" id="company_phone_number2" 
                    value="{{ old('company_phone_number2') }}" class="form-control number_only">
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 text-end">
        <button type="button" id="btn_next_to_step2" class="btn btn-primary btn-lg">
            បន្តទៅដំណាក់កាលទី ២ <i class="fa fa-arrow-right"></i>
        </button>
    </div>
</div>