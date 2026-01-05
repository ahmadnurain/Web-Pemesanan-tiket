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

    <!-- Hero -->
    <section class="relative w-full h-[300px] md:h-[400px] bg-cover bg-center bg-fixed"
        style="background-image: url('{{ Storage::url($destination->photos->first()->path ?? 'default-image.jpg') }}');">
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-transparent"></div>
        
        <div class="relative z-10 h-full flex flex-col justify-center items-center text-center px-4">
            <div class="w-20 h-20 bg-emerald-500 rounded-full flex items-center justify-center mb-4 shadow-lg shadow-emerald-500/50 reveal">
                <i class="fas fa-check text-4xl text-white"></i>
            </div>
            <h1 class="text-white text-3xl md:text-5xl font-bold mb-2 drop-shadow-lg reveal-2">Pembayaran Berhasil</h1>
            <p class="text-white/90 text-lg md:text-xl max-w-2xl reveal-3">Terima kasih. E-ticket akan dikirim ke email Anda.</p>
        </div>


    </section>
@endsection

@section('content')

    <!-- Konten -->
    <!-- Konten -->
    <section class="mt-10 mb-20 reveal-3">
        <div class="w-full max-w-5xl mx-auto px-4">
            <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl border border-white/50 p-6 md:p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- Left Column: Customer & Payment Status -->
                    <div class="space-y-6">
                        <!-- Card untuk data customer -->
                        <div class="bg-gray-50/80 rounded-2xl p-6 border border-gray-100">
                            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-user-circle text-emerald-600"></i> Data Pemesan
                            </h2>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between border-b border-gray-200 pb-2">
                                    <span class="text-gray-500">Nama</span>
                                    <span class="font-semibold text-gray-800">{{ $customer->name }}</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-200 pb-2">
                                    <span class="text-gray-500">Email</span>
                                    <span class="font-semibold text-gray-800">{{ $customer->email }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">No. HP</span>
                                    <span class="font-semibold text-gray-800">{{ $customer->phone_number }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Status Pembayaran -->
                        <div class="bg-emerald-50/50 rounded-2xl p-6 border border-emerald-100">
                            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-receipt text-emerald-600"></i> Status Pembayaran
                            </h2>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between items-center border-b border-emerald-100 pb-2">
                                    <span class="text-gray-500">Status</span>
                                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">Berhasil</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">Tanggal</span>
                                    <span class="font-semibold text-gray-800">{{ $customer->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Ticket Details -->
                    <div class="space-y-6">
                        <!-- Card untuk data pembelian tiket -->
                        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/10 rounded-bl-full -mr-4 -mt-4"></div>
                            
                            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2 relative z-10">
                                <i class="fas fa-ticket-alt text-emerald-600"></i> Detail Tiket
                            </h2>
                            
                            <div class="space-y-4 relative z-10">
                                <div class="text-center p-4 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                    <div class="text-xs text-gray-500 mb-1">Kode Tiket</div>
                                    <div class="text-2xl font-mono font-bold text-gray-800 tracking-wider">{{ $customer->ticket_code }}</div>
                                    <div class="text-xs text-red-500 mt-1 font-medium">*Simpan kode ini</div>
                                </div>

                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between border-b border-gray-100 pb-2">
                                        <span class="text-gray-500">Jumlah Tiket</span>
                                        <span class="font-semibold text-gray-800">{{ $customer->total_tickets }} Orang</span>
                                    </div>
                                    <div class="flex justify-between border-b border-gray-100 pb-2">
                                        <span class="text-gray-500">Total Harga</span>
                                        <span class="font-bold text-emerald-600 text-lg">IDR {{ number_format($customer->amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-1">
                                        <span class="text-gray-500">Status Tiket</span>
                                        <span class="{{ $customer->ticket_status === 'unused' ? 'text-blue-600 bg-blue-50' : 'text-gray-600 bg-gray-100' }} px-3 py-1 rounded-full text-xs font-bold">
                                            {{ $customer->ticket_status === 'unused' ? 'Belum Digunakan' : 'Sudah Digunakan' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col gap-3">
                            @php
                                $downloadUrl = URL::temporarySignedRoute('ticket.download', now()->addHours(24), [
                                    'transaction' => $customer->uuid,
                                ]);

                                $resendUrl = URL::temporarySignedRoute('ticket.resend', now()->addMinutes(10), [
                                    'transaction' => $customer->uuid,
                                ]);
                            @endphp
                            
                            <a href="{{ $downloadUrl }}"
                                class="w-full inline-flex justify-center items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-emerald-200 hover:shadow-emerald-300 hover:-translate-y-1 transition-all">
                                <i class="fas fa-download"></i> Download E-Ticket (PDF)
                            </a>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <button id="resendBtn" data-url="{{ $resendUrl }}"
                                    class="w-full inline-flex justify-center items-center gap-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 font-semibold py-3 px-4 rounded-xl transition-all">
                                    <i class="fas fa-envelope"></i> Kirim Ulang
                                </button>
                                <a href="/destinasi" 
                                   class="w-full inline-flex justify-center items-center gap-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 font-semibold py-3 px-4 rounded-xl transition-all">
                                    <i class="fas fa-compass"></i> Destinasi Lain
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script>
        const resendBtn = document.getElementById('resendBtn');
        resendBtn?.addEventListener('click', async () => {
            const url = resendBtn.dataset.url;
            resendBtn.disabled = true;
            const prev = resendBtn.innerHTML;
            resendBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Mengirim...';
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                });
                if (!res.ok) throw new Error(await res.text());
                resendBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Dikirim!';
                setTimeout(() => {
                    resendBtn.innerHTML = prev;
                    resendBtn.disabled = false;
                }, 1500);
            } catch (e) {
                resendBtn.innerHTML = prev;
                resendBtn.disabled = false;
                alert('Gagal mengirim ulang e-ticket. Coba beberapa saat lagi.');
            }
        });
    </script>
@endsection
