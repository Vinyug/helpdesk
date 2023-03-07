<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
                
        <!-- Bootstrap CSS -->
        {{-- dev --}}
        @vite(['resources/js/app.js'])
        {{-- prod --}}
        <link rel="stylesheet" href="build/assets/app-09ee2eee.css">
    </head>

    <body class="antialiased">

        @include('includes.header')
        
        @include('includes.navbar')

        {{-- start content --}}
        @yield('content')
        {{-- end content --}}

        @include('includes.footer')

    </body>

    <!-- Bootstrap JS -->
    {{-- prod --}}
    <script src="build/assets/app-8fc1926a.js"></script>
</html>
