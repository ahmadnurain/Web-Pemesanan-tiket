<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Destinations extends Model
{
    use HasFactory;

    // Field yang bisa diisi
    protected $fillable = ['name', 'description', 'location', 'category_id', 'ticket_price'];

    /**
     * Relasi ke kategori (Category)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke transaksi (Transactions)
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(TicketTransaction::class);
    }

    /**
     * Relasi ke admin (User) melalui destinasi
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /**
     * Relasi ke foto (Photos)
     */
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class, 'destinations_id', 'id');
    }

    /**
     * Relasi ke video (Videos)
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }
}
