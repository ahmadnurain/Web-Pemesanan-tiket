@extends('layouts.app')

@section('title','Halaman tidak ditemukan')

@section('content')
    <div class="text-center py-16">
        <div class="text-7xl mb-4">🧭</div>
        <h1 class="text-2xl font-bold mb-2">Halaman tidak ditemukan</h1>
        <p class="text-gray-600 mb-6">Maaf, halaman yang Anda cari tidak tersedia.</p>
        <div class="flex items-center justify-center gap-3">
            <a href="/" class="rounded-md bg-emerald-600 text-white px-4 py-2 hover:bg-emerald-700">Kembali ke Beranda</a>
            <a href="/destinasi" class="rounded-md border px-4 py-2 hover:bg-slate-50">Lihat Destinasi</a>
        </div>
    </div>
@endsection
