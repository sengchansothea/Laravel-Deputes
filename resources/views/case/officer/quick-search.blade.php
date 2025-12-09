<form id="frm_search" action="{{ url('officer') }}" method="get" onsubmit="return required()">
    <input type="hidden" name="opt_search" value="quick" />
    @csrf
    <div class="row">
        <div class="col-7">
            <label class="form-label mb-1 fw-bold" style="visibility: hidden">តួនាទី</label>
            <input type="text" id="search" name="search" placeholder="សូមវាយឈ្មោះមន្ត្រី ឫអត្តលេខមន្ត្រី ដើម្បីស្វែងរក" value="{{ request('search') }}" class="form-control"  />
        </div>
        <div class="col-3">
            <label class="form-label mb-1 fw-bold">តួនាទី</label>
            {!! showSelect('officer_role_id',$arrOfficerRole, old('officer_role_id', request('officer_role_id')), "", "onchange='this.form.submit()'") !!}
        </div>
        <div class="col-sm-2">
            <label class="form-label mb-1 fw-bold" style="visibility: hidden">តួនាទី</label>
            <button type="submit" class="btn btn-success fw-bold form-control">ស្វែងរក</button>
        </div>
    </div>
</form>
