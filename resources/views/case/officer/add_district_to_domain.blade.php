@php
$domainID = $adata['domainID'];
$proID = $adata['proID'];
$proName = $adata['proName'];
$arrDists = $adata['arrDists'];
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
                    <form id="addDistricts" action="{{ url('domain/distict/add/'.$domainID.'/'.$proID) }}" method="GET">
                        @csrf
                    <div class="card-header">
                        <h5>ខេត្ត/រាជធានី: <label class="form-label text-danger">{{ $proName }}</label> <label class="form-label text-warning">(ការិយាល័យទី{{ Num2Unicode($domainID) }})</label></h5>
                    </div>
                    <div class="card-body">
                        @if(count($arrDists) > 0)
                            <input name="subject_all" class="select-all checkbox_animated" type="checkbox">
                            <label class="form-label fw-bold"  style="font-size: 18px">
                                ទាំងអស់
                            </label><br/><br/>
                            @foreach($arrDists as $disID => $disName)
                            <div class="row animate-chk">
                                <div class="col d-block">
                                    <input class="checkbox_animated select_dist" name="distname[]" value="{{ $disID }}" id="distname_{{ $disID }}" type="checkbox">
                                    <label class="form-label text-info fw-bold" for="distname_{{ $disID }}" style="font-size: 18px">
                                        {{ $disName }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info add-btn fw-bold">
                                    បន្ថែមស្រុក/ខណ្ឌ/ក្រុង
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
                $('.select-all').on('change', function() {
                    if(this.checked){
                        $('.select_dist').prop('checked', true);
                    }else{
                        $('.select_dist').prop('checked', false);
                    }
                });
            });
        </script>

    </x-slot>
</x-admin.layout-main>
