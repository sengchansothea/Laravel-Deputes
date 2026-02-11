@php
    $chkAllowAccess = $adata['allowAccess'];
    $userID = $adata['userID'];
@endphp

<x-admin.layout-main :adata="$adata">
    <x-slot name="moreCss">
        <link rel="stylesheet" href="{{ rurl('assets/css/select2.css') }}">
    </x-slot>

    <style>
        /* Actions column layout */
        .actions-cell {
            display: flex;
            flex-direction: column;
            /* Stack vertically */
            gap: 8px;
            /* Space between buttons */
            align-items: center;
            /* Center horizontally */
        }

        /* All buttons inside actions */
        .actions-cell .btn {
            width: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            cursor: pointer;
            transition: all 0.2s;
        }

        /* Hover effect: white text + dark background */
        .actions-cell .btn:hover {
            color: white;
            /* Text becomes white */
            background-color: #0d6efd;
            /* Bootstrap primary dark color */
            text-decoration: none;
            /* Remove underline */
        }

        /* Table row hover */
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn-custom {
            min-width: 36px;
            padding: 4px 8px;
        }
    </style>

    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Search Section --}}
                        <div class="text-hanuman mb-3">
                            @if ($adata['opt_search'] == 'quick')
                                @include('case.quick-search')
                            @else
                                @include('case.advance-search')
                            @endif
                        </div>

                        {{-- Cases Table --}}
                        <div class="table-responsive">
                            <div class="bg-primary text-center text-white py-2 text-hanuman-22 mb-2">
                                {{ $adata['pagetitle'] }} : {{ number_format($adata['totalRecord']) }}
                            </div>

                            <table class="table table-hover align-middle" id="complaintsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th width="1%">ល.រ</th>
                                        <th width="20%">
                                            លេខសំណុំរឿង
                                            <a href="{{ request()->fullUrlWithQuery([
                                                'sort_by' => 'case_number',
                                                'order' => request('sort_by') === 'case_number' && request('order') === 'asc' ? 'desc' : 'asc',
                                            ]) }}"
                                                style="color:black; text-decoration:none;">
                                                {{ request('sort_by') === 'case_number' ? (request('order') === 'asc' ? '🔼' : '🔽') : '🔽' }}
                                            </a>
                                        </th>


                                        <th width="13%" class="text-center text-nowrap">
                                            ប្រភេទពាក្យបណ្ដឹង
                                            <a href="{{ request()->fullUrlWithQuery([
                                                'sort_by' => 'case_date',
                                                'order' => request('sort_by') === 'case_date' && request('order') === 'asc' ? 'desc' : 'asc',
                                            ]) }}"
                                                style="color:black; text-decoration:none;">
                                                {{ request('sort_by') === 'case_date' ? (request('order') === 'asc' ? '🔼' : '🔽') : '🔽' }}
                                            </a>
                                        </th>


                                        <th width="15%">
                                            ដើមបណ្ដឹង
                                            <a href="{{ request()->fullUrlWithQuery([
                                                'sort_by' => 'disputant_name',
                                                'order' => request('sort_by') === 'disputant_name' && request('order') === 'asc' ? 'desc' : 'asc',
                                            ]) }}"
                                                style="color:black; text-decoration:none;">
                                                {{ request('sort_by') === 'disputant_name' ? (request('order') === 'asc' ? '🔼' : '🔽') : '🔽' }}
                                            </a>
                                        </th>


                                        <th width="20%">ចុងបណ្ដឹង</th>
                                        <th width="10%">មន្ត្រីទទួលបន្ទុក</th>
                                        <th width="25%" class="center">ដំណើរការបណ្តឹង</th>
                                        <th width="3%">សកម្មភាព</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $userOfficerID = $adata['userOfficerID']; @endphp
                                    @foreach ($adata['cases'] as $row)
                                        @php
                                            $arrOfficerIDs = $adata['officerIDsByCase'][$row->id] ?? [];
                                            $noterName = getCaseOfficer($row->id, 0, 8, $adata['caseOfficers']);
                                            $caseYear = !empty($row->case_date)
                                                ? date2Display($row->case_date, 'Y')
                                                : myDate('Y');
                                        @endphp
                                        <tr>
                                            <td>{{ $adata['cases']->firstItem() + $loop->iteration - 1 }}</td>
                                            <td>{{ $row->case_number }}</td>

                                            {{-- Case Type --}}
                                            <td class="text-center">
                                                {{ $row->caseType->case_type_name }}
                                                @if (!empty($row->case_date))
                                                    <span class="text-danger fw-bold d-block mt-1">
                                                        [{{ date2Display($row->case_date) }}]
                                                    </span>
                                                @endif
                                                @php
                                                    $showFile = showFile(
                                                        1,
                                                        $row->case_file,
                                                        pathToDeleteFile("case_doc/form1/$caseYear/"),
                                                        'notdelete',
                                                        'tbl_case',
                                                        'id',
                                                        $row->id,
                                                        'case_file',
                                                        '',
                                                    );
                                                    if ($showFile) {
                                                        echo '<span class="fw-bold">ឯកសារពាក្យបណ្ដឹង</span><br>' .
                                                            $showFile;
                                                    }
                                                @endphp
                                            </td>

                                            {{-- Disputant --}}
                                            <td>
                                                <label class="form-label blue fw-bold">
                                                    {{ $row->disputant->name ?? '-' }}
                                                </label>
                                            </td>

                                            {{-- Company --}}
                                            <td class="fw-bold purple">
                                                @if (!empty($row->company->company_name_khmer))
                                                    <div>{{ $row->company->company_name_khmer }}</div>
                                                @endif
                                                @if (!empty($row->company->company_name_latin))
                                                    <div>{{ $row->company->company_name_latin }}</div>
                                                @endif
                                            </td>

                                            {{-- Officers --}}
                                            <td class="text-nowrap">
                                                អ្នកផ្សះផ្សា:<br>
                                                <span class="text-danger fw-bold">{!! getCaseOfficer($row->id, 0, 6, $adata['caseOfficers']) !!}</span>
                                                <br>
                                                អ្នកកត់ត្រា:<br>
                                                @if (!empty($noterName))
                                                    <span class="text-danger fw-bold">{!! $noterName !!}</span>
                                                @else
                                                    <span class="text-info fw-bold">- គ្មាន</span>
                                                @endif
                                            </td>

                                            {{-- Case Status --}}
                                            <td class="center" style="line-height: 28px">
                                                @php
                                                    $caseStatus = generateCaseStatus($row);
                                                    echo displayCaseStatus($caseStatus);
                                                @endphp
                                                <br>
                                                <a class="btn btn-outline-success"
                                                    href="{{ url('cases/' . $row->id) }}" target="_blank">
                                                    មើលដំណើរការបណ្ដឹង
                                                </a>
                                            </td>

                                            {{-- Actions --}}
                                            <td class="text-center actions-cell">
                                                {{-- Edit --}}
                                                @if ($chkAllowAccess || in_array($userOfficerID, $arrOfficerIDs) || $userID == $row->user_created)
                                                    <a href="{{ url('cases/' . $row->id . '/edit') }}" target="_blank"
                                                        class="btn btn-outline-primary" title="កែប្រែពាក្យបណ្ដឹង">
                                                        <i data-feather="edit"></i>
                                                        <span class="btn-label">កែប្រែ</span>
                                                    </a>
                                                @endif

                                                {{-- Delete --}}
                                                @if ($chkAllowAccess || $userID == $row->user_created)
                                                    <form action="{{ url('cases/' . $row->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-outline-danger delete-btn"
                                                            title="លុបពាក្យបណ្ដឹង">
                                                            <i data-feather="trash"></i>
                                                            <span class="btn-label">លុប</span>
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Download --}}
                                                <a href="{{ url('export/word/case/' . $row->id) }}" target="_blank"
                                                    class="btn btn-outline-success" title="ទាញយកពាក្យបណ្ដឹង">
                                                    <i data-feather="download"></i>
                                                    <span class="btn-label">ទាញយក</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- Pagination --}}
                            @if ($adata['cases']->hasPages())
                                <div class="pagination">
                                    {!! $adata['cases']->links('pagination::bootstrap-5') !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JS Section --}}
    <x-slot name="moreAfterScript">
        <script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // SweetAlert Delete
                document.querySelectorAll('.delete-btn').forEach(button => {
                    button.addEventListener('click', () => {
                        Swal.fire({
                            title: 'តើអ្នកពិតជាចង់លុប មែនឫ?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'លុបចោល',
                            cancelButtonText: 'អត់ទេ'
                        }).then(result => {
                            if (result.isConfirmed) {
                                button.closest('form').submit();
                            }
                        });
                    });
                });

                // Initialize Select2
                const selects = ['#inOutDomain', '#domainID', '#statusID', '#stepID', '#year', '#business_activity',
                    '#total_emp', '#insp_status', '#province_id', '#district_id', '#commune_id'
                ];
                selects.forEach(id => $(id).select2());

                // Dynamic province/district/commune
                $("#province_id").on('change', function() {
                    const province_id = $(this).val();
                    $("#district_id, #commune_id").val('').empty();
                    if (province_id) get_district_data(province_id);
                });

                $("#district_id").on('change', function() {
                    const district_id = $(this).val();
                    $("#commune_id").val('').empty();
                    if (district_id) get_commune_data(district_id);
                });

                function get_district_data(province_id) {
                    $.getJSON("{{ url('ajaxGetDistrict') }}/" + province_id, {
                        "_token": "{{ csrf_token() }}"
                    }, function(response) {
                        if (response.data?.length) {
                            $("#district_id").append("<option value='0'>Please Select</option>");
                            response.data.forEach(d => $("#district_id").append(
                                `<option value='${d.id}'>${d.name}</option>`));
                        }
                    });
                }

                function get_commune_data(district_id) {
                    $.getJSON("{{ url('ajaxGetCommune') }}/" + district_id, {
                        "_token": "{{ csrf_token() }}"
                    }, function(response) {
                        if (response.data?.length) {
                            $("#commune_id").append("<option value='0'>Please Select</option>");
                            response.data.forEach(c => $("#commune_id").append(
                                `<option value='${c.id}'>${c.name}</option>`));
                        }
                    });
                }
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const table = document.getElementById('complaintsTable');
                const headers = table.querySelectorAll('th.sortable');
                let sortDirection = {};

                headers.forEach((header, index) => {
                    sortDirection[index] = true; // true = ascending

                    header.addEventListener('click', () => {
                        const type = header.getAttribute('data-type');
                        sortTableByColumn(table, index, type, sortDirection[index]);
                        sortDirection[index] = !sortDirection[index]; // toggle next click
                    });
                });

                function sortTableByColumn(table, column, type, asc = true) {
                    const tbody = table.tBodies[0];
                    const rows = Array.from(tbody.querySelectorAll('tr'));

                    const sortedRows = rows.sort((a, b) => {
                        let aText = a.cells[column].textContent.trim();
                        let bText = b.cells[column].textContent.trim();

                        if (type === 'number') {
                            return asc ? aText - bText : bText - aText;
                        } else if (type === 'date') {
                            return asc ?
                                new Date(aText) - new Date(bText) :
                                new Date(bText) - new Date(aText);
                        } else { // string
                            return asc ?
                                aText.localeCompare(bText) :
                                bText.localeCompare(aText);
                        }
                    });

                    // remove old rows
                    while (tbody.firstChild) {
                        tbody.removeChild(tbody.firstChild);
                    }

                    // append sorted rows
                    tbody.append(...sortedRows);

                    // update ល.រ
                    tbody.querySelectorAll('tr').forEach((row, i) => {
                        row.cells[0].textContent = i + 1;
                    });
                }
            });
        </script>

        @include('script.my_sweetalert2')
    </x-slot>
</x-admin.layout-main>
