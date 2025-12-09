<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <form class="" name="frm_officer_create" id="frm_officer_create" action="{{ route('officer.store') }}" method="POST">
                    @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="card-block row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="row col-12">
                                    <div class="form-group col-md-4 col-sm-12 mt-3">
                                        <label class="fw-bold required pb-2">ឈ្មោះមន្ត្រីផ្សះផ្សា (ខ្មែរ)</label>
                                        <input type="text" name="officer_name_khmer" id="officer_name_khmer" value="{{ old('officer_name_khmer') }}" class="form-control" />
                                        @error('officer_name_khmer')
                                        <div class="text-danger p-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-sm-12 mt-3">
                                        <label class="fw-bold required pb-2">ឈ្មោះមន្ត្រីផ្សះផ្សា (ឡាតាំង)</label>
                                        <input type="text" name="officer_name_latin" id="officer_name_latin" value="{{ old('officer_name_latin') }}" class="form-control" />
                                        @error('officer_name_latin')
                                            <div class="text-danger p-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-sm-12 mt-3">
                                        <label class="fw-bold required pb-2">អត្តលេខមន្ត្រី</label>
                                        <input type="text" name="officer_id2" id="officer_id2" value="{{ old('officer_id2') }}" class="form-control" />
                                        @error('officer_id2')
                                        <div class="text-danger p-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row col-12">
                                    <div class="form-group col-md-4 col-sm-12 mt-3">
                                        <label class="fw-bold required pb-2">មុខងារ</label>
{{--                                        <input type="text" name="officer_role" id="officer_role" value="{{ old('officer_role') }}" class="form-control" />--}}
                                        {!! showSelect('officer_role', arrayOfficerRole(1,0, "សូមជ្រើសរើស"), old('officer_role', request('officer_role')), " select2") !!}
                                        @error('officer_role')
                                        <div class="text-danger p-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-sm-12 mt-3">
                                        <label class="fw-bold required pb-2">លេខទូរសព្ទ</label>
                                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" class="form-control" />
                                        @error('phone_number')
                                        <div class="text-danger p-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-sm-12 mt-3">
                                        <label class="fw-bold required pb-2">ភេទ</label>
                                        {!! showSelect('sex',arrayGender(), old('sex'), "select2", "", "", "required") !!}
                                        @error('sex')
                                        <div class="text-danger p-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">បញ្ចូលពត៌មានមន្ត្រី</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <x-slot name="moreAfterScript">
        <script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ rurl('assets/myjs/sweetalert2.10.10.1.all.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#sex').select2();
                $('#officer_role').select2();
            });
        </script>
    </x-slot>
</x-admin.layout-main>
