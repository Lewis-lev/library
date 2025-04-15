<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    @include('layouts.navigation')

    <main class="max-w-7xl mx-auto py-6">
        <h1 class="text-2xl font-bold">Admin Dashboard</h1>
        {{ $slot }}
    </main>
</body>
</html>
