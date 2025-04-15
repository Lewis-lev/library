@extends('layouts.guest')

@section('title', 'Welcome')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col justify-center items-center px-6">
    <div class="max-w-4xl text-center">
        <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-white mb-6">
            Welcome to <span class="text-indigo-600">MyLibrary</span>
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
            Discover and borrow books from our growing collection. Whether you're an avid reader or just getting started, we have something for you.
        </p>

        <div class="flex justify-center space-x-4">
            <a href="{{ route('login') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                Login
            </a>
            <a href="{{ route('register') }}" class="px-6 py-3 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition">
                Register
            </a>
        </div>
    </div>
</div>
@endsection
