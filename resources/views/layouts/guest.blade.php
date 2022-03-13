<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/webapp.css') }}">
    <link rel="stylesheet" href="{{ asset('css/guest.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</head>
<body>
<header>
    <div class="header-guest">
    </div>
</header>

<main>
    <div class="container">
        @yield('content')
    </div>
</main>

@include ('layouts/partials/_footer')
</body>
</html>
