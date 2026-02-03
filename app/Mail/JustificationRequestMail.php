<?php

namespace App\Mail;

use App\Models\BankStatement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JustificationRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $statement;
    public $customMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(BankStatement $statement, string $customMessage)
    {
        $this->statement = $statement;
        $this->customMessage = $customMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Action requise : Justificatif de paiement EduPass',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payments.justification-request',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
