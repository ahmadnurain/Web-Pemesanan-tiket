@extends('layouts.app')

@section('title', 'Wisata Majalengka - Jelajahi Keindahan Alam')
@push('head')
    <link rel="preload" as="image" href="{{ asset('images/bg2.webp') }}" fetchpriority="high">
@endpush

@section('full')
    <!-- Hero Section -->
    <section class="relative w-full h-screen min-h-[600px] overflow-hidden bg-gray-900">
        <!-- LCP High Priority Hero Image -->
        <img src="{{ asset('images/bg2.webp') }}" 
             alt="Wisata Majalengka Background" 
             width="1920" height="1080"
             fetchpriority="high"
             decoding="async"
             class="absolute inset-0 w-full h-full object-cover object-center z-0">
             
        <div class="absolute inset-0 z-0 bg-gradient-to-b from-black/70 via-black/50 to-transparent"></div>
        
        <div class="relative z-10 flex flex-col items-center justify-center h-full text-white px-4 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-emerald-500/20 border border-emerald-400/30 backdrop-blur-sm text-emerald-100 text-sm font-medium mb-6 reveal">
                ✨ Official Tourism Portal of Majalengka
            </span>
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold leading-tight mb-6 drop-shadow-2xl reveal-2 tracking-tight">
                Majalengka<span class="text-emerald-400">.</span>
            </h1>
            <p class="text-lg md:text-2xl max-w-3xl mb-10 text-gray-100 font-light reveal-3 leading-relaxed">
                Temukan surga tersembunyi di Jawa Barat. Dari pegunungan hijau hingga warisan budaya yang memukau.
            </p>
            
            <div class="flex flex-col md:flex-row gap-4 reveal-4 w-full max-w-md md:max-w-none justify-center">
                <a href="#tren"
                    class="group relative px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full font-semibold transition-all duration-300 shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:shadow-[0_0_30px_rgba(16,185,129,0.5)] flex items-center justify-center gap-2">
                    <span>Mulai Petualangan</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="#kategori"
                    class="px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/30 text-white rounded-full font-semibold transition-all duration-300 flex items-center justify-center">
                    Lihat Kategori
                </a>
            </div>
        </div>


    </section>
@endsection

