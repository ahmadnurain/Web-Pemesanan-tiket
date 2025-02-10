@extends('layouts.app')

@section('title', 'Destinasi Wisata')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach ($destinations as $destination)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Ambil foto pertama jika ada -->
                @if ($destination->photos->isNotEmpty())
                    <img src="{{ asset('storage/' . $destination->photos->first()->path) }}" alt="{{ $destination->name }}"
                        class="w-full h-48 object-cover">
                @else
                    <img src="{{ asset('uploads/photos/default.jpg') }}" alt="Default Image" class="w-full h-48 object-cover">
                @endif

                <div class="p-4">
                    <h3 class="text-lg font-semibold">{{ $destination->name }}</h3>
                    <p class="text-gray-600">Lokasi: {{ $destination->location }}</p>
                    <p class="text-gray-700 font-bold">Harga: IDR
                        {{ number_format($destination->ticket_price, 0, ',', '.') }}</p>

                    <a href="{{ route('order.form', ['id' => $destination->id]) }}"
                        class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Pesan Tiket
                    </a>
                </div>
            </div>
        @endforeach
    </div>

@endsection
