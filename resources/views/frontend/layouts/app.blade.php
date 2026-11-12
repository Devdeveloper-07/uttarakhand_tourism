<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Uttarakhand Travels')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('frontend/css/main.css') }}?v={{ filemtime(public_path('frontend/css/main.css')) }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/details.css') }}?v={{ filemtime(public_path('frontend/css/details.css')) }}">
    @stack('styles')
</head>

<body class="frontend-site">

    @include('frontend.partials.header')

    <main>

        @yield('content')

    </main>

    @include('frontend.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('frontend/js/script.js') }}"></script>

    @stack('scripts')

</body>

</html>
