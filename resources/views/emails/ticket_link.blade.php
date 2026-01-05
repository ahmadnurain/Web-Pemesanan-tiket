<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Tautan Unduh E-Ticket</title>
</head>

<body style="margin:0;padding:0;background:#f8fafc;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f8fafc;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0"
                    style="background:#ffffff;border-radius:12px;padding:24px;font-family:Arial, Helvetica, sans-serif;color:#111827;">
                    <tr>
                        <td>
                            <h2 style="margin:0 0 8px;">Tautan Unduh E-Ticket</h2>
                            <p style="margin:0 0 16px;">Halo {{ $transaction->name }},</p>
                            <p style="margin:0 0 16px;">
                                Berikut adalah tautan unduh e-ticket untuk <strong>{{ $destination->name }}</strong>.
                            </p>

                            <div style="text-align:center;margin:24px 0;">
                                <a href="{{ $downloadUrl }}"
                                    style="background:#059669;color:#ffffff;padding:12px 20px;border-radius:8px;display:inline-block;text-decoration:none;font-weight:600;">
                                    Unduh E-Ticket (PDF)
                                </a>
                            </div>

                            <p style="margin:0 0 8px;font-size:12px;color:#6b7280;text-align:center;">
                                Jika tombol tidak berfungsi, salin dan tempel tautan ini di peramban Anda:
                            </p>
                            <p style="margin:0 0 16px;font-size:12px;text-align:center;">
                                <a href="{{ $downloadUrl }}" style="color:#059669;word-break:break-all;">
                                    {{ $downloadUrl }}
                                </a>
                            </p>

                            <hr style="border:none;border-top:1px solid #e5e7eb;margin:20px 0;">

                            <p style="margin:0;color:#374151;font-size:14px;">
                                Simpan e-ticket dan tunjukkan saat check-in di lokasi.<br>
                                Terima kasih,<br>
                                {{ config('app.name') }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
