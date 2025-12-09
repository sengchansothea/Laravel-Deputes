@php
$domainID = $adata['domainID'];
$proID = $adata['proID'];
$proName = $adata['proName'];
$disName = $adata['disName'];
$disID = $adata['disID'];
$arrComs = $adata['arrComs'];
@endphp
{{--{{ dd($proID) }}--}}
{{--{{ dd($arrDists) }}--}}
<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <form id="addDistricts" action="{{ url('domain/commune/add/'.$domainID.'/'.$proID.'/'. $disID) }}" method="GET">
                        @csrf
                    <div class="card-header">
                        <h5>សង្កាត់/ឃុំ: <label class="form-label text-danger">{{ $disName }}</label> <label class="form-label text-info">(ខេត្ត/រាជធានី: {{ $proName }})</label> <label class="form-label text-warning">(ការិយាល័យទី{{ Num2Unicode($domainID) }})</label></h5>
                    </div>
                    <div class="card-body">
                        @if(count($arrComs) > 0)
                            <input name="subject_all" class="select-all checkbox_animated" type="checkbox">
                            <label class="form-label fw-bold"  style="font-size: 18px">
                                ទាំងអស់
                            </label><br/><br/>
                            @foreach($arrComs as $comID => $comName)
                            <div class="row animate-chk">
                                <div class="col d-block">
                                    <input class="checkbox_animated select_com" name="comname[]" value="{{ $comID }}" id="comname_{{ $comID }}" type="checkbox">
                                    <label class="form-label text-info fw-bold" for="comname_{{ $comID }}" style="font-size: 18px">
                                        {{ $comName }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info add-btn fw-bold">
                                    បន្ថែមឃុំ/សង្កាត់
                                </button>
                            </div>
                        @else
                            <a class="form-label text-danger fw-bold" href="{{ route('domain.index') }}">ពុំមានស្រុក/ក្រុង/ខណ្ឌ សម្រាប់ជ្រើសរើសឡើយ</a>
                        @endif
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="moreAfterScript">
        <script>
            $(document).ready(function() {
                $(document).ready(function() {
                    $('.select-all').on('change', function() {
                        if(this.checked){
                            $('.select_com').prop('checked', true);
                        }else{
                            $('.select_com').prop('checked', false);
                        }
                    });
                });
            });
        </script>
    </x-slot>
</x-admin.layout-main>
