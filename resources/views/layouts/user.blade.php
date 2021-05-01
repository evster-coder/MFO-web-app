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
        <link rel="stylesheet" href="{{ asset('css/header.css') }}">

        <link rel="stylesheet" href="{{ asset('css/crudindex.css') }}">

        <link rel="stylesheet" href="{{ mix('css/app.css') }}">


        <script src="{{ asset('js/menu.js') }}" defer></script>
        <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>

        
        <!-- Scripts -->
        <script src="{{ asset('js/orgUnitList.js') }}" defer></script>

        <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>

        @stack('assets')
    </head>
    <body>

        <header>
            @include ('layouts.partials._header')
        </header>

        <main>
            <div class="container wrap">
                @yield('content')
            </div>
        </main>

        @include ('layouts.partials._footer')

    </body>
</html>
