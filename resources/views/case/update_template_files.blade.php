@php
//    $pathToUpload = "storage/doc_template/";
//    $pathToUpload = storage_path("doc_template/");
    $pathToUpload = pathToUploadFile("doc_template/");
    $caseTemplate = file_exists(pathToUploadFile("doc_template/1_case_report.docx")) ? "1_case_report.docx" : "";
    $invTemplate = file_exists(pathToUploadFile("doc_template/invitation_letter.docx")) ? "invitation_letter.docx" : "";
    $invReconcilTemplate = file_exists(pathToUploadFile("doc_template/invitation_reconcilation.docx")) ? "invitation_reconcilation.docx" : "";
    $log34Template = file_exists(pathToUploadFile("doc_template/3_log34_employee_info.docx")) ? "3_log34_employee_info.docx" : "";
    $log5Template = file_exists(pathToUploadFile("doc_template/5_log5_company_info.docx")) ? "5_log5_company_info.docx" : "";
    $log6Template = file_exists(pathToUploadFile("doc_template/6_log6.docx")) ? "6_log6.docx" : "";
    $testTemplate = file_exists(pathToUploadFile("doc_template/test_template.docx")) ? "test_template.docx" : "";

@endphp
<x-admin.layout-main :adata="$adata" >
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <form name="frm_update_template" action="{{ url('template/update') }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="card">
                        <div class="card-body mt-3">
                            <div class="row">
                                @php
                                    $templates = [
                                        ['label' => '១. ទម្រង់លិខិតពាក្យបណ្តឹង', 'field' => 'case_template', 'value' => $caseTemplate],
                                        ['label' => '២. ទម្រង់លិខិតសាកសួរ', 'field' => 'inv_template', 'value' => $invTemplate],
                                        ['label' => '៣. ទម្រង់លិខិតដោះស្រាយ', 'field' => 'inv_reconcil_template', 'value' => $invReconcilTemplate],
                                        ['label' => '៤. ទម្រង់លិខិតសាកសួរកម្មករនិយោជិត', 'field' => 'log34_template', 'value' => $log34Template],
                                        ['label' => '៥. ទម្រង់លិខិតសាកសួរសហគ្រាស គ្រឹះស្ថាន', 'field' => 'log5_template', 'value' => $log5Template],
                                        ['label' => '៦. ទម្រង់លិខិតផ្សះផ្សារ', 'field' => 'log6_template', 'value' => $log6Template],
                                        ['label' => '៧. ទម្រង់លិខិតសាកល្បង', 'field' => 'test_template', 'value' => $testTemplate],
                                    ];
                                @endphp

                                @foreach($templates as $index => $item)
                                    <div class="col-md-6 mb-5">
                                        <label class="blue text-hanuman-22 d-block mb-2">{{ $item['label'] }}</label>
                                        <input type="hidden" name="{{ $item['field'] }}_old" value="{{ $item['value'] }}">
                                        @php
                                            $showFile = myShowFileOnly(1, $item['value'], $pathToUpload, "delete", "tbl_case", "id", "1");
                                            echo $showFile ?: "<div class='py-2'>" . upload_file($item['field'], "(ប្រភេទឯកសារ docx មានទំហំធំបំផុត 15MB)") . "</div>";
                                        @endphp
                                    </div>
                                @endforeach
                                <div class="col-12 mt-0">
                                    <div class="form-check checkbox checkbox-solid-danger">
                                        <input class="form-check-input"
                                               id="chkbox_go" name="chkbox_go"
                                               oninvalid="this.setCustomValidity('សូមចុចធីក ក្នុងប្រអប់ជាមុនសិន ដើម្បីបញ្ជាក់នូវការផ្ទៀងផ្ទាត់')"
                                               oninput="this.setCustomValidity('')"
                                               type="checkbox" required>
                                        <label class="form-check-label text-danger fw-bold" for="chkbox_go">
                                            ពត៌មានខាងលើ ត្រូវបានពិនិត្យ និងផ្ទៀងផ្ទាត់យ៉ាងត្រឹមត្រូវ រួចរាល់អស់ហើយ
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success fw-bold">រក្សារទុក</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-slot name="moreAfterScript">
        @include('script.my_sweetalert2')
        @include('case.script.case_closed_script')
        <script src="{{ rurl('assets/myjs/sweetalert2.10.10.1.all.min.js') }}"></script>

    </x-slot>
</x-admin.layout-main>
