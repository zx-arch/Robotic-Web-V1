<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Absah.id</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- favicon --}}
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('user.partials.sidebar')
        @include('user.partials.navbar')

        @yield('content')
    </div>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <script>
        $(document).ready(function() {});
    </script>


    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
    <script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>
    <script src="{{ asset('dist/js/pages/dashboard.js') }}"></script>
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.has-treeview > a').on('click', function(e) {
                e.preventDefault();
                $('.has-treeview.menu-open').not($(this).parent()).removeClass('menu-open');
                $(this).parent().toggleClass('menu-open');
            });
        });
    </script>
    <script>
        const pajakMenu = document.getElementById("pajak-menu");
        pajakMenu.addEventListener("click", function(e) {
            if (pajakMenu.classList.contains("menu-open")) {
                pajakMenu.classList.remove("menu-open");
            } else {
                pajakMenu.classList.add("menu-open");
            }
        });
    </script>
    <script>
        const pembayaranMenu = document.getElementById("pembayaran-menu");
        pembayaranMenu.addEventListener("click", function(e) {
            if (pembayaranMenu.classList.contains("menu-open")) {
                pembayaranMenu.classList.remove("menu-open");
            } else {
                pembayaranMenu.classList.add("menu-open");
            }
        });
    </script>
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <script>
        $(document).ready(function() {});
    </script>
</body>

</html>
