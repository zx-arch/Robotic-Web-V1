<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Absah.id | Aplikasi Pembelajaran Madrasah</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('assets/img/favicon.png') }}??">

    <!-- all css here -->
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
    <link rel="stylesheet" href="{{ asset('assets/new/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/new/css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://absah.id/dashmitra/web/assets/91d0ddb4/css/adminlte.min.css?v=1614909824" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body id="body">

    @include('headerlogin')<br><br><br><br><br>

    <noscript>
        <div class="alert alert-warning" role="alert">
            Hidupkan JavaScript Anda !!!
        </div>
    </noscript>
    @yield('content')

                    
    @include('themes.ezone.partials.modals')
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
        // Memeriksa apakah JavaScript aktif
            if ('addEventListener' in window) {
                window.addEventListener("load", function() {
                    document.getElementById('card_js').style.display = 'block';
                });
            } else {
                var overlay = document.createElement('div');
                overlay.style.position = 'fixed';
                overlay.style.top = '0';
                overlay.style.left = '0';
                overlay.style.width = '100%';
                overlay.style.height = '100%';
                overlay.style.opacity = '0.5';
                overlay.style.backgroundColor = 'rgba(255, 255, 255, 0.9)'; // Warna background putih dengan opacity 0.9
                overlay.style.zIndex = '9999';
                document.body.style.visibility = 'hidden';
                // Menambahkan elemen overlay ke dalam body
                document.body.appendChild(overlay);

                var buttons = document.querySelectorAll('button, input[type="button"], input[type="submit"], input[type="reset"]');
                buttons.forEach(function(button) {
                    button.disabled = true;
                });
            }
        });
    </script>

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
