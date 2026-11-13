<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo/logo333.png') }}">
    <title>@yield('title', 'Dashboard') | Uttarakhand Travels</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('admin/css/main.css') }}?v={{ filemtime(public_path('admin/css/main.css')) }}">
    @if (request()->routeIs('admin.dashboard'))
        <link rel="stylesheet" href="{{ asset('admin/css/dashboard-redesign.css') }}?v={{ filemtime(public_path('admin/css/dashboard-redesign.css')) }}">
    @endif
    @stack('styles')
</head>
<body class="admin-dashboard">

<div class="wrapper">
    @include('layouts.partials.admin-sidebar')

    <div class="main">
        @include('layouts.partials.admin-navbar')

        <div class="content">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('admin/js/admin.js') }}"></script>
@yield('scripts')

</body>
</html>
