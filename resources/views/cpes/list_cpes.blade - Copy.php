<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
    </x-slot>
    <x-slot name="moreBeforeScript">
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <form name="frm2" action="{{ route('cpes.index') }}" method="GET">
                    @csrf
                    <div class="card">
                        <div class="card-header py-3">
                            <div class="bg-primary text-center div_number text-hanuman-22 mt-3">
                                {!! "ចំនួនខេត្តរាជធានី"." : ".$adata['total'] !!}
                            </div>
                        </div>
                        <div class="card-body pt-0 mt-0">
                            <div class="card-block row">
                                <div class="col-sm-12 col-lg-12 col-xl-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-bordered">
                                            <thead class="table-light">
                                            <tr>
                                                <th scope="col">{{__('general.k_no')}}</th>
                                                <th scope="col">{{ __('general.k_ownername') }}</th>
                                                <th scope="col">ឈ្មោះ Login</th>
                                                <th scope="col">ប្រភេទ</th>
                                                <th scope="col" class="text-nowrap">រាជធានី-ខេត្ត</th>
                                                <th scope="col">តួនាទី់</th>
                                                <th scope="col">បណ្តឹង</th>
                                                <th scope="col">{{ __('general.k_status') }}</th>
                                                <th scope="col">សកម្មភាព</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach( $adata['query_cpes'] as $row )
                                                <tr class="border-bottom-primary">
                                                    <td class="col-sm-1">{{ $adata['query_cpes']->firstItem() + $loop->iteration - 1 }}</td>
                                                    <td class="col-sm-2">
                                                        <a class="fw-bold blue" href="{{ url('user/'.$row->id.'/edit') }}" target="_blank" title="កែប្រែពត៌មានអ្នកប្រើប្រាស់">dff</a>
                                                    </td>
                                                    <td class="col-sm-2">
                                                        <a class="fw-bold pink" href="{{ url('user/change_password/'.$row->id) }}" title="ផ្លាស់ប្តូរលេខសម្ងាត់" target="_blank" class="user-link">{{ $row->username }}</a>
                                                    </td>
                                                    <td class="col-sm-2">
                                                        @php
                                                            $kCategory = $row->k_category;
                                                            $style = " label-primary";
                                                            if($kCategory == 1){
                                                                $style = " badge-danger";
                                                            }
                                                            elseif($kCategory == 2){
                                                                $style = " badge-success";
                                                            }
                                                            elseif($kCategory == 3){
                                                                $style = "badge-secondary";
                                                            }
                                                        @endphp
                                                        @if(!empty($row->category->k_category_name))
                                                            <h5><span class="badge {{$style}} text-light p-2">{{ strtoupper($row->category->k_category_name) }}</span></h5>
                                                        @endif

                                                    </td>
                                                    <td class="col-sm-1 text-nowrap">
                                                        @if(!empty($row->province->pro_khname))
                                                            <label class="form-label fw-bold text-info">{{ $row->province->pro_khname }}</label>
                                                        @endif
                                                    </td>
                                                    <td class="col-sm-1 text-nowrap">
                                                        @php
                                                        if(!empty($row->officerRole)){
                                                          $officerRoleID = $row->officerRole->id;
                                                            if($officerRoleID == 1 || $officerRoleID == 2){
                                                                $styleClass = " text-danger";
                                                            }
                                                            elseif($officerRoleID == 3 || $officerRoleID == 7 || $officerRoleID == 11){
                                                                $styleClass = "blue";
                                                            }elseif($officerRoleID == 4 || $officerRoleID == 8 || $officerRoleID == 12){
                                                                $styleClass = "text-success";
                                                            }elseif($officerRoleID == 5 || $officerRoleID == 9 || $officerRoleID == 13){
                                                                $styleClass = "pink";
                                                            }elseif($officerRoleID == 6 || $officerRoleID == 10 || $officerRoleID == 14){
                                                                $styleClass = "text-purple";
                                                            }
                                                        }
                                                        @endphp
                                                        @if(!empty($row->officerRole))
                                                           <label class="form-label fw-bold {{ $styleClass }}">{{ strtoupper($row->officerRole->officer_role) }}</label>
                                                        @elseif(!empty($row->category))
{{--                                                            <label class="form-label fw-bold">{{ strtoupper($row->category->k_category_name) }}</label>--}}
                                                        @endif</td>
{{--                                                    <td class="text-center">--}}
{{--                                                        @if(empty(countCasesByUser($row->id)))--}}
{{--                                                            <label class="form-label fw-bold text-warning">គ្មាន</label>--}}
{{--                                                        @else--}}
{{--                                                            <a class="fw-bold text-danger" href="{{ url('user/case/entry/'.$row->id) }}" target="_blank">--}}
{{--                                                                {{ countCasesByUser($row->id) }}--}}
{{--                                                            </a--}}
{{--                                                        @endif--}}
{{--                                                    </td>--}}
                                                    <td class="text-center">
                                                        @php
                                                            $caseCount = $caseCounts[$row->id] ?? 0;
                                                        @endphp

                                                        @if($caseCount == 0)
                                                            <label class="form-label fw-bold text-warning">គ្មាន</label>
                                                        @else
                                                            <a class="fw-bold text-danger" href="{{ url('user/case/entry/'.$row->id) }}" target="_blank">
                                                                {{ $caseCount }}
                                                            </a>
                                                        @endif
                                                    </td>

                                                    <td class="col-sm-1">
                                                        @if($row->banned == 1 )
                                                            <h5><span class="badge badge-danger">INACTIVE</span></h5>
                                                        @else
                                                            <h5><span class="badge badge-success">ACTIVE</span></h5>
                                                        @endif
                                                    </td>
                                                    <td class="col-sm-1">
                                                        @if($row->banned == 1 )
                                                            <a href="#" onClick="confirmEnableUser('{{ url('user/change_status'.'/'.$row->id.'/0') }}')" title="Active User"><h5><span class="badge badge-primary">ENABLE</span></h5></a>
                                                        @else
                                                            <a href="#" onClick="confirmDisableUser('{{ url('user/change_status'.'/'.$row->id.'/1') }}')" title="Disable User"><h5><span class="badge badge-danger">DISABLE</span></h5></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        <br>
                                        <div class="pagination" >
                                            @if( $adata['query_cpes']->hasPages() )
                                                {!! $adata['query_cpes']->links('pagination::bootstrap-5') !!}
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--        {{ dd(request('business_province')) }}--}}
    <x-slot name="moreAfterScript">
        @include('script.my_sweetalert2')
        <!-- Plugins Select2-->
        <script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>

        <script>
            $(document).ready(function() {
                $('#k_province').select2();
                $('#k_category').select2();
                $('#officer_role_id').select2();
            });
        </script>
        <script>
            function list_officer(url){
                window.location = url;
            }
        </script>
        <script type="text/javascript">
            function confirmEnableUser(strRoute)
            {
                Swal.fire({
                    title: 'តើអ្នកចង់បើកដំណើរការ អ្នកប្រើប្រាស់មួយនេះ វិញមែនទេ?',
                    // text: "The Data are removed and could not restore.",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ពិតមែនហើយ',
                    cancelButtonText: 'អត់ទេ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = strRoute;
                        // Swal.fire(
                        //     'Deleted!',
                        //     'Record has been deleted.',
                        //     'success'
                        // )
                    }
                })
            }
            function confirmDisableUser(strRoute)
            {
                Swal.fire({
                    title: 'តើអ្នកចង់ផ្អាកដំណើរការ អ្នកប្រើប្រាស់មួយនេះមែនទេ?',
                    // text: "The Data are removed and could not restore.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ពិតមែនហើយ',
                    cancelButtonText: 'អត់ទេ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = strRoute;
                        // Swal.fire(
                        //     'Deleted!',
                        //     'Record has been deleted.',
                        //     'success'
                        // )
                    }
                })
            }

        </script>


    </x-slot>
</x-admin.layout-main>
