<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Uttarakhand Tourism</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admin/css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/hotel.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/hotelcreate.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/romm.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/gallery.css') }}">


    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

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

</body>
</html>
