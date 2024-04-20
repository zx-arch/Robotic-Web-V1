
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <style>
            .custom-popup {
                display: none;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                padding: 20px;
                background-color: #fff;
                border: 1px solid #ddd;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
                z-index: 1000;
            }
    
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }
        </style>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Absah.id</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{url('assets/img/favicon.png')}}??">
		
        <link href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
        <link href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('themes/ezone/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{ asset('assets/new/bootstrap/dist/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/new/css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('themes/ezone/assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('themes/ezone/assets/css/magnific-popup.css') }}">
        <link rel="stylesheet" href="{{ asset('themes/ezone/assets/css/animate.css') }}">
        <link rel="stylesheet" href="{{ asset('themes/ezone/assets/css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('themes/ezone/assets/css/themify-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('themes/ezone/assets/css/pe-icon-7-stroke.css') }}">
        <link rel="stylesheet" href="{{ asset('themes/ezone/assets/css/icofont.css') }}">
        <link rel="stylesheet" href="{{ asset('themes/ezone/assets/css/meanmenu.min.css') }}">
        <link rel="stylesheet" href="{{ asset('themes/ezone/assets/css/easyzoom.css') }}">
        <link rel="stylesheet" href="{{ asset('themes/ezone/assets/css/bundle.css') }}">
        <link rel="stylesheet" href="{{ asset('themes/ezone/assets/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('themes/ezone/assets/css/responsive.css') }}">
        <script src="{{ asset('themes/ezone/assets/js/vendor/modernizr-2.8.3.min.js') }}"></script>
        {{-- <meta name="csrf-token" content="{{ csrf_token()}}"> --}}
    </head>
    <body>

            @include('user.header2')
        
            @yield('content')
        

        {{-- @include('partials.footer') --}}


		<!-- all js here -->
        <script src="{{ asset('themes/ezone/assets/js/vendor/jquery-1.12.0.min.js') }}"></script>
        <script src="{{ asset('themes/ezone/assets/js/popper.js') }}"></script>
        <script src="{{ asset('themes/ezone/assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('themes/ezone/assets/js/jquery.magnific-popup.min.js') }}"></script>
        <script src="{{ asset('themes/ezone/assets/js/isotope.pkgd.min.js') }}"></script>
        <script src="{{ asset('themes/ezone/assets/js/imagesloaded.pkgd.min.js') }}"></script>
        <script src="{{ asset('themes/ezone/assets/js/jquery.counterup.min.js') }}"></script>
        <script src="{{ asset('themes/ezone/assets/js/waypoints.min.js') }}"></script>
        <script src="{{ asset('themes/ezone/assets/js/ajax-mail.js') }}"></script>
        <script src="{{ asset('themes/ezone/assets/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('themes/ezone/assets/js/plugins.js') }}"></script>
        <script src="{{ asset('themes/ezone/assets/js/main.js') }}"></script>
        <script src="{{ asset('themes/ezone/assets/js/app.js') }}"></script>
    </body>
</html>
