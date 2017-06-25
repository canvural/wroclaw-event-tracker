<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Wroclaw Event Tracker') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://unpkg.com/tachyons/css/tachyons.min.css">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
</head>
<body class="w-100 sans-serif">
    @include('layouts.partials.nav')
    <main class="center ph4">

        <div id="app">
            {{ $slot }}
        </div>
    </main>

    <!-- Scripts -->
    <script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>
