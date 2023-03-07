<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Share+Tech&display=swap" rel="stylesheet">
                
        <!-- Bootstrap CSS -->
        {{-- dev --}}
        @vite(['resources/js/app.js'])
        {{-- prod --}}
        {{-- <link rel="stylesheet" href="build/assets/app-09ee2eee.css"> --}}
    </head>

    <body class="d-flex flex-column min-vh-100 antialiased">

        @include('includes.header')
        
        @include('includes.navbar')

        @include('includes.jumbotron')
        {{-- start content --}}
        @yield('content')
        {{-- end content --}}

        @include('includes.footer')


        <!-- Bootstrap JS -->
        {{-- prod --}}
        {{-- <script src="build/assets/app-8fc1926a.js"></script> --}}
    </body>
</html>
