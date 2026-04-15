<?php

namespace Tests\Feature;

use App\Mail\ContactInquiryMail;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactInquiryTest extends TestCase
{
    public function test_contact_form_sends_an_enquiry_email(): void
    {
        Mail::fake();

        $payload = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'organization' => 'Peak Client Ltd',
            'email' => 'jane@example.com',
            'phone' => '+254700000000',
            'date_of_event' => '2026-08-20',
            'venue' => 'Nairobi Serena Hotel',
            'guest_count' => '250',
            'event_type' => 'Brand Launch',
            'additional_info' => 'Full staging, show calling, screens, and audio support required.',
            'consent' => '1',
        ];

        $response = $this->post(route('contact.submit'), $payload);

        $response->assertRedirect(url('/#contact'));
        $response->assertSessionHas('contact_status');

        Mail::assertSent(ContactInquiryMail::class, function (ContactInquiryMail $mail) use ($payload) {
            return $mail->hasTo('info@peakexperience.co.ke')
                && $mail->enquiry['name'] === 'Jane Doe'
                && $mail->enquiry['first_name'] === $payload['first_name']
                && $mail->enquiry['last_name'] === $payload['last_name']
                && $mail->enquiry['organization'] === $payload['organization']
                && $mail->enquiry['email'] === $payload['email']
                && $mail->enquiry['phone'] === $payload['phone']
                && $mail->enquiry['date_of_event'] === $payload['date_of_event']
                && $mail->enquiry['venue'] === $payload['venue']
                && $mail->enquiry['guest_count'] === $payload['guest_count']
                && $mail->enquiry['event_type'] === $payload['event_type']
                && $mail->enquiry['additional_info'] === $payload['additional_info'];
        });
    }

    public function test_contact_form_requires_all_expected_fields(): void
    {
        Mail::fake();

        $response = $this->from(route('home'))->post(route('contact.submit'), []);

        $response->assertRedirect(route('home'));
        $response->assertSessionHasErrors([
            'first_name',
            'last_name',
            'organization',
            'email',
            'phone',
            'date_of_event',
            'venue',
            'event_type',
            'consent',
        ]);

        Mail::assertNothingSent();
    }
}
