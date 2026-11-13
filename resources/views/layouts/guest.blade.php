<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('assets/logo/logo333.png') }}">

        <title>@yield('title', 'Uttarakhand Travels')</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="{{ asset('frontend/css/auth.css') }}?v={{ filemtime(public_path('frontend/css/auth.css')) }}">
        @stack('styles')
    </head>
    <body class="auth-page">
        <div class="auth-shell">
            <a class="auth-site-brand" href="{{ route('home') }}" aria-label="Uttarakhand Travels home">
                <span class="auth-brand-emblem" aria-hidden="true"><i class="fa-solid fa-mountain-sun"></i></span>
                <span>Uttarakhand <strong>Travels</strong></span>
            </a>
            {{ $slot }}
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
</html>
