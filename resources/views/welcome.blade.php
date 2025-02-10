<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    @include('includes.navbar')
    <!-- Hero Section -->
    <section class="relative w-full h-screen bg-cover bg-center flex-grow"
        style="background-image: url('{{ asset('images/bg2.jpg') }}');">

        <div class="relative z-10 flex flex-col items-start justify-center h-full text-white px-6 md:px-[10rem]">
            <h1 class="text-3xl md:text-6xl lg:text-8xl font-bold leading-tight pb-6 text-white drop-shadow-lg reveal">
                Jelajahi Pesona Majalengka
            </h1>
            <p class="mt-2 text-sm md:text-lg max-w-full md:max-w-2xl pb-6 reveal-2">
                Temukan keindahan alam dan budaya Majalengka hanya dengan beberapa klik. Dari perbukitan hijau hingga
                destinasi wisata bersejarah, semua tersedia di sini. Pesan tiket dengan mudah, nikmati pengalaman wisata
                tanpa ribet!
            </p>
            <button
                class="mt-4 md:mt-6 px-4 py-2 md:px-6 md:py-3 bg-green-700 hover:bg-green-800 rounded-lg shadow-lg font-semibold text-white reveal-3">
                Jelajahi Destinasi
            </button>
        </div>
    </section>

    <section>
        <!-- Card Informasi Kategori -->
        <div class="md:absolute md:bottom-[-10rem] md:left-1/2 transform md:-translate-x-1/2 w-full max-w-7xl px-4">
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 bg-white p-4 shadow-lg rounded-xl md:text-center reveal-4">
                <!-- Alam -->
                <div class="p-4 rounded-lg ">
                    <div class="flex justify-center items-center mb-2 md:mb-4">
                        <img src="{{ asset('/images/Mountain.png') }}" alt="Alam"
                            class="w-8 h-8 md:w-12 md:h-12 bg-green-500 rounded-full p-2">
                    </div>
                    <h3 class="text-base md:text-lg font-semibold">Alam</h3>
                    <p class="mt-2 text-gray-600 text-sm md:text-base lg:text-sm">Destinasi dengan keindahan alam
                        seperti pegunungan, air terjun, dan pantai.</p>
                </div>
                <!-- Budaya -->
                <div class="p-4 rounded-lg ">
                    <div class="flex justify-center items-center mb-2 md:mb-4">
                        <img src="{{ asset('/images/Pagoda.png') }}" alt="Budaya"
                            class="w-8 h-8 md:w-12 md:h-12 bg-green-500 rounded-full p-2">
                    </div>
                    <h3 class="text-base md:text-lg font-semibold">Budaya</h3>
                    <p class="mt-2 text-gray-600 text-sm md:text-base lg:text-sm">Wisata terkait warisan budaya, seperti
                        museum,
                        keraton, dan desa adat.</p>
                </div>
                <!-- Religi -->
                <div class="p-4 rounded-lg ">
                    <div class="flex justify-center items-center mb-2 md:mb-4">
                        <img src="{{ asset('/images/Vector (1).png') }}" alt="Religi"
                            class="w-8 h-8 md:w-12 md:h-12 bg-green-500 rounded-full p-2">
                    </div>
                    <h3 class="text-base md:text-lg font-semibold">Religi</h3>
                    <p class="mt-2 text-gray-600 text-sm md:text-base lg:text-sm">Tempat spiritual seperti masjid, pura,
                        gereja,
                        dan lokasi ziarah.</p>
                </div>
                <!-- Hiburan -->
                <div class="p-4 rounded-lg ">
                    <div class="flex justify-center items-center mb-2 md:mb-4">
                        <img src="{{ asset('/images/Lap Pool.png') }}" alt="Hiburan"
                            class="w-8 h-8 md:w-12 md:h-12 bg-green-500 rounded-full p-2">
                    </div>
                    <h3 class="text-base md:text-lg font-semibold">Hiburan</h3>
                    <p class="mt-2 text-gray-600 text-sm md:text-base lg:text-sm">Aktivitas rekreasi modern seperti
                        taman bermain,
                        bioskop, dan waterpark.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10 bg-white py-[17rem] pb-[10rem] items-center">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl font-bold text-center mb-6 reveal-5">Tren Tahun Ini</h2>
            <!-- Set up your HTML -->
            <div class="owl-carousel reveal-6">
                <!-- Card 1 -->
                @foreach ($destinations as $destination)
                    <!-- Card -->
                    <a href="{{ route('order.form', ['id' => $destination->id]) }}" class="block ">
                        <div
                            class="bg-white rounded-lg shadow-md border overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            {{-- Tampilkan semua foto terkait --}}
                            @foreach ($destination->photos as $photo)
                                <img src="{{ Storage::url($photo->path) }}" alt="{{ $destination->name }}"
                                    class="w-full h-48 object-cover">
                            @endforeach

                            <div class="p-4">
                                <h3 class="text-lg font-semibold ">{{ $destination->name }}</h3>
                                <p class="text-sm text-gray-600 ">{{ $destination->location }}</p>
                                <div class="mt-2 ">
                                    <span
                                        class="text-green-500 font-bold ">Rp{{ number_format($destination->ticket_price, 0, ',', '.') }}</span>
                                    <span class="text-sm text-gray-600 ">/ 1 days</span>
                                </div>
                                <div class="mt-3 flex justify-between items-center ">
                                    <div class="flex items-center space-x-1 text-yellow-500">
                                        <span>⭐ 8.2</span>
                                        <span class="text-gray-600 text-sm">(reviews)</span>
                                    </div>
                                    <button class="text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path
                                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78l1.06 1.06L12 20.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    <footer class="bg-green-700 text-white py-16 px-24 relative overflow-hidden">
        <!-- Blur Overlay -->
        <div class="absolute inset-0 bg-green-700  opacity-50 backdrop-blur-lg z-10"></div>

        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center " style="background-image: url('/images/bg2.jpg'); z-0;"></div>

        <div
            class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center md:items-start space-y-6 md:space-y-0 relative z-20">
            <!-- Section 1: Wisata Majalengka -->
            <div class="text-center md:text-left reveal reveal-1">
                <h2 class="font-bold text-2xl mb-5">Wisata Majalengka</h2>
                <p>Temukan dan pesan tiket wisata favorit Anda di Majalengka!</p>
                <p class="mt-16">&copy; 2024 Wisata Majalengka. Semua hak dilindungi.</p>
            </div>

            <!-- Section 2: Kontak Kami -->
            <div class="text-center md:text-left reveal reveal-2">
                <h2 class="font-bold text-2xl mb-3">Kontak Kami</h2>
                <p class="mb-7">Email: <a href="mailto:info@wisatamajalengka.com"
                        class="underline">info@wisatamajalengka.com</a></p>
                <p class="mb-7">Telepon: <a href="tel:+6281234567890" class="underline">+62 812-3456-7890</a></p>
                <p class="mb-5">Alamat: Jl. Raya Majalengka No. 123, Majalengka, Jawa Barat</p>
            </div>

            <!-- Section 3: Ikuti Kami -->
            <div class="text-center md:text-left reveal reveal-3">
                <h2 class="font-bold text-2xl">Ikuti Kami</h2>
                <div class="flex justify-center md:justify-start space-x-4 mt-2 text-lg">
                    <a href="#" class="hover:text-gray-200"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="hover:text-gray-200"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="hover:text-gray-200"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="hover:text-gray-200"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </footer>







    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="/js/owl.carousel.min.js"></script>
    <script src="/js/script.js"></script>
    <script src="https://unpkg.com/scrollreveal"></script>
</body>
