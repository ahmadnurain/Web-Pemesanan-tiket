@extends('layouts.app')

@section('title', 'Pesan Tiket - ' . $destination->name)

@section('full')
    {{-- Global Background Pattern & Ornaments (Fixed) --}}
    <div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-[0.4]"></div>
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-emerald-200/20 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2 animate-float"></div>
        <div class="absolute top-1/2 right-0 w-[600px] h-[600px] bg-teal-200/20 rounded-full blur-[120px] translate-x-1/3 -translate-y-1/2 animate-float-delayed"></div>
        <div class="absolute bottom-0 left-1/4 w-[400px] h-[400px] bg-blue-200/20 rounded-full blur-[80px] translate-y-1/3 animate-float-reverse"></div>
    </div>

    <!-- Hero terpisah -->
    <section class="relative w-full h-[300px] md:h-[400px] bg-cover bg-center bg-fixed"
        style="background-image: url('{{ Storage::url($destination->photos->first()->path ?? 'default-image.jpg') }}');">
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-transparent"></div>
        
        <div class="relative z-10 h-full flex flex-col justify-center items-center text-center px-4">
            <h1 class="text-white text-3xl md:text-5xl font-bold mb-2 drop-shadow-lg reveal">{{ $destination->name }}</h1>
            <p class="text-white/90 text-lg md:text-xl max-w-2xl reveal-2">Lengkapi data pemesan dan lanjutkan ke pembayaran.</p>
        </div>


    </section>
@endsection

