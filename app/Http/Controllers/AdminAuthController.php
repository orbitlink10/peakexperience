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
    /**
     * @return array<string, array{label:string,heading:string,description:string}>
     */
    private function placeholderSections(): array
    {
        return [
            'overview' => [
                'label' => 'Overview',
                'heading' => 'Overview',
                'description' => 'This section is now connected in the sidebar. Dashboard widgets and summary cards can be added here next.',
            ],
            'services' => [
                'label' => 'Services',
                'heading' => 'Services',
                'description' => 'Use this section for service management once those editing tools are added to the admin panel.',
            ],
            'team' => [
                'label' => 'Team',
                'heading' => 'Team',
                'description' => 'This area is reserved for team profiles, bios, and related media.',
            ],
            'sliders' => [
                'label' => 'Sliders',
                'heading' => 'Sliders',
                'description' => 'Slider controls are not implemented yet, but this menu item now routes correctly.',
            ],
            'clients' => [
                'label' => 'Clients',
                'heading' => 'Clients',
                'description' => 'This section can be used for client logos, testimonials, or case study listings.',
            ],
            'invoices' => [
                'label' => 'Invoices',
                'heading' => 'Invoices',
                'description' => 'Invoice management has not been built yet, but the admin navigation now opens a real screen.',
            ],
            'videos' => [
                'label' => 'Videos',
                'heading' => 'Videos',
                'description' => 'Video library and upload controls can be added here when that workflow is ready.',
            ],
            'pages' => [
                'label' => 'Pages',
                'heading' => 'Pages',
                'description' => 'Use this area for standalone page management when those editors are added.',
            ],
            'contact-page' => [
                'label' => 'Contact Page',
                'heading' => 'Contact Page',
                'description' => 'Contact page editing is not implemented yet, but the sidebar item now responds correctly.',
            ],
        ];
    }

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
            'sectionImages' => $content['section_images'],
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

    public function section(Request $request, string $section): View
    {
        $sections = $this->placeholderSections();
        abort_unless(array_key_exists($section, $sections), 404);

        $page = $sections[$section];

        return view('admin.placeholder', $this->sharedData($request, $page['label']) + [
            'pageHeading' => $page['heading'],
            'pageDescription' => $page['description'],
        ]);
    }

    public function updateHomepage(Request $request): RedirectResponse
    {
        $existingContent = HomepageContent::load();

        $request->validate([
            'logo_file' => ['nullable', 'image', 'max:5120'],
            'logo_remove' => ['nullable', 'boolean'],
            'section_images' => ['nullable', 'array'],
            'section_images.hero.path' => ['nullable', 'string', 'max:255'],
            'section_images.hero.file' => ['nullable', 'image', 'max:5120'],
            'section_images.hero.remove' => ['nullable', 'boolean'],
            'section_images.intro.path' => ['nullable', 'string', 'max:255'],
            'section_images.intro.file' => ['nullable', 'image', 'max:5120'],
            'section_images.intro.remove' => ['nullable', 'boolean'],
            'section_images.services.path' => ['nullable', 'string', 'max:255'],
            'section_images.services.file' => ['nullable', 'image', 'max:5120'],
            'section_images.services.remove' => ['nullable', 'boolean'],
            'section_images.proof.path' => ['nullable', 'string', 'max:255'],
            'section_images.proof.file' => ['nullable', 'image', 'max:5120'],
            'section_images.proof.remove' => ['nullable', 'boolean'],
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

        $sectionImages = $existingContent['section_images'] ?? HomepageContent::defaults()['section_images'];
        foreach (['hero', 'intro', 'services', 'proof'] as $key) {
            $currentPath = trim((string) data_get(
                $request->input('section_images', []),
                $key . '.path',
                data_get($sectionImages, $key . '.path', '')
            ));

            if ($request->boolean("section_images.$key.remove")) {
                $this->deletePublicAsset(HomepageContent::storedPath($currentPath));
                $currentPath = '';
            }

            if ($request->hasFile("section_images.$key.file")) {
                $this->deletePublicAsset(HomepageContent::storedPath($currentPath));
                $currentPath = $request->file("section_images.$key.file")->store('homepage/sections', 'public');
            }

            $sectionImages[$key] = [
                'path' => $currentPath,
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
            'section_images' => $sectionImages,
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
            ['label' => 'Overview', 'href' => route('admin.section', ['section' => 'overview'])],
            ['label' => 'Services', 'href' => route('admin.section', ['section' => 'services'])],
            ['label' => 'Team', 'href' => route('admin.section', ['section' => 'team'])],
            ['label' => 'Gallery', 'href' => route('admin.gallery')],
            ['label' => 'Sliders', 'href' => route('admin.section', ['section' => 'sliders'])],
            ['label' => 'Clients', 'href' => route('admin.section', ['section' => 'clients'])],
            ['label' => 'Invoices', 'href' => route('admin.section', ['section' => 'invoices'])],
            ['label' => 'Videos', 'href' => route('admin.section', ['section' => 'videos'])],
            ['label' => 'Pages', 'href' => route('admin.section', ['section' => 'pages'])],
            ['label' => 'Homepage', 'href' => route('admin.homepage')],
            ['label' => 'Contact Page', 'href' => route('admin.section', ['section' => 'contact-page'])],
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
