<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/date-picker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/timepicker.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    </x-slot>
    @php
        $log6= $adata['log6'];
    @endphp
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form name="uploadFile" action="{{ $adata['url'] }}" method="POST" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="case_id" id="case_id" value="{{ $adata['case_id'] }}" >
                            <input type="hidden" name="id" id="id" value="{{ $adata['id'] }}" >
                            <input type="hidden" name="form_upload" value="normal" >
                            <input type="hidden" name="url_opt" value="{{ $adata['url_opt'] }}" >
                            <input type="hidden" name="file_name" value="{{ $adata['file_name'] }}" >
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="fw-bold">កាលបរិច្ឆេទណាត់ជួប</label>
                                    <input type="text" name="status_date" id="status_date" value="" class="form-control" data-language="en" required >
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="fw-bold">ម៉ោង</label>
                                    <div class="input-group clockpicker" data-autoclose="true">
                                        <input name="status_time" id="status_time" value="" class="form-control" type="text" data-bs-original-title="" required >
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 mt-3">
                                    <label class="fw-bold">លិខិតសុំផ្សះផ្សាឡើងវិញ</label>
                                    <input type="file" id="fileInput" name="file" required>
                                </div>
                                <div class="form-group col-sm-4 mt-4">
                                    <button type="submit" class="btn btn-success form-control">Upload</button>
                                </div>
                                <div class="form-group col-sm-4 mt-4">
                                    <a href="{{ url('cases/'.$adata['case_id']) }}" class="btn btn-info form-control">
                                        ត្រឡប់ទៅកាន់ដំណើរការបណ្ដឹង
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="moreAfterScript">
        <script type="text/javascript">
            $(document).ready(function(){
                $('#status_date').datepicker({
                });
            });
        </script>
        <!-- Plugins Datepicker-->
        <script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ rurl('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <!-- Plugins Timepicker-->
        <script src="{{ rurl('assets/js/time-picker/jquery-clockpicker.min.js') }}"></script>
        <script src="{{ rurl('assets/js/time-picker/highlight.min.js') }}"></script>
        <script src="{{ rurl('assets/js/time-picker/clockpicker.js') }}"></script>
        <!-- Plugins Select2-->
        <script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>
    </x-slot>
</x-admin.layout-main>
