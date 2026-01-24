@extends('layouts.app')

@section('title', ($destination->name ?? 'Destinasi') . ' — Detail Destinasi')

@section('full')
    {{-- Global Background Pattern & Ornaments (Fixed) --}}
    <div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-[0.4]"></div>
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-emerald-200/20 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2 animate-float"></div>
        <div class="absolute top-1/2 right-0 w-[600px] h-[600px] bg-teal-200/20 rounded-full blur-[120px] translate-x-1/3 -translate-y-1/2 animate-float-delayed"></div>
        <div class="absolute bottom-0 left-1/4 w-[400px] h-[400px] bg-blue-200/20 rounded-full blur-[80px] translate-y-1/3 animate-float-reverse"></div>
    </div>

    @php
        $cover = optional($destination->photos->first() ?? null)->path ?? null;
        $coverUrl = $cover ? asset('storage/' . $cover) : asset('images/bg2.webp');
    @endphp
    <section class="relative w-full h-[500px] md:h-[600px] bg-cover bg-center bg-fixed" style="background-image: url('{{ $coverUrl }}');">
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-transparent"></div>
        
        <div class="relative z-10 h-full flex flex-col justify-center px-4 md:px-24 pb-20">
            <nav class="text-sm mb-6 reveal" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-4 py-2 rounded-full border border-white/20 w-fit">
                    <li><a href="/" class="text-white/80 hover:text-white transition-colors">Beranda</a></li>
                    <li class="text-white/60">/</li>
                    <li><a href="/destinasi" class="text-white/80 hover:text-white transition-colors">Destinasi</a></li>
                    <li class="text-white/60">/</li>
                    <li class="text-white font-medium line-clamp-1">{{ $destination->name }}</li>
                </ol>
            </nav>
            
            <h1 class="font-bold text-4xl sm:text-5xl md:text-7xl leading-tight text-white mb-4 drop-shadow-2xl reveal-2">{{ $destination->name }}</h1>
            
            <div class="flex flex-wrap items-center gap-4 text-white/90 text-lg reveal-3">
                <span class="flex items-center gap-2 bg-emerald-600/80 backdrop-blur-sm px-3 py-1 rounded-lg">
                    <i class="fas fa-map-marker-alt"></i> {{ $destination->location }}
                </span>
                <span class="flex items-center gap-2 bg-yellow-500/80 backdrop-blur-sm px-3 py-1 rounded-lg">
                    <i class="fas fa-star"></i> 4.8 (200+ Reviews)
                </span>
            </div>
        </div>


    </section>