@section('content')
    <!-- Global Background Pattern & Ornaments (Fixed) -->
    <div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-[0.4]"></div>
        <!-- Decorative Blobs -->
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-emerald-200/20 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2 animate-float"></div>
        <div class="absolute top-1/2 right-0 w-[600px] h-[600px] bg-teal-200/20 rounded-full blur-[120px] translate-x-1/3 -translate-y-1/2 animate-float-delayed"></div>
        <div class="absolute bottom-0 left-1/4 w-[400px] h-[400px] bg-blue-200/20 rounded-full blur-[80px] translate-y-1/3 animate-float-reverse"></div>
    </div>

    <!-- Stats Section -->
    <section class="py-10 relative z-30 -mt-16 md:-mt-24">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 bg-white rounded-2xl shadow-xl p-8 border border-gray-100 reveal relative overflow-hidden">
                <!-- Subtle pattern on card -->
                <div class="absolute top-0 right-0 p-4 opacity-5">
                    <i class="fas fa-map text-9xl text-emerald-900"></i>
                </div>
                
                <div class="text-center relative z-10">
                    <div class="text-3xl md:text-4xl font-bold text-emerald-600 mb-1">50+</div>
                    <div class="text-sm text-gray-500 font-medium uppercase tracking-wider">Destinasi</div>
                </div>
                <div class="text-center border-l border-gray-100 relative z-10">
                    <div class="text-3xl md:text-4xl font-bold text-emerald-600 mb-1">10k+</div>
                    <div class="text-sm text-gray-500 font-medium uppercase tracking-wider">Pengunjung</div>
                </div>
                <div class="text-center border-l border-gray-100 relative z-10">
                    <div class="text-3xl md:text-4xl font-bold text-emerald-600 mb-1">4.8</div>
                    <div class="text-sm text-gray-500 font-medium uppercase tracking-wider">Rating</div>
                </div>
                <div class="text-center border-l border-gray-100 relative z-10">
                    <div class="text-3xl md:text-4xl font-bold text-emerald-600 mb-1">24/7</div>
                    <div class="text-sm text-gray-500 font-medium uppercase tracking-wider">Support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kategori Section -->
    <section id="kategori" class="py-16 relative">
        <div class="relative z-10">
            <div class="text-center mb-12 reveal">
                <span class="text-emerald-600 font-semibold tracking-wider uppercase text-sm">Jelajahi Minatmu</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-gray-900">Kategori Wisata</h2>
                <div class="w-20 h-1.5 bg-emerald-500 mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 reveal-2">
                <!-- Card 1 -->
                <div class="group bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:-translate-y-2 cursor-pointer">
                    <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 transition-colors duration-300">
                        <img src="{{ asset('/images/Mountain.png') }}" width="32" height="32" alt="Alam" class="w-8 h-8 group-hover:brightness-0 group-hover:invert transition-all">
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Wisata Alam</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Nikmati udara segar pegunungan, air terjun yang jernih, dan pemandangan hijau yang menenangkan jiwa.</p>
                </div>

                <!-- Card 2 -->
                <div class="group bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:-translate-y-2 cursor-pointer">
                    <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-orange-500 transition-colors duration-300">
                        <img src="{{ asset('/images/Pagoda.png') }}" width="32" height="32" alt="Budaya" class="w-8 h-8 group-hover:brightness-0 group-hover:invert transition-all">
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Seni & Budaya</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Pelajari sejarah lokal, kunjungi museum, dan saksikan pertunjukan seni tradisional yang memukau.</p>
                </div>

                <!-- Card 3 -->
                <div class="group bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:-translate-y-2 cursor-pointer">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-500 transition-colors duration-300">
                        <img src="{{ asset('/images/Vector (1).png') }}" width="32" height="32" alt="Religi" class="w-8 h-8 group-hover:brightness-0 group-hover:invert transition-all">
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Wisata Religi</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Temukan ketenangan batin dengan mengunjungi masjid bersejarah dan tempat ziarah yang sakral.</p>
                </div>

                <!-- Card 4 -->
                <div class="group bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:-translate-y-2 cursor-pointer">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-500 transition-colors duration-300">
                        <img src="{{ asset('/images/Lap Pool.png') }}" width="32" height="32" alt="Hiburan" class="w-8 h-8 group-hover:brightness-0 group-hover:invert transition-all">
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Rekreasi Keluarga</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Taman bermain modern, waterpark seru, dan spot foto instagramable untuk liburan keluarga.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured/Trending Section -->
    <section id="tren" class="py-20 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 reveal">
                <div>
                    <span class="text-emerald-600 font-semibold tracking-wider uppercase text-sm">Destinasi Populer</span>
                    <h2 class="text-3xl md:text-4xl font-bold mt-2 text-gray-900">Paling Banyak Dikunjungi</h2>
                </div>
                <a href="{{ route('destinations.index') }}" class="hidden md:inline-flex items-center gap-2 text-emerald-600 font-semibold hover:text-emerald-700 transition-colors">
                    Lihat Semua Destinasi <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="owl-carousel reveal-2">
                @foreach ($destinations as $destination)
                    <a href="{{ route('destinations.show', $destination) }}" class="block group h-full">
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden h-full flex flex-col transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                            @php($photo = $destination->photos->first())
                            <div class="relative h-64 overflow-hidden">
                                <img loading="lazy" width="400" height="256"
                                    src="{{ $photo ? Storage::url($photo->path) : asset('images/bg2.webp') }}"
                                    alt="{{ $destination->name }}" 
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-emerald-700 shadow-sm">
                                    Popular
                                </div>
                            </div>
                            <div class="p-6 flex-grow flex flex-col">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-xl font-bold text-gray-900 line-clamp-1 group-hover:text-emerald-600 transition-colors">{{ $destination->name }}</h3>
                                    <div class="flex items-center gap-1 text-yellow-500 text-sm font-bold">
                                        <i class="fas fa-star"></i> 4.8
                                    </div>
                                </div>
                                <p class="text-gray-500 text-sm mb-4 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-emerald-500"></i> {{ $destination->location }}
                                </p>
                                <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                                    <div>
                                        <span class="text-xs text-gray-400 block">Mulai dari</span>
                                        <span class="text-emerald-600 font-bold text-lg">Rp{{ number_format($destination->ticket_price, 0, ',', '.') }}</span>
                                    </div>
                                    <span class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                        <i class="fas fa-arrow-right"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            
            <div class="mt-8 text-center md:hidden">
                <a href="{{ route('destinations.index') }}" class="inline-flex items-center gap-2 text-emerald-600 font-semibold hover:text-emerald-700 transition-colors">
                    Lihat Semua Destinasi <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Why Choose Us / Promo Section -->
    <section class="py-20">
        <div class="bg-emerald-900 rounded-3xl overflow-hidden relative shadow-2xl reveal">
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-emerald-500 rounded-full blur-3xl opacity-20"></div>
            <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-teal-500 rounded-full blur-3xl opacity-20"></div>
            
            <div class="relative z-10 grid md:grid-cols-2 gap-10 items-center p-8 md:p-16">
                <div class="text-white">
                    <h2 class="text-3xl md:text-4xl font-bold mb-6">Kenapa Memilih Wisata Majalengka?</h2>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-emerald-800 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-emerald-400"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">Informasi Terlengkap</h4>
                                <p class="text-emerald-200 text-sm">Detail destinasi, harga tiket, dan fasilitas terupdate.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-emerald-800 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-ticket-alt text-emerald-400"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">Pemesanan Mudah</h4>
                                <p class="text-emerald-200 text-sm">Booking tiket online tanpa antri, cepat dan aman.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-emerald-800 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-headset text-emerald-400"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">Layanan Pelanggan</h4>
                                <p class="text-emerald-200 text-sm">Tim support siap membantu perjalanan wisata Anda.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="relative">
                    <img src="{{ asset('images/bg2.webp') }}" alt="Experience" class="rounded-2xl shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-500 border-4 border-white/10">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 text-center reveal relative">
        <div class="relative z-10">
            <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-6">Siap untuk Berpetualang?</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto mb-10">Jangan lewatkan kesempatan untuk menikmati keindahan alam Majalengka. Pesan tiket Anda sekarang dan buat kenangan tak terlupakan.</p>
            <a href="{{ route('destinations.index') }}" class="inline-block px-10 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full font-bold text-lg shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                Cari Destinasi Sekarang
            </a>
        </div>
    </section>
