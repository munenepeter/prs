<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <link rel="icon" href="{{ asset('img/gmk.png') }}" sizes="16x16 32x32" type="image/png">
    <title>{{ config('app.name', 'Laravel') }} </title>

    <link rel="stylesheet" href="https://use.typekit.net/jvi3grf.css">

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body>
    <main>
        {{ $slot }}
    </main>
</body>

</html>
