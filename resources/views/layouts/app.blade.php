
<!doctype html>
<html class="no-js" lang="">
    
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Ganic - Organic Food & Grocery Market Template</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
        <!-- Place favicon.ico in the root directory -->
		<base href="{{ url('/') }}/">

		<!-- CSS here -->
       @include('layouts.includes.styles')
    </head>
    <body>

        <!-- preloader -->
        <div id="preloader">
            <div id="loading-center">
                <div class="loader">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
        <!-- preloader-end -->


		<!-- Scroll-top -->
        <button class="scroll-top scroll-to-target" data-target="html">
            <i class="fas fa-angle-up"></i>
        </button>
        <!-- Scroll-top-end-->

        <!-- header-area -->
        <header>

            <!-- header-message -->
            <!-- <div class="header-message-wrap">
                <div class="container custom-container">
                    <div class="row">
                        <div class="col-12">
                            <div class="top-notify-message">
                                <p>place your complaints (if any) within 24hrs of receiving your delivery</p>
                                <span class="message-remove">X</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- header-message-end -->

            @include('layouts.includes.header')

            @include('layouts.includes.navbar')
        </header>
        <!-- header-area-end -->


        <!-- main-area -->
        <main>

           @yield('content')

        </main>
        <!-- main-area-end -->


        @include('layouts.includes.footer')





		<!-- JS here -->
        @include('layouts.includes.scripts')
    </body>
</html>
