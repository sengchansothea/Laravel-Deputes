<div class="section-title">៣. កម្មវត្ថុបណ្ដឹង</div>

<div class="subsection-title">- កម្មវត្ថុបណ្ដឹង</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required-field">
            <label for="case_objective_id">កម្មវត្ថុបណ្ដឹង</label>
            {!! showSelect('case_objective_id', $arrObjectiveCase, old('case_objective_id'), 'select2', '', '', 'required') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="case_ojective_other">កម្មវត្ថុបណ្ដឹងផ្សេងៗ</label>
            <input type="text" name="case_ojective_other" id="case_ojective_other" 
                value="{{ old('case_ojective_other') }}" class="form-control">
        </div>
    </div>
</div>

<div class="subsection-title">- ការផ្ដាច់កិច្ចសន្យា</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="terminated_contract_date">ថ្ងៃខែឆ្នាំផ្ដាច់កិច្ចសន្យាការងារ</label>
            <input type="text" name="terminated_contract_date" id="terminated_contract_date" 
                value="{{ old('terminated_contract_date') }}" class="form-control datepicker" 
                placeholder="DD-MM-YYYY">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="terminated_contract_time">ម៉ោងផ្ដាច់កិច្ចសន្យាការងារ</label>
            <div class="input-group clockpicker" data-autoclose="true">
                <input type="text" name="terminated_contract_time" id="terminated_contract_time" 
                    value="{{ old('terminated_contract_time') }}" class="form-control" placeholder="HH:MM">
            </div>
        </div>
    </div>
</div>

<div class="subsection-title">- អង្គហេតុនៃវិវាទ</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <textarea name="case_objective_des" id="case_objective_des" rows="5" class="form-control">{{ old('case_objective_des') }}</textarea>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-6 text-start">
        <button type="button" id="btn_back_to_step2" class="btn btn-secondary btn-lg">
            <i class="fa fa-arrow-left"></i> ត្រឡប់ទៅដំណាក់កាលទី ២
        </button>
    </div>
    <div class="col-6 text-end">
        <button type="button" id="btn_next_to_step4" class="btn btn-primary btn-lg">
            បន្តទៅដំណាក់កាលទី ៤ <i class="fa fa-arrow-right"></i>
        </button>
    </div>
</div>