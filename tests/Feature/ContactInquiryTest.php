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
            'name' => 'Jane Doe',
            'organization' => 'Peak Client Ltd',
            'email' => 'jane@example.com',
            'phone' => '+254700000000',
            'event_details' => 'Launch event on August 20, 2026 at Nairobi. 250 guests expected.',
        ];

        $response = $this->post(route('contact.submit'), $payload);

        $response->assertRedirect(url('/#contact'));
        $response->assertSessionHas('contact_status');

        Mail::assertSent(ContactInquiryMail::class, function (ContactInquiryMail $mail) use ($payload) {
            return $mail->hasTo('info@peakexperience.co.ke')
                && $mail->enquiry['name'] === $payload['name']
                && $mail->enquiry['organization'] === $payload['organization']
                && $mail->enquiry['email'] === $payload['email']
                && $mail->enquiry['phone'] === $payload['phone']
                && $mail->enquiry['event_details'] === $payload['event_details'];
        });
    }

    public function test_contact_form_requires_all_expected_fields(): void
    {
        Mail::fake();

        $response = $this->from(route('home'))->post(route('contact.submit'), []);

        $response->assertRedirect(route('home'));
        $response->assertSessionHasErrors([
            'name',
            'organization',
            'email',
            'phone',
            'event_details',
        ]);

        Mail::assertNothingSent();
    }
}
