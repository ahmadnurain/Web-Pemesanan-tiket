@extends('layouts.app')

@section('title', 'Destinasi Wisata')

@section('content')
    <div class="relative min-h-screen bg-cover bg-center"
        style="background-image: url('{{ Storage::url($destination->photos->first()->path ?? 'default-image.jpg') }}')">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative z-10 flex items-center justify-center min-h-screen px-4">
            <div class="w-full max-w-4xl mx-auto px-5 py-5 bg-white rounded-[20px] shadow-lg my-[7rem]">

                <!-- Card Foto Wisata -->
                <div class="bg-white rounded-[20px] shadow-lg mb-6">
                    <h1 class="text-4xl font-semibold text-green-500 text-center mb-5">Pembayaran Berhasil !!</h1>
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

                            <!-- Ticket Code -->
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-qrcode text-gray-600"></i>
                                <span class="font-medium text-gray-600">Kode Tiket:</span>
                                <span class="text-black font-semibold">
                                    {{ $customer->ticket_code }}
                                    <span class="text-red-500 font-bold">
                                        (Harap catat kode tiket Anda!!)
                                    </span>
                                </span>


                            </div>
                        </div>
                    </div>

                    <!-- Status Pembayaran -->
                    <div class="bg-gray-100 p-6 rounded-[20px] text-lg">
                        <h2 class="text-2xl font-semibold mb-4">Status Pembayaran</h2>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span class="font-medium text-gray-600">Status:</span>
                                <span class="text-black font-semibold">Berhasil</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-calendar-alt text-gray-600"></i>
                                <span class="font-medium text-gray-600">Tanggal Pembayaran:</span>
                                <span class="text-black font-semibold">{{ $customer->created_at }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Tiket -->
                    <div class="bg-gray-100 p-6 rounded-[20px] text-lg">
                        <h2 class="text-2xl font-semibold mb-4">Status Tiket</h2>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <i
                                    class="fas fa-ticket-alt {{ $customer->ticket_status === 'unused' ? 'text-blue-500' : 'text-green-500' }}"></i>
                                <span class="font-medium text-gray-600">Status Tiket:</span>
                                <span class="text-black font-semibold">
                                    {{ $customer->ticket_status === 'unused' ? 'Belum Digunakan' : 'Sudah Digunakan' }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
