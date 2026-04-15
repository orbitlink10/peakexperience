<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactInquiryMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @param  array{
     *   name:string,
     *   first_name:string,
     *   last_name:string,
     *   organization:string,
     *   email:string,
     *   phone:string,
     *   date_of_event:string,
     *   venue:string,
     *   guest_count:string,
     *   event_type:string,
     *   additional_info:string
     * }  $enquiry
     */
    public function __construct(public array $enquiry)
    {
    }

    public function envelope(): Envelope
    {
        $replyTo = [];
        if ($this->enquiry['email'] !== '') {
            $replyTo[] = new Address($this->enquiry['email'], $this->enquiry['name']);
        }

        return new Envelope(
            subject: 'New Peak Experience enquiry from ' . $this->enquiry['name'],
            replyTo: $replyTo,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-inquiry',
        );
    }
}
