<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="title" content="DISPUTES MANAGEMENT SYSTEM" />
    <meta name="keywords" content="DISPUTES MANAGEMENT SYSTEM" />
    <meta name="description" content="DISPUTES MANAGEMENT SYSTEM" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Disputes Management System - សូមស្វាគមន៍មកកាន់ប្រព័ន្ធ គ្រប់គ្រងពាក្យបណ្ដឹង">
    <meta name="keywords" content="Disputes Management System - សូមស្វាគមន៍មកកាន់ប្រព័ន្ធ គ្រប់គ្រងពាក្យបណ្ដឹង">
    <meta name="author" content="MLVT">
    <link rel="icon" type="image/svg+xml" sizes="any" href="{{ rurl('assets/images/mlvt.svg') }}">
    <title>DISPUTES</title>
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/fontawesome.css') }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/icofont.css') }}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/themify.css') }}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/flag-icon.css') }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/feather-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/prism.css') }}">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/bootstrap/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ rurl('assets/css/color-1.css') }}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/style_this_page.css') }}">
{{--    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/menu-tabs.css') }}">--}}
    <style>

    </style>



    @isset($moreCss) {{ $moreCss }} @endisset
    @isset($moreCss2) {{ $moreCss2 }} @endisset
    @isset($moreBeforeScript) {{ $moreBeforeScript }} @endisset
</head>
<body>
<div class="loader-wrapper">
    <div class="loader bg-white">
        <div class="whirly-loader"> </div>
    </div>
</div>

<div class="page-wrapper">
    <x-admin.layout-header></x-admin.layout-header>
    <div class="page-body-wrapper">
        <x-admin.layout-sidebar :adata="$adata"></x-admin.layout-sidebar>
        <div class="page-body">
            <div class="container-fluid">
                <div class="page-header">
                    <div class="row">
                        <div class="col">
                            <div class="page-header-left">
                                <h3 class="text-hanuman">{!! $adata['pagetitle'] !!}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{ $slot }}
        </div>

        <x-admin.layout-footer></x-admin.layout-footer>
    </div>
</div>

<script src="{{ rurl('assets/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ rurl('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ rurl('assets/js/icons/feather-icon/feather.min.js') }}"></script>
<script src="{{ rurl('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
<script src="{{ rurl('assets/js/sidebar-menu.js') }}"></script>
<script src="{{ rurl('assets/js/config.js') }}"></script>
<script src="{{ rurl('assets/js/prism/prism.min.js') }}"></script>
<script src="{{ rurl('assets/js/clipboard/clipboard.min.js') }}"></script>
<script src="{{ rurl('assets/js/custom-card/custom-card.js') }}"></script>
<script src="{{ rurl('assets/js/typeahead/handlebars.js') }}"></script>
<script src="{{ rurl('assets/js/typeahead/typeahead.bundle.js') }}"></script>
<script src="{{ rurl('assets/js/typeahead/typeahead.custom.js') }}"></script>
<script src="{{ rurl('assets/js/chat-menu.js') }}"></script>
<script src="{{ rurl('assets/js/tooltip-init.js') }}"></script>
<script src="{{ rurl('assets/js/typeahead-search/handlebars.js') }}"></script>
<script src="{{ rurl('assets/js/typeahead-search/typeahead-custom.js') }}"></script>
{{--<script src="{{ rurl('assets/myjs/menu-tabs.js') }}"></script>--}}
<script src="{{ rurl('assets/js/script.js') }}"></script>
@stack("childScript")
@stack("childScript2")
{{--@isset($moreAfterScript2) {{ $moreAfterScript2 }} @endisset--}}
@isset($moreAfterScript) {{ $moreAfterScript }} @endisset
</body>
</html>
