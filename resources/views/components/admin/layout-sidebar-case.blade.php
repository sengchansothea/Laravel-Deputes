<li class="active">
    <a class="sidebar-header" href="#">
        <i data-feather="user-check"></i><span>វិវាទបុគ្គល</span>
        <i class="fa fa-angle-right pull-right"></i>
    </a>
    <ul class="sidebar-submenu">
        <li>
            <a class="active" href="{{ url('cases/create') }}">
                <i class="fa fa-circle"></i>បង្កើតពាក្យបណ្ដឹងថ្មី
            </a>
        </li>
        <li>
            <a class="active" href="{{ route('cases.create.step1') }}">
                <i class="fa fa-circle"></i>បង្កើតពាក្យបណ្ដឹងថ្មី (NEW)
            </a>
        </li>
        <li>
            <a class="active" href="{{ url('cases/') }}">
                <i class="fa fa-circle"></i>បញ្ជីពាក្យបណ្ដឹង
            </a>
        </li>
        <li>
            <a class="active" href="{{ url('user/case/entry/' . Auth::id()) }}">
                <i class="fa fa-circle"></i>ពាក្យបណ្ដឹងដែលបានបញ្ចូល
            </a>
        </li>
        <li>
            <a href="{{ url('/cases/?opt_search=advance') }}">
                <i class="fa fa-circle"></i>ស្វែងរក (Advanced)
            </a>
        </li>
    </ul>
</li>
