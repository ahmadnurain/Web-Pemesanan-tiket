<?php

namespace App\Models;

use App\Models\TicketScan;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketTransaction extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan (opsional, jika tabel tidak mengikuti konvensi Laravel)
    protected $table = 'ticket_transactions';

    // Field yang bisa diisi secara mass-assignment
    protected $fillable = [

        'destination_id',
        'name',
        'uuid',
        'email',
        'phone_number',
        'ticket_code',
        'ticket_status',
        'amount',
        'payment_status',
        'payment_type',
        'total_tickets',
        'snap_token',
        'order_id',  // Tambahkan 'order_id' di sini
        'ticket_type',
        'visit_date',
        'qr_secret',
        'scan_count',
        'last_scanned_at',
        'used_at',
        'scanned_by',
    ];

    // Relasi ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tabel Destinations
    public function destination()
    {
        return $this->belongsTo(Destinations::class, 'destination_id');
    }
    // Fungsi untuk memeriksa apakah tiket sudah digunakan
    public function isUsed()
    {
        return $this->ticket_status === 'used';
    }

    // Fungsi untuk memeriksa apakah pembayaran sudah selesai
    public function isPaymentSucceeded()
    {
        return $this->payment_status === 'succeeded';
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
                if (empty($model->qr_secret)) $model->qr_secret = Str::random(48);
            }
        });
    }

    public function scans()
    {
        return $this->hasMany(TicketScan::class, 'ticket_transaction_id');
    }

    public function qrPayload(): string
    {
        $msg = $this->uuid;
        $sig = hash_hmac('sha256', $msg, $this->qr_secret ?? '');
        return $msg . '|' . $sig; // inilah yang di-encode ke QR
    }

    public function markUsedBy(int $userId): void
    {
        if (!$this->used_at) {
            $this->used_at = now();
            $this->ticket_status = 'used';
        }
        $this->scanned_by = $userId;
        $this->scan_count++;
        $this->last_scanned_at = now();
        $this->save();
    }
}
