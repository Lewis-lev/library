<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'The Reading Room')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notif.css') }}">

</head>

<body class="font-sans antialiased">
    @include('layouts.navigation')

    <div id="notification-toast-container"
        style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 1055; min-width: 250px;">
        <!-- Toasts will be appended dynamically -->
    </div>

    <div class="main-content">
        <main class="py-10">
            @yield('content')
        </main>
    </div>

    @include('layouts.footer')

    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>

    @if (auth()->check() && auth()->user()->role == 'admin')
        <script>
            window.pusherKey = "{{ config('broadcasting.connections.pusher.key') }}";
            window.pusherCluster = "{{ config('broadcasting.connections.pusher.options.cluster') }}";
        </script>
        <script src="{{ asset('js/notif.js') }}"></script>
    @endif
</body>



</html>
