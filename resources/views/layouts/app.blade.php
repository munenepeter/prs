<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" xmlns:livewire="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('img/gmk.png') }}" sizes="16x16 32x32" type="image/png">

    @isset($title)
        <title>{{ $title }} - {{ config('app.name', 'Laravel') }}</title>
    @else
        <title>{{ config('app.name', 'PRS') }}</title>
    @endisset

    <link rel="stylesheet" href="https://use.typekit.net/jvi3grf.css">

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <livewire:styles />
</head>

<body>
    <x-navbar />

    <main class="uk-section uk-section-default" id="main" uk-height-viewport="expand:true;offset-top:true;">
        <div class="uk-container uk-container-xlarge">
            <article id="dashboard" class="uk-background-default uk-grid uk-grid-column-medium" uk-grid>
                <div class="uk-width-4-5@m">
                    <div class="uk-panel">
                        <x-statuses />
                        {{ $slot }}
                    </div>
                </div>
                <div class="uk-width-1-5@m uk-visible@m">
                    <x-sidebar />
                </div>
            </article>
        </div>
    </main>

    {{-- 	<x-footer /> --}}

    <livewire:scripts />

    <script>
        document.addEventListener("livewire:load", () => {
            window.livewire.hook("element.updated", (el, component) => {
                if (el.hasAttribute("uk-icon")) {
                    UIkit.icon(el, {
                        icon: el.getAttribute("uk-icon"),
                    });
                }

                if (el.hasAttribute('uk-close')) {
                    UIkit.icon(el, {
                        icon: 'close-icon',
                        ratio: 0.8
                    })
                }

                if (el.hasAttribute("uk-spinner")) {
                    UIkit.spinner(el);
                }

                if (el.hasAttribute('uk-grid')) {
                    UIkit.update(el);
                }

                if (el.hasAttribute("uk-pagination-previous")) {
                    UIkit.icon(el, {
                        icon: 'arrow-left',
                    });
                }

                if (el.hasAttribute("uk-pagination-next")) {
                    UIkit.icon(el, {
                        icon: 'arrow-right',
                    });
                }

                if (el.hasAttribute("uk-img")) {
                    UIkit.img(el, {
                        dataSrc: el.getAttribute("data-src"),
                    });
                }
            });
        });

        function successNotification(message, duration = 3000) {
            UIkit.notification({
                message: `<span class="uk-icon uk-margin-small-right" uk-icon="icon: circle-check;"></span>
					${message}`,
                status: 'success',
                pos: 'top-right',
                timeout: duration
            });
        };

        function dangerNotification(message, duration = 3000) {
            UIkit.notification({
                message: `<span class="uk-icon uk-margin-small-right" uk-icon="icon: circle-x;"></span>
					${message}`,
                status: 'danger',
                pos: 'top-right',
                timeout: duration
            });
        };

        window.addEventListener('access-denied', event => {
            dangerNotification(event.detail.message, 3000);
        });
    </script>

    @stack('scripts')
</body>

</html>
