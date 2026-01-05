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

                            <!-- Tipe Tiket -->
                            <div>
                                <label for="ticket_type" class="block text-sm font-bold text-gray-700 mb-1">Tipe Tiket</label>
                                <select id="ticket_type" name="ticket_type"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-sm px-4 py-3">
                                    <option value="dewasa" selected>Dewasa</option>
                                    <option value="anak">Anak</option>
                                </select>
                            </div>

                            <!-- Jumlah Tiket -->
                            <div class="md:col-span-2">
                                <label for="total_tickets" class="block text-sm font-bold text-gray-700 mb-1">Jumlah Tiket</label>
                                <input type="number" id="total_tickets" name="total_tickets" placeholder="1"
                                    required min="1" step="1"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-sm px-4 py-3" />
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
        // === Ambil harga tiket secara aman (hindari 60.000 -> 60) ===
        const ticketPrice = (() => {
            const raw = @json($destination->ticket_price); // aman untuk number/string
            if (typeof raw === 'number') return raw; // contoh: 60000
            // kalau string, buang semua non-digit (titik/koma/IDR)
            const cleaned = String(raw).replace(/[^\d]/g, '');
            return parseInt(cleaned, 10) || 0;
        })();

        // --- Util: format IDR ---
        function fmtIDR(n) {
            return n ? `IDR ${Number(n).toLocaleString('id-ID')}` : 'IDR 0';
        }

        // --- Ambil elemen sekali ---
        const qtyEl = document.getElementById('total_tickets');
        const amtEl = document.getElementById('amount'); // input tampilan (readonly)
        const sumQty = document.getElementById('summaryTickets');
        const sumTot = document.getElementById('summaryTotal');
        const dateEl = document.getElementById('visit_date');
        const typeEl = document.getElementById('ticket_type');

        // (Opsional) kalau kamu punya hidden integer murni:
        // <input type="hidden" id="amount_raw" name="amount" />
        const amtRaw = document.getElementById('amount_raw');

        // --- Hitung & render total ---
        function computeAndRenderTotal() {
            const qty = Math.max(1, parseInt(qtyEl?.value || '1', 10) || 1);
            if (qtyEl) qtyEl.value = qty;

            // kalau nanti tipe tiket beda harga, bisa pakai factor di sini
            // let factor = (typeEl?.value === 'anak') ? 0.8 : 1;
            // const total = ticketPrice * qty * factor;
            const total = ticketPrice * qty;

            if (amtEl) amtEl.value = fmtIDR(total); // tampilan
            if (amtRaw) amtRaw.value = total; // kirim integer murni ke server (opsional)
            if (sumQty) sumQty.textContent = qty;
            if (sumTot) sumTot.textContent = fmtIDR(total);
        }

        // --- Pasang listener DULU ---
        qtyEl?.addEventListener('input', computeAndRenderTotal);
        typeEl?.addEventListener('change', computeAndRenderTotal);

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
                if (typeEl && data.type) typeEl.value = data.type;
                if (qtyEl && data.qty) qtyEl.value = data.qty;

                computeAndRenderTotal(); // hitung setelah prefill
                sessionStorage.removeItem('prefill_order');
            } catch {
                computeAndRenderTotal();
            }
        })();

        // --- Validasi submit sederhana ---
        document.getElementById('orderForm')?.addEventListener('submit', function(e) {
            const totalTickets = parseInt(qtyEl?.value || '0', 10) || 0;
            if (totalTickets <= 0) {
                alert('Jumlah tiket harus lebih dari 0!');
                e.preventDefault();
            }
        });
        // --- Validasi Input (Nama, HP, Email) ---
        const nameInput = document.getElementById('name');
        const nameError = document.getElementById('nameError');
        
        const phoneInput = document.getElementById('phone_number'); 
        const phoneError = document.getElementById('phoneError');
        
        const emailInput = document.getElementById('email');
        const submitBtn = document.getElementById('submitBtn');

        function validateInputs() {
            if (!nameInput || !phoneInput || !submitBtn) return;

            // 1. Validasi Nama (Huruf & Spasi)
            const nameVal = nameInput.value;
            const isNameValid = /^[a-zA-Z\s]*$/.test(nameVal);
            
            if (!isNameValid) {
                nameError.classList.remove('hidden');
            } else {
                nameError.classList.add('hidden');
            }

            // 2. Validasi HP (Angka Saja)
            const phoneVal = phoneInput.value;
            const isPhoneValid = /^[0-9]*$/.test(phoneVal);

            if (!isPhoneValid) {
                phoneError.classList.remove('hidden');
            } else {
                phoneError.classList.add('hidden');
            }

            // 3. Tombol Submit (Disable jika salah satu error)
            if (!isNameValid || !isPhoneValid) {
                submitBtn.disabled = true;
            } else {
                submitBtn.disabled = false;
            }
        }

        // --- Event Listeners ---

        if (nameInput) {
            nameInput.addEventListener('input', function() {
                // Limit 50 chars
                if (this.value.length > 50) this.value = this.value.slice(0, 50);
                validateInputs();
            });
        }

        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                // Limit 13 chars
                if (this.value.length > 13) this.value = this.value.slice(0, 13);
                // Real-time clean (opsional, tapi user minta error message, jadi validasi saja)
                validateInputs();
            });
        }

        if (emailInput) {
            emailInput.addEventListener('input', function() {
                // Limit 50 chars only
                if (this.value.length > 50) this.value = this.value.slice(0, 50);
            });
        }

    </script>

@endsection
