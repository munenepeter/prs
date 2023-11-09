<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
 <link rel="icon" href="{{ asset('img/gmk.png') }}" sizes="16x16 32x32" type="image/png">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://use.typekit.net/jvi3grf.css">

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

</head>

<body>
    <main class="uk-sectio uk-section-large uk-section-default uk-flex uk-flex-center uk-flex-middle uk-flex-row"
        uk-height-viewport="expand: true">

        <div class="uk-container uk-container-xsmall">

            <div class="uk-width-1-1 uk-width-2-3@m uk-align-center">
                <div class="uk-tile  uk-padding-large uk-tile-default uk-box-shadow-medium uk-border-rounded">
                    @if (session('status'))
                        <p class="uk-text-success">
                            {{ session('status') }}
                        </p>
                    @endif

                    {{ $slot }}
                </div>
            </div>

        </div>

    </main>


</body>

</html>
