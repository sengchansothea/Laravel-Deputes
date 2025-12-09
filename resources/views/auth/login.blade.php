<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Disputes Management System - សូមស្វាគមន៍មកកាន់ប្រព័ន្ធ គ្រប់គ្រងពាក្យបណ្ដឹង">
    <meta name="keywords" content="Disputes Management System - សូមស្វាគមន៍មកកាន់ប្រព័ន្ធ គ្រប់គ្រងពាក្យបណ្ដឹង">
    <meta name="author" content="MLVT">
    <link rel="icon" type="image/svg+xml" sizes="any" href="{{ rurl('assets/images/mlvt.svg') }}">
    <title>DISPUTES SYSTEM - Login</title>

    {{-- <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/fontawesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/icofont.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/themify.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/flag-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/feather-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/prism.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/vertical-menu.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/style.css') }}">
    {{-- <link id="color" rel="stylesheet" href="{{ rurl('assets/css/color-1.css') }}" media="screen"> --}}
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/login_v2.css') }}">

    <style>
    	:root {
    		--bs-blue: #0d6efd;
    		--bs-indigo: #6610f2;
    		--bs-purple: #6f42c1;
    		--bs-pink: #d63384;
    		--bs-red: #dc3545;
    		--bs-orange: #fd7e14;
    		--bs-yellow: #ffc107;
    		--bs-green: #198754;
    		--bs-teal: #20c997;
    		--bs-cyan: #0dcaf0;
    		--bs-white: #fff;
    		--bs-gray: #6c757d;
    		--bs-gray-dark: #343a40;
    		--bs-primary: #4380F9;
    		--bs-primary-dark: #040368;
    		--bs-secondary: #6c757d;
    		--bs-success: #198754;
    		--bs-info: #0dcaf0;
    		--bs-warning: #ffc107;
    		--bs-danger: #dc3545;
    		--bs-light: #f8f9fa;
    		--bs-dark: #212529;
    		--bs-font-sans-serif: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    		--bs-font-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    		--bs-gradient: linear-gradient(180deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0)); }
    	body {
    		background: linear-gradient(270deg,#1f71df 0%,#2872f0 100%);
    	}
    	.logo-wrapper img {
    		width: 540px;
    	}
    	.text-primary-dark {
    		color: var(--bs-primary-dark);
    	}
    	.text-gray-dark {
    		color: var(--bs-gray-dark);
    	}
    	.center-screen {
    		display: flex;
    		flex-direction: column;
    		justify-content: center;
    		align-items: center;
    		text-align: center;
    		min-height: 100vh;
    	}
    </style>
</head>
<body class="center-screen">
<div class="loader-wrapper">
    <div class="loader bg-white">
        <div class="whirly-loader"> </div>
    </div>
</div>
<div class="container page-wrapper vertical">
    <div class="row">
        <div class="col-12 col-md-6 mx-auto login-wrap">
            <div class="card mt-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="col-details">
                                <div class="logo-wrapper text-center">
                                    <a href="{{ url('/') }}"><img class="img-fluid" src="{{ rurl('assets/images/logo2.png') }}" alt=""></a>
                                </div>

                                <div class="d-flex align-items-center h-100">
                                    <div class="w-100">
                                        <form class="mt-3 mb-2" method="POST" action="{{ route('login') }}">
                                            @csrf
                                            <!-- reCAPTCHA Token -->
                                            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                                            <div class="text-center">
												<p class="text-hanuman text-primary fs-5">
													Disputes Management System
												</p>
											</div>
                                            <div class="row">
                                                <div class="col-12 col-sm-10 mx-auto">
                                                    <div class="mb-3">
                                                        <div class="input-group">
																	<span class="input-group-text">
																		<i class="icofont icofont-user"></i>
																	</span>
                                                            <input class="form-control form-control-md" type="text"  name="username" placeholder="Username">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="input-group">
																	<span class="input-group-text">
																		<i class="icofont icofont-key"></i>
																	</span>
                                                            <input class="form-control form-control-md" type="password" name="password" placeholder="Password">
                                                        </div>
                                                    </div>
                                                    @if ($errors->any())
                                                        <div class="alert alert-danger">
                                                            <ul>
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
{{--                                                    <div class="text-center mt-3">--}}
{{--                                                        <a href="https://uat-accounts.mlvt.gov.kh/login?redirect_uri={{ urlencode('https://uat-disputes.mlvt.gov.kh/mainboard') }}"--}}
{{--                                                           class="btn btn-primary w-100">--}}
{{--                                                            <i class="icofont icofont-lock me-1"></i> Login via MLVT SSO--}}
{{--                                                        </a>--}}
{{--                                                    </div>--}}
                                                    <div class="form-row mt-3">
                                                        <button class="btn btn-primary btn-block btn-md w-100" type="submit">
                                                            <i class="icofont icofont-lock me-1"></i>Login
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Google reCAPTCHA v3 Script -->
@include('script.google_recaptcha_v3')

<script src="{{ rurl('assets/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ rurl('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
{{-- <script src="{{ rurl('assets/js/icons/feather-icon/feather.min.js') }}"></script>
<script src="{{ rurl('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
<script src="{{ rurl('assets/js/sidebar-menu.js') }}"></script>
<script src="{{ rurl('assets/js/config.js') }}"></script>
<script src="{{ rurl('assets/js/prism/prism.min.js') }}"></script> --}}
<script src="{{ rurl('assets/js/script.js') }}"></script>
{{-- <script src="{{ rurl('assets/js/jquery.drilldown.js') }}"></script>
<script src="{{ rurl('assets/js/vertical-menu.js') }}"></script>
<script src="{{ rurl('assets/js/megamenu.js') }}"></script> --}}
</body>
</html>
