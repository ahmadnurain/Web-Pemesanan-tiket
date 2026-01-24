<x-filament-panels::page>
    <div class="grid gap-6 md:grid-cols-2">
        {{-- LEFT COLUMN: SCANNER --}}
        <div class="space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Scan QR Code</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Arahkan kamera ke QR Code tiket pengunjung.</p>
                    </div>
                    <div class="rounded-full bg-emerald-50 p-2 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                        <x-heroicon-o-qr-code class="h-6 w-6" />
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    {{-- PENTING: wire:ignore supaya Livewire tidak merusak DOM kamera saat re-render --}}
                    <div id="reader" wire:ignore class="h-64 w-full object-cover"></div>
                    <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                        <div class="h-48 w-48 rounded-lg border-2 border-white/50 shadow-[0_0_0_9999px_rgba(0,0,0,0.5)] "></div>
                    </div>
                </div>

                <div class="mt-4 flex items-start gap-2 rounded-lg bg-blue-50 p-3 text-xs text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                    <x-heroicon-o-information-circle class="h-5 w-5 shrink-0" />
                    <p>Pastikan pencahayaan cukup dan QR Code terlihat jelas di dalam kotak panduan.</p>
                </div>
            </div>

            {{-- INPUT MANUAL --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="mb-3 text-sm font-bold text-gray-800 dark:text-gray-100">Input Manual</h3>
                <div class="flex gap-3">
                    <div class="flex-1">
                        <x-filament::input.wrapper>
                            <x-filament::input wire:model.defer="manualCode"
                                placeholder="TKT-XXXXXXXX" class="uppercase" />
                        </x-filament::input.wrapper>
                    </div>
                    <x-filament::button wire:click="submitManual" icon="heroicon-o-magnifying-glass" color="gray">
                        Cek
                    </x-filament::button>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: RESULT --}}
        <div class="space-y-6">
            @if ($error)
                <div class="rounded-2xl border border-red-200 bg-red-50 p-6 text-center dark:border-red-800 dark:bg-red-900/30">
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300">
                        <x-heroicon-o-x-circle class="h-8 w-8" />
                    </div>
                    <h3 class="text-lg font-bold text-red-800 dark:text-red-200">Gagal Validasi</h3>
                    <p class="text-sm text-red-600 dark:text-red-300">{{ $error }}</p>
                </div>
            @endif

            @if ($result)
                @php
                    $tx = $result['tx'] ?? null;
                    $status = $result['status'] ?? null;
                    
                    // Define full classes to ensure Tailwind JIT picks them up
                    $bgClass = match($status) {
                        'ok' => 'bg-emerald-500',
                        'used' => 'bg-amber-500',
                        'expired' => 'bg-red-500',
                        'too_early' => 'bg-blue-500',
                        default => 'bg-gray-500'
                    };
                    
                    $borderClass = match($status) {
                        'ok' => 'border-emerald-200 dark:border-emerald-700',
                        'used' => 'border-amber-200 dark:border-amber-700',
                        'expired' => 'border-red-200 dark:border-red-700',
                        'too_early' => 'border-blue-200 dark:border-blue-700',
                        default => 'border-gray-200 dark:border-gray-700'
                    };

                    $title = match($status) {
                        'ok' => 'Silakan Masuk',
                        'used' => 'Sudah Check-In',
                        'expired' => 'Akses Ditolak',
                        'too_early' => 'Belum Jadwalnya',
                        default => 'Status Tidak Diketahui'
                    };
                @endphp

                <div class="overflow-hidden rounded-2xl border {{ $borderClass }} bg-white shadow-lg w-full max-w-md mx-auto md:max-w-none dark:bg-gray-800">
                    <div class="{{ $bgClass }} p-6 text-center text-gray-900">
                        {{-- Icon Status --}}
                        <div class="mx-auto mb-3 h-10 w-10 opacity-90 flex items-center justify-center rounded-full bg-white/20 p-1">
                            @if($status === 'ok')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-full w-full">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @elseif($status === 'used')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-full w-full">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            @elseif($status === 'expired')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-full w-full">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            @elseif($status === 'too_early')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-full w-full">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-full w-full">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            @endif
                        </div>
                        
                        <h2 class="text-2xl font-bold tracking-tight">{{ $title }}</h2>
                        <p class="opacity-90 font-mono text-lg mt-1">{{ $tx->ticket_code }}</p>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Nama Pengunjung</span>
                            <span class="font-semibold text-gray-900 text-right dark:text-gray-100">{{ $tx->name }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Destinasi</span>
                            <span class="font-semibold text-gray-900 text-right dark:text-gray-100">{{ $tx->destination->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Tanggal Kunjungan</span>
                            <span class="font-semibold text-gray-900 text-right dark:text-gray-100">
                                {{ $tx->visit_date ? \Carbon\Carbon::parse($tx->visit_date)->format('d M Y') : '-' }}
                            </span>
                        </div>
                        @if($tx->items && $tx->items->count() > 0)
                            <div class="border-b border-gray-100 pb-2 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Rincian Tiket</span>
                                <div class="space-y-1">
                                    @foreach($tx->items as $item)
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-600 dark:text-gray-300">{{ $item->name }}</span>
                                            <span class="font-semibold text-gray-900 dark:text-gray-100">x{{ $item->quantity }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="flex justify-between items-center border-b border-gray-100 pb-2 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Jumlah Tiket</span>
                                <span class="font-semibold text-gray-900 text-right dark:text-gray-100">{{ $tx->total_tickets }} Orang</span>
                            </div>
                        @endif
                        
                        @if($status === 'ok')
                        <div class="mt-4 rounded-lg bg-emerald-50 p-3 text-center text-sm font-medium text-emerald-700 border border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800">
                            <div class="flex items-center justify-center gap-2">
                                <x-heroicon-s-check-circle class="h-5 w-5" />
                                <span>Tiket valid & terverifikasi</span>
                            </div>
                        </div>
                        @elseif($status === 'expired')
                        <div class="mt-4 rounded-lg bg-red-50 p-3 text-center text-sm font-medium text-red-700 border border-red-100 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800">
                            <div class="flex items-center justify-center gap-2">
                                <x-heroicon-s-x-circle class="h-5 w-5" />
                                <span>Masa berlaku tiket telah habis</span>
                            </div>
                            <div class="text-xs mt-1 opacity-80">Berlaku: {{ $tx->visit_date ? \Carbon\Carbon::parse($tx->visit_date)->format('d M Y') : '-' }}</div>
                        </div>
                        @elseif($status === 'too_early')
                        <div class="mt-4 rounded-lg bg-blue-50 p-3 text-center text-sm font-medium text-blue-700 border border-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800">
                            <div class="flex items-center justify-center gap-2">
                                <x-heroicon-s-clock class="h-5 w-5" />
                                <span>Tiket belum memasuki jadwal</span>
                            </div>
                            <div class="text-xs mt-1 opacity-80">Baru berlaku pada: {{ $tx->visit_date ? \Carbon\Carbon::parse($tx->visit_date)->format('d M Y') : '-' }}</div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-3 text-xs text-gray-400 text-center border-t border-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400">
                        Scan ID: {{ $tx->id }} • {{ now()->format('H:i:s') }}
                    </div>
                </div>
            @else
                {{-- Placeholder state --}}
                <div class="flex h-64 flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 text-center dark:border-gray-700 dark:bg-gray-800">
                    <div class="mb-3 rounded-full bg-white p-4 shadow-sm dark:bg-gray-700">
                        <x-heroicon-o-ticket class="h-8 w-8 text-gray-400 dark:text-gray-500" />
                    </div>
                    <h3 class="text-base font-semibold text-gray-600 dark:text-gray-300">Menunggu Scan</h3>
                    <p class="max-w-xs text-sm text-gray-400 dark:text-gray-500">Hasil validasi tiket akan muncul di sini setelah Anda melakukan scan.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- html5-qrcode --}}
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        // ====== Scanner: satu instance global + cooldown ======
  window._qrScannerInstance = window._qrScannerInstance || null;
  window._qrCooldown = false;
  window._qrLast = null;

  const COMPONENT_ID = @json($this->getId());

  function callLivewireHandleScan(text) {
    const comp = window.Livewire.find(COMPONENT_ID);
    if (!comp) return console.warn('Livewire component not found');
    comp.call('handleScan', text);
  }

  function makeDynamicQrbox(viewfinderWidth, viewfinderHeight) {
    // Isi ~75% sisi terpendek supaya area tangkap besar dan "forgiving"
    const minEdge = Math.min(viewfinderWidth, viewfinderHeight);
    const boxSize = Math.floor(minEdge * 0.75);
    return { width: boxSize, height: boxSize };
  }

  function initScannerOnce() {
    if (window._qrScannerInstance) return;

    const el = document.getElementById('reader');
    if (!el) return;

    const onScanSuccess = (decodedText) => {
      if (window._qrCooldown || window._qrLast === decodedText) return;
      window._qrLast = decodedText;
      window._qrCooldown = true;
      callLivewireHandleScan(decodedText);
      setTimeout(() => { window._qrCooldown = false; }, 350); // lebih responsif
    };

    const onScanFailure = (_e) => { /* abaikan noisy errors */ };

    // Konfigurasi "sensitif"
    const config = {
      fps: 24,
      // Dynamic qrbox agar area deteksi besar pada berbagai resolusi
      qrbox: (viewfinderWidth, viewfinderHeight) => makeDynamicQrbox(viewfinderWidth, viewfinderHeight),
      rememberLastUsedCamera: true,
      showTorchButtonIfSupported: true,
      // Gunakan native BarcodeDetector jika tersedia (lebih cepat & toleran)
      experimentalFeatures: { useBarCodeDetectorIfSupported: true },
      // Bantu fokus dan pilih kamera belakang
      videoConstraints: {
        facingMode: { ideal: "environment" },
        focusMode: "continuous", // sebagian device mendukung
        // Bisa tambahkan preferensi resolusi moderat agar noise rendah
        width:  { ideal: 1280 },
        height: { ideal: 720 }
      },
      // Hanya fokus ke QR untuk performa
      formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ],
      // Tipe scan kamera saja (hindari file mode jika tidak dipakai)
      supportedScanTypes: [ Html5QrcodeScanType.SCAN_TYPE_CAMERA ],
    };

    const scanner = new Html5QrcodeScanner('reader', config);
    scanner.render(onScanSuccess, onScanFailure);
    window._qrScannerInstance = scanner;
  }

  document.addEventListener('livewire:load', initScannerOnce);
  document.addEventListener('livewire:navigated', initScannerOnce);
    </script>

    </script>

    <style>
        /* Override html5-qrcode select styles for dark mode visibility */
        #html5-qrcode-select-camera {
            color: #374151 !important; /* gray-700 */
            background-color: #ffffff !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem;
            padding: 0.25rem 0.5rem;
        }
        .dark #html5-qrcode-select-camera {
            color: #e5e7eb !important; /* gray-200 */
            background-color: #374151 !important; /* gray-700 */
            border-color: #4b5563 !important; /* gray-600 */
        }
        
        /* Fix button styles if needed */
        #html5-qrcode-button-camera-permission, 
        #html5-qrcode-button-camera-start, 
        #html5-qrcode-button-camera-stop {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
        }
    </style>

</x-filament-panels::page>
