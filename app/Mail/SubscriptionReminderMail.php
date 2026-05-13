<?php

namespace App\Mail;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Notification $notification)
    {
        $this->notification->loadMissing(['client', 'subscription']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: sprintf(
                '%s subscription reminder',
                strtoupper($this->notification->subscription->service_type)
            ),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-reminder',
        );
    }
}
