@php
$domains = $adata['domain']
@endphp
{{--{{ dd(arrayOfficerCaseInHand()) }}--}}
{{--{{ dd($domains) }}--}}
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
{{--        <style>--}}
{{--            .card .card-header {--}}
{{--                border-bottom: none;--}}
{{--                padding: 30px 30px 0px 30px;--}}
{{--            }--}}

{{--            .card .card-body {--}}
{{--                padding-top: 0px;--}}
{{--            }--}}
{{--        </style>--}}
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
{{--                    <div class="card-header text-hanuman">--}}
{{--                        @if($adata['opt_search'] == "quick")--}}
{{--                            @include("case.officer.quick-search")--}}
{{--                        @else--}}
{{--                            @include("case.officer.advance-search")--}}
{{--                        @endif--}}
{{--                        <div class="bg-secondary text-center div_number text-hanuman-20 mt-4">--}}
{{--                            ចំនួនមន្ត្រីផ្សះផ្សាសរុប : {{ number_format($adata['total']) }}--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="card-body">
                        <div class="card-block row">
                            <div class="col-sm-12 col-lg-12 col-xl-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead class="table-primary">
                                        <tr>
                                            <th scope="col" class="text-center">{{__('general.k_no')}}</th>
                                            <th scope="col" class="text-center">ការិយាល័យ</th>
                                            <th scope="col" class="text-center">ខេត្ត/រាជធានី</th>
                                            <th scope="col" class="text-center">ស្រុក/ខណ្ឌ និង ឃុំ/សង្កាត់</th>
                                            <th scope="col" class="text-center">សកម្មភាព</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $index = 1;
                                        @endphp
                                        @foreach( $domains as $domain )
{{--                                            {{ dd($domain->domainProvince->province_id) }}--}}
                                            <tr class="border-bottom-secondary col-sm-12">
                                                <td class="col-sm-1 fw-bold text-center">{{ Num2Unicode($index) }}</td>
                                                <td class="col-sm-3 text-center fw-bold text-danger" rowspan="{{ count($domain->domainProvince)+1 }}">
                                                    @php
                                                        $excludedProIDs = getAllProIDInDomain();
                                                        $excludedProIDs [] = 12;
                                                    @endphp
                                                    <label class="form-label">{{ $domain->domain_name }}</label>
                                                    <form action="{{ route('domain.show', $domain->id) }}" method="GET">
                                                        @csrf
                                                        {!! showSelect("k_province_".$domain->id,arrayProvincesExcludedByProID($excludedProIDs, 1, "0", "សូមជ្រើសរើសខេត្ត/រាជធានី"), old("k_province_".$domain->id, request("k_province_".$domain->id))) !!}
                                                        <button type="submit" class="btn btn-success add-btn p-2 mt-2 fw-bold">
                                                            បន្ថែមខេត្ត
                                                        </button>
                                                    </form>
                                                </td>
                                                <td class="col-sm-2">
                                                    <label class="fw-bold text-warning">ភ្នំពេញ</label>
                                                </td>
                                                <td class="fw-bold">
                                                    <div class="row col-sm-12 mb-3">
                                                        <div class="col-sm-6">ស្រុក/ខណ្ឌ</div>
                                                        <div class="col-sm-6">ឃុំ/សង្កាត់</div>
                                                    </div>
                                                @if(count($domain->domainDistrict) > 0)
                                                    @foreach($domain->domainDistrict as $dis)
{{--                                                                {{ dd($dis) }}--}}
                                                    <div class="row col-sm-12">
                                                        <div class="col-sm-6 text-primary">
                                                            <label class="form-label">- {{ $dis->district->dis_khname }}</label>
                                                        </div>
                                                        @if(!empty($dis->commune))
                                                        <div class="col-sm-6">
                                                            <label class="form-label">- {{ $dis->commune->com_khname }}</label>
                                                        </div>
                                                        @else
                                                        <div class="col-sm-6 text-primary">
                                                            <label class="form-label">- មិនកំណត់</label>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    @endforeach
                                                @endif
                                                @if(count($domain->domainDistintCommune) > 0)
                                                    @foreach($domain->domainDistintCommune as $dDis)
                                                    <div class="row col-sm-12">
                                                        <div class="col-sm-6">
                                                            <label class="form-label text-danger">- {{ $dDis->district->dis_khname }}</label>
                                                        </div>
                                                        @if(count($domain->domainCommune) > 0)
                                                            @foreach($domain->domainCommune as $com)
                                                        <div class="col-sm-6">
                                                            <label class="form-label fw-bold text-danger">- {{ $com->commune->com_khname }} </label>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label class="form-label fw-bold text-info"></label>
                                                        </div>
                                                            @endforeach
                                                        @else
                                                            <div class="col-sm-6">
                                                                <label class="form-label fw-bold text-info"></label>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @endforeach
                                                @endif
                                                </td>
                                                <td class="col-sm-1">
{{--                                                    <div class="d-flex justify-content-center">--}}
{{--                                                        <a class="btn btn-success me-2" href="{{ url('domain/12/edit') }}" title="កែប្រែ" target="_blank"><i data-feather="edit"></i></a>--}}
{{--                                                    </div>--}}
                                                </td>
                                            </tr>@php $index ++; @endphp
                                            @foreach($domain->domainProvince as $dPro)
                                                {{--                                                {{ dd(count($domain->domainProvince)) }}--}}
                                                <tr class="border-bottom-secondary">
                                                    <td class="fw-bold text-center">{{ Num2Unicode($index) }}</td>
                                                    {{--                                                <td class="text-nowrap fw-bold text-danger text-center"></td>--}}
                                                    <td class="fw-bold text-warning">
                                                        <label class="form-label text-success">{{ $dPro->province->pro_khname }}</label>
                                                    </td>
                                                    <td class=""><label class="form-label fw-bold text-success">មិនកំណត់</label></td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            @if($dPro->province_id == 12)
                                                                <a class="btn btn-success me-2" href="{{ url('domain/'.$dPro->province_id) }}" title="កែប្រែ" target="_blank"><i data-feather="edit"></i></a>
                                                            @else
                                                                <form id="deleteForm" action="{{ url('domain/'.$dPro->province_id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button" class="btn btn-danger delete-btn"  title="លុបខេត្ត ចេញពីការិយាល័យ">
                                                                        <i data-feather="trash-2"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                @php $index ++; @endphp
                                            @endforeach
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <br>
{{--                                    <div class="pagination" >--}}
{{--                                        @if( $adata['officers']->hasPages() )--}}
{{--                                            {!! $adata['officers']->links('pagination::bootstrap-5') !!}--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="moreAfterScript">
        <script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ rurl('assets/myjs/sweetalert2.10.10.1.all.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('#k_province_1').select2();
                $('#k_province_2').select2();
                $('#k_province_3').select2();
                $('#k_province_4').select2();
            });
        </script>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function () {
                // Attach SweetAlert2 confirmation to delete button
                const deleteButtons = document.querySelectorAll('.delete-btn');
                deleteButtons.forEach(button => {
                    button.addEventListener('click', function (event) {
                        event.preventDefault(); // Prevent default form submission
                        Swal.fire({
                            title: 'តើអ្នកពិតជាចង់លុប មែនឫ?', // Dialog title
                            icon: 'warning', // Dialog icon (warning)
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'ពិតមែនហើយ', // Confirm button text
                            cancelButtonText: 'បោះបង់', // Cancel button text
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // If user confirms, submit the form
                                button.closest('form').submit();
                            }
                        });
                    });
                });
            });
        </script>

    </x-slot>
</x-admin.layout-main>
