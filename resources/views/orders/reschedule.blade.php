@extends('layouts.app')

@section('title', 'Reschedule Tiket - ' . $transaction->ticket_code)

@section('full')
    {{-- Global Background --}}
    <div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-[0.4]"></div>
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-emerald-200/20 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2 animate-float"></div>
    </div>
@endsection

@section('content')
    <section class="max-w-xl mx-auto mt-20 mb-20 px-4 reveal">
        <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl border border-white/50 p-8">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 mb-3">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Ubah Jadwal Kunjungan</h1>
                <p class="text-gray-500 mt-1">Kode Tiket: {{ $transaction->ticket_code }}</p>
            </div>

            @if(session('error'))
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 flex items-start gap-3">
                    <i class="fas fa-exclamation-circle mt-1"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <form action="{{ route('orders.reschedule.process', $transaction) }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- Info Tiket --}}
                <div class="bg-gray-50 rounded-xl p-4 text-sm border border-gray-100">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-500">Destinasi</span>
                        <span class="font-bold text-gray-800">{{ $transaction->destination->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Jadwal Saat Ini</span>
                        <span class="font-bold text-gray-800">
                            {{ $transaction->visit_date ? \Carbon\Carbon::parse($transaction->visit_date)->isoFormat('dddd, D MMMM Y') : 'Tiket Terbuka' }}
                        </span>
                    </div>
                </div>

                {{-- Input Tanggal Baru --}}
                <div>
                    <label for="visit_date" class="block text-sm font-bold text-gray-700 mb-2">Pilih Tanggal Baru</label>
                    <input type="date" id="visit_date" name="visit_date" required
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        class="w-full rounded-xl border-gray-200 bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 min-h-[48px] px-4 py-2 shadow-sm" />
                    <p class="text-xs text-gray-400 mt-2">
                        * Ubah jadwal hanya dapat dilakukan untuk tanggal setelah hari ini.
                    </p>
                    @error('visit_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('orders.lookup.result') }}" 
                       class="w-1/3 inline-flex justify-center items-center py-3 bg-white border border-gray-200 text-gray-600 font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="w-2/3 inline-flex justify-center items-center py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-transform active:scale-95">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </section>
@endsection
