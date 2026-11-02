<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Uttarakhand Tourism')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('frontend/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/hotel.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/hotelview.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/contact.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/gallery.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/about.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/packageshow.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/package.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/footer.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('frontend/css/premium.css') }}?v={{ filemtime(public_path('frontend/css/premium.css')) }}">
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
