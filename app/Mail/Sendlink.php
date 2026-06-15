<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Sendlink extends Mailable
{
    use Queueable, SerializesModels;

    public $name , $Verification_Link;
    /**
     * Create a new message instance.
     */
    public function __construct($name , $Verification_Link)
    {
        $this->name = $name;
        $this->Verification_Link = $Verification_Link;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Resend Password Link',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.sendlink',
            with:[
                'name'=> $this->name ,
                'verificationLink' => $this->Verification_Link,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
