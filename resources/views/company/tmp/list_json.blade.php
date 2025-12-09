{{ dd($adata) }}
{{--{{ dd($jsonData->result->companys->links) }}--}}
@php
//    $jsonData= ($jsonData->result->companys->links);

        $adata = $jsonData->result;
        $companys= $adata->companys;
        $i= $companys->from;

@endphp
{{--{{  dd($jsonData) }}--}}

<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
                @include('layouts.test')
    </x-slot>
{{--        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">--}}

    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-block row">
                            <div class="col-sm-12 col-lg-12 col-xl-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="table-primary">
                                        <tr>
                                            <th scope="col">លរ</th>
                                            <th scope="col">ឈ្មោះរោងចក្រ សហគ្រាស</th>
                                            <th scope="col">សកម្មភាពសេដ្ឋកិច្ច</th>
                                            <th scope="col">រាជធានី-ខេត្ត</th>
                                            <th scope="col">ស្វ័យរាយការណ៍</th>
                                            <th scope="col">អធិការកិច្ចការងារ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach( $companys->data as $row )
                                            <tr>
                                                <th scope="row">
                                                    {{{ $i }}}
                                                </th>
                                                <td>
                                                    {{ $row->company_name_khmer }}
                                                    <br> {{ $row->company_name_latin }}
                                                </td>
                                                <td>{{ $row->bus_khmer_name }}</td>
                                                <td>
                                                    {{ $row->province_name }}
                                                    <br>({{ $row->district_name }})
                                                    <br> {!! googleMap($row->company_id, $row->google_map_link) !!}
                                                </td>
                                                <td>@mdo</td>
                                                <td>@mdo</td>
                                            </tr>
                                            @php $i++; @endphp
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    {{ dd($companys->links) }}
                                    @if( $companys->hasPages() )
ddd
                                        @endif
{{--                                    {{ dd($companys->links) }}--}}
                                    {!! pagination($companys->links) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--    {{ dd($adata) }}--}}
</x-admin.layout-main>




