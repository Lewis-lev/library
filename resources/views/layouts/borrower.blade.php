<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Borrower Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-gray-100">
    @include('layouts.navigation')

    <main class="max-w-7xl mx-auto py-6">
        <h1 class="text-2xl font-bold">Borrower Dashboard</h1>
        {{ $slot }}
    </main>
</body>
</html>
