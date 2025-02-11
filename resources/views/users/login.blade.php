<!DOCTYPE html>
<html lang="en">

<head>

    <!-- PAGE TITLE HERE -->
    <title>Login : Herab Groceries </title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Chris Motari">
    <meta name="robots" content="index, follow">
    <meta name="format-detection" content="telephone=no">

    <meta name="keywords" content="admin dashboard, admin template, analytics, bootstrap, bootstrap 5, bootstrap 5 admin template, job board admin, job portal admin, modern, responsive admin dashboard, sales dashboard, sass, ui kit, web app, frontend">
    <meta name="description" content="We proudly present Jobick, a Job Admin dashboard HTML Template, If you are hiring a job expert you would like to build a superb website for your Jobick, it's a best choice.">

    <meta property="og:title" content="Jobick : Job Admin Dashboard Bootstrap 5 Template + FrontEnd">
    <meta property="og:description" content="We proudly present Jobick, a Job Admin dashboard HTML Template, If you are hiring a job expert you would like to build a superb website for your Jobick, it's a best choice.">
    <meta property="og:image" content="https://jobick.dexignlab.com/xhtml/social-image.png">

    <meta name="twitter:title" content="Jobick : Job Admin Dashboard Bootstrap 5 Template + FrontEnd">
    <meta name="twitter:description" content="We proudly present Jobick, a Job Admin dashboard HTML Template, If you are hiring a job expert you would like to build a superb website for your Jobick, it's a best choice.">
    <meta name="twitter:image" content="https://jobick.dexignlab.com/xhtml/social-image.png">
    <meta name="twitter:card" content="summary_large_image">

    <!-- Mobile Specific -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <base href="{{ url('/backend') }}/">
    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="{{ asset('backend/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link class="main-css" href="{{ asset('/backend/css/style.css') }}" rel="stylesheet">

</head>

<body>
    <div class="fix-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-6">
                    <div class="card mb-0 h-auto">
                        <div class="card-body">
                            <div class="text-center mb-3 logo-img">
                                <a href="index.html"><img class="logo-auth light" src="images/logo.png" alt=""></a>
                                <!-- <a href="index.html"><img class="logo-auth dark" src="images/logo-dark.png" alt=""></a> -->
                            </div>
                            <h4 class="text-center mb-4">Sign to your account</h4>
                            <form action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class=" mb-4">
                                    <label class="form-label">Email Address<span class="text-danger scale5 ms-2">*</span></label>
                                    <input type="text" class="form-control" placeholder="Email" aria-label="email" value="{{ old('email') }}" name="email">
                                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                                </div>
                                <div class="mb-sm-4 mb-3 position-relative">
                                    <label class="form-label" for="dlab-password">Password</label>
                                    <input type="password" id="dlab-password" class="form-control" value="123456" name="password">
                                    <span class="show-pass eye">
                                        <i class="fa fa-eye-slash"></i>
                                        <i class="fa fa-eye"></i>
                                    </span>
                                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--**********************************
	Scripts
***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset('backend/js/global.min.js') }}"></script>
    <script src="{{ asset('backend/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('backend/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('backend/js/dlabnav-init.js') }}"></script>
    <script src="{{ asset('backend/js/custom.min.js') }}"></script>
    <script src="{{ asset('backend/js/demo.js') }}"></script>
    <script src="{{ asset('backend/js/styleSwitcher.js') }}"></script>

</body>

</html>