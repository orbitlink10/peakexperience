<?php

namespace App\Http\Controllers;

use App\Support\CaseStudyContent;
use App\Support\GalleryContent;
use App\Support\HomepageContent;
use App\Support\PageContent;
use App\Support\PublicPageContent;
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
            'posts' => [
                'label' => 'Case Study',
                'heading' => 'Case Study',
                'description' => 'Use this area for case study management.',
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
            'contactSettings' => $content['contact'],
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
        return view('admin.pages.index', $this->sharedData($request, 'pages') + $this->pageAdminContext('pages') + [
            'pages' => PageContent::load(),
        ]);
    }

    public function posts(Request $request): View
    {
        return view('admin.pages.index', $this->sharedData($request, 'posts') + $this->pageAdminContext('posts') + [
            'pages' => $this->postsOnly(PageContent::load()),
        ]);
    }

    public function createPage(Request $request): View
    {
        return view('admin.pages.form', $this->sharedData($request, 'pages') + $this->pageAdminContext('pages') + [
            'pageData' => PageContent::defaults(),
            'pageTypes' => $this->pageTypes(),
            'formMode' => 'create',
        ]);
    }

    public function createPost(Request $request): View
    {
        return view('admin.pages.form', $this->sharedData($request, 'posts') + $this->pageAdminContext('posts') + [
            'pageData' => array_merge(PageContent::defaults(), [
                'heading_two' => 'Brief',
                'delivery_heading' => 'Delivery',
                'type' => 'Post',
            ]),
            'pageTypes' => ['Post'],
            'formMode' => 'create',
        ]);
    }

    public function caseStudies(Request $request): View
    {
        return view('admin.case-studies.index', $this->sharedData($request, 'case-study') + [
            'caseStudies' => CaseStudyContent::load(),
            'pageContent' => PublicPageContent::ourWork(),
        ]);
    }

    public function updateCaseStudiesPage(Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'eyebrow' => ['required', 'string', 'max:120'],
            'title' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:700'],
        ]);

        PublicPageContent::saveOurWork($payload);

        return redirect()->route('admin.case-studies.index')->with('status', 'Our Work page intro updated successfully.');
    }

    public function servicesPage(Request $request): View
    {
        return view('admin.services-page', $this->sharedData($request, 'services') + [
            'pageContent' => PublicPageContent::services(),
        ]);
    }

    public function updateServicesPage(Request $request): RedirectResponse
    {
        $existingContent = PublicPageContent::services();

        $request->validate([
            'eyebrow' => ['required', 'string', 'max:120'],
            'title' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:900'],
            'cards' => ['required', 'array', 'min:1'],
            'cards.*.title' => ['required', 'string', 'max:140'],
            'cards.*.description' => ['required', 'string', 'max:700'],
            'cards.*.image' => ['nullable', 'string', 'max:255'],
            'cards.*.image_alt' => ['nullable', 'string', 'max:180'],
            'cards.*.image_file' => ['nullable', 'image', 'max:5120'],
            'cards.*.remove_image' => ['nullable', 'boolean'],
        ]);

        $cards = [];
        foreach ((array) $request->input('cards', []) as $index => $card) {
            $imagePath = trim((string) ($card['image'] ?? data_get($existingContent, "cards.$index.image", '')));

            if ((bool) ($card['remove_image'] ?? false)) {
                $this->deletePublicAsset(HomepageContent::storedPath($imagePath));
                $imagePath = '';
            }

            if ($request->hasFile("cards.$index.image_file")) {
                $this->deletePublicAsset(HomepageContent::storedPath($imagePath));
                $imagePath = $request->file("cards.$index.image_file")->store('homepage/services-page', 'public');
            }

            $cards[] = [
                'title' => trim((string) $card['title']),
                'description' => trim((string) $card['description']),
                'image' => $imagePath,
                'image_alt' => trim((string) ($card['image_alt'] ?? '')),
            ];
        }

        PublicPageContent::saveServices([
            'eyebrow' => trim((string) $request->input('eyebrow')),
            'title' => trim((string) $request->input('title')),
            'description' => trim((string) $request->input('description')),
            'cards' => $cards,
        ]);

        return redirect()->route('admin.section', ['section' => 'services'])->with('status', 'Our Services page updated successfully.');
    }

    public function createCaseStudy(Request $request): View
    {
        return view('admin.case-studies.form', $this->sharedData($request, 'case-study') + [
            'caseStudy' => CaseStudyContent::defaults(),
            'formMode' => 'create',
        ]);
    }

    public function storeCaseStudy(Request $request): RedirectResponse
    {
        $caseStudies = CaseStudyContent::load();
        $payload = $this->validatedCaseStudyPayload($request);
        $imagePath = $this->storeCaseStudyImage($request, '');
        $timestamp = now()->toIso8601String();

        $caseStudies[] = [
            'id' => (string) Str::uuid(),
            'slug' => $this->uniqueContentSlug($payload['title'], $caseStudies, 'case-study'),
            'title' => $payload['title'],
            'image' => $imagePath,
            'image_alt' => $payload['image_alt'],
            'status' => $payload['status'],
            'order' => $payload['order'],
            'description' => $payload['description'],
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];

        CaseStudyContent::save($caseStudies);

        return redirect()->route('admin.case-studies.index')->with('status', 'Case study created successfully.');
    }

    public function editCaseStudy(Request $request, string $caseStudyId): View
    {
        $caseStudy = CaseStudyContent::findById($caseStudyId);
        abort_unless(is_array($caseStudy), 404);

        return view('admin.case-studies.form', $this->sharedData($request, 'case-study') + [
            'caseStudy' => $caseStudy,
            'formMode' => 'edit',
        ]);
    }

    public function updateCaseStudy(Request $request, string $caseStudyId): RedirectResponse
    {
        $caseStudies = CaseStudyContent::load();
        $caseStudy = null;

        foreach ($caseStudies as $index => $item) {
            if ($item['id'] === $caseStudyId) {
                $caseStudy = $item;
                break;
            }
        }

        abort_unless(is_array($caseStudy), 404);

        $payload = $this->validatedCaseStudyPayload($request);
        $imagePath = $this->storeCaseStudyImage($request, (string) ($caseStudy['image'] ?? ''));
        $caseStudies[$index] = [
            'id' => $caseStudy['id'],
            'slug' => $this->uniqueContentSlug($payload['title'], $caseStudies, 'case-study', $caseStudy['id']),
            'title' => $payload['title'],
            'image' => $imagePath,
            'image_alt' => $payload['image_alt'],
            'status' => $payload['status'],
            'order' => $payload['order'],
            'description' => $payload['description'],
            'created_at' => (string) ($caseStudy['created_at'] ?? now()->toIso8601String()),
            'updated_at' => now()->toIso8601String(),
        ];

        CaseStudyContent::save($caseStudies);

        return redirect()->route('admin.case-studies.index')->with('status', 'Case study updated successfully.');
    }

    public function deleteCaseStudy(Request $request, string $caseStudyId): RedirectResponse
    {
        $caseStudies = [];
        $deleted = false;

        foreach (CaseStudyContent::load() as $caseStudy) {
            if ($caseStudy['id'] === $caseStudyId) {
                $this->deletePublicAsset(HomepageContent::storedPath((string) ($caseStudy['image'] ?? '')));
                $deleted = true;
                continue;
            }

            $caseStudies[] = $caseStudy;
        }

        abort_unless($deleted, 404);

        CaseStudyContent::save($caseStudies);

        return redirect()->route('admin.case-studies.index')->with('status', 'Case study deleted successfully.');
    }

    public function storePage(Request $request): RedirectResponse
    {
        return $this->storePageLikeContent($request, 'pages');
    }

    public function storePost(Request $request): RedirectResponse
    {
        return $this->storePageLikeContent($request, 'posts');
    }

    public function editPage(Request $request, string $pageId): View
    {
        $page = PageContent::findById($pageId);
        abort_unless(is_array($page), 404);

        return view('admin.pages.form', $this->sharedData($request, 'pages') + $this->pageAdminContext('pages') + [
            'pageData' => $page,
            'pageTypes' => $this->pageTypes(),
            'formMode' => 'edit',
        ]);
    }

    public function editPost(Request $request, string $postId): View
    {
        $post = PageContent::findById($postId);
        abort_unless(is_array($post) && $post['type'] === 'Post', 404);

        return view('admin.pages.form', $this->sharedData($request, 'posts') + $this->pageAdminContext('posts') + [
            'pageData' => $post,
            'pageTypes' => ['Post'],
            'formMode' => 'edit',
        ]);
    }

    public function updatePage(Request $request, string $pageId): RedirectResponse
    {
        return $this->updatePageLikeContent($request, $pageId, 'pages');
    }

    public function updatePost(Request $request, string $postId): RedirectResponse
    {
        return $this->updatePageLikeContent($request, $postId, 'posts');
    }

    private function updatePageLikeContent(Request $request, string $pageId, string $adminContext): RedirectResponse
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
        abort_unless($adminContext !== 'posts' || $page['type'] === 'Post', 404);

        $payload = $this->validatedPagePayload($request, $adminContext);
        if ($adminContext === 'posts') {
            $payload['type'] = 'Post';
        }

        $imagePath = $this->storePageImage($request, (string) ($page['image'] ?? ''));
        $galleryImages = $this->storePageGalleryImages($request, is_array($page['gallery_images'] ?? null) ? $page['gallery_images'] : []);
        $updatedPage = [
            'id' => $page['id'],
            'slug' => $this->uniquePageSlug($payload['title'], $pages, $page['id']),
            'meta_title' => $payload['meta_title'],
            'meta_description' => $payload['meta_description'],
            'title' => $payload['title'],
            'image' => $imagePath,
            'image_alt' => $payload['image_alt'],
            'gallery_images' => $galleryImages,
            'event_date' => $payload['event_date'],
            'heading_two' => $payload['heading_two'],
            'delivery_heading' => $payload['delivery_heading'],
            'delivery_description' => $payload['delivery_description'],
            'type' => $payload['type'],
            'description' => $payload['description'],
            'created_at' => (string) ($page['created_at'] ?? now()->toIso8601String()),
            'updated_at' => now()->toIso8601String(),
        ];

        $pages[$index] = $updatedPage;

        PageContent::save($pages);

        return redirect($this->pageAdminRedirectUrl($adminContext))->with('status', $this->pageAdminLabel($adminContext) . ' updated successfully.');
    }

    public function deletePage(Request $request, string $pageId): RedirectResponse
    {
        return $this->deletePageLikeContent($pageId, 'pages');
    }

    public function deletePost(Request $request, string $postId): RedirectResponse
    {
        return $this->deletePageLikeContent($postId, 'posts');
    }

    private function deletePageLikeContent(string $pageId, string $adminContext): RedirectResponse
    {
        $pages = [];
        $deleted = false;

        foreach (PageContent::load() as $page) {
            if ($page['id'] === $pageId) {
                abort_if($adminContext === 'posts' && $page['type'] !== 'Post', 404);
                $this->deletePublicAsset(HomepageContent::storedPath((string) ($page['image'] ?? '')));
                $this->deletePageGalleryImages(is_array($page['gallery_images'] ?? null) ? $page['gallery_images'] : []);
                $deleted = true;
                continue;
            }

            $pages[] = $page;
        }

        abort_unless($deleted, 404);

        PageContent::save($pages);

        return redirect($this->pageAdminRedirectUrl($adminContext))->with('status', $this->pageAdminLabel($adminContext) . ' deleted successfully.');
    }

    public function bulkDeletePages(Request $request): RedirectResponse
    {
        return $this->bulkDeletePageLikeContent($request, 'pages');
    }

    public function bulkDeletePosts(Request $request): RedirectResponse
    {
        return $this->bulkDeletePageLikeContent($request, 'posts');
    }

    private function bulkDeletePageLikeContent(Request $request, string $adminContext): RedirectResponse
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
                if ($adminContext === 'posts' && $page['type'] !== 'Post') {
                    $remainingPages[] = $page;
                    continue;
                }

                $this->deletePublicAsset(HomepageContent::storedPath((string) ($page['image'] ?? '')));
                $this->deletePageGalleryImages(is_array($page['gallery_images'] ?? null) ? $page['gallery_images'] : []);
                continue;
            }

            $remainingPages[] = $page;
        }

        PageContent::save($remainingPages);

        return redirect($this->pageAdminRedirectUrl($adminContext))->with('status', 'Selected ' . strtolower($this->pageAdminPluralLabel($adminContext)) . ' deleted.');
    }

    public function section(Request $request, string $section): View
    {
        if ($section === 'services') {
            return $this->servicesPage($request);
        }

        if ($section === 'pages') {
            return $this->pages($request);
        }

        if ($section === 'case-study') {
            return $this->caseStudies($request);
        }

        if ($section === 'posts') {
            return $this->posts($request);
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
            'contact.whatsapp_phone' => ['nullable', 'string', 'max:40'],
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

        $contact = array_merge(
            HomepageContent::defaults()['contact'],
            (array) ($existingContent['contact'] ?? []),
            [
                'whatsapp_phone' => trim((string) data_get(
                    $request->input('contact', []),
                    'whatsapp_phone',
                    data_get($existingContent, 'contact.whatsapp_phone', '')
                )),
            ]
        );

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
            'contact' => $contact,
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
    private function storePageLikeContent(Request $request, string $adminContext): RedirectResponse
    {
        $pages = PageContent::load();
        $payload = $this->validatedPagePayload($request, $adminContext);
        if ($adminContext === 'posts') {
            $payload['type'] = 'Post';
        }

        $imagePath = $this->storePageImage($request, '');
        $galleryImages = $this->storePageGalleryImages($request, []);
        $timestamp = now()->toIso8601String();

        array_unshift($pages, [
            'id' => (string) Str::uuid(),
            'slug' => $this->uniquePageSlug($payload['title'], $pages),
            'meta_title' => $payload['meta_title'],
            'meta_description' => $payload['meta_description'],
            'title' => $payload['title'],
            'image' => $imagePath,
            'image_alt' => $payload['image_alt'],
            'gallery_images' => $galleryImages,
            'event_date' => $payload['event_date'],
            'heading_two' => $payload['heading_two'],
            'delivery_heading' => $payload['delivery_heading'],
            'delivery_description' => $payload['delivery_description'],
            'type' => $payload['type'],
            'description' => $payload['description'],
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);

        PageContent::save($pages);

        return redirect($this->pageAdminRedirectUrl($adminContext))->with('status', $this->pageAdminLabel($adminContext) . ' created successfully.');
    }

    /**
     * @param  array<int, array<string, mixed>>  $pages
     * @return array<int, array<string, mixed>>
     */
    private function postsOnly(array $pages): array
    {
        return array_values(array_filter(
            $pages,
            fn (array $page): bool => (string) ($page['type'] ?? '') === 'Post'
        ));
    }

    /**
     * @return array<string, mixed>
     */
    private function pageAdminContext(string $adminContext): array
    {
        $isPosts = $adminContext === 'posts';

        return [
            'adminContext' => $adminContext,
            'pageHeading' => $isPosts ? 'Case Study' : 'Pages',
            'pageDescription' => $isPosts ? 'Manage case studies and published content.' : 'Manage site pages and published content.',
            'listTitle' => $isPosts ? 'Case Study List' : 'Post List',
            'createRouteName' => $isPosts ? 'admin.posts.create' : 'admin.pages.create',
            'storeRouteName' => $isPosts ? 'admin.posts.store' : 'admin.pages.store',
            'editRouteName' => $isPosts ? 'admin.posts.edit' : 'admin.pages.edit',
            'updateRouteName' => $isPosts ? 'admin.posts.update' : 'admin.pages.update',
            'deleteRouteName' => $isPosts ? 'admin.posts.delete' : 'admin.pages.delete',
            'bulkDeleteRouteName' => $isPosts ? 'admin.posts.bulk-delete' : 'admin.pages.bulk-delete',
            'routeIdName' => $isPosts ? 'postId' : 'pageId',
            'contentLabel' => $isPosts ? 'Case Study' : 'Page',
            'createButtonLabel' => $isPosts ? 'Add Case Study' : 'Add Page',
            'emptyMessage' => $isPosts
                ? 'No case studies created yet. Use Add Case Study to create the first one with the new template.'
                : 'No pages created yet. Use Add Page to create the first one with the new template.',
            'cancelUrl' => $this->pageAdminRedirectUrl($adminContext),
        ];
    }

    private function pageAdminRedirectUrl(string $adminContext): string
    {
        return $adminContext === 'posts'
            ? route('admin.posts.index')
            : route('admin.section', ['section' => 'pages']);
    }

    private function pageAdminLabel(string $adminContext): string
    {
        return $adminContext === 'posts' ? 'Case study' : 'Page';
    }

    private function pageAdminPluralLabel(string $adminContext): string
    {
        return $adminContext === 'posts' ? 'Case studies' : 'Pages';
    }

    private function validatedPagePayload(Request $request, string $adminContext = 'pages'): array
    {
        $isPosts = $adminContext === 'posts';
        $validated = $request->validate([
            'event_date' => [$isPosts ? 'required' : 'nullable', 'date'],
            'meta_title' => [$isPosts ? 'nullable' : 'required', 'string', 'max:160'],
            'meta_description' => ['required', 'string', 'max:320'],
            'title' => ['required', 'string', 'max:180'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'max:5120'],
            'image_remove' => ['nullable', 'boolean'],
            'gallery_existing' => ['nullable', 'array', 'max:6'],
            'gallery_existing.*' => ['nullable', 'string', 'max:255'],
            'gallery_images' => ['nullable', 'array', 'max:6'],
            'gallery_images.*' => ['nullable', 'image', 'max:5120'],
            'gallery_remove' => ['nullable', 'array', 'max:6'],
            'gallery_remove.*' => ['nullable', 'boolean'],
            'image_alt' => ['nullable', 'string', 'max:180'],
            'heading_two' => [$isPosts ? 'nullable' : 'required', 'string', 'max:220'],
            'delivery_heading' => ['nullable', 'string', 'max:220'],
            'delivery_description' => ['nullable', 'string'],
            'type' => ['required', Rule::in($this->pageTypes())],
            'description' => ['required', 'string'],
        ]);

        $title = trim((string) $validated['title']);

        return [
            'event_date' => trim((string) ($validated['event_date'] ?? '')),
            'meta_title' => trim((string) ($validated['meta_title'] ?? '')) ?: $title,
            'meta_description' => trim((string) $validated['meta_description']),
            'title' => $title,
            'image_alt' => trim((string) ($validated['image_alt'] ?? '')),
            'heading_two' => trim((string) ($validated['heading_two'] ?? '')) ?: 'Brief',
            'delivery_heading' => trim((string) ($validated['delivery_heading'] ?? '')) ?: 'Delivery',
            'delivery_description' => trim((string) ($validated['delivery_description'] ?? '')),
            'type' => trim((string) $validated['type']),
            'description' => trim((string) $validated['description']),
        ];
    }

    /**
     * @return array{
     *   title:string,
     *   image_alt:string,
     *   status:string,
     *   order:int,
     *   description:string
     * }
     */
    private function validatedCaseStudyPayload(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'max:5120'],
            'image_remove' => ['nullable', 'boolean'],
            'image_alt' => ['nullable', 'string', 'max:180'],
            'status' => ['required', Rule::in(['Active', 'Draft'])],
            'order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'description' => ['nullable', 'string'],
        ]);

        return [
            'title' => trim((string) $validated['title']),
            'image_alt' => trim((string) ($validated['image_alt'] ?? '')),
            'status' => trim((string) $validated['status']),
            'order' => (int) ($validated['order'] ?? 0),
            'description' => trim((string) ($validated['description'] ?? '')),
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

    private function storeCaseStudyImage(Request $request, string $existingPath): string
    {
        $imagePath = trim((string) $request->input('image_path', $existingPath));

        if ($request->boolean('image_remove')) {
            $this->deletePublicAsset(HomepageContent::storedPath($imagePath));
            $imagePath = '';
        }

        if ($request->hasFile('image_file')) {
            $this->deletePublicAsset(HomepageContent::storedPath($imagePath));
            $imagePath = $request->file('image_file')->store('homepage/case-studies', 'public');
        }

        return $imagePath;
    }

    /**
     * @param  array<int, string>  $existingImages
     * @return array<int, string>
     */
    private function storePageGalleryImages(Request $request, array $existingImages): array
    {
        $submittedImages = $request->input('gallery_existing', []);
        $submittedImages = is_array($submittedImages) ? $submittedImages : [];
        $galleryImages = [];

        for ($index = 0; $index < 6; $index++) {
            $imagePath = trim((string) ($submittedImages[$index] ?? ($existingImages[$index] ?? '')));

            if ($request->boolean("gallery_remove.$index")) {
                $this->deletePublicAsset(HomepageContent::storedPath($imagePath));
                $imagePath = '';
            }

            if ($request->hasFile("gallery_images.$index")) {
                $this->deletePublicAsset(HomepageContent::storedPath($imagePath));
                $imagePath = $request->file("gallery_images.$index")->store('homepage/pages/gallery', 'public');
            }

            if ($imagePath !== '') {
                $galleryImages[] = $imagePath;
            }
        }

        return $galleryImages;
    }

    /**
     * @param  array<int, string>  $images
     */
    private function deletePageGalleryImages(array $images): void
    {
        foreach ($images as $image) {
            $this->deletePublicAsset(HomepageContent::storedPath((string) $image));
        }
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
        return $this->uniqueContentSlug($title, $pages, 'page', $ignoreId);
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private function uniqueContentSlug(string $title, array $items, string $fallback, ?string $ignoreId = null): string
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = $fallback;
        }

        $slug = $base;
        $suffix = 2;

        while (collect($items)->contains(function (array $item) use ($slug, $ignoreId): bool {
            return (string) ($item['slug'] ?? '') === $slug
                && (string) ($item['id'] ?? '') !== (string) $ignoreId;
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
            ['label' => 'Our Services', 'href' => route('our-services')],
            ['label' => 'Our Process', 'href' => '#'],
            ['label' => 'Our Team', 'href' => '#'],
            ['label' => 'Media', 'href' => '#'],
            ['label' => 'Contact', 'href' => '#'],
            ['label' => 'Blog', 'href' => '#'],
            ['label' => 'Payment', 'href' => '#'],
        ];

        $menu = [
            ['key' => 'overview', 'label' => 'Overview', 'href' => route('admin.section', ['section' => 'overview'])],
            ['key' => 'case-study', 'label' => 'Our Work', 'href' => route('admin.case-studies.index')],
            ['key' => 'services', 'label' => 'Our Services', 'href' => route('admin.section', ['section' => 'services'])],
            ['key' => 'posts', 'label' => 'Case Study', 'href' => route('admin.posts.index')],
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
