<?php

namespace Tests\Feature;

use App\Models\HomepageSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminGalleryTest extends TestCase
{
    use RefreshDatabase;

    private const TINY_PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+yf9sAAAAASUVORK5CYII=';

    public function test_admin_can_upload_a_gallery_image(): void
    {
        Storage::fake('public');

        $response = $this
            ->withSession(['admin_authenticated' => true, 'admin_username' => 'admin'])
            ->post(route('admin.gallery.upload'), [
                'image_file' => UploadedFile::fake()->createWithContent('lighting-team.png', base64_decode(self::TINY_PNG)),
            ]);

        $response->assertRedirect(route('admin.gallery'));
        $response->assertSessionHas('status', 'Image added successfully.');

        $gallery = HomepageSetting::query()->where('key', 'gallery')->first();

        $this->assertNotNull($gallery);
        $this->assertIsArray($gallery->value);
        $this->assertCount(9, $gallery->value);
        $this->assertSame('Lighting Team', $gallery->value[8]['title']);
        $this->assertStringStartsWith('homepage/gallery/', $gallery->value[8]['image']);
        Storage::disk('public')->assertExists($gallery->value[8]['image']);
    }

    public function test_admin_can_delete_selected_gallery_images(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()
            ->createWithContent('stage.png', base64_decode(self::TINY_PNG))
            ->store('homepage/gallery', 'public');

        HomepageSetting::query()->create([
            'key' => 'gallery',
            'value' => [
                ['title' => 'Remote Image', 'image' => 'https://example.com/remote.jpg'],
                ['title' => 'Stored Image', 'image' => $path],
            ],
        ]);

        $response = $this
            ->withSession(['admin_authenticated' => true, 'admin_username' => 'admin'])
            ->post(route('admin.gallery.delete'), [
                'selected' => [1],
            ]);

        $response->assertRedirect(route('admin.gallery'));
        $response->assertSessionHas('status', 'Selected images deleted.');

        $gallery = HomepageSetting::query()->where('key', 'gallery')->first();

        $this->assertNotNull($gallery);
        $this->assertSame([
            ['title' => 'Remote Image', 'image' => 'https://example.com/remote.jpg'],
        ], $gallery->value);
        Storage::disk('public')->assertMissing($path);
    }
}
