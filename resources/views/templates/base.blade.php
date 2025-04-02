<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Event Flow | @yield('page_title')</title>
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <script src="https://kit.fontawesome.com/fe3ed46693.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('libs/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/css/sb-admin-2.min.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('styles')
</head>

<body class="@yield('body-class')">
    @yield('content')

    <script src="{{ asset('libs/js/jquery.min.js') }}"></script>
    <script src="{{ asset('libs/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('libs/js/bootstrap.5.bundle.min.js') }}"></script>
    <script src="{{ asset('libs/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('libs/js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('scripts')
</body>

</html>
