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
                <div class="col-lg-12 col-md-12">
                    <div class="card mb-0 h-auto">
                        <div class="card-body">
                            <div class="text-center mb-3 logo-img">
                                <a href="/"><img class="logo-auth light" src="images/logo.png" alt=""></a>
                                <!-- <a href="/"><img class="logo-auth dark" src="images/logo-dark.png" alt=""></a> -->
                            </div>
                            <h4 class="text-center mb-4">Sign up to {{config('app.name')}}</h4>
                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                @include('users.form')
                            </form>
                            <div class="new-account mt-3 __web-inspector-hide-shortcut__">
                                <p>Already have an account? <a class="text-primary" href="{{route('login')}}">Login</a></p>
                            </div>
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