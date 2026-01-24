<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinationTicketType extends Model
{
    protected $fillable = ['destination_id', 'name', 'price', 'description'];

    public function destination()
    {
        return $this->belongsTo(Destinations::class, 'destination_id');
    }
}
