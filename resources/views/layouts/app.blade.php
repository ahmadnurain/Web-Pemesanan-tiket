<!DOCTYPE html>
<html lang="en" class="scroll-smooth">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Tailwind CDN -->

    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Vendor styles -->
    <link rel="stylesheet" href="/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/css/owl.theme.default.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


    <style>
        [x-cloak] {
            display: none !important
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 5px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #059669;
        }

        /* Background Pattern */
        .bg-pattern {
            background-image: radial-gradient(#cbd5e1 1.5px, transparent 1.5px);
            background-size: 24px 24px;
        }

        /* Floating Animation */
        @keyframes float {
            0% { transform: translate(0, 0px); }
            50% { transform: translate(0, 20px); }
            100% { transform: translate(0, -0px); }
        }

        @keyframes float-delayed {
            0% { transform: translate(0, 0px); }
            50% { transform: translate(20px, 20px); }
            100% { transform: translate(0, -0px); }
        }

        @keyframes float-reverse {
            0% { transform: translate(0, 0px); }
            50% { transform: translate(-20px, 10px); }
            100% { transform: translate(0, -0px); }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        .animate-float-delayed {
            animation: float-delayed 8s ease-in-out infinite;
        }
        .animate-float-reverse {
            animation: float-reverse 7s ease-in-out infinite;
        }

        /* Owl Carousel Customization */
        .owl-carousel .owl-stage-outer {
            padding-bottom: 20px; /* Space for shadow */
            padding-top: 10px;
        }
        
        /* Navigation Buttons - Centered Bottom */
        .owl-carousel .owl-nav {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-top: 20px;
        }

        .owl-carousel .owl-nav button.owl-prev,
        .owl-carousel .owl-nav button.owl-next {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: white !important;
            border: 1px solid #e5e7eb !important;
            color: #10b981 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .owl-carousel .owl-nav button.owl-prev:hover,
        .owl-carousel .owl-nav button.owl-next:hover {
            background: #10b981 !important;
            color: white !important;
            border-color: #10b981 !important;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.2);
        }
    </style>

    <title>@yield('title', 'Wisata Majalengka')</title>
    @stack('head')
</head>

<body class="min-h-dvh bg-slate-50 text-gray-800"
    style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'">
    @include('includes.navbar')


    {{-- Slot opsional untuk section full-bleed (hero, banner, dll) --}}
    @yield('full')


    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>


    @include('includes.footer')


    {{-- Script halaman (akan dieksekusi saat window load di child) --}}
    @yield('scripts')


    <!-- Vendor scripts (global only, order matters) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="/js/owl.carousel.min.js" defer></script>
    <script src="https://unpkg.com/scrollreveal" defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/ui.js') }}" defer></script>
</body>


</html>
