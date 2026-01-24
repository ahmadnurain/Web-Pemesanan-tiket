@extends('layouts.app')

@section('title', 'Hasil Pencarian Pesanan')

@section('full')
    {{-- Global Background Pattern --}}
    <div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-[0.4]"></div>
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-emerald-200/20 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2 animate-float"></div>
        <div class="absolute top-1/2 right-0 w-[600px] h-[600px] bg-teal-200/20 rounded-full blur-[120px] translate-x-1/3 -translate-y-1/2 animate-float-delayed"></div>
    </div>

    {{-- Hero --}}
    <section class="relative w-full h-[250px] bg-cover bg-center bg-fixed"
        style="background-image: url('{{ asset('images/bg2.webp') }}');">
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-transparent"></div>
        <div class="relative z-10 h-full flex flex-col justify-center items-center text-center px-4">
            <h1 class="text-white text-3xl md:text-4xl font-bold mb-2 drop-shadow-lg reveal">Pesanan Anda</h1>
            <p class="text-white/90 text-lg reveal-2">
                Berikut adalah daftar transaksi yang ditemukan untuk email <span class="font-bold underline">{{ session('lookup_email') }}</span>.
            </p>
        </div>
    </section>
@endsection

@section('content')
    <section class="max-w-4xl mx-auto mt-10 mb-20 px-4 reveal-3">
        
        @if(session('status'))
            <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 flex items-start gap-3">
                <i class="fas fa-check-circle mt-1"></i>
                <div>{{ session('status') }}</div>
            </div>
        @endif

        <div class="grid gap-6">
            @forelse($transactions as $tx)
                <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-lg border border-white/50 overflow-hidden flex flex-col md:flex-row">
                    {{-- Left Stripe (Status Color) --}}
                    <div class="w-full md:w-2 {{ $tx->isPaymentSucceeded() ? 'bg-emerald-500' : ($tx->payment_status == 'pending' ? 'bg-orange-400' : 'bg-red-500') }}"></div>

                    <div class="p-6 flex-1">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                            <div>
                                <div class="text-xs font-bold text-gray-500 uppercase tracking-wide">Kode Tiket</div>
                                <div class="text-xl font-bold text-gray-900">{{ $tx->ticket_code }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-bold text-gray-500 uppercase tracking-wide">Tanggal Kunjungan</div>
                                <div class="text-lg font-bold text-emerald-700">
                                    {{ $tx->visit_date ? \Carbon\Carbon::parse($tx->visit_date)->isoFormat('dddd, D MMMM Y') : 'Tiket Terbuka' }}
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 my-4 pt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Destinasi</div>
                                <div class="font-semibold text-gray-800">{{ $tx->destination->name ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Total Tiket</div>
                                <div class="font-semibold text-gray-800">
                                    {{ $tx->total_tickets }} Orang
                                    @if($tx->items->count() > 0)
                                        <span class="text-xs text-gray-400 block">
                                            ({{ $tx->items->map(fn($i) => $i->quantity . ' ' . $i->name)->join(', ') }})
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Status</div>
                                @if($tx->isPaymentSucceeded())
                                    @if($tx->isUsed())
                                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                                            <i class="fas fa-check-double"></i> Sudah Dipakai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">
                                            <i class="fas fa-check"></i> Aktif / Lunas
                                        </span>
                                    @endif
                                @elseif($tx->payment_status == 'pending')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-medium text-orange-800">
                                        <i class="fas fa-clock"></i> Menunggu Bayar
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                        <i class="fas fa-times"></i> {{ ucfirst($tx->payment_status) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="mt-6 flex flex-wrap gap-3">
                            @if($tx->isPaymentSucceeded())
                                {{-- RESCHEDULE BUTTON --}}
                                @if(!$tx->isUsed())
                                    <a href="{{ route('orders.reschedule.form', $tx) }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-emerald-600 transition-colors shadow-sm">
                                        <i class="fas fa-calendar-alt text-emerald-500"></i> Reschedule
                                    </a>
                                @endif
                                
                                {{-- DOWNLOAD BUTTON --}}
                                <form action="{{ route('orders.lookup.send_link', $tx) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-emerald-700 transition-colors shadow-md shadow-emerald-100">
                                        <i class="fas fa-envelope"></i> Kirim E-Ticket ke Email
                                    </button>
                                </form>
                            @else
                                <div class="text-sm text-gray-500 italic">Selesaikan pembayaran untuk mengunduh tiket.</div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center bg-white rounded-2xl shadow">
                    <div class="text-gray-400 text-5xl mb-4"><i class="fas fa-search"></i></div>
                    <h3 class="text-lg font-bold text-gray-600">Tidak ada transaksi ditemukan.</h3>
                    <p class="text-gray-400">Silakan coba pencarian kembali.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('orders.lookup.form') }}" class="text-gray-500 hover:text-emerald-700 font-medium transition-colors inline-flex items-center gap-1">
                <i class="fas fa-arrow-left"></i> Cari Pesanan Lain
            </a>
        </div>
    </section>
@endsection
