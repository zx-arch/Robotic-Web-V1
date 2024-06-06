<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-5-dashboard/style.css')}}">
    <link href="{{asset('assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <style>
        .info-box {
            display: flex;
            align-items: center;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .info-box:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .info-box-icon {
            background: rgba(0, 123, 255, 0.1);
            border-radius: 50%;
            padding: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
            color: #007bff;
            margin-right: 20px;
        }

        .info-box-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .info-box-text {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
        }

        .info-box-number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }

    </style>

    <link rel="stylesheet" href="{{asset('assets/css/customcolor.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

</head>

<body>
    <div class="wrapper">

        @include('user.partials.sidebar')

        <div class="main">
            @include('user.partials.navbar')
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="{{asset('plugins/bootstrap-5-dashboard/script.js')}}"></script>
</body>

</html>