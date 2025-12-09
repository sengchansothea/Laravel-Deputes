@if(auth()->user()->k_category < 3)
<li class="active">
    <a class="sidebar-header" href="#">
        <i data-feather="user-check"></i><span>លិខិតអញ្ជើញ</span>
        <i class="fa fa-angle-right pull-right"></i>
    </a>
    <ul class="sidebar-submenu">
        <li>
            <a class="active" href="{{ url('invitations') }}">
                <i class="fa fa-circle"></i>បញ្ជីលិខិតអញ្ជើញ
            </a>
        </li>
    </ul>
</li>
@endif
