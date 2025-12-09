@php
    use Illuminate\Support\Str;
    $cases = $adata['cases'];
    $user = $adata['user'];
@endphp
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
{{--                        @if($user)--}}
{{--                        <div class="row col-12 fw-bold d-flex">--}}
{{--                            <label class="form-label">ឈ្មោះអ្នកប្រើប្រាស់៖--}}
{{--                                <span class="text-danger fw-bold text-hanuman-18">--}}
{{--                                    {{ $user->k_fullname }}--}}
{{--                                </span>--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        @endif--}}
                        <div class="text-hanuman">
                            @if($adata['opt_search'] == "quick")
                                @include("user.quick-search")
                            @else
                                @include("user.advance-search")
                            @endif
                        </div>
                        <div class="bg-primary text-center div_number text-hanuman-20 mt-3">
                            ចំនួនបណ្តឹងដែលបានបញ្ចូល : {{ $cases->total() }}
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="card-block row">
                            <div class="col-sm-12 col-lg-12 col-xl-12">
                                @if($cases->total() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead class="table-light">
                                        <tr>
                                            <th scope="col">{{__('general.k_no')}}</th>
                                            <th scope="col" class="text-center">បណ្តឹង</th>
                                            <th scope="col" class="text-center text-nowrap">ដើមបណ្តឹង</th>
                                            <th scope="col" class="text-center text-nowrap">ចុងបណ្តឹង</th>
                                            <th scope="col" class="text-nowrap">អ្នកផ្សះផ្សា</th>
                                            <th scope="col" class="text-center">កម្មវត្ថុបណ្តឹង</th>
                                            <th scope="col" class="text-nowrap text-center">ស្ថានភាពបណ្តឹង</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach( $cases as $case )
                                            <tr class="border-bottom-success">
                                                <td class="text-center">
                                                    <label class="fw-bold">{{ $adata['cases']->firstItem() + $loop->iteration - 1  }}</label>
                                                </td>
                                                <td class="text-nowrap text-center">
                                                    <label class="form-label m-0">កាលបរិច្ឆេទបណ្តឹង</label><br/>
                                                    <span class="text-danger fw-bold">[{{ date2Display($case->case_date) }}]</span><br/><br/>
{{--                                                    @if(!empty($case->case_date_entry))--}}
{{--                                                        <br/><br/>--}}
{{--                                                        <label class="form-label m-0">កាលបរិច្ឆេទប្តឹង</label><br/>--}}
{{--                                                        <span class="text-info fw-bold">[{{ date2Display($case->case_date_entry) }}]</span>--}}
{{--                                                    @endif--}}

{{--                                                    @php--}}
{{--                                                        $caseYear = !empty($case->case_date) ? date2Display($case->case_date,'Y') : myDate('Y');--}}
{{--                                                        $show_file= showFile(1, $case->case_file, pathToDeleteFile('case_doc/form1/'.$caseYear."/"), "notdelete", "tbl_case", "id", $case->id,  "case_file", "");--}}
{{--                                                        if($show_file){--}}
{{--                                                            echo '<br/><br/><span class="fw-bold">ឯកសារពាក្យបណ្ដឹង</span>'."<br/".$show_file;--}}
{{--                                                        }--}}
{{--                                                    @endphp--}}
                                                    <label class="form-label blue fw-bold">*ការិយាល័យទី<span class="">{{ Num2Unicode(showCaseDomainID($case->id)) }}</span></label><br/>
                                                </td>
                                                {{--                                                កម្មករដើមបណ្តឹង ក្រុមហ៊ុនចុងបណ្តឹង (កម្មករប្តឹងក្រុមហ៊ុន)--}}
                                                @if($case->case_type_id == 1)
                                                <td class="text-center text-nowrap">
                                                    <label class="form-label fw-bold text-danger">{{ $case->caseDisputant->disputant->name }}</label>
                                                </td>
                                                <td class="text-center">
                                                    @if(!empty($case->company))
                                                    <label class="form-label fw-bold blue">{{ $case->company->company_name_khmer }}</label>
                                                    @if(!empty($case->company->company_name_latin))
                                                        <br/><label class="form-label fw-bold blue">{{ $case->company->company_name_latin }}</label>
                                                    @endif
                                                    @endif
                                                </td>
                                                    {{--                                                ក្រុមហ៊ុនដើមបណ្តឹង កម្មករចុងបណ្តឹង (ក្រុមហ៊ុនប្តឹងកម្មករ)--}}
                                                @elseif($case->case_type_id == 2)
                                                <td class="fw-bold text-info">@if(!empty($case->company)){{ $case->company->company_name_khmer }}@endif</td>
                                                <td class="fw-bold text-danger">{{ $case->caseDisputant->disputant->name }}</td>
                                                @endif
                                                <td class="text-nowrap">
                                                    <label class="form-label fw-bold text-danger">
                                                        {!! getCaseOfficerName($case->id) !!}
                                                    </label><br/>
                                                </td>
                                                <td>
{{--                                                    @php--}}
{{--                                                        $userInCase = getUserByCaseID($case->id);--}}
{{--                                                    @endphp--}}
                                                    <label class="form-label fw-bold blue">
                                                        {{ Str::limit($case->case_objective_des) }}
                                                    </label><br/>
{{--                                                    @php--}}
{{--                                                        $arrOffices = [--}}
{{--                                                                "20" => "ការិយាល័យទី១",--}}
{{--                                                                "21" => "ការិយាល័យទី២",--}}
{{--                                                                "22" => "ការិយាល័យទី៣",--}}
{{--                                                                "23" => "ការិយាល័យទី៤",--}}
{{--                                                            ];--}}
{{--                                                        $select = showSelect('userID', $arrOffices, old('officerID'), "userID");--}}
{{--                                                        $button = '<button type="submit" class="btn btn-success form-control fw-bold">បញ្ជូន</button>';--}}
{{--                                                    @endphp--}}
{{--                                                    <form name="frmChangeUserInCase" action="{{ url('change/user/case') }}" method="POST">--}}
{{--                                                        @method('PUT')--}}
{{--                                                        @csrf--}}
{{--                                                        <input type="hidden" name="caseID" value="{{ $case->id }}" >--}}
{{--                                                        <div class="row col-12">--}}
{{--                                                            <div class="form-group col-sm-7">--}}
{{--                                                                {!! $select !!}--}}
{{--                                                            </div>--}}
{{--                                                            <div class="form-group col-sm-5">--}}
{{--                                                                {!! $button !!}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </form>--}}
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
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    <div class="pagination" >
                                        @if( $cases->hasPages() )
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
