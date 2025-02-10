<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    //    
    use HasFactory;

    protected $fillable = ['destination_id', 'path'];

    // Relasi ke Destinations
    public function destination()
    {
        return $this->belongsTo(Destinations::class);
    }
}
