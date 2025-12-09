<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Case Management System - សូមស្វាគមន៍មកកាន់ប្រព័ន្ធ គ្រប់គ្រងពាក្យបណ្ដឹង">
    <meta name="keywords" content="Case Management System - សូមស្វាគមន៍មកកាន់ប្រព័ន្ធ គ្រប់គ្រងពាក្យបណ្ដឹង">
    <meta name="author" content="MLVT">
    <link rel="icon" type="image/svg+xml" sizes="any" href="{{ rurl('assets/images/mlvt.svg') }}">
    <title>CASE - Login</title>

    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/fontawesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/icofont.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/themify.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/flag-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/feather-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/prism.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ rurl('assets/css/color-1.css') }}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ rurl('assets/css/login_v2.css') }}">
</head>
<body>
<div class="loader-wrapper">
    <div class="loader bg-white">
        <div class="whirly-loader"> </div>
    </div>
</div>
vvv
<div class="page-wrapper vertical">
    <div class="row">
        <div class="col-12 col-lg-9 mx-auto login-wrap">
            <div class="card mt-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-5">
                            <div class="col-details">
                                <div class="logo-wrapper">
                                    <a href="{{ url('/') }}"><img class="img-fluid" src="{{ rurl('assets/images/logo-text.png') }}" alt=""></a>
                                </div>

                                @include('inspection.nav-vertical')
                            </div>
                        </div>

                        <div class="col-12 col-md-7">
                            <div class="col-login col-login-primary">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center h-100">
                                        <div class="w-100">
                                            <div class="text-center">
                                                <h5 class="text-moul">ចូលប្រព័ន្ធ សម្រាប់មន្ត្រីត្រួតពិនិត្យ</h5>
                                            </div>
                                            <form class="my-5" method="POST" action="{{ route('login') }}">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-12 col-sm-8 mx-auto">
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

                                                        <div class="form-row mt-3">
                                                            <button class="btn btn-secondary btn-block btn-md w-100" type="submit">
                                                                <i class="icofont icofont-lock me-1"></i>Login
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="copyright">
                                    &copy; ២០២១ រក្សាសិទ្ធគ្រប់យ៉ាងដោយ ក្រសួងការងារ និងបណ្តុះបណ្តាលវិជ្ជាជីវៈនៃព្រះរាជាណាចក្រកម្ពុជា.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ rurl('assets/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ rurl('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ rurl('assets/js/icons/feather-icon/feather.min.js') }}"></script>
<script src="{{ rurl('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
<script src="{{ rurl('assets/js/sidebar-menu.js') }}"></script>
<script src="{{ rurl('assets/js/config.js') }}"></script>
<script src="{{ rurl('assets/js/prism/prism.min.js') }}"></script>
<script src="{{ rurl('assets/js/script.js') }}"></script>
<script src="{{ rurl('assets/js/jquery.drilldown.js') }}"></script>
<script src="{{ rurl('assets/js/vertical-menu.js') }}"></script>
<script src="{{ rurl('assets/js/megamenu.js') }}"></script>
</body>
</html>
