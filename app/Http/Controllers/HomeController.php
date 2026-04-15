<?php

namespace App\Http\Controllers;

use App\Support\HomepageContent;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
     *   contactPhones: array<int, array{display:string,dial:string}>
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
        ];
    }
}
