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
                    <form name="frmSearch" action="{{ route('company.index') }}" method="GET">
                        <div class="card-header text-hanuman">
                            @if($adata['opt_search'] == "quick")
                                @include("company.quick-search")
                            @else
                                @include("company.advance-search")
                            @endif
                            <div class="bg-primary text-center div_number text-hanuman-20 mt-4">
                                ចំនួនរោងចក្រ សហគ្រាសសរុប : {{ number_format($adata['total']) }}
                            </div>
                        </div>
                    </form>
                    <div class="card-body">
                        <div class="card-block row">
                            <div class="col-sm-12 col-lg-12 col-xl-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead class="table-light">
                                        <tr>
                                            <th scope="col">{{__('general.k_no')}}</th>
                                            <th scope="col">ឈ្មោះរោងចក្រ សហគ្រាស</th>
                                            <th scope="col">សកម្មភាពសេដ្ឋកិច្ច</th>
                                            <th scope="col" class="text-nowrap">កាលបរិច្ឆេទ</th>
                                            <th scope="col" class="text-nowrap">អាស័យដ្ឋាន</th>
                                            <th scope="col">សកម្មភាព</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach( $adata['companies'] as $com )
                                            @php
                                                $cases = $com->cases;
                                            @endphp
                                            <tr class="border-bottom-primary">
                                                <td>{{ $adata['companies']->firstItem() + $loop->iteration - 1 }}</td>
                                                <td class="">
                                                @if(!empty($com->company_name_khmer))
                                                    <label class="form-label text-primary fw-bold">{{ $com->company_name_khmer }}</label><br/>
                                                @endif
                                                @if(!empty($com->company_name_latin))
                                                    <label class="form-label text-warning fw-bold">{{ $com->company_name_latin }}</label><br/>
                                                @endif
                                                @if(!empty($com->company_phone_number))
                                                    <label class="form-label">លេខទូរសព្ទៈ <span class="text-danger fw-bold">{{ $com->company_phone_number }}</span></label><br/>
                                                @endif
                                                @if(!empty($com->nssf_number))
                                                    <label class="form-label">បសសៈ <span class="text-danger fw-bold">{{ $com->nssf_number }}</span></label><br/>
                                                @endif
                                                @if(!empty($com->company_register_number))
                                                    <label class="form-label">លេខចុះបញ្ជីៈ <span class="text-danger fw-bold">{{ $com->company_register_number }}</span></label><br/>
                                                @endif
                                                @if(!empty($com->company_tin))
                                                    <label class="form-label">លេខ TIN <span class="text-danger fw-bold">{{ $com->company_tin }}</span></label><br/>
                                                @endif
                                                <label class="form-label fw-bold">ប្រវត្តិក្នុងវិវាទ @if($cases->count() > 0) <a href="{{ route('company.show', $com->id) }}" class="text-danger" title="ចុចមើលប្រវត្តិក្នុងវិវាទ" target="_blank">[{{ $cases->count() }}]</a> @else <span class="text-info">[គ្មាន]</span> @endif</label>
                                                </td>
                                                <td>
                                                @if(!empty($com->businessActivity))
                                                    <label class="form-label">សកម្មភាពសេដ្ឋកិច្ចៈ <span class="text-primary fw-bold">{{ $com->businessActivity->bus_khmer_name }}</span></label><br/>
                                                @endif
                                                @if(!empty($com->first_business_act))
                                                    <label class="form-label">សកម្មភាពអាជីវកម្មចម្បងៈ <span class="text-primary fw-bold">{{ $com->first_business_act }}</span></label><br/>
                                                @endif
                                                @if(!empty($com->cSIC1))
                                                    <label class="form-label">កម្រិតទី១ៈ <span class="text-primary fw-bold fw-bold">{{ $com->cSIC1->description_kh }}</span></label><br/>
                                                @endif
                                                @if(!empty($com->cSIC2))
                                                    <label class="form-label">កម្រិតទី២ៈ <span class="text-primary fw-bold fw-bold">{{ $com->cSIC2->description_kh }}</span></label><br/>
                                                @endif
                                                @if(!empty($com->cSIC3))
                                                    <label class="form-label">កម្រិតទី៣ៈ <span class="text-primary fw-bold">{{ $com->cSIC3->description_kh }}</span></label><br/>
                                                @endif
                                                @if(!empty($com->cSIC4))
                                                    <label class="form-label">កម្រិតទី៤ៈ <span class="text-primary fw-bold">{{ $com->cSIC4->description_kh }}</span></label><br/>
                                                @endif
                                                @if(!empty($com->cSIC5))
                                                    <label class="form-label">កម្រិតទី៥ៈ <span class="text-primary fw-bold">{{ $com->cSIC5->description_kh }}</span></label><br/>
                                                @endif
                                                @if($com->company_type_id > 0)
                                                    <label class="form-label">ប្រភេទសហគ្រាសៈ <span class="text-primary fw-bold">{{ $com->companyType->company_type_name }}</span></label><br/>
                                                @endif
                                                </td>
                                                <td class="text-nowrap">
                                                    <label class="form-label mb-0">បើកសហគ្រាស</label><br/>
                                                    @if(!empty($com->open_date))
                                                        <span class="text-success fw-bold">[{{ date2Display($com->open_date) }}]</span><br/><br/>
                                                    @else
                                                        <span class="text-danger fw-bold">[គ្មានពត៌មាន]</span><br/><br/>
                                                    @endif
                                                    <label class="form-label mb-0">ចុះបញ្ជីៈ</label><br/>
                                                    @if(!empty($com->registration_date))
                                                        <span class="text-success fw-bold">[{{ date2Display($com->registration_date) }}]</span>
                                                    @else
                                                        <span class="text-danger fw-bold">[គ្មានពត៌មាន]</span><br/><br/>
                                                    @endif
                                                </td>
                                                <td class="text-nowrap">
                                                @if(!empty($com->province->pro_khname))
                                                    <label class="form-label text-warning fw-bold">- {{ $com->province->pro_khname }}</label><br/>
                                                @endif
                                                @if(!empty($com->district->dis_khname))
                                                    <label class="form-label text-warning fw-bold">- {{ $com->district->dis_khname }}</label><br/>
                                                @endif
                                                @if(!empty($com->commune->com_khname))
                                                    <label class="form-label text-warning fw-bold">- {{ $com->commune->com_khname }}</label><br/>
                                                @endif
                                                @if(!empty($com->village->vil_khname))
                                                    <label class="form-label text-warning fw-bold">- {{ $com->village->vil_khname }}</label>
                                                @endif
                                                </td>
                                                <td>
                                                    <div class="pb-2">
                                                        <a href="{{ route('company.edit', $com->id) }}" class="btn btn-primary text-nowrap form-control fw-bold" title="កែប្រែពត៌មានគូវិវាទ" target="_blank">
                                                            កែប្រែ
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    <div class="pagination" >
                                        @if( $adata['companies']->hasPages() )
                                            {!! $adata['companies']->links('pagination::bootstrap-5') !!}
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
        <script>
        </script>
    </x-slot>
</x-admin.layout-main>
