<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        
        <link rel="stylesheet" href="{{ asset('css/webapp.css') }}">
        <link rel="stylesheet" href="{{ asset('css/footer.css') }}">


        <!-- Scripts -->
        <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
    </head>
    <body>

        @include ('layouts.partials._header')

        <main>
            @yield('content')
        </main>

        @include ('layouts.partials._footer')

    </body>
</html>
