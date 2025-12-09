@php
    use Illuminate\Support\Str;
    $disputant = $adata['disputant'];
    $cases = $disputant->case;
@endphp
{{--{{ dd($cases->count()) }}--}}
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
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
                        <div class="row col-12 fw-bold d-flex">
                            <label class="form-label">កម្មករនិយោជិត៖
                                <span class="text-danger fw-bold text-hanuman-18">
                                    {{ $disputant->name }}
                                </span>
                            </label>
                        </div>
                        <div class="bg-primary text-center div_number text-hanuman-20 mt-3">
                            ប្រវត្តិក្នុងគូវិវាទសរុប : {{ $cases->count() }}
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
                                            <th scope="col">កាលបរិច្ឆេទ</th>
                                            <th scope="col" class="text-center">សហគ្រាស គ្រឹះស្ថាន</th>
                                            <th scope="col" class="text-center">មន្ត្រីផ្សះផ្សា</th>
                                            <th scope="col" class="text-center">កម្មវត្ថុបណ្តឹង</th>
                                            <th scope="col" class="text-center">ស្ថានភាពបណ្តឹង</th>
                                        </tr>
                                        </thead>
                                        @php
                                            $index = 1;
                                        @endphp
                                        <tbody>
{{--                                        {{ dd($cases) }}--}}
                                        @foreach( $cases as $case )
{{--                                            {{ dd($case) }}--}}
                                                <tr class="border-bottom-primary">
                                                <td>
                                                    <label class="form-label fw-bold">{{ Num2Unicode($index) }}</label>
                                                </td>
                                                <td class="text-nowrap">
                                                    <label class="form-label m-0">កាលបរិច្ឆេទជម្លោះ</label><br/>
                                                    <span class="text-danger fw-bold">[{{ $case->case_date }}]</span>
                                                    @if(!empty($case->case_date_entry))
                                                    <br/><br/>
                                                    <label class="form-label m-0">កាលបរិច្ឆេទប្តឹង</label><br/>
                                                    <span class="text-danger fw-bold">[{{ $case->case_date_entry }}]</span>
                                                    @endif
                                                </td>
                                                <td class="text-center fw-bold">
                                                    <label class="form-label blue">
                                                        {{ $case->company->company_name_khmer }}
                                                    </label>
                                                    @if(!empty($case->company->company_name_latin))
                                                        <br/>
                                                        <label class="form-label text-info">{{ $case->company->company_name_latin }}</label>
                                                    @endif
                                                </td>
                                                <td class="text-nowrap text-center">
                                                    <label class="form-label fw-bold text-danger">
                                                        {!! getCaseOfficerName($case->id,0,6,"") !!}
                                                    </label>
                                                </td>
                                                <td>
                                                    <label class="form-label fw-bold">{{ Str::limit($case->case_objective_des) }}</label>
                                                </td>
                                                    <td class="text-nowrap text-center">
                                                        @php
                                                            $caseStatus = generateCaseStatus($case);
    //                                                        print_r ($caseStatus);
                                                            echo displayCaseStatus($caseStatus);
                                                        @endphp
                                                        <br>
                                                        <a class="btn btn-success-gradien custom fw-bold" href="{{ url('cases/'.$case->id) }}" title="មើលដំណើរការបណ្ដឹង" target="_blank">មើលដំណើរការបណ្ដឹង</a>
                                                    </td>
                                            </tr>
                                            @php $index ++ @endphp
                                        @endforeach
                                        </tbody>
                                    </table>
{{--                                    <br>--}}
{{--                                    <div class="pagination" >--}}
{{--                                        @if( $cases->hasPages() )--}}
{{--                                            {!! $$cases->links('pagination::bootstrap-5') !!}--}}
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
</x-admin.layout-main>
