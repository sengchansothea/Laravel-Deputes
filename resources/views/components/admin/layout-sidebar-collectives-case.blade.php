{{--@if(auth()->user()->k_category < 3)--}}
<li class="active">
    <a class="sidebar-header" href="#">
        <i data-feather="user-check"></i><span>វិវាទការងាររួម</span>
        <i class="fa fa-angle-right pull-right"></i>
    </a>
    <ul class="sidebar-submenu">
        <li>
            <a class="active" href="{{ url('collective_cases/create') }}">
                <i class="fa fa-circle"></i>បង្កើតបណ្តឹងវិវាទការងាររួម
            </a>
        </li>
        <li>
            <a class="active" href="{{ url('collective_cases/') }}">
                <i class="fa fa-circle"></i>តារាងវិវាទការងាររួម
            </a>
        </li>
    </ul>
</li>

