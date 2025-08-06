<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('admin_css/css/vendor.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_css/css/icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_css/css/app.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-PvNcazjx3bDAzjlfKEXAMPLEKEY..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('styles')
    <style>
        label.form-label {
            font-weight: 500;
        }

        /* Badge notification hiệu ứng rung nhẹ */
        .notification-badge {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        .btn-notification:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
    </style>
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
    <script src="{{ asset('admin_css/js/vendor.min.js') }}"></script>
    <script src="{{ asset('admin_css/js/app.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Vendor Javascript (Require in all Page) -->
    <script src="{{ asset('admin_css/js/vendor.js') }}"></script>


    <!-- App Javascript (Require in all Page) -->
    <script src="{{ asset('admin_css/js/app.js') }}"></script>


    <!-- Vector Map Js -->
    <script src="{{ asset('admin_css/vendor/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('admin_css/vendor/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ asset('admin_css/vendor/jsvectormap/maps/world.js') }}"></script>


    <!-- Dashboard Js -->
    <script src="{{ asset('admin_css/js/pages/dashboard.js') }}"></script>
    @stack('scripts')

    {{-- End main wrapper --}}
    @yield('scripts')
</body>

</html>