@endsection

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 mb-20">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <!-- Left: Gallery & Info -->
            <div class="md:col-span-8 space-y-6">


                <!-- About -->
                <section>
                    <h2 class="text-xl font-semibold mb-2">Tentang Destinasi</h2>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $destination->description }}</p>
                </section>

                <!-- Facilities -->
                @php
                    $facilities = $destination->facilities ?? ['Toilet', 'Parkir', 'Mushola', 'Restoran'];
                @endphp
                <section>
                    <h2 class="text-xl font-semibold mb-2">Fasilitas</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($facilities as $f)
                            <span
                                class="inline-flex items-center gap-2 rounded-full bg-emerald-50 text-emerald-700 px-3 py-1 text-sm"><i
                                    class="fas fa-check-circle"></i>{{ $f }}</span>
                        @endforeach
                    </div>
                </section>

                <!-- Open Hours -->
                <section>
                    <h2 class="text-xl font-semibold mb-2">Jam Operasional</h2>
                    <p class="text-gray-700">{{ $destination->operating_hours ?? 'Setiap hari 07.00–17.00' }}</p>
                </section>

                <!-- Location / Map -->
                @php
                    $mapUrl = $destination->map_embed_url ?? null;
                    if (!$mapUrl && ($destination->lat ?? null) && ($destination->lng ?? null)) {
                        $mapUrl =
                            'https://www.google.com/maps?q=' .
                            $destination->lat .
                            ',' .
                            $destination->lng .
                            '&hl=id&z=15&output=embed';
                    }
                @endphp
                <section>
                    <h2 class="text-xl font-semibold mb-3">Lokasi</h2>
                    <p class="text-gray-700 break-words">{{ $destination->location }}</p>
                    @if ($mapUrl)
                        <div class="mt-3 aspect-[16/9] w-full rounded-xl overflow-hidden shadow">
                            <iframe src="{{ $mapUrl }}" class="w-full h-full" style="border:0;" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade" allowfullscreen
                                aria-label="Peta lokasi {{ $destination->name }}"></iframe>
                        </div>
                        <div class="mt-3">
                            <a href="{{ str_replace('&output=embed', '', $mapUrl) }}" target="_blank" rel="noopener"
                                class="inline-flex items-center gap-2 rounded-md border px-4 py-2 hover:bg-slate-50"><i
                                    class="fas fa-external-link-alt"></i> Buka Peta</a>
                        </div>
                    @endif
                </section>
            </div>

            <!-- Right: Booking Panel -->
            <aside class="md:col-span-4 reveal-2">
                <div class="rounded-3xl bg-white/80 backdrop-blur-md shadow-xl border border-white/50 p-6 sticky top-24"
                    id="bookingPanel">
                    
                    <!-- Header Harga (Hanya muncul jika SINGLE type / Legacy, jika Multi type harga ada di per-item) -->
                    @if($destination->ticketTypes->isEmpty())
                        <div class="flex items-end justify-between mb-6 pb-6 border-b border-gray-100">
                            <div>
                                <div class="text-gray-500 text-sm font-medium mb-1">Harga Tiket Masuk</div>
                                <div class="text-3xl font-bold text-emerald-600">Rp
                                    {{ number_format($destination->ticket_price, 0, ',', '.') }}</div>
                            </div>
                            <div class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded-lg font-bold">
                                Per Orang
                            </div>
                        </div>
                    @else
                        <div class="mb-4 pb-4 border-b border-gray-100">
                            <div class="text-lg font-bold text-gray-800">Pilih Tiket</div>
                            <div class="text-sm text-gray-500">Tentukan jumlah tiket sesuai tipe</div>
                        </div>
                    @endif

                    <div class="space-y-5">
                        <!-- Tanggal Kunjungan -->
                        <div>
                            <label for="visit_date" class="block text-sm font-bold text-gray-700 mb-2">Tanggal Kunjungan</label>
                            <div class="relative">
                                <input type="date" id="visit_date"
                                    class="w-full rounded-xl border-gray-200 bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 min-h-[48px] px-4 py-2 shadow-sm" />
                            </div>
                        </div>
                        
                        <!-- Pilihan Tiket -->
                        @if($destination->ticketTypes->isNotEmpty())
                            <!-- MODE MULTI TIKET -->
                            <div class="space-y-3">
                                @foreach($destination->ticketTypes as $type)
                                    <div class="flex items-center justify-between p-3 rounded-xl border border-gray-200 bg-white hover:border-emerald-200 transition-colors">
                                        <div class="flex-1">
                                            <div class="font-bold text-gray-800 text-sm">{{ $type->name }}</div>
                                            <div class="text-emerald-600 font-semibold text-xs" data-price="{{ $type->price }}">
                                                Rp {{ number_format($type->price, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" class="w-8 h-8 rounded-lg border border-gray-200 bg-gray-50 hover:bg-emerald-50 text-gray-600 flex items-center justify-center font-bold"
                                                onclick="updateDetailQty('{{ $type->id }}', -1)">-</button>
                                            
                                            <input type="number" 
                                                id="qty_{{ $type->id }}" 
                                                data-type-id="{{ $type->id }}"
                                                value="0" 
                                                min="0"
                                                class="w-10 text-center border-none bg-transparent font-bold text-gray-800 focus:ring-0 p-0 detail-qty-multi" 
                                                readonly />

                                            <button type="button" class="w-8 h-8 rounded-lg border border-gray-200 bg-gray-50 hover:bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold"
                                                onclick="updateDetailQty('{{ $type->id }}', 1)">+</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- MODE SINGLE / LEGACY -->
                            <div>
                                <label for="ticket_qty" class="block text-sm font-bold text-gray-700 mb-2">Jumlah Tiket</label>
                                <div class="flex items-center gap-3">
                                    <button type="button" id="legacy_minus" class="w-12 h-12 rounded-xl border border-gray-200 bg-white hover:bg-emerald-50 text-gray-600 flex items-center justify-center text-xl font-bold">−</button>
                                    <input type="number" id="legacy_qty" min="1" value="1"
                                        class="flex-1 text-center rounded-xl border-gray-200 bg-white focus:ring-2 focus:ring-emerald-500 font-bold text-lg" 
                                        readonly />
                                    <button type="button" id="legacy_plus" class="w-12 h-12 rounded-xl border border-gray-200 bg-white hover:bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">+</button>
                                </div>
                            </div>
                        @endif

                        <!-- Total Pembayaran -->
                        <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4 mt-6">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 font-medium">Total Pembayaran</span>
                                <span class="text-xl font-bold text-emerald-700" id="detail_total_display">Rp 0</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 pt-2">
                            <button type="button" id="btn_book_now"
                                class="w-full inline-flex justify-center items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-lg min-h-[52px] px-6 rounded-xl shadow-lg shadow-emerald-200 hover:shadow-emerald-300 hover:-translate-y-1 transition-all">
                                <i class="fas fa-ticket-alt" aria-hidden="true"></i> Pesan Sekarang
                            </button>
                            <button type="button"
                                class="w-full inline-flex justify-center items-center gap-2 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-semibold min-h-[52px] px-6 rounded-xl transition-all"
                                onclick="window.history.back()">
                                Kembali
                            </button>
                        </div>
                        
                        <p class="text-center text-xs text-gray-500 flex items-center justify-center gap-1">
                            <i class="fas fa-shield-alt text-emerald-500"></i> Transaksi Aman & Terpercaya
                        </p>
                    </div>
                </div>
            </aside>
        </div>

        <!-- Similar destinations (tetap sama code aslinya) -->
        <section class="mt-32 reveal">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Mungkin Anda Suka</h2>
                <a href="/destinasi" class="text-emerald-600 font-semibold hover:text-emerald-700 flex items-center gap-2">
                    Lihat Lainnya <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            @php($similar = $similarDestinations ?? collect())
            @if ($similar->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($similar as $item)
                        @php($p = optional($item->photos->first() ?? null)->path ?? null)
                        <a href="{{ route('destinations.show', $item) }}"
                            class="group rounded-2xl overflow-hidden bg-white/80 backdrop-blur-sm shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-white/50 flex flex-col h-full">
                            <div class="relative h-56 overflow-hidden">
                                <img loading="lazy" decoding="async"
                                    src="{{ $p ? asset('storage/' . $p) : asset('images/bg2.webp') }}"
                                    alt="{{ $item->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                            <div class="p-5 flex flex-col flex-grow">
                                <h3 class="font-bold text-xl text-gray-900 mb-2 line-clamp-1 group-hover:text-emerald-600 transition-colors">{{ $item->name }}</h3>
                                <p class="text-sm text-gray-500 mb-4 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-emerald-500"></i>{{ $item->location }}
                                </p>
                                <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center">
                                    <span class="text-xs text-gray-400">Mulai dari</span>
                                    <div class="text-emerald-600 font-bold text-lg">IDR {{ number_format($item->ticket_price, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl bg-white shadow ring-1 ring-gray-950/5 p-6 text-center text-gray-600">Tidak ada
                    rekomendasi destinasi serupa saat ini.</div>
            @endif
        </section>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
             // Logic Multi Ticket
             window.updateDetailQty = (id, change) => {
                const el = document.getElementById('qty_' + id);
                if(!el) return;
                let val = parseInt(el.value || 0);
                val += change;
                if(val < 0) val = 0;
                el.value = val;
                recalcDetailTotal();
             };

             // Logic Legacy Ticket
             const legacyQty = document.getElementById('legacy_qty');
             const legacyBasePrice = {{ (int) $destination->ticket_price }};
             
             document.getElementById('legacy_minus')?.addEventListener('click', () => {
                 if(!legacyQty) return;
                 let v = parseInt(legacyQty.value||1);
                 if(v > 1) v--; 
                 legacyQty.value = v;
                 recalcDetailTotal();
             });
             document.getElementById('legacy_plus')?.addEventListener('click', () => {
                 if(!legacyQty) return;
                 let v = parseInt(legacyQty.value||1);
                 v++;
                 legacyQty.value = v;
                 recalcDetailTotal();
             });

             const totalDisplay = document.getElementById('detail_total_display');
             
             function recalcDetailTotal() {
                 let total = 0;
                 // Cek multi
                 const multiInputs = document.querySelectorAll('.detail-qty-multi');
                 if(multiInputs.length > 0) {
                     multiInputs.forEach(i => {
                         const q = parseInt(i.value||0);
                         // cari price sibling
                         const container = i.closest('.flex-1')?.parentElement; // div flex
                         // ah struktur DOM tadi: flex justify-between -> div flex-1 -> ... 
                         // my structure: 
                         // .flex.items-center.justify-between
                         //    div.flex-1 -> price div [data-price]
                         //    div.flex.gap-2 -> input
                         const row = i.closest('.justify-between');
                         const priceEl = row?.querySelector('[data-price]');
                         const p = parseInt(priceEl?.getAttribute('data-price') || 0);
                         total += q * p;
                     });
                 } else if(legacyQty) {
                     total = parseInt(legacyQty.value||1) * legacyBasePrice;
                 }
                 
                 if(totalDisplay) {
                     totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
                 }
             }

             // Init
             recalcDetailTotal();

             // Handle Tombol Pesan
             const btnBook = document.getElementById('btn_book_now');
             const dateEl = document.getElementById('visit_date');
             
             btnBook?.addEventListener('click', () => {
                 const payload = {
                     dest_id: {{ $destination->id }},
                     date: dateEl?.value || null
                 };

                 const multiInputs = document.querySelectorAll('.detail-qty-multi');
                 if(multiInputs.length > 0) {
                     // Mode Multi
                     let tickets = {};
                     let totalQ = 0;
                     multiInputs.forEach(i => {
                         const q = parseInt(i.value||0);
                         const tid = i.getAttribute('data-type-id');
                         if(q > 0) {
                             tickets[tid] = q;
                             totalQ += q;
                         }
                     });
                     if(totalQ === 0) {
                         alert('Harap pilih minimal 1 tiket.');
                         return;
                     }
                     payload.tickets = tickets; 
                 } else {
                     // Mode Legacy
                     payload.qty = parseInt(legacyQty?.value||1);
                 }

                 // Simpan ke session storage
                 try {
                     sessionStorage.setItem('prefill_order', JSON.stringify(payload));
                 }catch(e){}
                 
                 // Redirect
                 window.location.href = "{{ route('order.form', $destination) }}";
             });
        });
    </script>
@endsection
