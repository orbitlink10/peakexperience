<?php

namespace Tests\Feature;

use App\Models\HomepageSetting;
use App\Support\HomepageContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminHomepageTest extends TestCase
{
    use RefreshDatabase;

    private const TINY_PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+yf9sAAAAASUVORK5CYII=';

    public function test_admin_can_upload_homepage_section_images(): void
    {
        Storage::fake('public');

        $defaults = HomepageContent::defaults();

        $response = $this
            ->withSession(['admin_authenticated' => true, 'admin_username' => 'admin'])
            ->post(route('admin.homepage.update'), [
                'section_images' => [
                    'hero' => ['path' => ''],
                    'intro' => ['path' => ''],
                    'services' => ['path' => ''],
                    'proof' => [
                        'path' => '',
                        'file' => UploadedFile::fake()->createWithContent('proof-image.png', base64_decode(self::TINY_PNG)),
                    ],
                ],
                'hero_video' => $defaults['hero_video'],
                'what_we_do' => $defaults['what_we_do'],
                'our_process' => $defaults['our_process'],
            ]);

        $response->assertRedirect(route('admin.homepage'));
        $response->assertSessionHas('status', 'Homepage updated successfully.');

        $sectionImages = HomepageSetting::query()->where('key', 'section_images')->first();

        $this->assertNotNull($sectionImages);
        $storedPath = (string) data_get($sectionImages?->value, 'proof.path', '');
        $this->assertStringStartsWith('homepage/sections/', $storedPath);
        Storage::disk('public')->assertExists($storedPath);
    }

    public function test_homepage_uses_uploaded_section_images(): void
    {
        HomepageSetting::query()->create([
            'key' => 'section_images',
            'value' => [
                'hero' => ['path' => 'homepage/sections/hero.png'],
                'intro' => ['path' => 'homepage/sections/intro.png'],
                'services' => ['path' => 'homepage/sections/services.png'],
                'proof' => ['path' => 'homepage/sections/proof.png'],
            ],
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee(route('homepage.asset', ['path' => 'homepage/sections/intro.png']), false);
        $response->assertSee(route('homepage.asset', ['path' => 'homepage/sections/services.png']), false);
        $response->assertSee(route('homepage.asset', ['path' => 'homepage/sections/proof.png']), false);
    }
}
