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

    <!-- Hero Section -->
    <section class="relative w-full h-[50vh] flex items-center justify-center overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0">
            <img src="{{ Storage::url($destination->photos->first()->path ?? 'default-image.jpg') }}" alt="Background"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-emerald-900/90"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto mt-16">
            <h1 class="text-white text-3xl md:text-5xl font-bold mb-2 drop-shadow-lg reveal">Pembayaran</h1>
            <p class="text-white/90 text-lg md:text-xl max-w-2xl mx-auto reveal-2">
                Selesaikan pembayaran untuk <span class="text-emerald-300 font-semibold">{{ $destination->name }}</span>.
            </p>
        </div>
    </section>
@endsection

@section('content')
    <div class="relative -mt-20 z-20 pb-20">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl border border-white/50 overflow-hidden">
                <div class="p-6 md:p-10">
                    <!-- Stepper -->
                    <div class="flex items-center justify-center gap-4 mb-10">
                        <div class="flex items-center gap-2 opacity-60">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">1</div>
                            <span class="font-medium text-gray-600 hidden md:inline">Data Pemesan</span>
                        </div>
                        <div class="w-12 h-0.5 bg-gray-300"></div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-sm shadow-lg shadow-emerald-500/30">2</div>
                            <span class="font-bold text-emerald-800 hidden md:inline">Pembayaran</span>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Customer Details -->
                        <div class="space-y-6">
                            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2 border-b pb-2 border-gray-200">
                                <i class="fas fa-user-circle text-emerald-600"></i> Data Pemesan
                            </h3>
                            <div class="space-y-4">
                                <div class="bg-white/50 p-4 rounded-xl border border-white/60 shadow-sm">
                                    <p class="text-sm text-gray-500 mb-1">Nama Lengkap</p>
                                    <p class="font-semibold text-gray-800 text-lg">{{ $customer->name }}</p>
                                </div>
                                <div class="bg-white/50 p-4 rounded-xl border border-white/60 shadow-sm">
                                    <p class="text-sm text-gray-500 mb-1">Email</p>
                                    <p class="font-semibold text-gray-800 text-lg">{{ $customer->email }}</p>
                                </div>
                                <div class="bg-white/50 p-4 rounded-xl border border-white/60 shadow-sm">
                                    <p class="text-sm text-gray-500 mb-1">Nomor Telepon</p>
                                    <p class="font-semibold text-gray-800 text-lg">{{ $customer->phone_number }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="space-y-6">
                            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2 border-b pb-2 border-gray-200">
                                <i class="fas fa-receipt text-emerald-600"></i> Ringkasan Pesanan
                            </h3>
                            <div class="bg-emerald-50/50 p-6 rounded-2xl border border-emerald-100">
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-gray-600">Destinasi</span>
                                    <span class="font-semibold text-gray-800 text-right">{{ $destination->name }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-gray-600">Jumlah Tiket</span>
                                    <span class="font-semibold text-gray-800">{{ $customer->total_tickets }}x</span>
                                </div>
                                <div class="border-t border-dashed border-emerald-200 my-4"></div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-800 font-bold text-lg">Total Bayar</span>
                                    <span class="text-emerald-700 font-bold text-2xl">IDR {{ number_format($customer->amount, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Payment Button -->
                            <div class="pt-4">
                                <button id="pay-button" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2 group">
                                    <span>Bayar Sekarang</span>
                                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </button>
                                <div id="payment-status" class="mt-4 text-center text-sm font-medium"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            // SnapToken is passed from Laravel view
            var snapToken = @json($snapToken); // Safely passing the snapToken from Blade
            document.getElementById('payment-status').innerHTML =
                '<span class="inline-flex items-center gap-2 text-green-700"><svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg> Membuka pembayaran...</span>';
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    // Handle successful payment response
                    console.log('Payment Success:', result);
                    document.getElementById('payment-status').innerHTML =
                        '<span class="text-green-700 font-semibold">Pembayaran Sukses!</span>';
                    // Redirect ke route finalize via POST (agar URL bersih)
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('payment.finalize') }}";

                    var csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = "{{ csrf_token() }}";
                    form.appendChild(csrf);

                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'snap_token';
                    input.value = snapToken;
                    form.appendChild(input);

                    document.body.appendChild(form);
                    form.submit();
                },
                onPending: function(result) {
                    // Handle pending payment response
                    console.log('Payment Pending:', result);
                    document.getElementById('payment-status').innerHTML =
                        '<span class="text-amber-600">Pembayaran Tertunda.</span>';
                },
                onError: function(result) {
                    // Handle payment error response
                    console.log('Payment Error:', result);
                    document.getElementById('payment-status').innerHTML =
                        '<span class="text-red-600">Pembayaran Gagal.</span>';
                }
            });
        };
    </script>

@endsection