@section('content')

    <!-- Panel konten -->
    <!-- Panel konten -->
    <section class="mt-10 mb-20 reveal-3">
        <div class="w-full max-w-5xl mx-auto px-4">
            <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl border border-white/50 overflow-hidden flex flex-col md:flex-row">
                
                <!-- Gambar dengan Teks -->
                <div class="md:w-5/12 relative min-h-[300px] md:min-h-full">
                    <img src="{{ Storage::url($destination->photos->first()->path) }}" alt="Destination Image"
                        class="absolute inset-0 w-full h-full object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 text-white">
                        <h2 class="text-2xl font-bold mb-1">{{ $destination->name }}</h2>
                        <p class="text-white/80 text-sm"><i class="fas fa-map-marker-alt mr-1"></i> {{ $destination->location }}</p>
                    </div>
                </div>

                <!-- Formulir Pesan Tiket -->
                <div class="md:w-7/12 p-6 md:p-10">
                    <!-- Stepper -->
                    <div class="flex items-center justify-center gap-4 mb-8">
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center text-sm font-bold shadow-lg shadow-emerald-200">1</div>
                            <span class="text-xs font-semibold text-emerald-700">Data Pemesan</span>
                        </div>
                        <div class="w-12 h-0.5 bg-gray-200"></div>
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center text-sm font-bold border border-gray-200">2</div>
                            <span class="text-xs font-medium text-gray-400">Pembayaran</span>
                        </div>
                    </div>

                    <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Formulir Pemesanan</h1>

                    <form id="orderForm" method="POST" action="{{ route('order.processOrder') }}" class="space-y-5">
                        @csrf
                        <input type="hidden" name="destination_id" value="{{ $destination->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Nama -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                                <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap" required maxlength="50"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-sm px-4 py-3" />
                                <p id="nameError" class="text-red-500 text-sm mt-1 hidden">Nama hanya boleh berisi huruf dan spasi.</p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                                <input type="email" id="email" name="email" placeholder="contoh@email.com" required maxlength="50"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-sm px-4 py-3" />
                            </div>

                            <!-- No HP -->
                            <div>
                                <label for="phone_number" class="block text-sm font-bold text-gray-700 mb-1">Nomor HP / WhatsApp</label>
                                <input type="tel" id="phone_number" name="phone_number" placeholder="08xxxxxxxxxx" required maxlength="13"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-sm px-4 py-3" />
                                <p id="phoneError" class="text-red-500 text-sm mt-1 hidden">Nomor HP hanya boleh berisi angka.</p>
                            </div>

                            <!-- Tanggal Kunjungan -->
                            <div>
                                <label for="visit_date" class="block text-sm font-bold text-gray-700 mb-1">Tanggal Kunjungan</label>
                                <input type="date" id="visit_date" name="visit_date"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-sm px-4 py-3" />
                            </div>

                            <!-- LOGIKA TIKET BARU: Multi Type atau Single -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pilihan Tiket</label>
                                
                                @if($destination->ticketTypes && $destination->ticketTypes->count() > 0)
                                    <!-- MULTI TYPE -->
                                    <div class="space-y-3">
                                        @foreach($destination->ticketTypes as $type)
                                            <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl hover:border-emerald-300 transition-colors">
                                                <div>
                                                    <div class="font-bold text-gray-800">{{ $type->name }}</div>
                                                    <div class="text-emerald-600 font-semibold text-sm" data-price="{{ $type->price }}" data-type="multi">
                                                        IDR {{ number_format($type->price, 0, ',', '.') }}
                                                    </div>
                                                    @if($type->description)
                                                        <div class="text-xs text-gray-400 mt-0.5">{{ $type->description }}</div>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <button type="button" class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 flex items-center justify-center font-bold"
                                                        onclick="updateQty('{{ $type->id }}', -1)">-</button>
                                                    
                                                    <input type="number" 
                                                           id="qty_{{ $type->id }}" 
                                                           name="ticket_types[{{ $type->id }}]" 
                                                           value="0" 
                                                           min="0" 
                                                           class="w-12 text-center border-none bg-transparent font-bold text-gray-800 focus:ring-0 p-0 ticket-qty-multi" 
                                                           readonly />

                                                    <button type="button" class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 hover:bg-emerald-200 flex items-center justify-center font-bold"
                                                        onclick="updateQty('{{ $type->id }}', 1)">+</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- Hidden input untuk kompatibilitas script lama jika perlu, tapi kita akan ubah scriptnya -->
                                @else
                                    <!-- LEGACY SINGLE TYPE -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label for="ticket_type" class="block text-xs font-semibold text-gray-500 mb-1">Kategori</label>
                                            <select id="ticket_type" name="ticket_type"
                                                class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-sm px-4 py-3">
                                                <option value="regular" selected>Regular</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="total_tickets" class="block text-xs font-semibold text-gray-500 mb-1">Jumlah</label>
                                            <input type="number" id="total_tickets" name="total_tickets" placeholder="1"
                                                value="1" min="1" step="1"
                                                class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-sm px-4 py-3" />
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Ringkasan Pemesanan -->
                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50/50 p-5 mt-6">
                            <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-receipt text-emerald-600"></i> Ringkasan Pemesanan
                            </h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center justify-between text-gray-600">
                                    <span>Harga per Tiket</span>
                                    <span class="font-medium">IDR {{ number_format($destination->ticket_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between text-gray-600">
                                    <span>Jumlah Tiket</span>
                                    <span class="font-medium" id="summaryTickets">0</span>
                                </div>
                                <div class="h-px bg-emerald-200 my-2"></div>
                                <div class="flex items-center justify-between text-base">
                                    <span class="text-gray-800 font-bold">Total Pembayaran</span>
                                    <span class="text-emerald-700 font-bold text-lg" id="summaryTotal">IDR 0</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="button" onclick="window.location.href='{{ route('home') }}'"
                                class="w-1/3 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 hover:text-gray-800 font-semibold py-3 px-4 rounded-xl transition-all">
                                Batal
                            </button>

                            <button type="submit" id="submitBtn"
                                class="w-2/3 bg-emerald-600 text-white font-bold py-3 px-4 rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-200 hover:shadow-emerald-300 hover:-translate-y-1 transition-all flex items-center justify-center gap-2 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:shadow-none disabled:hover:translate-y-0">
                                Lanjut Pembayaran <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // === Ambil harga tiket legacy (untuk fallback) ===
        const legacyPrice = (() => {
            const raw = @json($destination->ticket_price); 
            if (typeof raw === 'number') return raw; 
            const cleaned = String(raw).replace(/[^\d]/g, '');
            return parseInt(cleaned, 10) || 0;
        })();

        // --- Util: format IDR ---
        function fmtIDR(n) {
            return n ? `IDR ${Number(n).toLocaleString('id-ID')}` : 'IDR 0';
        }

        const sumQtyEl = document.getElementById('summaryTickets');
        const sumTotEl = document.getElementById('summaryTotal');
        const legacyQtyEl = document.getElementById('total_tickets'); // Legacy input

        // --- Fungsi untuk tombol + / - pada Multi Ticket ---
        window.updateQty = function(id, change) {
            const input = document.getElementById('qty_' + id);
            if (!input) return;
            let val = parseInt(input.value || '0', 10);
            val += change;
            if (val < 0) val = 0;
            input.value = val;
            computeAndRenderTotal();
        };

        // --- Hitung & Render Total ---
        function computeAndRenderTotal() {
            let totalQty = 0;
            let totalPrice = 0;

            // Cek apakah mode Multi Ticket aktif?
            const multiInputs = document.querySelectorAll('.ticket-qty-multi');
            
            if (multiInputs.length > 0) {
                // Mode Multi
                multiInputs.forEach(input => {
                    const qty = parseInt(input.value || '0', 10);
                    if (qty > 0) {
                        // Cari harga dari elemen sibling/parent
                        // Struktur: container -> div(kiri) -> price div
                        const container = input.closest('.justify-between'); 
                        const priceEl = container ? container.querySelector('[data-price]') : null;
                        const price = priceEl ? parseInt(priceEl.getAttribute('data-price'), 10) : 0;
                        
                        totalQty += qty;
                        totalPrice += (qty * price);
                    }
                });
            } else {
                // Mode Legacy
                if (legacyQtyEl) {
                    const qty = parseInt(legacyQtyEl.value || '1', 10);
                    // Legacy price
                    totalQty = qty;
                    totalPrice = qty * legacyPrice;
                }
            }

            if (sumQtyEl) sumQtyEl.textContent = totalQty;
            if (sumTotEl) sumTotEl.textContent = fmtIDR(totalPrice);
        }

        // --- Definisi Elemen untuk Prefill (Restored) ---
        const dateEl = document.getElementById('visit_date');
        const typeEl = document.getElementById('ticket_type'); // Legacy select

        // --- Prefill dari sessionStorage, lalu HITUNG ---
        (function prefillFromSessionStorage() {
            const raw = sessionStorage.getItem('prefill_order');
            if (!raw) {
                computeAndRenderTotal();
                return;
            }
            try {
                const data = JSON.parse(raw);
                if (!data || String(data.dest_id) !== String({{ $destination->id }})) {
                    computeAndRenderTotal();
                    return;
                }
                if (dateEl && data.date) dateEl.value = data.date;

                // Handle Legacy Prefill
                if (typeEl && data.type) typeEl.value = data.type;
                if (legacyQtyEl && data.qty) legacyQtyEl.value = data.qty;

                // Handle Multi Tickets Prefill
                if (data.tickets && typeof data.tickets === 'object') {
                    for (const [tid, qty] of Object.entries(data.tickets)) {
                         const el = document.getElementById('qty_' + tid);
                         if (el) el.value = qty;
                         // Jika input native hidden/readonly, trigger input event manual mungkin diperlukan
                         // tapi karena kita panggil computeAndRenderTotal di akhir, display aman.
                    }
                }

                computeAndRenderTotal(); // hitung setelah prefill
                sessionStorage.removeItem('prefill_order');
            } catch (e) {
                console.error(e);
                computeAndRenderTotal();
            }
        })();

        // --- Listeners ---
        // Listener untuk Legacy
        legacyQtyEl?.addEventListener('input', computeAndRenderTotal);
        // Listener untuk Multi (input manual)
        document.querySelectorAll('.ticket-qty-multi').forEach(el => {
            el.addEventListener('input', computeAndRenderTotal);
        });

        // Initialize
        computeAndRenderTotal();

        // --- Validasi Submit ---
        document.getElementById('orderForm')?.addEventListener('submit', function(e) {
            let total = 0;
            const multiInputs = document.querySelectorAll('.ticket-qty-multi');
            if (multiInputs.length > 0) {
                multiInputs.forEach(i => total += parseInt(i.value||0, 10));
            } else {
                total = parseInt(legacyQtyEl?.value||0, 10);
            }

            if (total <= 0) {
                alert('Mohon pilih minimal satu tiket!');
                e.preventDefault();
            }
        });

        // --- Validasi Input Teks (Nama, HP) ---
        // (Tetap sama seperti sebelumnya)
        const nameInput = document.getElementById('name');
        const nameError = document.getElementById('nameError');
        const phoneInput = document.getElementById('phone_number'); 
        const phoneError = document.getElementById('phoneError');
        const submitBtn = document.getElementById('submitBtn');

        function validateInputs() {
            if (!nameInput || !phoneInput || !submitBtn) return;
            const isNameValid = /^[a-zA-Z\s]*$/.test(nameInput.value);
            nameError.classList.toggle('hidden', isNameValid);

            const isPhoneValid = /^[0-9]*$/.test(phoneInput.value);
            phoneError.classList.toggle('hidden', isPhoneValid);

            submitBtn.disabled = (!isNameValid || !isPhoneValid);
        }

        nameInput?.addEventListener('input', () => {
            validateInputs();
        });
        phoneInput?.addEventListener('input', () => {
            if (phoneInput.value.length > 13) phoneInput.value = phoneInput.value.slice(0, 13);
            validateInputs();
        });

    </script>

@endsection
