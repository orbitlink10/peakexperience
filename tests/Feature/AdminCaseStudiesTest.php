<?php

namespace Tests\Feature;

use App\Models\HomepageSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCaseStudiesTest extends TestCase
{
    use RefreshDatabase;

    private const TINY_PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+yf9sAAAAASUVORK5CYII=';

    public function test_case_study_section_displays_management_list(): void
    {
        HomepageSetting::query()->create([
            'key' => 'case_studies',
            'value' => [
                [
                    'id' => 'case-1',
                    'slug' => 'summit-production',
                    'title' => 'Summit Production',
                    'image' => '',
                    'image_alt' => 'Summit Production',
                    'status' => 'Active',
                    'order' => 1,
                    'description' => 'A polished summit delivery.',
                    'created_at' => now()->toIso8601String(),
                    'updated_at' => now()->toIso8601String(),
                ],
            ],
        ]);

        $response = $this
            ->withSession(['admin_authenticated' => true, 'admin_username' => 'admin'])
            ->get(route('admin.case-studies.index'));

        $response->assertOk();
        $response->assertSee('Our Work List');
        $response->assertSee('Add Our Work');
        $response->assertSee('Summit Production');
        $response->assertDontSee('<th>Heading</th>', false);
        $response->assertSee('<th>Title</th>', false);
    }

    public function test_admin_can_create_update_and_delete_case_study(): void
    {
        Storage::fake('public');

        $createResponse = $this
            ->withSession(['admin_authenticated' => true, 'admin_username' => 'admin'])
            ->post(route('admin.case-studies.store'), [
                'title' => 'Africa Fintech Forum',
                'image_file' => UploadedFile::fake()->createWithContent('forum.png', base64_decode(self::TINY_PNG)),
                'image_alt' => 'Africa Fintech Forum setup',
                'status' => 'Active',
                'order' => 2,
                'description' => 'Conference staging and production.',
            ]);

        $createResponse->assertRedirect(route('admin.case-studies.index'));
        $createResponse->assertSessionHas('status', 'Case study created successfully.');

        $setting = HomepageSetting::query()->where('key', 'case_studies')->first();
        $this->assertNotNull($setting);
        $this->assertSame('africa-fintech-forum', $setting->value[0]['slug']);
        $this->assertStringStartsWith('homepage/case-studies/', $setting->value[0]['image']);
        Storage::disk('public')->assertExists($setting->value[0]['image']);

        $caseStudyId = $setting->value[0]['id'];
        $imagePath = $setting->value[0]['image'];

        $updateResponse = $this
            ->withSession(['admin_authenticated' => true, 'admin_username' => 'admin'])
            ->put(route('admin.case-studies.update', ['caseStudyId' => $caseStudyId]), [
                'title' => 'Updated Forum',
                'image_path' => $imagePath,
                'image_alt' => 'Updated forum',
                'status' => 'Draft',
                'order' => 4,
                'description' => 'Updated summary.',
            ]);

        $updateResponse->assertRedirect(route('admin.case-studies.index'));

        $setting->refresh();
        $this->assertSame('updated-forum', $setting->value[0]['slug']);
        $this->assertSame('Draft', $setting->value[0]['status']);
        $this->assertSame(4, $setting->value[0]['order']);

        $deleteResponse = $this
            ->withSession(['admin_authenticated' => true, 'admin_username' => 'admin'])
            ->delete(route('admin.case-studies.delete', ['caseStudyId' => $caseStudyId]));

        $deleteResponse->assertRedirect(route('admin.case-studies.index'));
        $deleteResponse->assertSessionHas('status', 'Case study deleted successfully.');

        $setting->refresh();
        $this->assertSame([], $setting->value);
        Storage::disk('public')->assertMissing($imagePath);
    }

    public function test_public_our_work_lists_saved_posts(): void
    {
        HomepageSetting::query()->create([
            'key' => 'pages',
            'value' => [
                [
                    'id' => 'post-1',
                    'slug' => 'active-case',
                    'meta_title' => 'Active Case',
                    'meta_description' => 'Visible post summary.',
                    'title' => 'Active Case',
                    'image' => 'https://example.com/active.jpg',
                    'image_alt' => 'Active post image',
                    'gallery_images' => [],
                    'event_date' => '2026-03-20',
                    'heading_two' => 'Brief',
                    'delivery_heading' => 'Delivery',
                    'delivery_description' => '',
                    'type' => 'Post',
                    'description' => '<p>Visible post body.</p>',
                    'created_at' => now()->toIso8601String(),
                    'updated_at' => now()->toIso8601String(),
                ],
                [
                    'id' => 'page-1',
                    'slug' => 'normal-page',
                    'meta_title' => 'Normal Page',
                    'meta_description' => 'Hidden page summary.',
                    'title' => 'Normal Page',
                    'image' => '',
                    'image_alt' => '',
                    'gallery_images' => [],
                    'heading_two' => 'Normal Page',
                    'type' => 'Page',
                    'description' => '<p>Hidden page body.</p>',
                    'created_at' => now()->toIso8601String(),
                    'updated_at' => now()->toIso8601String(),
                ],
            ],
        ]);

        $response = $this->get(route('our-work'));

        $response->assertOk();
        $response->assertSee('Our Work');
        $response->assertSee('Active Case');
        $response->assertSee('Visible post summary.');
        $response->assertSee('https://example.com/active.jpg', false);
        $response->assertSee(route('pages.show', ['page' => 'active-case']), false);
        $response->assertDontSee('<a class="work-card" href="' . route('pages.show', ['page' => 'normal-page']) . '">', false);
    }
}
