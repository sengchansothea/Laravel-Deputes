<form id="frm_search" action="{{ url('disputant') }}" method="get" onsubmit="return required()">
    <input type="hidden" name="opt_search" value="quick" />
    @csrf
    <div class="row mt-3 mb-2">
        <div class="col-sm-5">
{{--            {!! OnlyDeveloperAccess("<a href='".url('company/get_company_from_lacms')."' target='_blank'>Get Company From LACMS</a>") !!}--}}
        </div>
        <div class="col-sm-5">
            <input type="text" id="search" name="search" placeholder="សូមវាយឈ្មោះកម្មករ លេខអត្តសញ្ញាណបណ្ណ ឫលេខប៉ាស្ព័រ ដើម្បីស្វែងរក" value="{{ request('search') }}" class="form-control"  />
        </div>
        <div class="col-sm-2">
            <button type="submit" class="btn btn-success form-control">ស្វែងរក</button>
        </div>
    </div>
</form>
