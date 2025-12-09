@php
$domain = $adata['domain']
@endphp
{{--{{ dd(arrayOfficerCaseInHand()) }}--}}
{{--{{ dd($domain) }}--}}
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
                                            <th scope="col" class="text-center">ស្រុក/ខណ្ឌ</th>
                                            <th scope="col" class="text-center">ឃុំ/សង្កាត់</th>
                                            <th scope="col" class="text-center">សកម្មភាព</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $index = 1;
                                        @endphp
                                        <tr class="border-bottom-secondary">
                                                <td class="fw-bold text-center">{{ Num2Unicode($index) }}</td>
                                                <td class="text-center fw-bold text-danger" rowspan="{{ count($domain->domainProvince)+1 }}">
                                                    <label class="form-label">{{ $domain->domain_name }}</label>
                                                    <div class="">
                                                        <a href="{{ route('officer.edit', $domain->id) }}" class="btn btn-success text-nowrap" title="កែប្រែពត៌មានមន្ត្រី" target="_blank">
                                                            បន្ថែមខេត្ត
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <label class="fw-bold text-warning">ភ្នំពេញ</label>
                                                </td>
                                                <td class="fw-bold text-primary">
                                                @if(count($domain->domainDistrict) > 0)
                                                    @foreach($domain->domainDistrict as $dis)
                                                        <label class="form-label">- {{ $dis->district->dis_khname }}</label> <br/>
                                                    @endforeach
                                                @endif
                                                    @if(count($domain->domainDistintCommune) > 0)
                                                        @foreach($domain->domainDistintCommune as $dDis)
                                                            <label class="form-label">- {{ $dDis->district->dis_khname }}</label> <br/>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                @if(count($domain->domainCommune) > 0)
                                                    <td class="">
                                                    @foreach($domain->domainCommune as $com)
                                                        @php $comDis = $com->district @endphp
{{--                                                        {{ dd($com) }}--}}
                                                            <label class="form-label fw-bold text-info">- {{ $com->commune->com_khname }} ({{ $comDis->dis_khname }})</label> <br/>
                                                    @endforeach
                                                    </td>
                                                @else
                                                <td class="text-center">
                                                    <label class="form-label fw-bold text-center">មិនកំណត់</label>
                                                </td>
                                                @endif
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a class="btn btn-success me-2" href="{{ url('domain/12/edit') }}" title="កែប្រែ" target="_blank"><i data-feather="edit"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @php $index ++; @endphp
                                        @foreach($domain->domainProvince as $dPro)
{{--                                                {{ dd(count($domain->domainProvince)) }}--}}
                                        <tr class="border-bottom-secondary">
                                                <td class="fw-bold text-center">{{ Num2Unicode($index) }}</td>
{{--                                                <td class="text-nowrap fw-bold text-danger text-center"></td>--}}
                                                <td class="fw-bold text-warning text-center">
                                                    <label class="form-label">{{ $dPro->province->pro_khname }}</label>
                                                </td>
                                                <td class="text-center"><label class="form-label fw-bold">មិនកំណត់</label></td>
                                                <td class="text-center"><label class="form-label fw-bold">មិនកំណត់</label></td>
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
