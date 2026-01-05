{{-- resources/views/destinations.blade.php --}}
@extends('layouts.app')

@section('title', 'Destinasi Wisata')

@section('full')
    {{-- Global Background Pattern & Ornaments (Fixed) --}}
    <div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-[0.4]"></div>
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-emerald-200/20 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2 animate-float"></div>
        <div class="absolute top-1/2 right-0 w-[600px] h-[600px] bg-teal-200/20 rounded-full blur-[120px] translate-x-1/3 -translate-y-1/2 animate-float-delayed"></div>
        <div class="absolute bottom-0 left-1/4 w-[400px] h-[400px] bg-blue-200/20 rounded-full blur-[80px] translate-y-1/3 animate-float-reverse"></div>
    </div>

    {{-- Page Hero --}}
    <section class="relative w-full h-[400px] md:h-[500px] bg-cover bg-center bg-fixed"
        style="background-image: url('{{ asset('images/bg2.webp') }}');">
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-transparent"></div>
        
        <div class="relative z-10 flex flex-col items-center justify-center h-full text-white px-4 text-center">
            <nav class="text-sm mb-4 reveal" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-4 py-2 rounded-full border border-white/20">
                    <li><a href="{{ url('/') }}" class="text-white/80 hover:text-white transition-colors">Beranda</a></li>
                    <li class="text-white/60">/</li>
                    <li class="text-white font-medium">Destinasi</li>
                </ol>
            </nav>
            <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-4 drop-shadow-2xl reveal-2 tracking-tight">
                Jelajahi <span class="text-emerald-400">Destinasi</span>
            </h1>
            <p class="text-lg md:text-xl max-w-2xl text-gray-100 font-light reveal-3 leading-relaxed">
                Temukan keindahan alam dan budaya Majalengka yang memukau. Pilih destinasi favorit Anda sekarang.
            </p>
        </div>


    </section>
@endsection

