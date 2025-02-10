<?php

namespace App\Models;

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
        'email',
        'phone_number',
        'ticket_code',
        'ticket_status',
        'amount',
        'payment_status',
        'payment_type',
        'total_tickets',
        'snap_token',
        'order_id'  // Tambahkan 'order_id' di sini
    ];

    // Relasi ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tabel Destinations
    public function destination()
    {
        return $this->belongsTo(Destinations::class);
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
}