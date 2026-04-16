<?php

namespace App\Http\Controllers;

use App\Support\GalleryContent;
use App\Support\HomepageContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLogin(): View
    {
        return view('admin.login');
    }

    /**
     * @throws ValidationException
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $adminUsername = (string) config('admin.username');
        $adminPassword = (string) config('admin.password');

        if (
            ! hash_equals($adminUsername, $credentials['username']) ||
            ! hash_equals($adminPassword, $credentials['password'])
        ) {
            throw ValidationException::withMessages([
                'username' => 'Invalid username or password.',
            ]);
        }

        $request->session()->regenerate();
        $request->session()->put([
            'admin_authenticated' => true,
            'admin_username' => $adminUsername,
        ]);

        return redirect()->route('admin.dashboard')->with('status', 'Login successful.');
    }

    public function gallery(Request $request): View
    {
        $galleryItems = GalleryContent::load();

        return view('admin.dashboard', $this->sharedData($request, 'Gallery') + [
            'galleryItems' => $galleryItems,
        ]);
    }

    public function uploadGalleryImage(Request $request): RedirectResponse
    {
        $request->validate([
            'image_file' => ['required', 'image', 'max:5120'],
        ]);

        $galleryItems = GalleryContent::load();
        $file = $request->file('image_file');
        $path = $file->store('homepage/gallery', 'public');
        $title = Str::of(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            ->replace(['-', '_'], ' ')
            ->squish()
            ->title()
            ->value();

        $galleryItems[] = [
            'title' => $title !== '' ? $title : 'Gallery Image',
            'image' => $path,
        ];

        GalleryContent::save($galleryItems);

        return redirect()->route('admin.gallery')->with('status', 'Image added successfully.');
    }

    public function deleteGalleryImages(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'selected' => ['required', 'array', 'min:1'],
            'selected.*' => ['integer', 'min:0'],
        ]);

        $selected = array_flip(array_map('intval', $validated['selected']));
        $remainingItems = [];

        foreach (GalleryContent::load() as $index => $item) {
            if (array_key_exists($index, $selected)) {
                $this->deletePublicAsset(HomepageContent::storedPath((string) ($item['image'] ?? '')));
                continue;
            }

            $remainingItems[] = [
                'title' => trim((string) ($item['title'] ?? '')),
                'image' => trim((string) ($item['image'] ?? '')),
            ];
        }

        GalleryContent::save($remainingItems);

        return redirect()->route('admin.gallery')->with('status', 'Selected images deleted.');
    }

    public function showHomepage(Request $request): View
    {
        $content = HomepageContent::load();

        return view('admin.homepage', $this->sharedData($request, 'Homepage') + [
            'logo' => $content['logo'],
            'heroVideo' => $content['hero_video'],
            'whatWeDo' => $content['what_we_do'],
            'ourProcess' => $content['our_process'],
            'iconOptions' => [
                'Staging',
                'Event Production',
                'Media',
                'Exhibition',
                'Audio',
                'Lighting',
                'Branding',
            ],
        ]);
    }

    public function updateHomepage(Request $request): RedirectResponse
    {
        $existingContent = HomepageContent::load();

        $request->validate([
            'logo_file' => ['nullable', 'image', 'max:5120'],
            'logo_remove' => ['nullable', 'boolean'],
            'hero_video.url' => ['nullable', 'string', 'max:255'],
            'what_we_do' => ['required', 'array', 'min:1'],
            'what_we_do.*.title' => ['required', 'string', 'max:120'],
            'what_we_do.*.icon' => ['required', 'string', 'max:80'],
            'what_we_do.*.text' => ['required', 'string', 'max:700'],
            'what_we_do.*.link_url' => ['nullable', 'url', 'max:255'],
            'what_we_do.*.image' => ['nullable', 'string', 'max:255'],
            'what_we_do.*.image_file' => ['nullable', 'image', 'max:5120'],
            'our_process' => ['required', 'array', 'min:1', 'max:8'],
            'our_process.*.title' => ['required', 'string', 'max:120'],
            'our_process.*.text' => ['required', 'string', 'max:700'],
        ]);

        $whatWeDo = [];
        foreach ((array) $request->input('what_we_do', []) as $index => $item) {
            $storedImage = trim((string) ($item['image'] ?? ''));
            if ($request->hasFile("what_we_do.$index.image_file")) {
                $this->deletePublicAsset(HomepageContent::storedPath($storedImage));
                $path = $request->file("what_we_do.$index.image_file")->store('homepage', 'public');
                $storedImage = $path;
            }

            $whatWeDo[] = [
                'title' => trim((string) $item['title']),
                'icon' => trim((string) $item['icon']),
                'text' => trim((string) $item['text']),
                'link_url' => trim((string) ($item['link_url'] ?? '')),
                'image' => $storedImage,
            ];
        }

        $logo = $existingContent['logo'] ?? ['url' => '', 'path' => ''];
        if ($request->boolean('logo_remove')) {
            $this->deletePublicAsset(HomepageContent::storedPath((string) ($logo['path'] ?? '')));
            $logo = ['url' => '', 'path' => ''];
        }

        if ($request->hasFile('logo_file')) {
            $this->deletePublicAsset(HomepageContent::storedPath((string) ($logo['path'] ?? '')));
            $path = $request->file('logo_file')->store('homepage/logo', 'public');
            $logo = [
                'url' => '',
                'path' => $path,
            ];
        }

        $heroVideo = [
            'url' => trim((string) data_get($request->input('hero_video', []), 'url', '')),
        ];

        $ourProcess = [];
        foreach ((array) $request->input('our_process', []) as $item) {
            $ourProcess[] = [
                'title' => trim((string) $item['title']),
                'text' => trim((string) $item['text']),
            ];
        }

        HomepageContent::save([
            'logo' => $logo,
            'hero_video' => $heroVideo,
            'what_we_do' => $whatWeDo,
            'our_process' => $ourProcess,
        ]);

        return redirect()->route('admin.homepage')->with('status', 'Homepage updated successfully.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['admin_authenticated', 'admin_username']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('status', 'Logged out successfully.');
    }

    /**
     * @return array{
     *   publicNav: array<int, array{label:string,href:string}>,
     *   sidebarItems: array<int, array{label:string,href:string,active:bool}>,
     *   adminName: string,
     *   today: string
     * }
     */
    private function sharedData(Request $request, string $activeSidebar): array
    {
        $publicNav = [
            ['label' => 'Home', 'href' => url('/')],
            ['label' => 'Our Services', 'href' => '#'],
            ['label' => 'Our Process', 'href' => '#'],
            ['label' => 'Our Team', 'href' => '#'],
            ['label' => 'Media', 'href' => '#'],
            ['label' => 'Contact', 'href' => '#'],
            ['label' => 'Blog', 'href' => '#'],
            ['label' => 'Payment', 'href' => '#'],
        ];

        $menu = [
            ['label' => 'Overview', 'href' => '#'],
            ['label' => 'Services', 'href' => '#'],
            ['label' => 'Team', 'href' => '#'],
            ['label' => 'Gallery', 'href' => route('admin.gallery')],
            ['label' => 'Sliders', 'href' => '#'],
            ['label' => 'Clients', 'href' => '#'],
            ['label' => 'Invoices', 'href' => '#'],
            ['label' => 'Videos', 'href' => '#'],
            ['label' => 'Pages', 'href' => '#'],
            ['label' => 'Homepage', 'href' => route('admin.homepage')],
            ['label' => 'Contact Page', 'href' => '#'],
        ];

        $sidebarItems = array_map(
            fn (array $item): array => $item + ['active' => $item['label'] === $activeSidebar],
            $menu
        );

        return [
            'publicNav' => $publicNav,
            'sidebarItems' => $sidebarItems,
            'adminName' => (string) $request->session()->get('admin_username', 'admin'),
            'today' => now()->format('l, d M Y'),
        ];
    }

    private function deletePublicAsset(string $path): void
    {
        if ($path !== '' && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
