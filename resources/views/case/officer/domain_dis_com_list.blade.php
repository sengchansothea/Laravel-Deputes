@php
$domains = $adata['domain'];
$excludedProIDs = $adata['excludedProIDs'];

@endphp
{{--{{ dd(arrayOfficerCaseInHand()) }}--}}
{{--{{ dd($domain_id) }}--}}
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
        <style>
            .card-block .table-responsive .table-bordered td {
                vertical-align: top;
            }

            .table-bordered thead, .table-bordered tbody, .table-bordered tfoot, .table-bordered tr, .table-bordered td, .table-bordered th {
                border-color: #1ea6ec;
            }
        </style>
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header text-hanuman">
                        @include("case.officer.filter_domain")
                    </div>
                    <div class="card-body">
                        <div class="card-block row">
                            <div class="col-sm-12 col-lg-12 col-xl-12">
                                <div class="table-responsive">
                                    <table class="table  table-bordered table-hover">
                                        <thead class="table-primary">
                                        <tr>
                                            <th scope="col" class="text-center">ការិយាល័យ</th>
                                            <th scope="col" class="text-center">ខេត្ត/រាជធានី</th>
                                            <th scope="col" class="text-center">ស្រុក/ខណ្ឌ និង ឃុំ/សង្កាត់</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $index = 1;
                                        @endphp

                                        @foreach( $domains as $domain )
                                            @php
                                                $numPro = $domain->domainProvince->count();
                                                $proIndex = 1;
                                            @endphp
                                            @if($numPro > 0)
                                                @foreach($domain->domainProvince as $dPro)
                                                    @php
                                                        $proStr = "ខេត្ត";
                                                        $disStr = "ក្រុង/ស្រុក";
                                                        $comStr = "សង្កាត់/ឃុំ";
                                                        if($dPro->province_id == 12){
                                                            $proStr = "រាជធានី";
                                                            $disStr = "ខណ្ឌ";
                                                            $comStr = "សង្កាត់";
                                                        }
                                                    @endphp
                                                    <tr class="col-sm-12">
                                                        @if($proIndex <= 1)
                                                            <td class="col-sm-3 text-center justify-content-center" rowspan="{{ count($domain->domainProvince) }}">
                                                                <label class="form-label fw-bold text-danger">{{ $domain->domain_name }}</label>
                                                                <form action="{{ url('domain/province/add/'.$domain->id) }}" method="GET">
                                                                    @csrf
                                                                    {!! showSelect("k_province_".$domain->id,arrayProvincesExcludedByProID($excludedProIDs, 1, "0", "ជ្រើសរើសខេត្ត/រាជធានី",1), old("k_province_".$domain->id, request("k_province_".$domain->id))) !!}
                                                                    <button type="submit" class="btn btn-danger add-btn p-2 mt-2 fw-bold">
                                                                        បន្ថែមខេត្ត
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        @endif
                                                        <td class="col-sm-3">
                                                            @php
                                                                $excludedDisIDs = getAllDisIDInDomain($dPro->province_id);
                                                            @endphp
                                                            @if(count($dPro->domainDistrict) > 0)
                                                                <label class="fw-bold text-warning mb-2">- {{ $dPro->province->pro_khname }}</label>
                                                            @else
                                                                <div class="d-flex">
                                                                    <form id="deleteProForm" action="{{ url('domain/province/delete/'.$dPro->domain_id.'/'.$dPro->province_id) }}" method="GET">
                                                                        @csrf
                                                                        <button type="button" class="btn btn-danger delete-btn p-0"  title="លុប{{ $proStr }} {{ $dPro->province->pro_khname }} ចេញពីការិយាល័យទី {{ Num2Unicode($domain->id) }}">
                                                                            <i data-feather="trash-2"></i>
                                                                        </button>
                                                                    </form>{!! nbs(1) !!}
                                                                    <label class="fw-bold text-success mb-2">{{ $dPro->province->pro_khname }}</label>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <a class="btn btn-success add-btn p-2 mt-2 fw-bold" href="{{ url('domain/distict/add/form/'.$dPro->domain_id.'/'.$dPro->province_id) }}">បន្ថែមស្រុក/ខណ្ឌ</a>
                                                            </div>
                                                        </td>
                                                        <td class="">
                                                            <div class="row col-sm-12 mb-3 fw-bold">
                                                                <div class="col-sm-6">ខណ្ឌ/ក្រុង/ស្រុក</div>
                                                                <div class="col-sm-6">សង្កាត់/ឃុំ</div>
                                                            </div>
                                                            @if(count($dPro->domainDistrict) > 0)
                                                                @foreach($dPro->domainDistrict as $dDis)
                                                                    @if($dDis->domain_id == $dPro->domain_id)
                                                                        <div class="row col-sm-12 mb-3">
                                                                            @if(count($dDis->domainCommune) > 0)
                                                                                <div class="col-sm-6 text-info">
                                                                                    <div class="d-flex">
                                                                                        <label class="form-label fw-bold">- {{ $dDis->district->dis_khname }}</label>
                                                                                    </div>
                                                                                    @php
                                                                                        $excludedComIDs = getAllComIDInDomain($dDis->province_id, $dDis->district_id);
                                                                                    @endphp
                                                                                    {{--                                                                            {{ dd($dis->district->dis_id) }}--}}
                                                                                    <form action="{{ url('domain/commune/add/form/'.$dDis->domain_id.'/'.$dDis->province_id.'/'.$dDis->district_id) }}" method="GET">
                                                                                        @csrf
                                                                                        <div>
                                                                                            <button type="submit" class="btn btn-info add-btn fw-bold">
                                                                                                បន្ថែមឃុំ/សង្កាត់
                                                                                            </button>
                                                                                        </div>
                                                                                    </form>
                                                                                    @push('childScript')
                                                                                        <script>
                                                                                            $(function() {
                                                                                                var id = {{ $dDis->district_id }};
                                                                                                $('#k_commune_'+id).select2();
                                                                                            });
                                                                                        </script>
                                                                                    @endpush
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    @foreach($dDis->domainCommune as $dCom)
                                                                                        @if($dCom->domain_id == $dDis->domain_id)
                                                                                            <div class="d-flex">
                                                                                                <form id="deleteCommuneForm" action="{{ url('domain/commune/delete/'.$dCom->domain_id.'/'.$dCom->province_id.'/'.$dCom->district_id.'/'.$dCom->commune_id) }}" method="GET">
                                                                                                    @csrf
                                                                                                    <button type="button" class="btn btn-danger delete-btn p-0"  title="ដក{{ $comStr }} {{ $dCom->commune->com_khname }} ចេញពីការិយាល័យទី {{ Num2Unicode($dCom->domain_id) }}">
                                                                                                        <i data-feather="trash-2"></i>
                                                                                                    </button>
                                                                                                </form>
                                                                                                {!! nbs(1) !!}
                                                                                                <label class="form-label text-info fw-bold">{{ $dCom->commune->com_khname }}</label>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </div>
                                                                            @else
                                                                                <div class="col-sm-6 text-warning">
                                                                                    <div class="d-flex">
                                                                                        <form id="" action="{{ url('domain/distict/delete/'.$dDis->domain_id.'/'.$dDis->province_id.'/'.$dDis->district_id) }}" method="GET">
                                                                                            @csrf
                                                                                            <button type="button" class="btn btn-danger delete-btn p-0"  title="ដក{{ $disStr }} {{ $dDis->district->dis_khname }} ចេញពីការិយាល័យទី {{ Num2Unicode($dDis->domain_id) }}">
                                                                                                <i data-feather="trash-2"></i>
                                                                                            </button>
                                                                                        </form>
                                                                                        {!! nbs(1) !!}
                                                                                        <label class="form-label fw-bold"> {{ $dDis->district->dis_khname }}</label>
                                                                                    </div>
                                                                                    @php
                                                                                        $excludedComIDs = getAllComIDInDomain($dDis->province_id, $dDis->district_id);
                                                                                    @endphp
                                                                                    {{--                                                                            {{ dd($dis->district->dis_id) }}--}}
                                                                                    <form action="{{ url('domain/commune/add/form/'.$dDis->domain_id.'/'.$dDis->province_id.'/'.$dDis->district_id) }}" method="GET">
                                                                                        @csrf
                                                                                        <div>
                                                                                            <button type="submit" class="btn btn-info add-btn p-2 mt-2 fw-bold">
                                                                                                បន្ថែមឃុំ/សង្កាត់
                                                                                            </button>
                                                                                        </div>
                                                                                    </form>
                                                                                    @push('childScript')
                                                                                        <script>
                                                                                            $(function() {
                                                                                                var id = {{ $dDis->district_id }};
                                                                                                $('#k_commune_'+id).select2();
                                                                                            });
                                                                                        </script>
                                                                                    @endpush
                                                                                </div>
                                                                                <div class="col-sm-6 text-warning fw-bold">- មិនកំណត់</div>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <div class="row col-sm-12 mb-3 text-danger fw-bold">
                                                                    <div class="col-sm-6">- មិនកំណត់</div>
                                                                    <div class="col-sm-6">- មិនកំណត់</div>
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @php $proIndex ++; @endphp
                                                    @php $index ++; @endphp
                                                @endforeach

                                            @endif
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
                $('#domain').select2();
                $('#k_province_1').select2();
                $('#k_province_2').select2();
                $('#k_province_3').select2();
                $('#k_province_4').select2();

                // for (let i = 0; i <= 25; i++) {
                //     $('#k_district_'+i).select2();
                // }

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
