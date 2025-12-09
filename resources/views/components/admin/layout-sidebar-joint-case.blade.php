@if(allowAccessFromHeadOffice())
<li class="active">
    <a class="sidebar-header" href="#">
        <i data-feather="user-check"></i><span>វឌ្ឍនភាពវិវាទការងារ</span>
        <i class="fa fa-angle-right pull-right"></i>
    </a>
    <ul class="sidebar-submenu">
        <li>
            <a class="active" href="{{ url('joint_cases/create') }}">
                <i class="fa fa-circle"></i>បង្កើតបណ្តឹងវិវាទការងារ
            </a>
        </li>
        <li>
            <a class="active" href="{{ url('joint_cases/') }}">
                <i class="fa fa-circle"></i>តារាងវិវាទការងារ
            </a>
        </li>
    </ul>
</li>
@endif
