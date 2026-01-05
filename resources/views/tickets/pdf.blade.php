<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>E-Ticket - {{ $transaction->ticket_code }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            line-height: 1.5;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #4CAF50;
        }

        .ticket-info,
        .customer-info {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .section-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            color: #4CAF50;
        }

        .row {
            margin-bottom: 8px;
        }

        .label {
            width: 150px;
            display: inline-block;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px dashed #ccc;
            margin-top: 30px;
            padding-top: 10px;
        }

        .qr {
            text-align: center;
            margin-top: 30px;
        }

        .note {
            font-size: 12px;
            color: #666;
            margin-top: 8px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>E-Ticket</h1>
        <p><strong>Kode Tiket:</strong> {{ $transaction->ticket_code }}</p>
    </div>

    <div class="customer-info">
        <div class="section-title">Data Pemesan</div>
        <div class="row"><span class="label">Nama:</span> {{ $transaction->name }}</div>
        <div class="row"><span class="label">Email:</span> {{ $transaction->email }}</div>
        <div class="row"><span class="label">No HP:</span> {{ $transaction->phone_number }}</div>
    </div>

    <div class="ticket-info">
        <div class="section-title">Detail Tiket</div>
        <div class="row"><span class="label">Destinasi:</span> {{ $transaction->destination->name ?? '-' }}</div>
        <div class="row"><span class="label">Jumlah Tiket:</span> {{ $transaction->total_tickets }}</div>
        @if (!empty($transaction->ticket_type))
            <div class="row"><span class="label">Tipe Tiket:</span> {{ ucfirst($transaction->ticket_type) }}</div>
        @endif
        @if (!empty($transaction->visit_date))
            <div class="row"><span class="label">Tanggal Kunjungan:</span>
                {{ \Carbon\Carbon::parse($transaction->visit_date)->translatedFormat('d F Y') }}
            </div>
        @endif
        <div class="row"><span class="label">Total Bayar:</span> Rp
            {{ number_format($transaction->amount, 0, ',', '.') }}</div>
        <div class="row"><span class="label">Status Tiket:</span>
            {{ $transaction->ticket_status === 'unused' ? 'Belum Digunakan' : 'Sudah Digunakan' }}
        </div>
        <div class="row"><span class="label">Tanggal Pembayaran:</span>
            {{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d F Y') }}
        </div>
    </div>


<div class="qr">
    @php
        use SimpleSoftwareIO\QrCode\Facades\QrCode;

        $payload = $transaction->uuid ?: $transaction->ticket_code;

        // hasilkan SVG murni
        $rawSvg = QrCode::format('svg')
            ->size(150)
            ->margin(0)
            ->errorCorrection('M')
            ->generate($payload);

        // buang XML declaration kalau ada (kadang bikin dompdf rewel)
        $rawSvg = preg_replace('/<\?xml.*?\?>/i', '', $rawSvg);

        // jadikan data URI agar diproses sebagai <img>
        $dataUri = 'data:image/svg+xml;base64,' . base64_encode($rawSvg);
    @endphp

    <img src="{{ $dataUri }}" alt="QR Ticket" width="150" height="150">
    <p class="note"><strong>Scan QR ini saat check-in.</strong></p>
</div>

    <div class="footer">
        Terima kasih telah melakukan pemesanan. Tiket ini berlaku untuk tanggal kunjungan yang telah ditentukan.<br>
        Harap simpan dan tunjukkan tiket ini saat masuk ke lokasi destinasi.
    </div>
</body>

</html>
