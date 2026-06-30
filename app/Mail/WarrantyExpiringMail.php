<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class WarrantyExpiringMail extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $equipment;
    public int $days;

    public function __construct(Collection $equipment, int $days = 30)
    {
        $this->equipment = $equipment;
        $this->days = $days;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Garantías próximas a vencer (' . $this->equipment->count() . ' equipos)',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.warranty-expiring',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
