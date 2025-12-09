<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="endless admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, endless admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" type="image/svg+xml" sizes="any" href="{{ rurl('assets/images/mlvt.svg') }}">
    <title>SICMS - Login</title>

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
xxx
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
                                                <h4 class="text-battambang mb-5">សូមស្វាគមន៍មកកាន់ របបស្វ័យប្រកាសអធិការកិច្ចការងារតាមប្រព័ន្ធស្វ័យប្រវត្តិកម្ម (SICMS)</h4>
                                                <h6>ដើម្បីអនុវត្តរបបស្វ័យប្រកាសអធិការកិច្ចការងារតាមប្រព័ន្ធស្វ័យប្រវត្តិកម្ម ម្ចាស់ឬនាយករោងចក្រ សហគ្រាស ត្រូវ</h6>
                                            </div>

                                            <div class="d-flex justify-content-center mt-3 mb-5">
                                                <a href="https://lacms.mlvt.gov.kh/client/emp/register" class="btn btn-md btn-primary d-flex text-white mx-2">
                                                    <div class="align-self-center me-2"><i data-feather="edit-3"></i></div>
                                                    <div class="align-self-center">ចុះឈ្មោះ</div>
                                                </a>
                                                <a href="https://lacms.mlvt.gov.kh/login?type=l10sicms" class="btn btn-md btn-success d-flex text-white mx-2">
                                                    <div class="align-self-center me-2"><i data-feather="lock"></i></div>
                                                    <div class="align-self-center">ចូលប្រព័ន្ធ</div>
                                                </a>
                                            </div>

                                            <p class="fs-6 text-center">
                                                សម្រាប់ព័ត៌មានបន្ថែម ម្ចាស់ ឬនាយករោងចក្រ សហគ្រាស អាចសួរព័ត៌មានបន្ថែមក្នុងក្រុម <span class="text-dark fw-bold">"សម្រាប់និយោជក"</span> តាមរយៈតេឡេក្រាម<br/>
                                                <a class="text-center text-dark" href="https://t.me/EmployerChat" target="_blank">https://t.me/EmployerChat</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="copyright">
                                    &copy; ២០២១ រក្សាសិទ្ធគ្រប់យ៉ាងដោយ ក្រសួងការងារ និងបណ្តុះបណ្តាលវិជ្ជាជីវៈនៃព្រះរាជាណាចក្រកម្ពុជា
                                </div>
                            </div>
                        </div>

                        <!-- <div class="col-12 col-md-7">
                            <div class="col-login">
                                <div class="text-center">
                                    <h3 class="text-battambang mb-3">សូមស្វាគមន៍មកកាន់ប្រព័ន្ធ របបស្វ័យប្រកាសអធិការកិច្ចការងារតាមប្រព័ន្ធស្វ័យប្រវត្តិកម្ម</h3>
                                    <h6>ដើម្បីប្រើប្រាស់របបស្វ័យប្រកាសអធិការកិច្ចការងារតាមប្រព័ន្ធស្វ័យប្រវត្តិកម្ម ម្ចាស់ ឬនាយករោងចក្រ សហគ្រាស ត្រូវ</h6>
                                </div>

                                <div class="d-flex justify-content-center my-5">
                                    <a href="https://lacms.mlvt.gov.kh/client/emp/register" class="btn btn-md btn-primary d-flex text-white mx-2">
                                        <div class="align-self-center me-2"><i data-feather="edit-3"></i></div>
                                        <div class="align-self-center">ចុះឈ្មោះ</div>
                                    </a>
                                    <a href="https://lacms.mlvt.gov.kh/login?type=inspection" class="btn btn-md btn-success d-flex text-white mx-2">
                                        <div class="align-self-center me-2"><i data-feather="lock"></i></div>
                                        <div class="align-self-center">ចូលប្រព័ន្ធ</div>
                                    </a>
                                </div>

                                <p class="fs-6 text-center">
                                    សម្រាប់ព័ត៌មានបន្ថែម ម្ចាស់ ឬនាយករោងចក្រ សហគ្រាស អាចសួរព័ត៌មានបន្ថែមក្នុងក្រុម <span class="text-dark fw-bold">"សម្រាប់និយោជក"</span> តាមរយៈតេឡេក្រាម<br/>
                                    <a class="text-center text-dark" href="https://t.me/EmployerChat" target="_blank">https://t.me/EmployerChat</a>
                                </p>

                                <div class="flex-grow-1 d-flex flex-column-reverse">
                                    <div class="copyright">
                                        &copy; ២០២១ រក្សាសិទ្ធគ្រប់យ៉ាងដោយ ក្រសួងការងារ និងបណ្តុះបណ្តាលវិជ្ជាជីវៈនៃព្រះរាជាណាចក្រកម្ពុជា.
                                    </div>
                                </div>
                            </div>
                        </div> -->
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
