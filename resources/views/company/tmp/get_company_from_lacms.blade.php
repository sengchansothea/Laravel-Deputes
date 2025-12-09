@php
//    $row=$adata['inspection'];
//    $row2= $row->menu2;
//    $inspection_id=$row->id;
//    $company_id=$row->insp_company_id;
@endphp
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        {{--        @include('layouts.test')--}}
    </x-slot>
    <form id="form_menu" method="POST" action="{{ url('') }}" class="row g-3" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
        <div class="container-fluid" id="div_container">
            <div class="row starter-main">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header text-hanuman">
                        </div>
                        <div class="card-body">
                            <div class="card-block row">
                                <div class="table-responsive">
                                    @for($i = 1; $i<=10; $i++)
                                        <li>
                                            <a href="{{ url('company/get_company_from_lacms/'.$i) }}">
                                                Get Company Page {{ $i }}
                                            </a>
                                        </li>
                                    @endfor
                                    @if($adata['page'] > 0)
                                        {!! saveApiCompanyList2DB($adata['page'], $adata['perPage']) !!}
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <x-slot name="moreAfterScript">
{{--        @include('inspection.dirrty_script')--}}
    </x-slot>
</x-admin.layout-main>
