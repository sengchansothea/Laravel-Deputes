@if(auth()->user()->k_category < 3)
<li class="active">
    <a class="sidebar-header" href="#">
        <i data-feather="printer"></i><span>របាយការណ៍</span>
        <i class="fa fa-angle-right pull-right"></i>
    </a>
    <ul class="sidebar-submenu">
        <li>
            <a class="active" href="{{ url('') }}">
                <i class="fa fa-circle"></i>Other
            </a>
        </li>
    </ul>
    @if(auth()->user()->k_category == 1)
    <ul class="sidebar-submenu">
        <li>
            <a class="active" href="{{ url('cpes') }}">
                <i class="fa fa-circle"></i>Mapping CPES & LACMS
            </a>
        </li>
    </ul>
    @endif
</li>
@endif

