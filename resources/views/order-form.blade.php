@extends('layouts.app')

@section('title', 'Pesan Tiket - ' . $destination->name)

@section('content')
    <div class="relative min-h-screen bg-cover bg-center"
        style="background-image: url('{{ Storage::url($destination->photos->first()->path ?? 'default-image.jpg') }}')">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative z-10 flex items-center justify-center min-h-screen px-4">
            <div class="w-full max-w-4xl mx-auto px-5 py-5 bg-white rounded-lg shadow-lg">

                <!-- Gambar dan Form dalam satu card yang nyambung -->
                <div class="flex flex-col md:flex-row w-full space-y-6 md:space-y-0 md:space-x-6">

                    <!-- Gambar dengan Teks -->
                    <div class="md:w-1/2 relative rounded-l-lg overflow-hidden">
                        <!-- Gambar -->
                        <img src="{{ Storage::url($destination->photos->first()->path) }}" alt="Destination Image"
                            class="w-full h-64 object-cover md:h-full" />

                        <!-- Teks di atas gambar -->
                        <div class="absolute bottom-4 left-4  text-white px-3 py-1 rounded">
                            <p class="text-xl font-semibold"> {{ $destination->name }}</p>
                        </div>
                    </div>

                    <!-- Formulir Pesan Tiket -->
                    <div class="md:w-1/2 p-4 space-y-4">
                        <h1 class="text-2xl font-semibold text-center mb-4 text-green-700">Pesan Tiket</h1>

                        <form id="orderForm" method="POST" action="{{ route('order.processOrder') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="destination_id" value="{{ $destination->id }}">

                            <!-- Nama -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                                <input type="text" id="name" name="name" placeholder="Nama" required
                                    class="mt-1 block w-full  rounded-md  bg-slate-200 focus:ring-1  focus:ring-green-500 focus:border-green-500 transition duration-300 shadow-sm focus:outline-none text-sm p-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" name="email" placeholder="Email" required
                                    class="mt-1 block w-full rounded-md bg-slate-200 focus:ring-1  border-gray-300  focus:ring-green-500 focus:border-green-500 transition duration-300 shadow-sm focus:outline-none text-sm p-2" />
                            </div>

                            <!-- No HP -->
                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor HP</label>
                                <input type="text" id="phone_number" name="phone_number" placeholder="Nomor HP" required
                                    class="mt-1 block w-full rounded-md bg-slate-200 focus:ring-1  border-gray-300  focus:ring-green-500 focus:border-green-500 transition duration-300 shadow-sm focus:outline-none text-sm p-2" />
                            </div>

                            <!-- Jumlah Tiket -->
                            <div>
                                <label for="total_tickets" class="block text-sm font-medium text-gray-700">Jumlah
                                    Tiket</label>
                                <input type="number" id="total_tickets" name="total_tickets" placeholder="Jumlah Tiket"
                                    required
                                    class="mt-1 block w-full rounded-md bg-slate-200 focus:ring-1  border-gray-300  focus:ring-green-500 focus:border-green-500 transition duration-300 shadow-sm focus:outline-none text-sm p-2" />
                            </div>

                            <!-- Total Harga -->
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700">Total Harga</label>
                                <input type="text" id="amount" name="amount" readonly
                                    class="mt-1 block w-full rounded-md  border-gray-300 bg-gray-200 text-gray-500 transition duration-300 shadow-sm focus:outline-none text-sm p-2" />
                            </div>

                            <div class="flex  gap-8">
                                <button type="button" onclick="window.location.href='{{ route('home') }}'"
                                    class="w-full bg-white border border-green-500 text-green-500  hover:text-white font-semibold py-2 px-4 rounded-md relative overflow-hidden group text-sm">
                                    <span
                                        class="absolute inset-0 bg-green-600 transition-all duration-300 transform -translate-x-full group-hover:translate-x-0"></span>
                                    <span class="relative z-10">Cancel</span>
                                </button>

                                <button type="submit"
                                    class="w-full bg-green-500 text-white font-semibold py-2 px-4 rounded-md hover:bg-green-600 transition duration-300 transform hover:scale-105 text-sm">
                                    Pesan Tiket
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



    </div>
@endsection

@section('scripts')
    <script>
        const ticketPrice = {{ $destination->ticket_price }}; // Harga tiket dari database

        // Menghitung total harga saat jumlah tiket berubah
        document.getElementById('total_tickets').addEventListener('input', function() {
            const total = this.value * ticketPrice;
            const formattedAmount = total ? `IDR ${total.toLocaleString()}` : ''; // Format IDR
            document.getElementById('amount').value = formattedAmount; // Menampilkan formatted amount
        });

        // Validasi tambahan sebelum submit form
        document.getElementById('orderForm').addEventListener('submit', function(event) {
            const totalTickets = document.getElementById('total_tickets').value;
            if (!totalTickets || totalTickets <= 0) {
                alert('Jumlah tiket harus lebih dari 0!');
                event.preventDefault(); // Hentikan submit form jika tidak valid
            }
        });
    </script>
@endsection
