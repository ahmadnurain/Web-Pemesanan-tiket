{{-- NAVBAR (Green & White) --}}
<nav role="navigation" aria-label="Navigasi utama" x-data="{ open: false }" x-init="$watch('open', v => document.body.classList.toggle('overflow-hidden', v))"
    @keydown.escape.window="open = false"
    class="sticky top-0 z-50 bg-white/90 backdrop-blur supports-[backdrop-filter]:bg-white/70 border-b border-green-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16">
        @php
            $isHome = request()->routeIs('home') || request()->is('/');
            $isDestinasi = request()->routeIs('destinasi*') || request()->is('destinasi*');
            $isOrders = request()->is('orders*');
            $isAbout = request()->is('about');
            $isContact = request()->is('contact');

            $navLinkBase =
                'group relative inline-flex items-center gap-2 px-1 py-2 text-sm font-medium text-neutral-800 hover:text-green-700 transition-colors';
            $navUnderline =
                " after:content-[''] after:absolute after:left-0 after:right-0 after:bottom-0 after:h-0.5 after:origin-center after:scale-x-0 after:bg-green-600 after:transition-transform group-hover:after:scale-x-100";
        @endphp

        <div class="flex h-full items-center justify-between">
            {{-- Brand --}}
            <a href="{{ route('home') }}"
                class="flex items-center gap-2 rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500">
                <i class="fas fa-map-marked-alt text-green-600 text-xl"></i>
                <span class="font-semibold tracking-tight text-neutral-900">
                    Wisata Majalengka
                </span>
            </a>

            {{-- Desktop menu --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ url('/') }}"
                    class="{{ $navLinkBase . $navUnderline }} {{ $isHome ? 'text-green-700 after:scale-x-100' : '' }}"
                    aria-current="{{ $isHome ? 'page' : 'false' }}">
                    Beranda
                </a>
                <a href="{{ url('/destinasi') }}"
                    class="{{ $navLinkBase . $navUnderline }} {{ $isDestinasi ? 'text-green-700 after:scale-x-100' : '' }}"
                    aria-current="{{ $isDestinasi ? 'page' : 'false' }}">
                    Destinasi
                </a>
                <a href="{{ url('/pesanan/cek') }}"
                    class="{{ $navLinkBase . $navUnderline }} {{ $isOrders ? 'text-green-700 after:scale-x-100' : '' }}"
                    aria-current="{{ $isOrders ? 'page' : 'false' }}">
                    Cek Pesanan
                </a>
                {{-- <a href="{{ url('/about') }}"
                    class="{{ $navLinkBase . $navUnderline }} {{ $isAbout ? 'text-green-700 after:scale-x-100' : '' }}"
                    aria-current="{{ $isAbout ? 'page' : 'false' }}">
                    Tentang
                </a>
                <a href="{{ url('/contact') }}"
                    class="{{ $navLinkBase . $navUnderline }} {{ $isContact ? 'text-green-700 after:scale-x-100' : '' }}"
                    aria-current="{{ $isContact ? 'page' : 'false' }}">
                    Kontak
                </a> --}}

                {{-- CTA --}}
                <a href="{{ url('/pesanan/cek') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-green-600 text-white px-3.5 py-2 text-sm font-semibold shadow-sm hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500">
                    <i class="fas fa-ticket-alt text-xs"></i>
                    E-Ticket
                </a>

            </div>

            {{-- Hamburger --}}
            <button @click="open = !open" :aria-expanded="open.toString()" aria-controls="mobile-menu-panel"
                aria-label="Buka menu"
                class="md:hidden inline-flex items-center justify-center rounded-lg p-2 text-neutral-800 hover:bg-green-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500">
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Overlay --}}


    {{-- Mobile panel --}}
    <div id="mobile-menu-panel" x-cloak x-show="open" x-transition.origin.top
        class="md:hidden fixed inset-x-0 top-16 z-[60] rounded-b-2xl bg-white shadow-xl border-t border-green-100">
        <div class="px-4 py-3">
            <div class="space-y-1">
                <a href="{{ url('/') }}" @click="open=false"
                    class="block rounded-lg px-3 py-2 text-[15px] {{ $isHome ? 'bg-green-50 text-green-700' : 'text-neutral-800 hover:bg-green-50' }}"
                    aria-current="{{ $isHome ? 'page' : 'false' }}">
                    Beranda
                </a>
                <a href="{{ url('/destinasi') }}" @click="open=false"
                    class="block rounded-lg px-3 py-2 text-[15px] {{ $isDestinasi ? 'bg-green-50 text-green-700' : 'text-neutral-800 hover:bg-green-50' }}">
                    Destinasi
                </a>
                <a href="{{ url('/pesanan/cek') }}" @click="open=false"
                    class="block rounded-lg px-3 py-2 text-[15px] {{ $isOrders ? 'bg-green-50 text-green-700' : 'text-neutral-800 hover:bg-green-50' }}">
                    Cek Pesanan
                </a>
                <a href="{{ url('/about') }}" @click="open=false"
                    class="block rounded-lg px-3 py-2 text:[15px] {{ $isAbout ? 'bg-green-50 text-green-700' : 'text-neutral-800 hover:bg-green-50' }}">
                    Tentang
                </a>
                <a href="{{ url('/contact') }}" @click="open=false"
                    class="block rounded-lg px-3 py-2 text-[15px] {{ $isContact ? 'bg-green-50 text-green-700' : 'text-neutral-800 hover:bg-green-50' }}">
                    Kontak
                </a>
            </div>

            <div class="mt-3 flex items-center gap-2">
                <a href="{{ url('/pesanan/cek') }}" @click="open=false"
                    class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-green-600 text-white px-4 py-2.5 text-sm font-semibold shadow-sm hover:bg-green-700">
                    <i class="fas fa-ticket-alt text-xs"></i>
                    E-Ticket
                </a>

            </div>

            <div class="h-2"> </div>
        </div>

    </div>

</nav>
