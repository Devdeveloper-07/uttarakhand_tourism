<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Uttarakhand Travels · @yield('title', 'Account')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('frontend/css/main.css') }}?v={{ filemtime(public_path('frontend/css/main.css')) }}">
</head>
<body class="account-page">
    @include('frontend.partials.header')
    @isset($header)<div class="container pt-4"><h1 class="h3 mb-0">{{ $header }}</h1></div>@endisset
    <main class="container py-4">{{ $slot }}</main>
    @include('frontend.partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
