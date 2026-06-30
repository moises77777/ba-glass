<?php

namespace App\Mail;

use App\Models\MaintenanceRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MaintenanceScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public MaintenanceRecord $maintenance;

    public function __construct(MaintenanceRecord $maintenance)
    {
        $this->maintenance = $maintenance;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mantenimiento programado - ' . $this->maintenance->ticket_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.maintenance-scheduled',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
