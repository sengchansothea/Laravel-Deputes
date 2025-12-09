{{--<x-admin.layout-main :adata="$adata" >--}}
    <x-slot name="moreCss">
        {{--        @include('layouts.test')--}}
    </x-slot>
    <div class="container-fluid">
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        No Access
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

                    </div>
                </div>
            </div>
        </div>
    </div>


{{--</x-admin.layout-main>--}}
