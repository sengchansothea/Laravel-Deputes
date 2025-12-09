@if(auth()->user()->k_category < 3)
<li class="active">
    <a class="sidebar-header" href="{{ url('template') }}">
        <i data-feather="book-open"></i><span>ទម្រង់លិខិត</span>
{{--        <i class="fa fa-angle-right pull-right"></i>--}}
    </a>
</li>
@endif
