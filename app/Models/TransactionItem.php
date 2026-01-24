<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'ticket_transaction_id',
        'destination_ticket_type_id',
        'name',
        'price_per_unit',
        'quantity',
        'total_price'
    ];

    public function transaction()
    {
        return $this->belongsTo(TicketTransaction::class, 'ticket_transaction_id');
    }

    public function ticketType()
    {
        return $this->belongsTo(DestinationTicketType::class, 'destination_ticket_type_id');
    }
}
