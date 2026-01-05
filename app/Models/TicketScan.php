<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TicketScan extends Model
{
    //

    protected $fillable = ['ticket_transaction_id', 'user_id', 'result', 'ip', 'user_agent'];
    public function ticket()
    {
        return $this->belongsTo(TicketTransaction::class, 'ticket_transaction_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
