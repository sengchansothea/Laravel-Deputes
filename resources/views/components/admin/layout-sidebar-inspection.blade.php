<li class="active">
    <a class="sidebar-header" href="#">
        <i data-feather="list"></i><span>បញ្ជីអធិការកិច្ច</span>
        <i class="fa fa-angle-right pull-right"></i>
    </a>
    <ul class="sidebar-submenu">
        <li>
            <a class="active" href="{{ url('inspection/list?opt_period=1') }}">
                <i class="fa fa-circle"></i>ថ្ងៃនេះ
            </a>
        </li>
        <li>
            <a href="{{ url('inspection/list') }}">
                <i class="fa fa-circle"></i>អធិការកិច្ចសរុប
            </a>
        </li>
        <li>
            <a class="active" href="{{ url('inspection/list/1') }}">
                <i class="fa fa-circle"></i>សាមញ្ញ
            </a>
        </li>
        <li>
            <a href="{{ url('inspection/list/2') }}">
                <i class="fa fa-circle"></i>តាមដានការដាក់កំហិត
            </a>
        </li>
        <li>
            <a class="active" href="{{ url('inspection/list/3') }}">
                <i class="fa fa-circle"></i>ពិសេស
            </a>
        </li>
        <li>
            <a href="{{ url('inspection/special') }}">
                <i class="fa fa-circle"></i>បណ្ដឹង
            </a>
        </li>
        <li>
            <a class="active" href="{{ url('/inspection/list?opt_search=advance') }}">
                <i class="fa fa-circle"></i>ស្វែងរក
            </a>
        </li>
    </ul>
</li>
