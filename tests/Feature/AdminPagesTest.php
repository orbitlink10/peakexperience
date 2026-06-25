<?php

namespace Tests\Feature;

use App\Models\HomepageSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    private const TINY_PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+yf9sAAAAASUVORK5CYII=';

    public function test_pages_section_displays_page_list_interface(): void
    {
        HomepageSetting::query()->create([
            'key' => 'pages',
            'value' => [
                [
                    'id' => 'page-1',
                    'slug' => 'starlink-in-kenya',
                    'meta_title' => 'Starlink in Kenya',
                    'meta_description' => 'Manage Starlink page',
                    'title' => 'Starlink in Kenya',
                    'image' => '',
                    'image_alt' => 'Starlink Dish',
                    'heading_two' => 'Why Starlink matters',
                    'type' => 'Post',
                    'description' => '<p>Body copy</p>',
                    'created_at' => now()->toIso8601String(),
                    'updated_at' => now()->toIso8601String(),
                ],
            ],
        ]);

        $response = $this
            ->withSession(['admin_authenticated' => true, 'admin_username' => 'admin'])
            ->get(route('admin.section', ['section' => 'pages']));

        $response->assertOk();
        $response->assertSee('Post List');
        $response->assertSee('Add Page');
        $response->assertSee('Starlink in Kenya');
    }

    public function test_page_form_renders_active_editor_controls(): void
    {
        $response = $this
            ->withSession(['admin_authenticated' => true, 'admin_username' => 'admin'])
            ->get(route('admin.pages.create'));

        $response->assertOk();
        $response->assertSee('contenteditable="true"', false);
        $response->assertSee('data-editor-menu-toggle', false);
        $response->assertSee('data-editor-link', false);
        $response->assertSee('data-editor-image', false);
        $response->assertSee('data-editor-video', false);
        $response->assertSee('data-editor-fullscreen', false);
    }

    public function test_posts_section_lists_only_posts_and_uses_post_routes(): void
    {
        HomepageSetting::query()->create([
            'key' => 'pages',
            'value' => [
                [
                    'id' => 'post-1',
                    'slug' => 'starlink-in-kenya',
                    'meta_title' => 'Starlink in Kenya',
                    'meta_description' => 'Manage Starlink post',
                    'title' => 'Starlink in Kenya',
                    'image' => '',
                    'image_alt' => 'Starlink Dish',
                    'heading_two' => 'Why Starlink matters',
                    'type' => 'Post',
                    'description' => '<p>Body copy</p>',
                    'created_at' => now()->toIso8601String(),
                    'updated_at' => now()->toIso8601String(),
                ],
                [
                    'id' => 'page-1',
                    'slug' => 'brand-experiences',
                    'meta_title' => 'Brand Experiences',
                    'meta_description' => 'Manage Brand Experiences page',
                    'title' => 'Brand Experiences',
                    'image' => '',
                    'image_alt' => 'Brand Experiences',
                    'heading_two' => 'Brand Experiences',
                    'type' => 'Page',
                    'description' => '<p>Page copy</p>',
                    'created_at' => now()->toIso8601String(),
                    'updated_at' => now()->toIso8601String(),
                ],
            ],
        ]);

        $response = $this
            ->withSession(['admin_authenticated' => true, 'admin_username' => 'admin'])
            ->get(route('admin.posts.index'));

        $response->assertOk();
        $response->assertSee('Posts');
        $response->assertSee('Post List');
        $response->assertSee('Add Post');
        $response->assertSee(route('admin.posts.create'), false);
        $response->assertSee(route('admin.posts.edit', ['postId' => 'post-1']), false);
        $response->assertSee('Starlink in Kenya');
        $response->assertDontSee('Brand Experiences');
    }

    public function test_admin_can_create_page_from_pages_template(): void
    {
        Storage::fake('public');

        $response = $this
            ->withSession(['admin_authenticated' => true, 'admin_username' => 'admin'])
            ->post(route('admin.pages.store'), [
                'meta_title' => 'Starlink Nairobi Meta',
                'meta_description' => 'Starlink Nairobi meta description',
                'title' => 'Starlink Nairobi',
                'image_file' => UploadedFile::fake()->createWithContent('starlink.png', base64_decode(self::TINY_PNG)),
                'image_alt' => 'Starlink Nairobi',
                'heading_two' => 'Starlink Nairobi Heading',
                'type' => 'Post',
                'description' => '<p>Detailed page description.</p>',
                'gallery_images' => [
                    0 => UploadedFile::fake()->createWithContent('gallery-one.png', base64_decode(self::TINY_PNG)),
                    1 => UploadedFile::fake()->createWithContent('gallery-two.png', base64_decode(self::TINY_PNG)),
                ],
            ]);

        $response->assertRedirect(route('admin.section', ['section' => 'pages']));
        $response->assertSessionHas('status', 'Page created successfully.');

        $pages = HomepageSetting::query()->where('key', 'pages')->first();

        $this->assertNotNull($pages);
        $this->assertIsArray($pages->value);
        $this->assertCount(1, $pages->value);
        $this->assertSame('starlink-nairobi', $pages->value[0]['slug']);
        $this->assertStringStartsWith('homepage/pages/', $pages->value[0]['image']);
        $this->assertCount(2, $pages->value[0]['gallery_images']);
        $this->assertStringStartsWith('homepage/pages/gallery/', $pages->value[0]['gallery_images'][0]);
        Storage::disk('public')->assertExists($pages->value[0]['image']);
        Storage::disk('public')->assertExists($pages->value[0]['gallery_images'][0]);
    }

    public function test_admin_can_create_post_from_posts_template(): void
    {
        Storage::fake('public');

        $response = $this
            ->withSession(['admin_authenticated' => true, 'admin_username' => 'admin'])
            ->post(route('admin.posts.store'), [
                'meta_title' => 'Starlink Nairobi Meta',
                'meta_description' => 'Starlink Nairobi meta description',
                'title' => 'Starlink Nairobi',
                'image_file' => UploadedFile::fake()->createWithContent('starlink.png', base64_decode(self::TINY_PNG)),
                'image_alt' => 'Starlink Nairobi',
                'heading_two' => 'Starlink Nairobi Heading',
                'type' => 'Post',
                'description' => '<p>Detailed post description.</p>',
            ]);

        $response->assertRedirect(route('admin.posts.index'));
        $response->assertSessionHas('status', 'Post created successfully.');

        $pages = HomepageSetting::query()->where('key', 'pages')->first();

        $this->assertNotNull($pages);
        $this->assertSame('starlink-nairobi', $pages->value[0]['slug']);
        $this->assertSame('Post', $pages->value[0]['type']);
        $this->assertStringStartsWith('homepage/pages/', $pages->value[0]['image']);
        Storage::disk('public')->assertExists($pages->value[0]['image']);
    }

    public function test_admin_can_update_and_delete_pages(): void
    {
        Storage::fake('public');

        $imagePath = UploadedFile::fake()
            ->createWithContent('existing.png', base64_decode(self::TINY_PNG))
            ->store('homepage/pages', 'public');

        $galleryPath = UploadedFile::fake()
            ->createWithContent('gallery.png', base64_decode(self::TINY_PNG))
            ->store('homepage/pages/gallery', 'public');

        HomepageSetting::query()->create([
            'key' => 'pages',
            'value' => [
                [
                    'id' => 'page-1',
                    'slug' => 'old-slug',
                    'meta_title' => 'Old Meta',
                    'meta_description' => 'Old description',
                    'title' => 'Old Title',
                    'image' => $imagePath,
                    'image_alt' => 'Old alt',
                    'heading_two' => 'Old heading',
                    'type' => 'Post',
                    'description' => '<p>Old body</p>',
                    'created_at' => now()->subDay()->toIso8601String(),
                    'updated_at' => now()->subDay()->toIso8601String(),
                ],
            ],
        ]);

        $updateResponse = $this
            ->withSession(['admin_authenticated' => true, 'admin_username' => 'admin'])
            ->put(route('admin.pages.update', ['pageId' => 'page-1']), [
                'meta_title' => 'Updated Meta',
                'meta_description' => 'Updated description',
                'title' => 'Updated Title',
                'image_path' => $imagePath,
                'image_alt' => 'Updated alt',
                'heading_two' => 'Updated heading',
                'type' => 'Page',
                'description' => '<p>Updated body</p>',
            ]);

        $updateResponse->assertRedirect(route('admin.section', ['section' => 'pages']));

        $pages = HomepageSetting::query()->where('key', 'pages')->first();
        $this->assertSame('updated-title', $pages->value[0]['slug']);
        $this->assertSame('Page', $pages->value[0]['type']);

        $deleteResponse = $this
            ->withSession(['admin_authenticated' => true, 'admin_username' => 'admin'])
            ->delete(route('admin.pages.delete', ['pageId' => 'page-1']));

        $deleteResponse->assertRedirect(route('admin.section', ['section' => 'pages']));
        $deleteResponse->assertSessionHas('status', 'Page deleted successfully.');

        $pages = HomepageSetting::query()->where('key', 'pages')->first();
        $this->assertSame([], $pages?->value);
        Storage::disk('public')->assertMissing($imagePath);
    }

    public function test_public_page_preview_renders_saved_content(): void
    {
        $galleryPath = 'https://example.com/page-gallery.jpg';

        HomepageSetting::query()->create([
            'key' => 'pages',
            'value' => [
                [
                    'id' => 'page-1',
                    'slug' => 'starlink-nairobi',
                    'meta_title' => 'Starlink Nairobi Meta',
                    'meta_description' => 'Starlink Nairobi description',
                    'title' => 'Starlink Nairobi',
                    'image' => 'https://example.com/page-image.jpg',
                    'image_alt' => 'Starlink Nairobi',
                    'gallery_images' => [$galleryPath],
                    'heading_two' => 'Stay connected in Nairobi',
                    'type' => 'Post',
                    'description' => '<p>Rendered page content</p>',
                    'created_at' => now()->toIso8601String(),
                    'updated_at' => now()->toIso8601String(),
                ],
            ],
        ]);

        $response = $this->get(route('pages.show', ['page' => 'starlink-nairobi']));

        $response->assertOk();
        $response->assertSee('Starlink Nairobi');
        $response->assertSee('Stay connected in Nairobi');
        $response->assertSee('class="block-heading"', false);
        $response->assertSee('class="block-copy"', false);
        $response->assertSee('class="gmasonry__wrap"', false);
        $response->assertSee($galleryPath, false);
        $response->assertDontSee('<p class="block-head__subtitle">Starlink Nairobi description</p>', false);
        $response->assertDontSee('<p>Starlink Nairobi description</p>', false);
        $response->assertSee('Rendered page content', false);
    }

    public function test_public_navigation_lists_saved_pages_under_what_we_do(): void
    {
        HomepageSetting::query()->create([
            'key' => 'pages',
            'value' => [
                [
                    'id' => 'page-1',
                    'slug' => 'brand-experiences',
                    'meta_title' => 'Brand Experiences',
                    'meta_description' => 'Brand experience page',
                    'title' => 'Brand Experiences',
                    'image' => '',
                    'image_alt' => 'Brand Experiences',
                    'heading_two' => 'Brand Experiences',
                    'type' => 'Page',
                    'description' => '<p>Brand experience content</p>',
                    'created_at' => now()->toIso8601String(),
                    'updated_at' => now()->toIso8601String(),
                ],
                [
                    'id' => 'page-2',
                    'slug' => 'exhibitions',
                    'meta_title' => 'Exhibitions',
                    'meta_description' => 'Exhibitions page',
                    'title' => 'Exhibitions',
                    'image' => '',
                    'image_alt' => 'Exhibitions',
                    'heading_two' => 'Exhibitions',
                    'type' => 'Page',
                    'description' => '<p>Exhibition content</p>',
                    'created_at' => now()->toIso8601String(),
                    'updated_at' => now()->toIso8601String(),
                ],
                [
                    'id' => 'page-3',
                    'slug' => 'conferences',
                    'meta_title' => 'Conferences',
                    'meta_description' => 'Conferences page',
                    'title' => 'Conferences',
                    'image' => '',
                    'image_alt' => 'Conferences',
                    'heading_two' => 'Conferences',
                    'type' => 'Page',
                    'description' => '<p>Conference content</p>',
                    'created_at' => now()->toIso8601String(),
                    'updated_at' => now()->toIso8601String(),
                ],
            ],
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('class="nav-dropdown"', false);
        $response->assertSee('Brand Experiences');
        $response->assertSee('Exhibitions');
        $response->assertSee('Conferences');
        $response->assertSee(route('pages.show', ['page' => 'exhibitions']), false);
    }
}
