<!doctype html>
<html lang="en" class="semi-dark">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <title>អធិការកិច្ចការងារតាមប្រព័ន្ធស្វ័យប្រវត្តិកម្ម - Online Labour Inspection</title>
</head>

<body class="bg-login">
<li>
    <a href="https://lacms.mlvt.gov.kh/login?type=inspection" >ចូលប្រព័ន្ធជាក្រុមហ៊ុន</a>
</li>
<li>
    <a href="{{ url("login") }}" >ចូលជាមន្ត្រីអធិការ</a>
</li>

<!--wrapper-->
<div class="wrapper">
    <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
        <div class="container-fluid">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                <div class="col mx-auto">
                    <div class="mb-4 text-center">
                        {{--                        <img src="{{ rurl('assets/images/logo-img.png') }}" width="180" alt="" />--}}
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="border p-4 rounded">

                                <div class="login-separater text-center mb-4"> <span>អធិការកិច្ចការងារតាមប្រព័ន្ធស្វ័យប្រវត្តិកម្ម</span>
                                    <hr/>
                                </div>
                                <div class="form-body">
                                    <form  method="POST" action="{{ url('login') }}" class="row g-3">
                                        @csrf
                                        <div class="col-12">
                                            <label for="inputEmailAddress" class="form-label">Username</label>
                                            <input type="text" name="username" value="test" class="form-control" id="inputEmailAddress" placeholder="Username">
                                        </div>
                                        <div class="col-12">
                                            <label for="inputChoosePassword" class="form-label">Enter Password</label>
                                            <div class="input-group" id="show_hide_password">
                                                <input type="password" name="password" value="12345678" class="form-control border-end-0" id="inputChoosePassword" placeholder="Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i>Log in</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
</div>
<a href="{{ url('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>



</body>
</html>




