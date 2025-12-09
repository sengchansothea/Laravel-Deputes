<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
    </x-slot>
    <x-slot name="moreBeforeScript">
    </x-slot>
    <div class="container-fluid">
        <h2 class="mb-4">CpesLocCd Records</h2>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <!-- ✅ Makes the table scrollable horizontally -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                <tr>
                    <th>LOC_SEQ</th>
                    <th>LVL_CD</th>
                    <th>LACMS_PRO_ID</th>
                    <th>LACMS_DIS_ID</th>
                    <th>LACMS_COM_ID</th>
                    <th>LACMS_VIL_ID</th>
                    <th>COUNTRY_ID</th>
                    <th>PROVINCE_ID</th>
                    <th>DISTRICT_ID</th>
                    <th>COMMUNE_ID</th>
                    <th>VILLAGE_ID</th>
                    <th>PARENT_ID</th>
                    <th>LVL</th>
                    <th>NM_EN</th>
                    <th>NM_KH</th>
                    <th>PROVINCE_ID_API</th>
                    <th>ADDR_FULL_CD</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach( $adata['query_cpes'] as $record )
                    <tr>
                        <form action="{{ route('cpes.update', $record->LOC_SEQ) }}" method="POST">
                            @csrf
                            <td>{{ $record->LOC_SEQ }}</td> <!-- Not editable -->
                            <td><input type="number" name="LVL_CD" value="{{ $record->LVL_CD }}" class="form-control"></td>
                            <td><input type="number" name="LACMS_PRO_ID" value="{{ $record->LACMS_PRO_ID }}" class="form-control"></td>
                            <td><input type="number" name="LACMS_DIS_ID" value="{{ $record->LACMS_DIS_ID }}" class="form-control"></td>
                            <td><input type="number" name="LACMS_COM_ID" value="{{ $record->LACMS_COM_ID }}" class="form-control"></td>
                            <td><input type="number" name="LACMS_VIL_ID" value="{{ $record->LACMS_VIL_ID }}" class="form-control"></td>
                            <td><input type="number" name="COUNTRY_ID" value="{{ $record->COUNTRY_ID }}" class="form-control"></td>
                            <td><input type="number" name="PROVINCE_ID" value="{{ $record->PROVINCE_ID }}" class="form-control"></td>
                            <td><input type="number" name="DISTRICT_ID" value="{{ $record->DISTRICT_ID }}" class="form-control"></td>
                            <td><input type="number" name="COMMUNE_ID" value="{{ $record->COMMUNE_ID }}" class="form-control"></td>
                            <td><input type="number" name="VILLAGE_ID" value="{{ $record->VILLAGE_ID }}" class="form-control"></td>
                            <td><input type="number" name="PARENT_ID" value="{{ $record->PARENT_ID }}" class="form-control"></td>
                            <td><input type="text" name="LVL" value="{{ $record->LVL }}" class="form-control"></td>
                            <td><input type="text" name="NM_EN" value="{{ $record->NM_EN }}" class="form-control"></td>
                            <td><input type="text" name="NM_KH" value="{{ $record->NM_KH }}" class="form-control"></td>
                            <td><input type="number" name="PROVINCE_ID_API" value="{{ $record->PROVINCE_ID_API }}" class="form-control"></td>
                            <td><input type="text" name="ADDR_FULL_CD" value="{{ $record->ADDR_FULL_CD }}" class="form-control"></td>

                            <td>
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </td>
                        </form>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
    {{--        {{ dd(request('business_province')) }}--}}
    <x-slot name="moreAfterScript">
        @include('script.my_sweetalert2')
        <!-- Plugins Select2-->
        <script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>

    </x-slot>
</x-admin.layout-main>
