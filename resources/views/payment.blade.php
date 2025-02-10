@extends('layouts.app')

@section('title', 'Destinasi Wisata')

@section('content')
    <div class="relative min-h-screen bg-cover bg-center"
        style="background-image: url('{{ Storage::url($destination->photos->first()->path ?? 'default-image.jpg') }}')">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative z-10 flex items-center justify-center min-h-screen px-4 ">
            <div class="w-full max-w-4xl mx-auto px-5 py-5 bg-white rounded-[20px] shadow-lg my-[7rem]">

                <!-- Card Foto Wisata -->
                <div class="bg-white rounded-[20px] shadow-lg mb-6 ">
                    <div class="relative">
                        <img src="{{ Storage::url($destination->photos->first()->path ?? 'default-image.jpg') }}"
                            alt="Destination Photo" class="w-full h-72 object-cover rounded-t-[20px]">
                        <div class="absolute bottom-4 left-4 bg-black bg-opacity-50 text-white px-4 py-2 rounded-full">
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="text-xl font-semibold">{{ $destination->name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card utama untuk informasi pembayaran -->
                <div class="space-y-6">

                    <!-- Card untuk data customer -->
                    <div class="bg-gray-100 p-6 rounded-[20px]">
                        <h2 class="text-2xl font-semibold mb-4">Customer</h2>
                        <div class="space-y-4 text-lg">
                            <!-- Nama Customer -->
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-user text-gray-600"></i>
                                <span class="font-medium text-gray-600">Nama:</span>
                                <span class="text-black font-semibold">{{ $customer->name }}</span>
                            </div>

                            <!-- Email Customer -->
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-envelope text-gray-600"></i>
                                <span class="font-medium text-gray-600">Email:</span>
                                <span class="text-black font-semibold">{{ $customer->email }}</span>
                            </div>

                            <!-- No HP Customer -->
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-phone-alt text-gray-600"></i>
                                <span class="font-medium text-gray-600">No HP:</span>
                                <span class="text-black font-semibold">{{ $customer->phone_number }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card untuk data pembelian tiket -->
                    <div class="bg-gray-100 p-6 rounded-[20px] text-lg">
                        <h2 class="text-2xl font-semibold mb-4">Pembelian Tiket</h2>
                        <div class="space-y-4">
                            <!-- Jumlah Tiket -->
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-ticket-alt text-gray-600"></i>
                                <span class="font-medium text-gray-600">Jumlah Tiket:</span>
                                <span class="text-black font-semibold">{{ $customer->total_tickets }}</span>
                            </div>

                            <!-- Total Harga -->
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-dollar-sign text-gray-600"></i>
                                <span class="font-medium text-gray-600">Total Harga:</span>
                                <span class="text-black font-semibold">IDR
                                    {{ number_format($customer->amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Pembayaran -->
                    <div class="flex justify-center mt-6">
                        <button id="pay-button" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">
                            Bayar Sekarang
                        </button>
                    </div>

                    <!-- Status Pembayaran -->
                    <div id="payment-status" class="mt-4 text-gray-700"></div>
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

            snap.pay(snapToken, {
                onSuccess: function(result) {
                    // Handle successful payment response
                    console.log('Payment Success:', result);
                    document.getElementById('payment-status').innerText = 'Pembayaran Sukses!';
                    // Redirect ke route success
                    window.location.href = "{{ route('payment.success') }}?snap_token=" +
                        encodeURIComponent(snapToken);
                },
                onPending: function(result) {
                    // Handle pending payment response
                    console.log('Payment Pending:', result);
                    document.getElementById('payment-status').innerText = 'Pembayaran Tertunda.';
                },
                onError: function(result) {
                    // Handle payment error response
                    console.log('Payment Error:', result);
                    document.getElementById('payment-status').innerText = 'Pembayaran Gagal.';
                }
            });
        };
    </script>

@endsection
