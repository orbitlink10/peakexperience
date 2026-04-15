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
            'name' => ['required', 'string', 'max:120'],
            'organization' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['required', 'string', 'max:40'],
            'event_details' => ['required', 'string', 'max:3000'],
        ]);

        $contactEmail = (string) data_get($this->contactData(), 'contactEmail', '');

        try {
            Mail::to($contactEmail)->send(new ContactInquiryMail($validated));
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
