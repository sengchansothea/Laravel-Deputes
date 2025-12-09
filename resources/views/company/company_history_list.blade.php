@php
    use Illuminate\Support\Str;
    $company = $adata['company'];
    $cases = $company->cases;
@endphp
{{--{{dd($cases)}}--}}
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
                            <label class="form-label">រោងចក្រ សហគ្រាស៖
                                <span class="text-danger fw-bold text-hanuman-18">{{ $company->company_name_khmer }}
                                    @if(!empty($company->company_name_latin)) <span class="text-info">[{{ $company->company_name_latin }}]</span> @endif
                                </span>
                            </label>
                        </div>
                        <div class="bg-primary text-center div_number text-hanuman-20 mt-4">
                            ប្រវត្តិក្នុងគូវិវាទសរុប : {{ number_format($cases->count()) }}
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
                                            <th scope="col">កាលបរិច្ឆេទប្តឹង</th>
                                            <th scope="col">ដើមបណ្តឹង</th>
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
                                                    <label class="form-label text-warning fw-bold">{{ Date2Display($case->case_date) }}</label>
                                                </td>
                                                {{-- {{ dd($case->caseDisputant->attendant_type_id) }} --}}
{{--                                                {{ dd($case->caseDisputant->disputant) }}--}}
                                                @if($case->caseDisputant->attendant_type_id == 1)
                                                <td class="text-nowrap fw-bold text-danger">{{ $case->caseDisputant->disputant->name }}</td>
{{--                                                <td class="text-nowrap fw-bold text-info">{{ $case->company->company_name_khmer }}</td>--}}
                                                @elseif($case->caseDisputant->attendant_type_id == 2)
                                                <td class="text-nowrap">{{ $case->company->company_name_khmer }}</td>
                                                <td class="text-nowrap">{{ $case->caseDisputant->disputant->name }}</td>
                                                @endif
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
