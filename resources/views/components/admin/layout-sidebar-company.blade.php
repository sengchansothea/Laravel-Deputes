@if(allowAccessFromHeadOffice())
<li class="active">
    <a class="sidebar-header" href="#">
        <i data-feather="grid"></i><span>រោងចក្រ សហគ្រាស</span>
        <i class="fa fa-angle-right pull-right"></i>
    </a>
    <ul class="sidebar-submenu">
        <li>
            <a class="active" href="{{ url('company') }}">
                <i class="fa fa-circle"></i>បញ្ជីរោងចក្រ សហគ្រាស
            </a>
        </li>
        <li>
            <a class="active" href="{{ url('/company/list/12') }}">
                <i class="fa fa-circle"></i>រាជធានីភ្នំពេញ
            </a>
        </li>
        <li>
            <a href="{{ url('/company/list/0?opt_search=advance') }}">
                <i class="fa fa-circle"></i>ស្វែងរក
            </a>
        </li>
    </ul>
</li>
@endif
