@extends('layouts.app')

@section('title', 'Cek Pesanan')

@section('full')
    {{-- Global Background Pattern & Ornaments (Fixed) --}}
    <div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-[0.4]"></div>
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-emerald-200/20 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2 animate-float"></div>
        <div class="absolute top-1/2 right-0 w-[600px] h-[600px] bg-teal-200/20 rounded-full blur-[120px] translate-x-1/3 -translate-y-1/2 animate-float-delayed"></div>
        <div class="absolute bottom-0 left-1/4 w-[400px] h-[400px] bg-blue-200/20 rounded-full blur-[80px] translate-y-1/3 animate-float-reverse"></div>
    </div>

    {{-- Page Hero --}}
    <section class="relative w-full h-[300px] md:h-[400px] bg-cover bg-center bg-fixed"
        style="background-image: url('{{ asset('images/bg2.webp') }}');">
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-transparent"></div>
        
        <div class="relative z-10 h-full flex flex-col justify-center items-center text-center px-4">
            <h1 class="text-white text-3xl md:text-5xl font-bold mb-2 drop-shadow-lg reveal">Cek Pesanan / E-Ticket</h1>
            <p class="text-white/90 text-lg md:text-xl max-w-2xl reveal-2">
                Masukkan email + salah satu data verifikasi untuk mendapatkan tautan unduh e-ticket Anda.
            </p>
        </div>


    </section>
@endsection

@section('content')

    {{-- Form Panel --}}
    {{-- Form Panel --}}
    <section class="max-w-xl mx-auto mt-10 mb-20 px-4 reveal-3">
        <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl border border-white/50 p-6 md:p-10">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-600 text-2xl">
                    <i class="fas fa-search"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Cari Pesanan Anda</h2>
                <p class="text-gray-600 mt-2 text-sm">
                    Wajib isi <span class="font-bold text-emerald-700">Email</span> + salah satu dari
                    <span class="font-bold text-emerald-700">Kode Tiket</span> atau
                    <span class="font-bold text-emerald-700">4 digit terakhir No. HP</span>.
                </p>
            </div>

            {{-- Alerts --}}
            @if (session('status'))
                <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 flex items-start gap-3">
                    <i class="fas fa-check-circle mt-1"></i>
                    <div>{{ session('status') }}</div>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 flex items-start gap-3">
                    <i class="fas fa-exclamation-circle mt-1"></i>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="lookupForm" method="POST" action="{{ route('orders.lookup.search') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <input id="email" name="email" type="email" required autocomplete="email"
                            value="{{ old('email') }}" placeholder="mis. nama@email.com"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 pl-11 text-neutral-800 shadow-sm
                                   focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" />
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>

                {{-- Optional Row --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Ticket Code --}}
                    <div>
                        <label for="ticket_code" class="block text-sm font-bold text-gray-700 mb-1">
                            Kode Tiket <span class="text-gray-400 font-normal text-xs">(opsional)</span>
                        </label>
                        <div class="relative">
                            <input id="ticket_code" name="ticket_code" type="text" value="{{ old('ticket_code') }}"
                                placeholder="mis. TKT-ABCD1234" maxlength="14"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 pl-11 text-neutral-800 shadow-sm
                                       focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" />
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-ticket-alt"></i>
                            </span>
                        </div>
                    </div>

                    {{-- Last 4 Phone --}}
                    <div>
                        <label for="phone_last4" class="block text-sm font-bold text-gray-700 mb-1">
                            4 Digit Akhir No. HP <span class="text-gray-400 font-normal text-xs">(opsional)</span>
                        </label>
                        <div class="relative">
                            <input id="phone_last4" name="phone_last4" type="text" inputmode="numeric" pattern="\d{4}"
                                maxlength="4" value="{{ old('phone_last4') }}" placeholder="mis. 1234"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 pl-11 text-neutral-800 shadow-sm
                                       focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" />
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-phone"></i>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Small note --}}
                <div class="rounded-xl bg-blue-50 border border-blue-100 px-4 py-3 text-xs text-blue-800 flex gap-3 items-start">
                    <i class="fas fa-info-circle mt-0.5 text-blue-600"></i>
                    <div>
                        Demi keamanan, kami <span class="font-bold">tidak menampilkan e-ticket langsung</span>.
                        Jika data cocok, sistem akan mengirim <span class="font-bold">tautan unduh bertanda
                        tangan</span> ke email Anda.
                    </div>
                </div>

                {{-- Submit --}}
                <button
                    class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 text-white px-4 py-3.5 font-bold text-lg hover:bg-emerald-700 shadow-lg shadow-emerald-200 hover:shadow-emerald-300 hover:-translate-y-1 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <i class="fas fa-paper-plane"></i> Kirim Tautan ke Email
                </button>
            </form>

            {{-- Optional: tombol kembali --}}
            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-emerald-700 font-medium text-sm transition-colors inline-flex items-center gap-1">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Validasi ringan di sisi klien: email wajib + (ticket_code || phone_last4)
        document.getElementById('lookupForm')?.addEventListener('submit', function(e) {
            const email = document.getElementById('email')?.value.trim();
            const code = document.getElementById('ticket_code')?.value.trim();
            const last4 = document.getElementById('phone_last4')?.value.trim();

            if (!email || (!code && !last4)) {
                e.preventDefault();
                alert('Wajib isi Email + salah satu: Kode Tiket atau 4 Digit Terakhir No. HP.');
            }
        });
    </script>
@endsection