@endsection

@section('scripts')
    <style>
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
             // Wait for deferred scripts to execute
             // We can check window.jQuery or just run it, usually deferred scripts run before DOMContentLoaded fires completely or right at it.
             // Actually, 'defer' scripts run before 'DOMContentLoaded'.
             
            if (typeof $ !== 'undefined') {
                $(document).ready(function(){
                    var $c = $('.owl-carousel');
                    if ($c.length && typeof $.fn.owlCarousel !== 'undefined') {
                        $c.owlCarousel({
                            loop: false,
                            rewind: true,
                            margin: 24,
                            nav: true,
                            dots: false,
                            navText: [
                                '<i class="fas fa-arrow-left"></i>',
                                '<i class="fas fa-arrow-right"></i>'
                            ],
                            responsive: {
                                0: {
                                    items: 1,
                                    stagePadding: 20
                                },
                                640: {
                                    items: 2
                                },
                                1024: {
                                    items: 3
                                }
                            }
                        });
                    }
                });
            }
            
            if (typeof ScrollReveal !== 'undefined') {
                ScrollReveal().reveal('.reveal', { 
                    distance: '40px',
                    origin: 'bottom',
                    duration: 1000,
                    interval: 150,
                    easing: 'cubic-bezier(0.5, 0, 0, 1)',
                    cleanup: true 
                });
                ScrollReveal().reveal('.reveal-2', { 
                    distance: '40px',
                    origin: 'bottom',
                    duration: 1000,
                    delay: 200,
                    interval: 150,
                    easing: 'cubic-bezier(0.5, 0, 0, 1)'
                });
                ScrollReveal().reveal('.reveal-3', { 
                    distance: '40px',
                    origin: 'bottom',
                    duration: 1000,
                    delay: 400,
                    easing: 'cubic-bezier(0.5, 0, 0, 1)'
                });
                ScrollReveal().reveal('.reveal-4', { 
                    distance: '40px',
                    origin: 'bottom',
                    duration: 1000,
                    delay: 600,
                    easing: 'cubic-bezier(0.5, 0, 0, 1)'
                });
            }
        });
    </script>
@endsection
