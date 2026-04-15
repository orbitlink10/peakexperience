<?php

namespace App\Http\Controllers;

use App\Mail\ContactInquiryMail;
use App\Support\HomepageContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class HomeController extends Controller
{
    public function index(): View
    {
        $content = HomepageContent::load();

        return view('home', [
            'logo' => $content['logo'],
            'heroVideo' => $content['hero_video'],
            'whatWeDo' => HomepageContent::services($content['what_we_do']),
            'ourProcess' => $content['our_process'],
        ] + $this->contactData());
    }

    public function service(string $service): View
    {
        $content = HomepageContent::load();
        $services = HomepageContent::services($content['what_we_do']);

        $serviceItem = null;
        foreach ($services as $item) {
            if (($item['slug'] ?? '') === $service) {
                $serviceItem = $item;
                break;
            }
        }

        abort_unless(is_array($serviceItem), 404);

        return view('service', [
            'logo' => $content['logo'],
            'service' => $serviceItem,
        ] + $this->contactData());
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'organization' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['required', 'string', 'max:40'],
            'date_of_event' => ['required', 'date'],
            'venue' => ['required', 'string', 'max:180'],
            'guest_count' => ['nullable', 'string', 'max:80'],
            'event_type' => ['required', 'string', 'max:120'],
            'additional_info' => ['nullable', 'string', 'max:3000'],
            'consent' => ['accepted'],
        ], [
            'first_name.required' => 'Please enter the client first name.',
            'last_name.required' => 'Please enter the client last name.',
            'organization.required' => 'Please enter your organization name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Please enter your phone number.',
            'date_of_event.required' => 'Please enter the event date.',
            'venue.required' => 'Please enter the venue or location.',
            'event_type.required' => 'Please select the event type.',
            'consent.accepted' => 'Please confirm that we may contact you about this enquiry.',
        ], [
            'date_of_event' => 'event date',
            'guest_count' => 'number of guests',
            'event_type' => 'event type',
            'additional_info' => 'additional information',
        ]);

        $enquiry = [
            'name' => trim($validated['first_name'] . ' ' . $validated['last_name']),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'organization' => $validated['organization'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'date_of_event' => $validated['date_of_event'],
            'venue' => $validated['venue'],
            'guest_count' => trim((string) ($validated['guest_count'] ?? '')),
            'event_type' => $validated['event_type'],
            'additional_info' => trim((string) ($validated['additional_info'] ?? '')),
        ];

        $contactEmail = (string) data_get($this->contactData(), 'contactEmail', '');

        try {
            Mail::to($contactEmail)->send(new ContactInquiryMail($enquiry));
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->to(url('/#contact'))
                ->withInput()
                ->with('contact_error', 'We could not send your enquiry right now. Please call or email us directly.');
        }

        return redirect()
            ->to(url('/#contact'))
            ->with('contact_status', 'Thank you. Your enquiry has been sent to Peak Experience.');
    }

    public function asset(string $path): BinaryFileResponse
    {
        $path = HomepageContent::storedPath($path);
        abort_unless($path !== '' && str_starts_with($path, 'homepage/'), 404);
        abort_unless(Storage::disk('public')->exists($path), 404);

        return response()->file(Storage::disk('public')->path($path));
    }

    /**
     * @return array{
     *   contactEmail: string,
     *   contactPhones: array<int, array{display:string,dial:string}>,
     *   paymentUrl: string,
     *   paymentLabel: string
     * }
     */
    private function contactData(): array
    {
        return [
            'contactEmail' => 'info@peakexperience.co.ke',
            'contactPhones' => [
                ['display' => '+254 119857961', 'dial' => '+254119857961'],
                ['display' => '+254 792243400', 'dial' => '+254792243400'],
            ],
            'paymentUrl' => trim((string) config('services.payment.url', '')),
            'paymentLabel' => trim((string) config('services.payment.label', 'Make Payment')),
        ];
    }
}
