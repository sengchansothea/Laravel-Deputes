@php
    use Illuminate\Support\Str;
    $cases = $adata['cases'];
    $user = $adata['user'];
@endphp
<x-admin.layout-main :adata="$adata">
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
                        {{--                        @if ($user) --}}
                        {{--                        <div class="row col-12 fw-bold d-flex"> --}}
                        {{--                            <label class="form-label">ឈ្មោះអ្នកប្រើប្រាស់៖ --}}
                        {{--                                <span class="text-danger fw-bold text-hanuman-18"> --}}
                        {{--                                    {{ $user->k_fullname }} --}}
                        {{--                                </span> --}}
                        {{--                            </label> --}}
                        {{--                        </div> --}}
                        {{--                        @endif --}}
                        <div class="text-hanuman">
                            @if ($adata['opt_search'] == 'quick')
                                @include('user.quick-search')
                            @else
                                @include('user.advance-search')
                            @endif
                        </div>
                        <div class="bg-primary text-center div_number text-hanuman-20 mt-3">
                            ចំនួនបណ្តឹងដែលបានបញ្ចូល : {{ $cases->total() }}
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="card-block row">
                            <div class="col-sm-12 col-lg-12 col-xl-12">
                                @if ($cases->total() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col">{{ __('general.k_no') }}</th>
                                                    <th scope="col" class="text-center">បណ្តឹង</th>
                                                    <th scope="col" class="text-center text-nowrap">ដើមបណ្តឹង</th>
                                                    <th scope="col" class="text-center text-nowrap">ចុងបណ្តឹង</th>
                                                    <th scope="col" class="text-nowrap">អ្នកផ្សះផ្សា</th>
                                                    <th scope="col" class="text-center">កម្មវត្ថុបណ្តឹង</th>
                                                    <th scope="col" class="text-nowrap text-center">ស្ថានភាពបណ្តឹង
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cases as $case)
                                                    <tr class="border-bottom-success">
                                                        <td class="text-center">
                                                            <label class="fw-bold">
                                                                {{ $cases->firstItem() + $loop->iteration - 1 }}
                                                            </label>
                                                        </td>

                                                        <td class="text-nowrap text-center">
                                                            <label
                                                                class="form-label m-0">កាលបរិច្ឆេទបណ្តឹង</label><br />
                                                            <span class="text-danger fw-bold">
                                                                [{{ date2Display($case->case_date) }}]
                                                            </span><br /><br />

                                                            <label class="form-label blue fw-bold">
                                                                *ការិយាល័យទី
                                                                <span>{{ Num2Unicode(showCaseDomainID($case->id)) }}</span>
                                                            </label>
                                                        </td>

                                                        {{-- ដើមបណ្តឹង / ចុងបណ្តឹង --}}
                                                        @if ($case->case_type_id == 1)
                                                            <td class="text-center text-nowrap">
                                                                <label class="form-label fw-bold text-danger">
                                                                    {{ $case->caseDisputant->disputant->name }}
                                                                </label>
                                                            </td>
                                                            <td class="text-center">
                                                                @if ($case->company)
                                                                    <label class="form-label fw-bold blue">
                                                                        {{ $case->company->company_name_khmer }}
                                                                    </label>
                                                                @endif
                                                            </td>
                                                        @else
                                                            <td class="fw-bold text-info">
                                                                {{ optional($case->company)->company_name_khmer }}
                                                            </td>
                                                            <td class="fw-bold text-danger">
                                                                {{ $case->caseDisputant->disputant->name }}
                                                            </td>
                                                        @endif

                                                        {{-- អ្នកផ្សះផ្សា --}}
                                                        <td class="text-nowrap">
                                                            <label class="form-label fw-bold text-danger">
                                                               {!! getCaseOfficer($case->id, 0, 6) !!}
                                                            </label>
                                                        </td>

                                                        {{-- កម្មវត្ថុបណ្តឹង --}}
                                                        <td>
                                                            <label class="form-label fw-bold blue">
                                                                <span class="blue fw-bold">
                                                                     {{ Str::limit($case->case_objective_des) }}
                                                                </span>
                                                            </label>
                                                        </td>

                                                        {{-- ស្ថានភាព --}}
                                                        <td class="text-nowrap text-center">
                                                            @php
                                                                echo displayCaseStatus(generateCaseStatus($case));
                                                            @endphp
                                                            <br>
                                                            <a class="btn btn-success-gradien fw-bold"
                                                                href="{{ url('cases/' . $case->id) }}" target="_blank">
                                                                មើលដំណើរការបណ្ដឹង
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                        <br>
                                        <div class="pagination">
                                            @if ($cases->hasPages())
                                                {!! $cases->links('pagination::bootstrap-5') !!}
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <x-slot name="moreAfterScript">
        <!-- Plugins Select2-->
        <script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>

        <script>
            $(document).ready(function() {
                $('.userID').select2();
                $('#domainID').select2();
                $('#inOutDomain').select2();
                $('#domainID').select2();
                $('#statusID').select2();
                $('#stepID').select2();


            });
        </script>
    </x-slot>
</x-admin.layout-main>
