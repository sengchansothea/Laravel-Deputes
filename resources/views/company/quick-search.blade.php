<form id="frm_search" action="{{ url('company/list') }}" method="get" onsubmit="return required()">
    <input type="hidden" name="opt_search" value="quick" />
    @csrf
    <div class="row">
{{--        <div class="col-sm-2">--}}
{{--            {!! OnlyDeveloperAccess("<a href='".url('company/get_company_from_lacms')."' target='_blank'>Get Company From LACMS</a>") !!}--}}
{{--        </div>--}}
        <div class="col-sm-9">
            <input type="text" id="search" name="search" placeholder="សូមវាយឈ្មោះរោងចក្រ សហគ្រាស ឬ លេខចុះបញ្ជីពាណិជ្ជកម្ម ឬ លេខTIN ដើម្បីស្វែងរក" value="{{ request('search') }}" class="form-control"  />
        </div>
        <div class="col-sm-3">
            <button type="submit" class="form-control btn btn-success-gradien fw-bold text-hanuman-17">
                <span class="fa fa-search me-2 text-white"></span>
                ស្វែងរកព័ត៌មានរោងចក្រ សហគ្រាស
            </button>
        </div>
    </div>


{{--    <div class="row">--}}
{{--        <div class="col-xs-12">--}}
{{--            <div class="main-box no-header clearfix">--}}
{{--                <div class="col-xs-12 clearfix">--}}

{{--                    <div class="row">--}}
{{--                        <div class="form-group col-xs-3">--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-xs-6">--}}
{{--                            <div class="input-group">--}}
{{--                                <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>--}}
{{--                                <input type="text" name="search" placeholder="សូមវាយឈ្មោះរោងចក្រ សហគ្រាស ឬ លេខចុះបញ្ជីពាណិជ្ជកម្ម ឬ លេខTIN ដើម្បីស្វែងរក" value="{{ request('search') }}" class="form-control"  />--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group col-xs-1">--}}
{{--                            <button type="submit" class="btn btn-success">Search</button>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-xs-2">--}}
{{--                            <a href="{{ url('company?opt_search=advance') }}">Advance Search</a>--}}
{{--                        </div>--}}

{{--                    </div>--}}

{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
</form>






{{--<div class="card basic-form">--}}
{{--    <form action="{{ url('inspection') }}" method="get">--}}
{{--        @csrf--}}
{{--        <input type="hidden" name="opt_search" value="quick" />--}}
{{--        <div class="row">--}}
{{--            <div class="col-lg-10 form-group">--}}
{{--                <input type="text"--}}
{{--                       name="search"--}}
{{--                       placeholder="Search Student"--}}
{{--                       class="form-control input-default"--}}
{{--                       value="{{ request('search') }}"--}}
{{--                />--}}
{{--            </div>--}}
{{--            <div class="col-lg-2 form-group">--}}
{{--                <a href="{{ url('inspection?opt_search=advance') }}">Advance Search</a>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--    </form>--}}
{{--</div>--}}
