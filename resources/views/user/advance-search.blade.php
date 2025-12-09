<x-slot name="moreCss2">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</x-slot>


<label for="autocomplete">Search:</label>
<input type="text" id="autocomplete">
<label for="details">Details:</label>
<input type="text" id="company_id" readonly>
<input type="text" id="company_name_khmer" readonly>

@push('childScript')
    <script src="https://code.jquery.com/jquery-3.6.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            $("#autocomplete").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ url('/autocomplete') }}",
                        dataType: "json",
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 2, // Minimum characters before triggering autocomplete
                select: function(event, ui) {
                    // Fetch and display details when an item is selected
                    $.ajax({
                        url: "{{ url('/get-details') }}",
                        dataType: "json",
                        data: {
                            name: ui.item.value
                        },
                        success: function(data) {

                            $("#company_id").val(data.company_id); // Replace 'details' with the actual field name
                            $("#company_name_khmer").val(data.company_name_khmer);
                        }
                    });
                }
            });
        });
    </script>
@endpush



<form action="{{ url('company/list') }}" method="get">
    @method('PATCH')
    @csrf
    <input type="hidden" name="opt_search" value="advance" />
    <div class="card-block row gy-3">
        <div class="col-12 col-md-5">
            <label class="fw-bold">ឈ្មោះរោងចក្រ សហគ្រាស ឬ លេខចុះបញ្ជីពាណិជ្ជកម្ម ឬ លេខTIN:</label>
            <input type="text" name="search" placeholder="" value="{{ request('search') }}" class="form-control" />
        </div>
        <div class="col-12 col-md-5">
            <label class="fw-bold">សកម្មភាពសេដ្ឋកិច្ច:</label>
            {!! showSelect('business_activity', arrayBusinessActivity(1,0, "មិនកំណត់"), old('business_activity', request('business_activity')), " select2") !!}
        </div>
        <div class="col-12 col-md-2">
            <label class="fw-bold">ចំនួនកម្មករ:</label>
            {!! showSelect('total_emp', arrayTotalEmployeeForSearch(), old('total_emp', request('total_emp')), " select2") !!}
        </div>
        <div class="col-12 col-md-3">
            <label class="fw-bold">ប្រភេទអធិការកិច្ច:</label>
            {!! showSelect('insp_status', arrayInspectionTypeForSearchCompany(), old('insp_status', request('insp_status')), " select2") !!}
        </div>
        <div class="col-12 col-md-2">
            <label class="fw-bold">អាសយដ្ឋាន:  រាជធានី-ខេត្ត:</label>
            {!! showSelect('province_id', arrayProvince(1,0, "មិនកំណត់"), old('province_id', request('province_id')), " select2") !!}
        </div>
        <div class="col-12 col-md-2">
            <label class="fw-bold">ក្រុង-ស្រុក-ខណ្ឌ:</label>
            {!! showSelect('district_id', arrayDistrict(request('province_id'), 1,0, "មិនកំណត់"), old('district_id', request('district_id')), " select2") !!}
{{--            <select class="form-control" name="district_id" id="district_id">--}}
{{--            </select>--}}
        </div>
        <div class="col-12 col-md-2">
            <label class="fw-bold">ឃុំ-សង្កាត់:</label>
            {!! showSelect('commune_id', arrayCommune(request('district_id'), 1,0, "មិនកំណត់"), old('commune_id', request('district_id')), " select2") !!}
        </div>
        <div class="col-12 col-md-2">
            <label style="color:white">x</label>
            <div class="input-group">
                <button type="submit" class="btn btn-success">ស្វែងរក</button>
            </div>
        </div>

    </div>

</form>
