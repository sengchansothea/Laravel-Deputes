@if(allowAccessFromHeadOffice())
<li class="active">
    <a class="sidebar-header" href="#">
        <i data-feather="user-check"></i><span>ភាគីវិវាទ</span>
        <i class="fa fa-angle-right pull-right"></i>
    </a>
    <ul class="sidebar-submenu">
        <li>
            <a class="active" href="{{ url('disputant') }}">
                <i class="fa fa-circle"></i>បញ្ជីភាគីវិវាទ
            </a>
        </li>

    </ul>
</li>
@endif
