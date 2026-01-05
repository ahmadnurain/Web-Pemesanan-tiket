<?php

namespace App\Mail;

use App\Models\Destinations;
use App\Models\TicketTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketLinkMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public TicketTransaction $transaction;
    public Destinations $destination;
    public string $downloadUrl;

    public function __construct(TicketTransaction $transaction, Destinations $destination, string $downloadUrl)
    {
        $this->transaction = $transaction;
        $this->destination = $destination;
        $this->downloadUrl = $downloadUrl;
    }

    public function build()
    {
        return $this->subject('Tautan Unduh E-Ticket Anda')
            ->view('emails.ticket_link')
            ->with([
                'transaction' => $this->transaction,
                'destination' => $this->destination,
                'downloadUrl' => $this->downloadUrl,
            ]);
    }
}
