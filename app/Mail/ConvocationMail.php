<?php

namespace App\Mail;

use App\Models\Convocation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ConvocationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Convocation $convocation
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $session = $this->convocation->examSession;

        return new Envelope(
            subject: "Convocation - {$session->type} du {$session->date->format('d/m/Y')}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.convocation',
            with: [
                'studentName' => $this->convocation->student->first_name,
                'sessionType' => $this->convocation->examSession->type,
                'sessionDate' => $this->convocation->examSession->date->format('d/m/Y'),
                'downloadUrl' => route('convocations.download', $this->convocation),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        // Attacher le PDF de convocation si disponible
        if ($this->convocation->pdf_url && Storage::exists($this->convocation->pdf_url)) {
            $attachments[] = Attachment::fromStorage($this->convocation->pdf_url)
                ->as("convocation-{$this->convocation->qr_code}.pdf")
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
