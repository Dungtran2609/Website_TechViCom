<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('admin_css/css/vendor.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_css/css/icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_css/css/app.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-PvNcazjx3bDAzjlfKEXAMPLEKEY..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('admin_css/css/custom-dropdown.css') }}">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/simplebar@latest/dist/simplebar.min.css" />
    <script src="https://unpkg.com/simplebar@latest/dist/simplebar.min.js"></script>

</head>

<body>
    <div class="wrapper">
        @include('admin.layouts.header')
        @include('admin.layouts.sidebar')


        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>


            @include('admin.layouts.footer')
        </div>
    </div>


    <!-- JS -->
    <script src="{{ asset('admin_css/js/config.js') }}"></script>
    {{-- <script src="{{ asset('admin_css/js/vendor.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('admin_css/js/app.min.js') }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Vendor Javascript (Require in all Page) -->
    <script src="{{ asset('admin_css/js/vendor.js') }}"></script>

    <script src="{{ asset('admin_css/js/app.js') }}"></script>
    <script src="{{ asset('admin_css/js/custom-dropdown.js') }}"></script>


    <!-- Vector Map Js -->
    <script src="{{ asset('admin_css/vendor/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('admin_css/vendor/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ asset('admin_css/vendor/jsvectormap/maps/world.js') }}"></script>


    <!-- Dashboard Js removed - not used -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

    {{-- End main wrapper --}}
    @yield('scripts')
</body>

</html>