@section('content')
    {{-- Anti-kedip untuk elemen x-cloak --}}
    <style>
        [x-cloak] {
            display: none !important
        }
    </style>

    @php
        // Safe defaults untuk seluruh view — mencegah undefined & parsing aneh
        $filters = is_array($filters ?? null) ? $filters : (array) ($filters ?? []);
        $categories = $categories ?? [];
        $locations = $locations ?? [];
        $minPrice = (int) ($minPrice ?? 0);
        $maxPrice = (int) ($maxPrice ?? 0);

        // Prepare Category Options for Dropdown
        $categoryOptions = [['value' => '', 'label' => 'Semua Kategori']];
        foreach ($categories as $cat) {
            $id = (string) data_get($cat, 'id', is_string($cat) ? $cat : '');
            $name = is_string($cat) ? $cat : data_get($cat, 'name') ?? 'Tanpa Nama';
            if ($id !== '') {
                $categoryOptions[] = ['value' => $id, 'label' => $name];
            }
        }

        // Handle single category value for dropdown (take first if array)
        $currentCat = $filters['category'] ?? '';
        if (is_array($currentCat)) {
            $currentCat = $currentCat[0] ?? '';
        }
    @endphp

    {{-- FORM GLOBAL: semua filter/sort dikirim via POST ke session --}}
    <form id="filtersForm" method="POST" action="{{ route('destinations.apply') }}"></form>

    {{-- Hidden fields yang dikendalikan dropdown custom (FancySelect) --}}
    <input type="hidden" id="sortField" name="sort" form="filtersForm" value="{{ $filters['sort'] ?? 'newest' }}">
    <input type="hidden" id="locationField" name="location" form="filtersForm" value="{{ $filters['location'] ?? '' }}">
    <input type="hidden" id="categoryField" name="category" form="filtersForm" value="{{ $currentCat }}">

    {{-- Konten utama (filters + hasil) --}}
    <section class="mt-10" x-data="{ filtersOpen: false }">
        {{-- Header + Controls --}}
        <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold">Destinasi Wisata</h2>
                <p class="text-gray-600 text-sm">
                    Menampilkan {{ $destinations->count() }} dari {{ $destinations->total() }} destinasi
                </p>
            </div>

            {{-- Kanan: tombol filter (mobile) + Sort --}}
            <div class="flex items-center gap-3">
                <button type="button" @click="filtersOpen=true"
                    class="md:hidden inline-flex items-center gap-2 rounded-md border px-3 py-2 shadow-sm hover:bg-green-50">
                    <i class="fas fa-sliders-h"></i> Filter
                </button>

                {{-- SORT (FancySelect -> update hidden sortField) --}}
                <div class="relative w-44" x-data="FancySelect({
                    fieldId: 'sortField',
                    placeholder: 'Urutkan',
                    options: [
                        { value: 'newest', label: 'Terbaru' },
                        { value: 'cheapest', label: 'Termurah' },
                        { value: 'popular', label: 'Terlaris' }
                    ]
                })" @keydown="onKeydown($event)">
                    <button type="button" @click="toggle()" :aria-expanded="open.toString()" aria-haspopup="listbox"
                        class="w-full inline-flex items-center justify-between rounded-xl border border-green-200 bg-white px-3 py-2 text-sm font-medium text-neutral-800 shadow-sm hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <span x-text="label"></span>
                        <svg class="h-4 w-4 transition-transform" :class="open && 'rotate-180'" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z" />
                        </svg>
                    </button>
                    <ul x-cloak x-show="open" x-transition role="listbox" :aria-activedescendant="activeId"
                        class="absolute z-20 mt-2 w-full overflow-auto rounded-xl border border-green-200 bg-white shadow-lg max-h-64">
                        <template x-for="(opt, i) in options" :key="opt.value">
                            <li role="option" :id="idFor(i)" :aria-selected="value === opt.value"
                                @mouseenter="activeIndex=i" @mouseleave="activeIndex=-1" @click="select(i)"
                                class="cursor-pointer px-3 py-2 text-sm flex items-center justify-between"
                                :class="(value === opt.value || activeIndex === i) ? 'bg-green-50 text-green-700' :
                                'text-neutral-800 hover:bg-green-50'">
                                <span x-text="opt.label"></span>
                                <svg x-show="value===opt.value" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.704 5.29a1 1 0 010 1.414l-7.01 7.01a1 1 0 01-1.415 0L3.296 8.72A1 1 0 014.71 7.304l3.16 3.16 6.303-6.303a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            {{-- Sidebar Filter (desktop) --}}
            <aside class="hidden md:block md:col-span-1 reveal">
                <div class="rounded-2xl bg-white/80 backdrop-blur-sm shadow-lg border border-white/50 p-6 space-y-8 sticky top-24">
                    {{-- CSRF ditempatkan di dalam form utama --}}
                    <input type="hidden" name="_token" form="filtersForm" value="{{ csrf_token() }}">

                    {{-- Cari (DESKTOP) --}}
                    <div>
                        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-search text-emerald-500"></i> Cari
                        </h3>
                        <div class="relative group">
                            <input id="searchInput" type="text" name="q" form="filtersForm"
                                value="{{ $filters['q'] ?? '' }}" placeholder="Cari destinasi..."
                                class="w-full rounded-xl border border-gray-200 bg-white/50 px-4 py-3 pl-10 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all" />
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>

                    {{-- Kategori (DESKTOP) - Dropdown --}}
                    <div>
                        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-tags text-emerald-500"></i> Kategori
                        </h3>
                        <div class="relative"
                            x-data='FancySelect({
                                fieldId: "categoryField",
                                placeholder: "Semua Kategori",
                                options: @json($categoryOptions)
                             })'
                            @keydown="onKeydown($event)">
                            <button type="button" @click="toggle()" :aria-expanded="open.toString()"
                                aria-haspopup="listbox"
                                class="w-full inline-flex items-center justify-between rounded-xl border border-gray-200 bg-white/50 px-4 py-3 text-sm font-medium text-gray-700 shadow-sm hover:bg-white hover:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                                <span x-text="label"></span>
                                <svg class="h-4 w-4 transition-transform text-gray-400" :class="open && 'rotate-180'"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z" />
                                </svg>
                            </button>
                            <ul x-cloak x-show="open" x-transition role="listbox" :aria-activedescendant="activeId"
                                class="absolute z-20 mt-2 w-full overflow-auto rounded-xl border border-gray-100 bg-white shadow-xl max-h-64 ring-1 ring-black/5 focus:outline-none">
                                <template x-for="(opt, i) in options" :key="opt.value">
                                    <li role="option" :id="idFor(i)" :aria-selected="value === opt.value"
                                        @mouseenter="activeIndex=i" @mouseleave="activeIndex=-1" @click="select(i)"
                                        class="cursor-pointer px-4 py-2.5 text-sm flex items-center justify-between transition-colors"
                                        :class="(value === opt.value || activeIndex === i) ? 'bg-emerald-50 text-emerald-700' :
                                        'text-gray-700 hover:bg-gray-50'">
                                        <span x-text="opt.label"></span>
                                        <svg x-show="value===opt.value" class="h-4 w-4 text-emerald-600" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M16.704 5.29a1 1 0 010 1.414l-7.01 7.01a1 1 0 01-1.415 0L3.296 8.72A1 1 0 014.71 7.304l3.16 3.16 6.303-6.303a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    {{-- Lokasi (DESKTOP) -> FancySelect update hidden locationField --}}
                    <div>
                        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-emerald-500"></i> Lokasi
                        </h3>
                        <div class="relative"
                            x-data='FancySelect({
                                fieldId: "locationField",
                                placeholder: "Semua Lokasi",
                                options: [{ value: "", label: "Semua Lokasi" }, ...(@json($locations)).map(l => ({ value: l, label: l }))]
                             })'
                            @keydown="onKeydown($event)">
                            <button type="button" @click="toggle()" :aria-expanded="open.toString()"
                                aria-haspopup="listbox"
                                class="w-full inline-flex items-center justify-between rounded-xl border border-gray-200 bg-white/50 px-4 py-3 text-sm font-medium text-gray-700 shadow-sm hover:bg-white hover:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                                <span x-text="label"></span>
                                <svg class="h-4 w-4 transition-transform text-gray-400" :class="open && 'rotate-180'"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z" />
                                </svg>
                            </button>
                            <ul x-cloak x-show="open" x-transition role="listbox" :aria-activedescendant="activeId"
                                class="absolute z-20 mt-2 w-full overflow-auto rounded-xl border border-gray-100 bg-white shadow-xl max-h-64 ring-1 ring-black/5 focus:outline-none">
                                <template x-for="(opt, i) in options" :key="opt.value">
                                    <li role="option" :id="idFor(i)" :aria-selected="value === opt.value"
                                        @mouseenter="activeIndex=i" @mouseleave="activeIndex=-1" @click="select(i)"
                                        class="cursor-pointer px-4 py-2.5 text-sm flex items-center justify-between transition-colors"
                                        :class="(value === opt.value || activeIndex === i) ? 'bg-emerald-50 text-emerald-700' :
                                        'text-gray-700 hover:bg-gray-50'">
                                        <span x-text="opt.label"></span>
                                        <svg x-show="value===opt.value" class="h-4 w-4 text-emerald-600" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M16.704 5.29a1 1 0 010 1.414l-7.01 7.01a1 1 0 01-1.415 0L3.296 8.72A1 1 0 014.71 7.304l3.16 3.16 6.303-6.303a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    {{-- Rentang Harga (DESKTOP) --}}
                    <div>
                        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-money-bill-wave text-emerald-500"></i> Harga Maksimal
                        </h3>
                        <input id="priceRange" name="price_max" form="filtersForm" type="range"
                            min="{{ $minPrice }}" max="{{ $maxPrice }}"
                            value="{{ (int) ($filters['price_max'] ?? $maxPrice) }}" class="w-full accent-emerald-600 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                        <div class="text-sm font-medium text-emerald-700 mt-2 text-right">
                            <span id="priceRangeLabel">
                                @if (isset($filters['price_max']) && (int) $filters['price_max'] < (int) $maxPrice)
                                    IDR {{ number_format((int) $filters['price_max'], 0, ',', '.') }}
                                @else
                                    Semua harga
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- Reset (POST) --}}
                    <div class="pt-4 border-t border-gray-100">
                        <form method="POST" action="{{ route('destinations.reset') }}">
                            @csrf
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 text-sm font-medium text-gray-500 hover:text-emerald-600 transition-colors py-2 rounded-lg hover:bg-gray-50">
                                <i class="fas fa-undo-alt"></i> Reset Filter
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            {{-- Mobile Drawer for Filters --}}
            <div x-show="filtersOpen" x-transition class="fixed inset-0 z-50 md:hidden" aria-modal="true"
                role="dialog">
                <div class="absolute inset-0 bg-black/40" @click="filtersOpen=false" aria-hidden="true"></div>
                <div class="absolute left-0 top-0 h-full w-80 max-w-[85%] bg-white shadow-xl p-4 overflow-auto">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold">Filter</h3>
                        <button class="p-2 rounded hover:bg-green-50" @click="filtersOpen=false"
                            aria-label="Tutup filter">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="space-y-6">
                        {{-- Cari (MOBILE - tanpa name; nilai disalin ke input desktop sebelum submit) --}}
                        <div>
                            <h4 class="font-semibold mb-2">Cari</h4>
                            <div class="relative">
                                <input id="searchInputMobile" type="text" value="{{ $filters['q'] ?? '' }}"
                                    placeholder="Cari destinasi atau lokasi..."
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>

                        {{-- Kategori (MOBILE) - Dropdown --}}
                        <div>
                            <h4 class="font-semibold mb-2">Kategori</h4>
                            <div class="relative"
                                x-data='FancySelect({
                                    fieldId: "categoryField",
                                    placeholder: "Semua Kategori",
                                    options: @json($categoryOptions)
                                 })'
                                @keydown="onKeydown($event)">
                                <button type="button" @click="toggle()" :aria-expanded="open.toString()"
                                    aria-haspopup="listbox"
                                    class="w-full inline-flex items-center justify-between rounded-xl border border-green-200 bg-white px-3 py-3 text-sm font-medium text-neutral-800 shadow-sm hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <span x-text="label"></span>
                                    <svg class="h-4 w-4 transition-transform" :class="open && 'rotate-180'"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z" />
                                    </svg>
                                </button>
                                <ul x-cloak x-show="open" x-transition role="listbox" :aria-activedescendant="activeId"
                                    class="absolute z-50 mt-2 w-full overflow-auto rounded-xl border border-green-200 bg-white shadow-lg max-h-64">
                                    <template x-for="(opt, i) in options" :key="opt.value">
                                        <li role="option" :id="idFor(i)" :aria-selected="value === opt.value"
                                            @mouseenter="activeIndex=i" @mouseleave="activeIndex=-1" @click="select(i)"
                                            class="cursor-pointer px-3 py-2 text-sm flex items-center justify-between"
                                            :class="(value === opt.value || activeIndex === i) ? 'bg-green-50 text-green-700' :
                                            'text-neutral-800 hover:bg-green-50'">
                                            <span x-text="opt.label"></span>
                                            <svg x-show="value===opt.value" class="h-4 w-4" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M16.704 5.29a1 1 0 010 1.414l-7.01 7.01a1 1 0 01-1.415 0L3.296 8.72A1 1 0 014.71 7.304l3.16 3.16 6.303-6.303a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        {{-- Lokasi (MOBILE) -> FancySelect update hidden locationField (sama dengan desktop) --}}
                        <div class="relative"
                            x-data='FancySelect({
                                fieldId: "locationField",
                                placeholder: "Semua Lokasi",
                                options: [{ value: "", label: "Semua Lokasi" }, ...(@json($locations)).map(l => ({ value: l, label: l }))]
                             })'
                            @keydown="onKeydown($event)">
                            <button type="button" @click="toggle()" :aria-expanded="open.toString()"
                                aria-haspopup="listbox"
                                class="w-full inline-flex items-center justify-between rounded-xl border border-green-200 bg-white px-3 py-3 text-sm font-medium text-neutral-800 shadow-sm hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                                <span x-text="label"></span>
                                <svg class="h-4 w-4 transition-transform" :class="open && 'rotate-180'"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z" />
                                </svg>
                            </button>
                            <ul x-cloak x-show="open" x-transition role="listbox" :aria-activedescendant="activeId"
                                class="absolute z-50 mt-2 w-full overflow-auto rounded-xl border border-green-200 bg-white shadow-lg max-h-64">
                                <template x-for="(opt, i) in options" :key="opt.value">
                                    <li role="option" :id="idFor(i)" :aria-selected="value === opt.value"
                                        @mouseenter="activeIndex=i" @mouseleave="activeIndex=-1" @click="select(i)"
                                        class="cursor-pointer px-3 py-2 text-sm flex items-center justify-between"
                                        :class="(value === opt.value || activeIndex === i) ? 'bg-green-50 text-green-700' :
                                        'text-neutral-800 hover:bg-green-50'">
                                        <span x-text="opt.label"></span>
                                        <svg x-show="value===opt.value" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M16.704 5.29a1 1 0 010 1.414l-7.01 7.01a1 1 0 01-1.415 0L3.296 8.72A1 1 0 014.71 7.304l3.16 3.16 6.303-6.303a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </li>
                                </template>
                            </ul>
                        </div>

                        {{-- Rentang Harga (MOBILE - tanpa name; sinkron ke desktop sebelum submit) --}}
                        <div>
                            <h4 class="font-semibold mb-2">Rentang Harga</h4>
                            <input id="priceRangeMobile" type="range" min="{{ $minPrice }}"
                                max="{{ $maxPrice }}" value="{{ (int) ($filters['price_max'] ?? $maxPrice) }}"
                                class="w-full">
                            <div class="text-xs text-gray-600 mt-1">
                                Sampai:
                                <span id="priceRangeLabelMobile">
                                    @if (isset($filters['price_max']) && (int) $filters['price_max'] < (int) $maxPrice)
                                        IDR {{ number_format((int) $filters['price_max'], 0, ',', '.') }}
                                    @else
                                        Semua harga
                                    @endif
                                </span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="pt-2 grid grid-cols-2 gap-2">
                            <form method="POST" action="{{ route('destinations.reset') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full rounded-md border px-4 py-2 text-center hover:bg-green-50">
                                    Reset
                                </button>
                            </form>
                            <button id="applyMobile"
                                class="rounded-md bg-emerald-600 text-white px-4 py-2 hover:bg-emerald-700">
                                Terapkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Results Grid --}}
            <section class="md:col-span-3">
                @if ($destinations->count() === 0)
                    <div class="rounded-2xl bg-white shadow ring-1 ring-gray-950/5 p-8 text-center text-gray-600">
                        <div class="text-4xl mb-2">🗺️</div>
                        <p>Tidak ada hasil.</p>
                        <div class="mt-3">
                            <form method="POST" action="{{ route('destinations.reset') }}">
                                @csrf
                                <button type="submit" class="text-emerald-700 underline">Reset filter</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div id="destinationsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 reveal-2">
                        @foreach ($destinations as $destination)
                            @php
                                $firstPhoto = optional($destination->photos)->first();
                                $photoPath = $firstPhoto ? data_get($firstPhoto, 'path') : null;
                                $imgSrc = $photoPath
                                    ? asset('storage/' . $photoPath)
                                    : asset('uploads/photos/default.jpg');
                            @endphp

                            <div
                                class="group rounded-2xl overflow-hidden bg-white/80 backdrop-blur-sm shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-white/50 flex flex-col h-full">
                                <div class="relative h-56 overflow-hidden">
                                    <img src="{{ $imgSrc }}" alt="{{ e($destination->name) }}"
                                        width="400" height="225" loading="lazy"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>

                                    <div class="absolute bottom-4 left-4 right-4">
                                        <h3 class="text-white font-bold text-xl mb-1 leading-tight">{{ $destination->name }}</h3>
                                        <p class="text-white/90 text-sm flex items-center gap-1">
                                            <i class="fas fa-map-marker-alt text-emerald-400"></i> {{ $destination->location }}
                                        </p>
                                    </div>
                                </div>
                                <div class="p-5 flex flex-col flex-grow">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-1 text-yellow-500 text-sm font-bold">
                                            <i class="fas fa-star"></i> 4.8
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs text-gray-400 block">Mulai dari</span>
                                            <span class="text-emerald-600 font-bold text-lg">IDR {{ number_format($destination->ticket_price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-auto pt-4 border-t border-gray-100 flex items-center gap-3">
                                        {{-- Lihat Detail --}}
                                        <a href="{{ route('destinations.show', $destination) }}"
                                            class="flex-1 inline-flex justify-center items-center gap-2 rounded-xl bg-emerald-50 text-emerald-700 px-4 py-2.5 font-semibold hover:bg-emerald-100 transition-colors">
                                            Detail
                                        </a>
                                        {{-- Pesan Tiket --}}
                                        <a href="{{ route('order.form', $destination) }}"
                                            class="flex-1 inline-flex justify-center items-center gap-2 rounded-xl bg-emerald-600 text-white px-4 py-2.5 font-semibold hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:shadow-emerald-300">
                                            <i class="fas fa-ticket-alt"></i> Pesan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-6 flex justify-center">
                        {{ $destinations->onEachSide(1)->links('pagination.custom') }}
                    </div>
                @endif
            </section>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Elemen form & setup
            const form = document.getElementById('filtersForm');
            form?.setAttribute('method', 'POST');
            form?.setAttribute('action', @json(route('destinations.apply')));

            // Pastikan CSRF token ada dalam form global
            if (form && !form.querySelector('input[name="_token"]')) {
                const token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = @json(csrf_token());
                form.appendChild(token);
            }

            // Input/kontrol DESKTOP
            const input = document.getElementById('searchInput'); // name="q"
            const priceRange = document.getElementById('priceRange'); // name="price_max"
            const priceLabel = document.getElementById('priceRangeLabel');
            // const catDesktop = document.querySelectorAll('input[name="category[]"][form="filtersForm"]'); // removed

            // Kontrol MOBILE
            const inputM = document.getElementById('searchInputMobile');
            const priceRangeM = document.getElementById('priceRangeMobile');
            const priceLabelM = document.getElementById('priceRangeLabelMobile');
            const applyBtn = document.getElementById('applyMobile');
            // const catMobile = document.querySelectorAll('.cat-mobile'); // removed

            // Nilai global untuk label harga
            const GLOBAL_MAX = {{ $maxPrice }};
            const fmtIDR = (n) => 'IDR ' + Number(n || 0).toLocaleString('id-ID');

            // Sinkron nilai MOBILE -> DESKTOP (sebelum submit)
            function syncMobileToDesktop() {
                if (input && inputM) input.value = (inputM.value || '').trim();
                if (priceRange && priceRangeM) priceRange.value = priceRangeM.value;
                // Kategori sudah sinkron otomatis via FancySelect (shared fieldId)
            }

            // Label harga
            function updatePriceLabels() {
                const vD = Number(priceRange?.value || GLOBAL_MAX);
                const vM = Number(priceRangeM?.value || GLOBAL_MAX);
                if (priceLabel) priceLabel.textContent = (vD >= GLOBAL_MAX) ? 'Semua harga' : fmtIDR(vD);
                if (priceLabelM) priceLabelM.textContent = (vM >= GLOBAL_MAX) ? 'Semua harga' : fmtIDR(vM);
            }
            updatePriceLabels();

            // Debounce
            const debounce = (fn, d = 400) => {
                let t;
                return (...a) => {
                    clearTimeout(t);
                    t = setTimeout(() => fn(...a), d);
                };
            };
            const debouncedSubmit = debounce(() => form?.submit(), 500);

            // Search (desktop)
            input?.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    form?.submit();
                }
            });
            input?.addEventListener('input', () => {
                const v = (input.value || '').trim();
                if (v.length === 0 || v.length >= 3) debouncedSubmit();
            });

            // Search (mobile)
            inputM?.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    syncMobileToDesktop();
                    form?.submit();
                }
            });
            inputM?.addEventListener('input', () => {
                const v = (inputM.value || '').trim();
                if (v.length === 0 || v.length >= 3) {
                    syncMobileToDesktop();
                    debouncedSubmit();
                }
            });

            // Kategori (FancySelect handles submit automatically on select)
            // catDesktop.forEach(el => el.addEventListener('change', () => form?.submit()));
            // catMobile.forEach(el => el.addEventListener('change', () => {
            //     syncMobileToDesktop();
            //     form?.submit();
            // }));

            // Harga
            priceRange?.addEventListener('input', updatePriceLabels);
            priceRangeM?.addEventListener('input', updatePriceLabels);
            priceRange?.addEventListener('change', () => form?.submit());
            priceRangeM?.addEventListener('change', () => {
                syncMobileToDesktop();
                form?.submit();
            });

            // Mobile Apply
            applyBtn?.addEventListener('click', (e) => {
                e.preventDefault();
                syncMobileToDesktop();
                form?.submit();
            });
        });

        // === FancySelect Component (Sort & Lokasi) ===

        function FancySelect({
            fieldId,
            options = [],
            placeholder = 'Pilih'
        }) {
            return {
                open: false,
                options,
                fieldId,
                value: '',
                label: placeholder,
                activeIndex: -1,
                get activeId() {
                    return this.activeIndex >= 0 ? this.idFor(this.activeIndex) : null
                },
                idFor(i) {
                    return `${this.fieldId}-opt-${i}`
                },

                init() {
                    const hidden = document.getElementById(this.fieldId);
                    this.value = hidden?.value ?? '';
                    const cur = this.options.find(o => o.value == this.value);
                    this.label = cur ? cur.label : placeholder;

                    // sinkron antar instance (desktop & mobile) yang pakai fieldId sama
                    document.addEventListener('fs:update', (e) => {
                        if (e.detail?.fieldId === this.fieldId) {
                            this.value = e.detail.value;
                            const match = this.options.find(o => o.value == this.value);
                            this.label = match ? match.label : placeholder;
                        }
                    });

                    // klik luar menutup
                    document.addEventListener('click', (e) => {
                        if (!this.$el.contains(e.target)) this.open = false;
                    });
                },

                toggle() {
                    this.open = !this.open;
                    if (this.open) {
                        this.activeIndex = Math.max(0, this.options.findIndex(o => o.value == this.value));
                    }
                },

                select(i) {
                    const opt = this.options[i];
                    if (!opt) return;
                    this.value = opt.value;
                    this.label = opt.label;
                    const hidden = document.getElementById(this.fieldId);
                    if (hidden) hidden.value = this.value;

                    // broadcast ke instance lain
                    document.dispatchEvent(new CustomEvent('fs:update', {
                        detail: {
                            fieldId: this.fieldId,
                            value: this.value
                        }
                    }));
                    this.open = false;

                    // submit otomatis
                    const form = document.getElementById('filtersForm');
                    form?.submit();
                },

                onKeydown(e) {
                    if (!this.open && (e.key === ' ' || e.key === 'Enter' || e.key === 'ArrowDown')) {
                        e.preventDefault();
                        this.toggle();
                        return;
                    }
                    if (!this.open) return;

                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        this.activeIndex = (this.activeIndex + 1) % this.options.length;
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        this.activeIndex = (this.activeIndex - 1 + this.options.length) % this.options.length;
                    } else if (e.key === 'Enter') {
                        e.preventDefault();
                        this.select(this.activeIndex >= 0 ? this.activeIndex : 0);
                    } else if (e.key === 'Escape') {
                        e.preventDefault();
                        this.open = false;
                    }
                }
            }
        }
    </script>
@endsection
