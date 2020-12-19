<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <script>
        window.serverData = {
            csrfToken: '{{ csrf_token() }}',
            dadataToken: '06bcc8fb27eb96eac7c1f4bcca1c76c3afb408fe',
        };
    </script>

    <div id="root">
        <div class="layout-content">
            <div class="container">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
