@php
    use Illuminate\Support\Str;
    $officer = $adata['officer'];
    $cases = $adata['cases'];
    $officerType = $adata['officerType'];
@endphp
{{--{{ dd($officer) }}--}}
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
                            <label class="form-label">មន្ត្រីផ្សះផ្សាវិវាទ៖
                                <span class="text-danger fw-bold text-hanuman-18">{{ $officer->officer_name_khmer }}
                                    @if(!empty($officer->officer_name_latin)) <span class="text-success">({{ $officer->officer_name_latin }})</span> @endif
                                </span>
                            </label>
                            @if($officerType == 0)
                                <label class="form-label">ជាអ្នកផ្សះផ្សា៖ <span class="text-danger fw-bold text-hanuman-18">{{ count($officer->caseOfficerSolvers) }}</span> <span class="text-success">[បណ្តឹង]</span></label>
                                <label class="form-label">ជាលេខាកត់ត្រា៖ <span class="text-danger fw-bold text-hanuman-18">{{ count($officer->caseOfficerNoters) }}</span> <span class="text-success">[បណ្តឹង]</span></label>
                            @elseif($officerType == 1)
                                <label class="form-label">តួនាទីជា៖ <span class="text-danger fw-bold text-hanuman-18">អ្នកផ្សះផ្សា</span></label>
                            @elseif($officerType == 2)
                                <label class="form-label">តួនាទីជា៖ <span class="text-danger fw-bold text-hanuman-18">អ្នកកត់ត្រា</span></label>
                            @endif
                        </div>
                        <div class="bg-primary text-center div_number text-hanuman-20 mt-3">
                            ប្រវត្តិអន្តរាគមន៍ក្នុងវិវាទសរុប : {{ $cases->count() }}
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
                                            <th scope="col" class="text-center">ដើមបណ្តឹង</th>
                                            <th scope="col" class="text-center">ចុងបណ្តឹង</th>
                                            <th scope="col" class="text-center">កម្មវត្ថុបណ្តឹង</th>
                                            <th scope="col" class="text-nowrap text-center">ស្ថានភាពបណ្តឹង</th>
                                        </tr>
                                        </thead>
                                        @php
                                            $index = 1;
                                        @endphp
                                        <tbody>
                                        @foreach( $cases as $row )
                                            @if(!empty($row->case))
                                                @php
                                                    $case = $row->case;
                                                @endphp
                                                <tr class="border-bottom-success">
                                                    <td><label class="form-label fw-bold">{{ $index }}</label></td>
                                                    <td class="text-nowrap">
                                                        <label class="form-label m-0">កាលបរិច្ឆេទជម្លោះ</label><br/>
                                                        <span class="text-danger fw-bold">[{{ date2Display($case->case_date) }}]</span>
                                                        @if(!empty($case->case_date_entry))
                                                            <br/><br/>
                                                            <label class="form-label m-0">កាលបរិច្ឆេទប្តឹង</label><br/>
                                                            <span class="text-info fw-bold">[{{ date2Display($case->case_date_entry) }}]</span>
                                                        @endif
                                                    </td>
                                                    {{--                                                កម្មករដើមបណ្តឹង ក្រុមហ៊ុនចុងបណ្តឹង (កម្មករប្តឹងក្រុមហ៊ុន)--}}
                                                    @if($case->case_type_id == 1)
                                                        <td class="fw-bold text-danger text-center">{{ $case->caseDisputant->disputant->name }}</td>
                                                        <td class="fw-bold text-info text-center">{{ $case->company->company_name_khmer }}</td>
                                                        {{--                                                ក្រុមហ៊ុនដើមបណ្តឹង កម្មករចុងបណ្តឹង (ក្រុមហ៊ុនប្តឹងកម្មករ)--}}
                                                    @elseif($case->case_type_id == 2)
                                                        <td class="fw-bold text-info">{{ $case->company->company_name_khmer }}</td>
                                                        <td class="fw-bold text-danger">{{ $case->caseDisputant->disputant->name }}</td>
                                                    @endif

                                                    <td class="fw-bold">{{ Str::limit($case->case_objective_des) }}</td>
                                                    <td class="text-nowrap text-center">
                                                        @php
                                                            $caseStatus = generateCaseStatus($case);
                                                            echo displayCaseStatus($caseStatus);
                                                        @endphp
                                                        <br>
                                                        <a class="btn btn-success-gradien custom fw-bold" href="{{ url('cases/'.$case->id) }}" title="មើលដំណើរការបណ្ដឹង" target="_blank">មើលដំណើរការបណ្ដឹង</a>
                                                    </td>
                                                </tr>
                                                @php $index ++ @endphp
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <br>
{{--                                    <div class="pagination" >--}}
{{--                                        @if( $cases->hasPages() )--}}
{{--                                            {!! $cases->links('pagination::bootstrap-5') !!}--}}
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
