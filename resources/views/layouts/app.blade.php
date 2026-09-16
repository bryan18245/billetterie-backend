<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ShopCI — Votre boutique en ligne')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @stack('styles')
</head>

<body>

    @include('partials.header')

    <main class="main-content">
        @include('partials.alert')

        @yield('content')
    </main>
    @include('partials.newsletter') {{-- ← ajoute cette ligne --}}

    @include('partials.footer')

    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')

</body>

</html>