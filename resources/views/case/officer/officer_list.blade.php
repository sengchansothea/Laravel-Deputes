@php
$officers = $adata['officers'];
$arrOfficerRole = [
    0 =>'សូមជ្រើសរើស',
    1 =>'រដ្ឋលេខាធិការ',
    2 =>'អនុរដ្ឋលេខាធិការ',
    3 =>'អគ្គនាយកនៃអគ្គនាយកដ្ឋានការងារ',
    4 =>'អគ្គនាយករងនៃអគ្គនាយកដ្ឋានការងារ',
    5 =>'ប្រធាននាយកដ្ឋានវិវាទការងារ',
    6 =>'អនុប្រធាននាយកដ្ឋានវិវាទការងារ',
    7 =>'ប្រធានការិយាល័យវិវាទការងារ',
    8 =>'អនុប្រធានការិយាល័យវិវាទការងារ',
    9 =>'មន្ត្រីការិយាល័យវិវាទការងារ',
];
@endphp

{{--{{ dd(arrayOfficerCaseInHand()) }}--}}
{{--{{ dd($officers) }}--}}
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
        <style>
            .card .card-header {
                border-bottom: none;
                padding: 30px 30px 0px 30px;
            }

            .card .card-body {
                padding-top: 0px;
            }
        </style>
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header text-hanuman">
                        @if($adata['opt_search'] == "quick")
                            @include("case.officer.quick-search")
                        @else
                            @include("case.officer.advance-search")
                        @endif
                        <div class="bg-primary text-center div_number text-hanuman-20 mt-4">
                            ចំនួនមន្ត្រីផ្សះផ្សាសរុប : {{ number_format($adata['total']) }}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-block row">
                            <div class="col-sm-12 col-lg-12 col-xl-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead class="table-light">
                                        <tr>
                                            <th scope="col">{{__('general.k_no')}}</th>
                                            <th scope="col" class="text-center">ឈ្មោះ</th>
                                            <th scope="col">ភេទ</th>
{{--                                            <th scope="col">អត្តលេខ</th>--}}
                                            <th scope="col" class="text-center">មុខងារ</th>
                                            <th scope="col" class="text-nowrap">ចំនួនបណ្តឹងនៅក្នុងដៃ</th>
                                            <th scope="col" class="text-nowrap">ប្រវត្តិអន្តរាគមន៍ក្នុងវិវាទ</th>
                                            @if($adata['k_category'] < 3)<th scope="col" class="text-center">សកម្មភាព</th>@endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach( $adata['officers'] as $officer )
                                            @php
                                            @endphp
{{--                                            {{ dd($officer->casesOfficers) }}--}}
                                            <tr class="border-bottom-success" >
                                                <td>
                                                    {{ $adata['officers']->firstItem() + $loop->iteration - 1 }}
                                                </td>
                                                <td class="text-nowrap fw-bold align-bottom text-center">
                                                    <a href="{{ url("officer/show")."/".$officer->id }}" class="text-danger fw-bold" title="ប្រវត្តិអន្តរាគមន៌ក្នុងវិវាទ" target="_blank">
                                                        <label class="form-label text-info">
                                                            @if(!empty($officer->officer_name_khmer)){{$officer->officer_name_khmer}}
                                                            @endif
                                                        </label><br/>
                                                        <label class="form-label text-danger">
                                                            @if(!empty($officer->officer_name_latin))
                                                                {{$officer->officer_name_latin}}
                                                            @endif
                                                        </label>
                                                    </a>
                                                </td>
                                                <td>
                                                    @if($officer->sex == 1)
                                                        <label class="fw-bold">ប្រុស</label>
                                                    @elseif($officer->sex == 2)
                                                        <label class="fw-bold ">ស្រី</label>
                                                    @endif
                                                </td>
{{--                                                <td class="fw-bold">{{ $officer->officer_id2 }}</td>--}}
                                                <td class="fw-bold text-center">@if(!empty($officer->officerRole)){{ $officer->officerRole->officer_role }}@endif</td>
                                                <td>
                                                    @php
                                                    $counterSolver = countCases($officer->caseOfficerSolvers ?? 0); // Count Officer as Solver
                                                    $counterNoter = countCases($officer->caseOfficerNoters ?? 0); // Count Officer as Noter
                                                    @endphp
                                                    <label class="form-label fw-bold">អ្នកផ្សះផ្សា៖</label>
                                                    @if($counterSolver > 0)
                                                        <label class="form-label text-danger fw-bold">{{ Num2Unicode($counterSolver) }} (បណ្តឹង)</label>
                                                    @else
                                                        <label class="form-label text-warning fw-bold">គ្មាន</label>
                                                    @endif
                                                    <br/>
                                                    <label class="form-label fw-bold">លេខាកត់ត្រា៖</label>
                                                    @if($counterNoter > 0)
                                                        <label class="form-label text-info fw-bold">{{ Num2Unicode($counterNoter) }} (បណ្តឹង)</label>
                                                    @else
                                                        <label class="form-label text-warning fw-bold">គ្មាន</label>
                                                    @endif
                                                </td>
                                                <td>
                                                    <label class="form-label fw-bold">អ្នកផ្សះផ្សា៖</label>
                                                    @if(count($officer->caseOfficerSolvers) > 0)
                                                    <label class="form-label">
                                                        <a href="{{ url("officer/show")."/".$officer->id."/1" }}" class="text-danger fw-bold" title="ប្រវត្តិជាអ្នកផ្សះផ្សា" target="_blank">
                                                            {{ Num2Unicode(count($officer->caseOfficerSolvers)) }} (បណ្តឹង)
                                                        </a>
                                                    </label>
                                                    @else
                                                        <span class="text-warning fw-bold">គ្មាន</span>
                                                    @endif
                                                    <br/>
                                                    <label class="form-label fw-bold">លេខាកត់ត្រា៖</label>
                                                    @if(count($officer->caseOfficerNoters) > 0)
                                                        <label class="form-label">
                                                            <a href="{{ url("officer/show")."/".$officer->id."/2" }}" class="text-info fw-bold" title="ប្រវត្តិជាលេខាកត់ត្រា" target="_blank">
                                                                {{ Num2Unicode(count($officer->caseOfficerNoters)) }} (បណ្តឹង)
                                                            </a>
                                                        </label>
                                                    @else
                                                        <span class="text-warning fw-bold">គ្មាន</span>
                                                    @endif
                                                </td>
                                                @if($adata['k_category'] < 3)
                                                <td class="text-center">
                                                    <div class="mb-2">
                                                        <a href="{{ route('officer.edit', $officer->id) }}" class="btn btn-primary fw-bold text-nowrap form-control" title="កែប្រែពត៌មានមន្ត្រី" target="_blank">
                                                            កែប្រែពត៌មាន
                                                        </a>
                                                    </div>
                                                    <div class="mb-2">
                                                        <form name="frm-delete" action="{{ route('officer.destroy', $officer->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button id="btn-delete" type="submit" class="btn btn-danger form-control fw-bold" title="លុបឈ្មោះមន្ត្រី">លុប</button>
                                                        </form>
                                                    </div>
                                                </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    <div class="pagination" >
                                        @if( $adata['officers']->hasPages() )
                                            {!! $adata['officers']->links('pagination::bootstrap-5') !!}
                                        @endif
                                    </div>
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
            $('#officer_role_id').select2();
            document.addEventListener('DOMContentLoaded', function () {
                // Attach SweetAlert2 confirmation to delete button
                const deleteButtons = document.querySelectorAll('#btn-delete');
                deleteButtons.forEach(button => {
                    button.addEventListener('click', function (event) {
                        event.preventDefault(); // Prevent default form submission
                        Swal.fire({
                            title: 'តើអ្នកពិតជាចង់លុប មែនឫ?', // Dialog title
                            icon: 'warning', // Dialog icon (warning)
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'លុបពត៌មានមន្ត្រី', // Confirm button text
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
