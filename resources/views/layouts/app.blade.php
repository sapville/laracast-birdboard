<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{--        <style>*  { border: red solid 1px; } </style>--}}
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</head>

<body class="font-sans antialiased bg-bgMain {{session('theme')}}">
    @include('layouts.navigation')
    <!-- Page Heading -->
    <header class="bg-bgCard shadow">
        <div class="font-semibold text-xl text-gray-800 max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                {{ $header }}
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main>
        <div class="content max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>
</body>
</html>
