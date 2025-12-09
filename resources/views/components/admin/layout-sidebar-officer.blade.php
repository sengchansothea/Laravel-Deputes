@if(allowAccessFromHeadOffice())
<li class="active">
    <a class="sidebar-header" href="#">
        <i data-feather="user-check"></i><span>មន្ត្រីផ្សះផ្សា</span>
        <i class="fa fa-angle-right pull-right"></i>
    </a>
    <ul class="sidebar-submenu">
        <li>
            <a class="active" href="{{ url('officer') }}">
                <i class="fa fa-circle"></i>បញ្ជីឈ្មោះមន្ត្រីផ្សះផ្សា
            </a>
        </li>
        @if(auth()->user()->k_category == 1 || auth()->user()->k_category == 2)
        <li>
            <a class="active" href="{{ url('officer/create') }}">
                <i class="fa fa-circle"></i>បញ្ចូលឈ្មោះមន្ត្រីផ្សះផ្សា
            </a>
        </li>
            <li>
                <a class="active" href="{{ url('domain') }}">
                    <i class="fa fa-circle"></i>តារាងបែងចែកដែនសមត្ថកិច្ច
                </a>
            </li>
        @endif
    </ul>
</li>
@endif
