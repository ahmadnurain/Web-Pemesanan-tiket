<?php

namespace App\Mail;

use App\Models\Destinations;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\TicketTransaction;
use Illuminate\Support\Facades\URL;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public TicketTransaction $transaction;
    public Destinations $destination;
    public string $pdfBytes;

    public function __construct(TicketTransaction $transaction, Destinations $destination, string $pdfBytes)
    {
        $this->transaction = $transaction;
        $this->destination = $destination;
        $this->pdfBytes    = $pdfBytes;
    }

    public function build()
    {
        $downloadUrl = URL::temporarySignedRoute(
            'ticket.download',
            now()->addDays(3),
            ['transaction' => $this->transaction->uuid]
        );
        return $this->subject('E-Ticket ' . $this->transaction->ticket_code)
            ->view('emails.ticket') // <-- pakai view HTML biasa
            ->with([
                'transaction' => $this->transaction,
                'destination' => $this->destination,
                'downloadUrl' => $downloadUrl, // pakai di template email
            ])
            ->attachData(
                $this->pdfBytes,
                'e-ticket-' . $this->transaction->ticket_code . '.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
