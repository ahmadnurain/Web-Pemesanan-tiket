<?php

// app/Jobs/SendEticketJob.php
namespace App\Jobs;

use App\Mail\TicketMail;
use App\Models\Destinations;
use App\Models\TicketTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEticketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $transactionId;

    public function __construct(int $transactionId)
    {
        $this->transactionId = $transactionId;
    }

    public function handle(): void
    {
        $tx   = TicketTransaction::findOrFail($this->transactionId);
        $dest = Destinations::findOrFail($tx->destination_id);

        $pdf = Pdf::loadView('tickets.pdf', [
            'transaction' => $tx,
            'destination' => $dest,
        ])->setOptions([
            'enable_svg' => true,
            'isRemoteEnabled' => true,   // berjaga2 kalau pakai data URI img
            'isHtml5ParserEnabled' => true,
        ]);

        Mail::to($tx->email)->send(
            new TicketMail($tx, $dest, $pdf->output())
        );
    }
}
