<x-admin.layout-main :adata="$adata" >
    <x-slot name="moreCss">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/select2.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/datatables.css') }}">
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body pt-0 mt-0">
                        <div class="card-block row">
                            <div class="col-sm-12">
                                <div class="table-responsive"><br/>
                                    <table class="table table-striped table-hover table-bordered data-table">
                                        <thead class="table-light">
                                        <tr>
                                            <th>{{ __('general.k_no') }}</th>
                                            <th>{{ __('general.k_ownername') }}</th>
                                            <th>ឈ្មោះ Login</th>
                                            <th>Email</th>
                                            <th>ប្រភេទ</th>
                                            <th>រាជធានី-ខេត្ត</th>
                                            <th>តួនាទី</th>
                                            <th>{{ __('general.k_status') }}</th>
                                            <th>សកម្មភាព</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="moreAfterScript">
        @include('script.my_sweetalert2')
        <script src="{{ rurl('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ rurl('assets/js/select2/select2-custom.js') }}"></script>
        <script src={{ rurl('assets/js/datatable/datatables/jquery.dataTables.min.js') }}></script>
        <script src="{{ rurl('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('user.index') }}",
                        data: function(d){
                            d.qsearch = $('#qsearch').val(); // Custom search input
                            d.k_category = $('#k_category').val();
                            d.k_province = $('#k_province').val();
                            d.officer_role_id = $('#officer_role_id').val();
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-sync fa-spin text-primary"></i>កំពុងដំណើរការ...',
                        lengthMenu: "បង្ហាញ _MENU_",
                        zeroRecords: "មិនមានទិន្នន័យ",
                        info: "បង្ហាញ _START_ ដល់ _END_ នៃ _TOTAL_",
                        infoEmpty: "មិនមានទិន្នន័យ",
                        infoFiltered: "(តម្រៀបពី _MAX_ ទិន្នន័យសរុប)",
                        search: "ស្វែងរក:",
                        paginate: {
                            first: "ដំបូង",
                            last: "ចុងក្រោយ",
                            next: "បន្ទាប់",
                            previous: "ថយក្រោយ"
                        }
                    },
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'k_fullname', name: 'k_fullname'},
                        {data: 'username', name: 'username'},
                        {data: 'email', name: 'email'},
                        {data: 'type', name: 'type'},
                        {data: 'province', name: 'province', orderable: false, searchable: false},
                        {data: 'role', name: 'role', orderable: false, searchable: false},
                        {data: 'status_badge', name: 'banned', orderable: false, searchable: false},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ]
                });

                // Add Filter Options
                $('#qsearch,#k_category,#k_province,#officer_role_id').change(function(){
                    $('.data-table').DataTable().ajax.reload();
                });
            });

        </script>
    </x-slot>
</x-admin.layout-main>
