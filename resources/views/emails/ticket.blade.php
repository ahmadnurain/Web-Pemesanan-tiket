{{-- resources/views/emails/ticket_html.blade.php --}}
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>E-Ticket</title>
    <meta name="x-apple-disable-message-reformatting">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="margin:0;padding:0;background:#f3f4f6;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%"
        style="background:#f3f4f6;">
        <tr>
            <td align="center" style="padding:24px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600"
                    style="max-width:600px;background:#ffffff;border-radius:12px;overflow:hidden;">
                    <tr>
                        <td
                            style="background:#059669;color:#ffffff;padding:20px 24px;font-family:Arial,Helvetica,sans-serif;">
                            <h1 style="margin:0;font-size:20px;">Pembayaran Berhasil — E-Ticket Terlampir</h1>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="padding:24px;font-family:Arial,Helvetica,sans-serif;color:#111827;font-size:14px;line-height:1.6;">
                            <p style="margin:0 0 12px;">Halo {{ $transaction->name }},</p>
                            <p style="margin:0 0 12px;">
                                Terima kasih, pembayaran Anda untuk <strong>{{ $destination->name }}</strong> telah
                                <em>berhasil</em>.<br>
                                <strong>E-ticket (PDF) terlampir pada email ini.</strong> Anda juga bisa mengunduhnya
                                lewat tombol di bawah.
                            </p>

                            <h2 style="margin:16px 0 8px;font-size:16px;">Ringkasan Pemesanan</h2>
                            <ul style="margin:0 0 16px 18px;padding:0;">
                                <li><strong>Kode Tiket:</strong> {{ $transaction->ticket_code }}</li>
                                <li><strong>Jumlah Tiket:</strong> {{ $transaction->total_tickets }}</li>
                                <li><strong>Destinasi:</strong> {{ $destination->name }}</li>
                                <li><strong>Tanggal Pembayaran:</strong>
                                    {{ \Carbon\Carbon::parse($transaction->created_at)->locale('id')->isoFormat('dddd, D MMMM Y [pukul] HH:mm') }}
                                </li>
                            </ul>

                            <table align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                style="margin:16px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $downloadUrl }}"
                                            style="background:#059669;color:#ffffff;padding:12px 20px;border-radius:8px;display:inline-block;text-decoration:none;font-weight:600;">
                                            Unduh E-Ticket (PDF)
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:12px;color:#6b7280;text-align:center;margin:8px 0 0;">
                                Jika tombol tidak berfungsi, salin tautan berikut:<br>
                                <a href="{{ $downloadUrl }}"
                                    style="color:#059669;text-decoration:underline;">
                                    {{ $downloadUrl }}
                                </a>
                            </p>

                            <p style="margin:16px 0 0;">Simpan e-ticket dan tunjukkan saat check-in di lokasi.</p>
                            <p style="margin:0;">Terima kasih,<br>{{ config('app.name') }}</p>
                        </td>
                    </tr>
                </table>
                <div style="color:#9ca3af;font-family:Arial,Helvetica,sans-serif;font-size:12px;margin-top:12px;">
                    Email ini dikirim otomatis. Mohon tidak membalas.
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
