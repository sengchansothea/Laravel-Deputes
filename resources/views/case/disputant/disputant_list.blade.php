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
                    <div class="card-body">
                        <div class="text-hanuman">
                            @if($adata['opt_search'] == "quick")
                                @include("case.disputant.quick-search")
                            @else
                                @include("case.disputant.advance-search")
                            @endif
                            <div class="bg-primary text-center div_number text-hanuman-20 mt-4">
                                ចំនួនគូវិវាទសរុប : {{ number_format($adata['total']) }}
                            </div>
                        </div>
                        <div class="card-block row">
                            <div class="col-sm-12 col-lg-12 col-xl-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead class="table-light">
                                        <tr>
                                            <th scope="col">{{__('general.k_no')}}</th>
                                            <th scope="col">ឈ្មោះ</th>
                                            <th scope="col">ភេទ</th>
                                            <th scope="col">ថ្ងៃខែឆ្នាំកំណើត</th>
                                            <th scope="col">សញ្ជាតិ</th>
                                            <th scope="col">ទីកន្លែងកំណើត</th>
                                            <th scope="col" class="text-center">សកម្មភាព</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach( $adata['disputants'] as $disputant )
{{--                                            {{ dd($disputant) }}--}}
                                            <tr class="border-bottom-primary">
                                                <td>
                                                    <label class="form-label fw-bold">{{ Num2Unicode($adata['disputants']->firstItem() + $loop->iteration - 1) }}</label>
                                                </td>
                                                <td class="text-nowrap fw-bold">
                                                    <label class="form-label text-danger">@if(!empty($disputant->name)){{$disputant->name}} @endif</label><br/>
                                                    <label class="form-label text-danger">@if(!empty($disputant->name_latin)){{$disputant->name_latin}} @endif</label><br/>
                                                    <label class="form-label">ប្រវត្តិក្នុងវិវាទ @if(count($disputant->case) > 0) <a href="{{ route('disputant.show', $disputant->id) }}" class="text-danger" title="ចុចមើលប្រវត្តិក្នុងវិវាទ" target="_blank">[{{ Num2Unicode(count($disputant->case)) }}]</a> @else <span class="text-info">[គ្មាន]</span> @endif</label>
                                                </td>
                                                <td class="text-nowrap">
                                                    @if($disputant->gender == 1)
                                                        <label class="form-label fw-bold">ប្រុស</label>
                                                    @elseif($disputant->gender == 2)
                                                        <label class="form-label fw-bold">ស្រី</label>
                                                    @endif
                                                </td>
                                                <td class="text-nowrap">
                                                    <label class="form-label fw-bold">{{ date2Display($disputant->dob) }}</label>

                                                </td>
                                                <td class="text-nowrap">
                                                @if(!empty($disputant->disNationality))
                                                    <label class="form-label fw-bold">{{ $disputant->disNationality->nationality_kh }}</label><br/>
                                                    @if(!empty($disputant->id_number))
                                                    <span>អត្តសញ្ញាណបណ្ណ៖ <label class="form-label text-danger fw-bold">{{ $disputant->id_number }}</label></span>
                                                    @endif
                                                    @if(!empty($disputant->phone_number))
                                                        <br/>
                                                        <span>លេខទូរសព្ទ៖ <label class="form-label text-danger fw-bold">{{ $disputant->phone_number }}</label></span>
                                                    @endif
                                                @endif
                                                </td>
                                                <td class="text-nowrap">
                                                    @if($disputant->nationality == 33)
                                                        @if(!empty($disputant->pobCommune->com_khname))
                                                            <label class="form-label">- [ឃុំ/សង្កាត់] <span class="text-danger fw-bold">{{ $disputant->pobCommune->com_khname }}</span></label><br/>
                                                        @endif
                                                        @if(!empty($disputant->pobDistrict->dis_khname))
                                                            <label class="form-label">- [ស្រុក/ខណ្ឌ] <span class="text-danger fw-bold">{{ $disputant->pobDistrict->dis_khname }}</span></label><br/>
                                                        @endif
                                                        @if(!empty($disputant->pobProvince->pro_khname))
                                                            <label class="form-label">- [រាជធានី/ខេត្ត] <span class="text-danger fw-bold">{{ $disputant->pobProvince->pro_khname }}</span></label><br/>
                                                        @endif
                                                    @else
                                                        @if(!empty($disputant->pob_address_abroad))
                                                            <label class="form-label">- (ក្រៅប្រទេស) <span class="text-danger fw-bold">{{ $disputant->pob_address_abroad }}</span></label>
                                                        @endif
                                                    @endif

                                                </td>
                                                <td>
                                                    <div class="pb-2">
                                                        <a href="{{ route('disputant.edit', $disputant->id) }}" class="btn btn-primary text-nowrap form-control fw-bold" title="កែប្រែពត៌មានគូវិវាទ" target="_blank">
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
                                        @if( $adata['disputants']->hasPages() )
                                            {!! $adata['disputants']->links('pagination::bootstrap-5') !!}
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
</x-admin.layout-main>
