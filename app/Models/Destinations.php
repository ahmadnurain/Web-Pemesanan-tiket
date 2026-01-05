<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Destinations extends Model
{
    use HasFactory;

    // Field yang bisa diisi
    protected $fillable = ['name', 'slug',  'description', 'location', 'category_id', 'ticket_price', 'user_id', 'operating_hours'];

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
        return $this->hasMany(TicketTransaction::class, 'destination_id', 'id');
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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        // Generate saat membuat jika slug kosong
        static::creating(function (self $model) {
            if (blank($model->slug)) {
                $model->slug = static::makeUniqueSlug($model->name);
            }
        });

        // Jika name berubah & slug tidak diisi manual, regen
        static::updating(function (self $model) {
            $nameChanged = $model->isDirty('name');
            $slugProvided = $model->isDirty('slug') && filled($model->slug);

            if ($nameChanged && !$slugProvided) {
                $model->slug = static::makeUniqueSlug($model->name, $model->getKey());
            }
        });
    }

    protected static function makeUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name ?: 'destinasi');
        $slug = $base;

        // Cek unik, kalau tabrakan tambahkan sufiks -xxxxxx
        $i = 0;
        while (static::query()
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()
        ) {
            // pakai random pendek biar cepat & tetap rapi
            $slug = $base . '-' . Str::lower(Str::random(6));
            if (++$i > 10) { // sangat kecil kemungkinan, tapi untuk jaga-jaga
                $slug = $base . '-' . Str::uuid();
                break;
            }
        }

        return $slug;
    }
}
