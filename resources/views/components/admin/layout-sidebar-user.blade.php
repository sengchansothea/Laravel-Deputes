<li class="active">
    <a class="sidebar-header" href="#">
        <i data-feather="users"></i><span>អ្នកប្រើប្រាស់</span>
        <i class="fa fa-angle-right pull-right"></i>
    </a>
    <ul class="sidebar-submenu">
        @if(allowAccessFromHeadOffice())
        <li>
            <a class="active" href="{{ url('user') }}">
                <i class="fa fa-circle"></i>បញ្ជីអ្នកប្រើប្រាស់
            </a>
        </li>
        @endif
        @if(auth()->user()->k_category < 3)
        <li>
            <a href="{{ url('user/create') }}">
                <i class="fa fa-circle"></i>បង្កើតអ្នកប្រើប្រាស់ថ្មី
            </a>
        </li>
        @endif
        <li>
            <a class="active" href="{{ url('user/change_password/owner') }}">
                <i class="fa fa-circle"></i>ផ្លាស់ប្តូរពាក្យសម្ងាត់
            </a>
        </li>
        <li>
            <a href="#"
               onclick="event.preventDefault(); document.getElementById('form-logout').submit();">
                <i class="fa fa-circle"></i>ចាកចេញ {{ Auth::user()->username }}
            </a>
            <form id="form-logout" method="post" action="{{ url('logout') }}"  style="display: none">
                @csrf
            </form>
        </li>
    </ul>
</li>
