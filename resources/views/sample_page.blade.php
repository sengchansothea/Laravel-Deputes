@php
    $row=$adata['inspection'];
    $row2= $row->menu2;
    $inspection_id=$row->id;
    $company_id=$row->insp_company_id;
@endphp
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        {{--        @include('layouts.test')--}}
    </x-slot>
    <form id="form_menu" method="POST" action="{{ url('inspection/garment/menu2/'.$row->id) }}" class="row g-3" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
        <input type="hidden" name="inspection_id" value="{{ $row->id }}" />
        <input type="hidden" name="insp_type" value="{{ $row->insp_type }}" />
        <div class="container-fluid" id="div_container">
            <div class="row starter-main">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header text-hanuman">
                            {!! pageHeaderGarment($row->company->company_name_khmer, $row->insp_type ) !!}
                        </div>
                        <div class="card-body">
                            <div class="card-block row">
                                <div class="col-sm-12 col-lg-12 col-xl-12">

                                </div>

                                <div class="gy-3">
                                    {{  buttonNextSave($row->lock_self_insp) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <x-slot name="moreAfterScript">
        @include('inspection.normal_garment.script.script9')
        @include('inspection.dirrty_script')
    </x-slot>
</x-admin.layout-main>





DB::beginTransaction();
try{

DB::commit();
return menuSaveRedirectGarment($request->input("btnSubmit"), 3, $inspection_id, $insp_type);
}
catch (\Exception $e) {
DB::rollback();
// something went wrong
return back()->with("message", sweetalert()->addWarning("បរាជ័យ"));
}
