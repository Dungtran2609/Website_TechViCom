<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('logo_techvicom.ico') }}">
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

    <!-- Back to Top Button Styles -->
    <style>
        #scrollToTopBtn {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 9998;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            outline: none;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s, transform 0.3s;
        }

        #scrollToTopBtn.show {
            opacity: 1;
            pointer-events: auto;
        }

        #scrollToTopBtn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        #scrollToTopBtn i {
            font-size: 1.3rem;
            color: #222;
        }

        @media (max-width: 600px) {
            #scrollToTopBtn {
                right: 16px;
                bottom: 16px;
                width: 40px;
                height: 40px;
            }
            
            #scrollToTopBtn i {
                font-size: 1.1rem;
            }
        }
    </style>

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

    <!-- Back to Top Button -->
    <button id="scrollToTopBtn" title="Lên đầu trang">
        <i class="fas fa-chevron-up"></i>
    </button>

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

    <!-- Back to Top Button Script -->
    <script>
        const scrollBtn = document.getElementById('scrollToTopBtn');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                scrollBtn.classList.add('show');
            } else {
                scrollBtn.classList.remove('show');
            }
        });
        scrollBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>

    @stack('scripts')

    {{-- End main wrapper --}}
    @yield('scripts')

    
</body>

</html>
