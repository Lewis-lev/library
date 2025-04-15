<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>

<body class="bg-gray-100 dark:bg-gray-900">

    <!-- Include Navbar -->
    @include('layouts.navigation')

    <!-- Page Content -->
    <div class="container mx-auto mt-4">
        @yield('content')
    </div>

</body>

</html>
