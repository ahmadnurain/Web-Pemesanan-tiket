<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Photo extends Model
{
    //   
    use HasFactory;

    protected $fillable = ['destinations_id', 'path'];

    // Relasi ke Destinations
    public function destination()
    {
        return $this->belongsTo(Destinations::class, 'destinations_id', 'id');
    }
}
