
<li class="breadcrumb-item active">
    <a href="#"
       onclick="event.preventDefault(); document.getElementById('form-logout').submit();">
        <i class="ti-power-off"></i>
        <span>Logout</span>
    </a>
    <form id="form-logout" method="post" action="{{ url('logout') }}"  style="display: none">
        @csrf
    </form>
</li>


