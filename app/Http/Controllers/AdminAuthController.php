<?php

namespace App\Http\Controllers;

use App\Support\GalleryContent;
use App\Support\HomepageContent;
use App\Support\PageContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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

        return view('admin.dashboard', $this->sharedData($request, 'gallery') + [
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

        return view('admin.homepage', $this->sharedData($request, 'homepage') + [
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

    public function pages(Request $request): View
    {
        return view('admin.pages.index', $this->sharedData($request, 'pages') + [
            'pages' => PageContent::load(),
        ]);
    }

    public function createPage(Request $request): View
    {
        return view('admin.pages.form', $this->sharedData($request, 'pages') + [
            'pageData' => PageContent::defaults(),
            'pageTypes' => $this->pageTypes(),
            'formMode' => 'create',
        ]);
    }

    public function storePage(Request $request): RedirectResponse
    {
        $pages = PageContent::load();
        $payload = $this->validatedPagePayload($request);
        $imagePath = $this->storePageImage($request, '');
        $timestamp = now()->toIso8601String();

        array_unshift($pages, [
            'id' => (string) Str::uuid(),
            'slug' => $this->uniquePageSlug($payload['title'], $pages),
            'meta_title' => $payload['meta_title'],
            'meta_description' => $payload['meta_description'],
            'title' => $payload['title'],
            'image' => $imagePath,
            'image_alt' => $payload['image_alt'],
            'heading_two' => $payload['heading_two'],
            'type' => $payload['type'],
            'description' => $payload['description'],
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);

        PageContent::save($pages);

        return redirect()->route('admin.section', ['section' => 'pages'])->with('status', 'Page created successfully.');
    }

    public function editPage(Request $request, string $pageId): View
    {
        $page = PageContent::findById($pageId);
        abort_unless(is_array($page), 404);

        return view('admin.pages.form', $this->sharedData($request, 'pages') + [
            'pageData' => $page,
            'pageTypes' => $this->pageTypes(),
            'formMode' => 'edit',
        ]);
    }

    public function updatePage(Request $request, string $pageId): RedirectResponse
    {
        $pages = PageContent::load();
        $page = null;

        foreach ($pages as $index => $item) {
            if ($item['id'] === $pageId) {
                $page = $item;
                break;
            }
        }

        abort_unless(is_array($page), 404);

        $payload = $this->validatedPagePayload($request);
        $imagePath = $this->storePageImage($request, (string) ($page['image'] ?? ''));
        $updatedPage = [
            'id' => $page['id'],
            'slug' => $this->uniquePageSlug($payload['title'], $pages, $page['id']),
            'meta_title' => $payload['meta_title'],
            'meta_description' => $payload['meta_description'],
            'title' => $payload['title'],
            'image' => $imagePath,
            'image_alt' => $payload['image_alt'],
            'heading_two' => $payload['heading_two'],
            'type' => $payload['type'],
            'description' => $payload['description'],
            'created_at' => (string) ($page['created_at'] ?? now()->toIso8601String()),
            'updated_at' => now()->toIso8601String(),
        ];

        $pages[$index] = $updatedPage;

        PageContent::save($pages);

        return redirect()->route('admin.section', ['section' => 'pages'])->with('status', 'Page updated successfully.');
    }

    public function deletePage(Request $request, string $pageId): RedirectResponse
    {
        $pages = [];
        $deleted = false;

        foreach (PageContent::load() as $page) {
            if ($page['id'] === $pageId) {
                $this->deletePublicAsset(HomepageContent::storedPath((string) ($page['image'] ?? '')));
                $deleted = true;
                continue;
            }

            $pages[] = $page;
        }

        abort_unless($deleted, 404);

        PageContent::save($pages);

        return redirect()->route('admin.section', ['section' => 'pages'])->with('status', 'Page deleted successfully.');
    }

    public function bulkDeletePages(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bulk_action' => ['required', Rule::in(['delete'])],
            'selected' => ['required', 'array', 'min:1'],
            'selected.*' => ['required', 'string'],
        ], [
            'bulk_action.required' => 'Choose a bulk action first.',
            'selected.required' => 'Select at least one page to apply the action.',
        ]);

        $selected = array_flip(array_map('strval', $validated['selected']));
        $remainingPages = [];

        foreach (PageContent::load() as $page) {
            if (array_key_exists((string) $page['id'], $selected)) {
                $this->deletePublicAsset(HomepageContent::storedPath((string) ($page['image'] ?? '')));
                continue;
            }

            $remainingPages[] = $page;
        }

        PageContent::save($remainingPages);

        return redirect()->route('admin.section', ['section' => 'pages'])->with('status', 'Selected pages deleted.');
    }

    public function section(Request $request, string $section): View
    {
        if ($section === 'pages') {
            return $this->pages($request);
        }

        $sections = $this->placeholderSections();
        abort_unless(array_key_exists($section, $sections), 404);

        $page = $sections[$section];

        return view('admin.placeholder', $this->sharedData($request, $section) + [
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
            'section_images.services.video_path' => ['nullable', 'string', 'max:255'],
            'section_images.services.video_url' => ['nullable', 'url', 'max:255'],
            'section_images.services.video_file' => ['nullable', 'file', 'mimes:mp4,webm,ogg,ogv,m4v,mov', 'max:51200'],
            'section_images.services.video_remove' => ['nullable', 'boolean'],
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

            $sectionImages[$key] = array_merge((array) data_get($sectionImages, $key, []), [
                'path' => $currentPath,
            ]);
        }

        $servicesVideoPath = trim((string) data_get(
            $request->input('section_images', []),
            'services.video_path',
            data_get($sectionImages, 'services.video_path', '')
        ));
        $servicesVideoUrl = trim((string) data_get(
            $request->input('section_images', []),
            'services.video_url',
            ''
        ));

        if ($request->boolean('section_images.services.video_remove')) {
            $this->deletePublicAsset(HomepageContent::storedPath($servicesVideoPath));
            $servicesVideoPath = '';
        }

        if ($servicesVideoUrl !== '') {
            if ($servicesVideoPath !== '' && $servicesVideoPath !== $servicesVideoUrl) {
                $this->deletePublicAsset(HomepageContent::storedPath($servicesVideoPath));
            }

            $servicesVideoPath = $servicesVideoUrl;
        }

        if ($request->hasFile('section_images.services.video_file')) {
            $this->deletePublicAsset(HomepageContent::storedPath($servicesVideoPath));
            $servicesVideoPath = $request->file('section_images.services.video_file')->store('homepage/sections', 'public');
        }

        $sectionImages['services'] = array_merge((array) ($sectionImages['services'] ?? []), [
            'video_path' => $servicesVideoPath,
        ]);

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
     *   meta_title:string,
     *   meta_description:string,
     *   title:string,
     *   image_alt:string,
     *   heading_two:string,
     *   type:string,
     *   description:string
     * }
     */
    private function validatedPagePayload(Request $request): array
    {
        $validated = $request->validate([
            'meta_title' => ['required', 'string', 'max:160'],
            'meta_description' => ['required', 'string', 'max:320'],
            'title' => ['required', 'string', 'max:180'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'max:5120'],
            'image_remove' => ['nullable', 'boolean'],
            'image_alt' => ['nullable', 'string', 'max:180'],
            'heading_two' => ['required', 'string', 'max:220'],
            'type' => ['required', Rule::in($this->pageTypes())],
            'description' => ['required', 'string'],
        ]);

        return [
            'meta_title' => trim((string) $validated['meta_title']),
            'meta_description' => trim((string) $validated['meta_description']),
            'title' => trim((string) $validated['title']),
            'image_alt' => trim((string) ($validated['image_alt'] ?? '')),
            'heading_two' => trim((string) $validated['heading_two']),
            'type' => trim((string) $validated['type']),
            'description' => trim((string) $validated['description']),
        ];
    }

    private function storePageImage(Request $request, string $existingPath): string
    {
        $imagePath = trim((string) $request->input('image_path', $existingPath));

        if ($request->boolean('image_remove')) {
            $this->deletePublicAsset(HomepageContent::storedPath($imagePath));
            $imagePath = '';
        }

        if ($request->hasFile('image_file')) {
            $this->deletePublicAsset(HomepageContent::storedPath($imagePath));
            $imagePath = $request->file('image_file')->store('homepage/pages', 'public');
        }

        return $imagePath;
    }

    /**
     * @return array<int, string>
     */
    private function pageTypes(): array
    {
        return ['Post', 'Page'];
    }

    /**
     * @param  array<int, array<string, mixed>>  $pages
     */
    private function uniquePageSlug(string $title, array $pages, ?string $ignoreId = null): string
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = 'page';
        }

        $slug = $base;
        $suffix = 2;

        while (collect($pages)->contains(function (array $page) use ($slug, $ignoreId): bool {
            return (string) ($page['slug'] ?? '') === $slug
                && (string) ($page['id'] ?? '') !== (string) $ignoreId;
        })) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    /**
     * @return array{
     *   publicNav: array<int, array{label:string,href:string}>,
     *   sidebarItems: array<int, array<string, mixed>>,
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
            ['key' => 'overview', 'label' => 'Overview', 'href' => route('admin.section', ['section' => 'overview'])],
            ['key' => 'services', 'label' => 'Services', 'href' => route('admin.section', ['section' => 'services'])],
            ['key' => 'team', 'label' => 'Team', 'href' => route('admin.section', ['section' => 'team'])],
            ['key' => 'gallery', 'label' => 'Gallery', 'href' => route('admin.gallery')],
            ['key' => 'sliders', 'label' => 'Sliders', 'href' => route('admin.section', ['section' => 'sliders'])],
            ['key' => 'clients', 'label' => 'Clients', 'href' => route('admin.section', ['section' => 'clients'])],
            ['key' => 'invoices', 'label' => 'Invoices', 'href' => route('admin.section', ['section' => 'invoices'])],
            ['key' => 'videos', 'label' => 'Videos', 'href' => route('admin.section', ['section' => 'videos'])],
            [
                'key' => 'pages',
                'label' => 'Pages',
                'href' => route('admin.section', ['section' => 'pages']),
                'children' => [
                    ['key' => 'homepage', 'label' => 'Homepage', 'href' => route('admin.homepage')],
                    ['key' => 'contact-page', 'label' => 'Contact Page', 'href' => route('admin.section', ['section' => 'contact-page'])],
                ],
            ],
        ];

        $sidebarItems = array_map(
            function (array $item) use ($activeSidebar): array {
                $children = array_map(
                    fn (array $child): array => array_merge($child, ['active' => $child['key'] === $activeSidebar]),
                    $item['children'] ?? []
                );

                $childIsActive = array_reduce(
                    $children,
                    fn (bool $active, array $child): bool => $active || $child['active'],
                    false
                );

                return array_merge($item, [
                    'active' => $item['key'] === $activeSidebar || $childIsActive,
                    'children' => $children,
                ]);
            },
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
