<div class="page-sidebar">
    <div class="main-header-left d-none d-lg-block">
        <div class="logo-wrapper p-0 justify-content-center">
            <h3 class="mb-0">
                @php
                    $labelText = '';
                    $fontSize = '22px';
                    $currentUser = auth()->user();
                    if (!empty($currentUser->officer_id)) {
                        $labelText = getOfficerRoleName($currentUser->officer_id, 1);
                        $fontSize = '15px';
                    } else {

                        $labelText = "Test";
                        switch ($currentUser->k_category) {
                            case 1:
                                $labelText = 'MASTER';
                                break;
                            case 2:
                                $labelText = 'ADMIN';
                                break;
                            case 3:
                                $labelText = 'ថ្នាក់កណ្តាល';
                                break;
                            default:
                                $labelText = 'ថ្នាក់ក្រោមជាតិ';
                                break;
                        }
                    }
                @endphp
                <label class="text-white fw-bold" style="font-size: {{ $fontSize }} !important;">
                    {{ $labelText }}
                </label>
            </h3>
        </div>
    </div>
    <div class="sidebar custom-scrollbar" >
        <ul class="sidebar-menu">
            <a class="sidebar-header" href="{{ url('dashboard?month='.myDate('m')."&year=".myDate('Y')) }}">
                <i data-feather="home"></i><span>ផ្ទាំងគ្រប់គ្រង</span>
            </a>
            </li>

            <x-admin.layout-sidebar-case :adata="$adata"></x-admin.layout-sidebar-case>
            <x-admin.layout-sidebar-collectives-case :adata="$adata"></x-admin.layout-sidebar-collectives-case>
            <x-admin.layout-sidebar-joint-case :adata="$adata"></x-admin.layout-sidebar-joint-case>
            <x-admin.layout-sidebar-invitation :adata="$adata"></x-admin.layout-sidebar-invitation>
{{--            <x-admin.layout-sidebar-log :adata="$adata"></x-admin.layout-sidebar-log>--}}
            <x-admin.layout-sidebar-disputant :adata="$adata"></x-admin.layout-sidebar-disputant>
            <x-admin.layout-sidebar-company :adata="$adata"></x-admin.layout-sidebar-company>
            <x-admin.layout-sidebar-officer :adata="$adata"></x-admin.layout-sidebar-officer>
            <x-admin.layout-sidebar-report :adata="$adata"></x-admin.layout-sidebar-report>
            <x-admin.layout-sidebar-template :adata="$adata"></x-admin.layout-sidebar-template>
            <x-admin.layout-sidebar-user :adata="$adata"></x-admin.layout-sidebar-user>
{{--            <x-admin.layout-sidebar-setting :adata="$adata"></x-admin.layout-sidebar-setting>--}}
        </ul>
    </div>
</div>
