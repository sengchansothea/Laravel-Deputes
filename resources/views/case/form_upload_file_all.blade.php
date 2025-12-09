<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        {{--        @include('layouts.test')--}}
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form name="uploadFile" action="{{ $adata['url'] }}" method="POST" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="case_id" id="case_id" value="{{ $adata['case_id'] }}" >
                            <input type="hidden" name="case_type" id="case_type" value="{{ $adata['case_type'] }}" >
                            <input type="hidden" name="case_year" id="case_year" value="{{ $adata['case_year'] }}" >
                            <input type="hidden" name="id" id="id" value="{{ $adata['id'] }}" >
                            <input type="hidden" name="form_upload" value="normal" >
                            <input type="hidden" name="url_opt" value="{{ $adata['url_opt'] }}" >
                            <input type="hidden" name="file_name" value="{{ $adata['file_name'] }}" >
                            <div class="row">
                                <div class="form-group col-sm-4 mt-4">
                                    <input type="file" id="file" name="file" required>
                                </div>
                                @php
                                    $urlBack = url('cases/'.$adata['case_id']);
                                    if($adata['case_type'] == 3){
                                        $urlBack = url('collective_cases/'.$adata['case_id']);
                                    }
                                @endphp
                                <div class="form-group col-sm-4 mt-4">
                                    <a href="{{ $urlBack }}" class="btn btn-info form-control fw-bold">
                                        ត្រឡប់ទៅកាន់ដំណើរការបណ្ដឹង
                                    </a>
                                </div>
                               
                                <div class="form-group col-sm-4 mt-4">
                                    <button type="submit" class="btn btn-success form-control fw-bold">Upload</button>
                                </div>
                            </div><br/>
                            <div class="row">
                                @error('file')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout-main>
