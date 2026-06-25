<?php

namespace App\Support;

use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Schema;

class PageContent
{
    /**
     * @return array<int, array{
     *     id:string,
     *     slug:string,
     *     meta_title:string,
     *     meta_description:string,
     *     title:string,
     *     image:string,
     *     image_alt:string,
     *     gallery_images:array<int, string>,
     *     heading_two:string,
     *     type:string,
     *     description:string,
     *     created_at:string,
     *     updated_at:string
     * }>
     */
    public static function load(): array
    {
        if (! Schema::hasTable('homepage_settings')) {
            return [];
        }

        $setting = HomepageSetting::query()->where('key', 'pages')->first();
        if (! is_array($setting?->value)) {
            return [];
        }

        return array_values(array_map(
            fn (array $page): array => self::normalize($page),
            array_filter($setting->value, 'is_array')
        ));
    }

    /**
     * @param  array<int, array<string, mixed>>  $pages
     */
    public static function save(array $pages): void
    {
        HomepageSetting::query()->updateOrCreate(
            ['key' => 'pages'],
            ['value' => array_values(array_map(fn (array $page): array => self::normalize($page), $pages))]
        );
    }

    /**
     * @return array{
     *     id:string,
     *     slug:string,
     *     meta_title:string,
     *     meta_description:string,
     *     title:string,
     *     image:string,
     *     image_alt:string,
     *     heading_two:string,
     *     type:string,
     *     description:string,
     *     created_at:string,
     *     updated_at:string
     * }
     */
    public static function defaults(): array
    {
        return [
            'id' => '',
            'slug' => '',
            'meta_title' => '',
            'meta_description' => '',
            'title' => '',
            'image' => '',
            'image_alt' => '',
            'gallery_images' => [],
            'event_date' => '',
            'heading_two' => '',
            'delivery_heading' => 'Delivery',
            'delivery_description' => '',
            'type' => 'Post',
            'description' => '',
            'created_at' => '',
            'updated_at' => '',
        ];
    }

    /**
     * @return array{
     *     id:string,
     *     slug:string,
     *     meta_title:string,
     *     meta_description:string,
     *     title:string,
     *     image:string,
     *     image_alt:string,
     *     heading_two:string,
     *     type:string,
     *     description:string,
     *     created_at:string,
     *     updated_at:string
     * }|null
     */
    public static function findById(string $id): ?array
    {
        foreach (self::load() as $page) {
            if ($page['id'] === $id) {
                return $page;
            }
        }

        return null;
    }

    /**
     * @return array{
     *     id:string,
     *     slug:string,
     *     meta_title:string,
     *     meta_description:string,
     *     title:string,
     *     image:string,
     *     image_alt:string,
     *     heading_two:string,
     *     type:string,
     *     description:string,
     *     created_at:string,
     *     updated_at:string
     * }|null
     */
    public static function findBySlug(string $slug): ?array
    {
        foreach (self::load() as $page) {
            if ($page['slug'] === $slug) {
                return $page;
            }
        }

        return null;
    }

    private static function normalize(array $page): array
    {
        $defaults = self::defaults();

        return [
            'id' => trim((string) ($page['id'] ?? $defaults['id'])),
            'slug' => trim((string) ($page['slug'] ?? $defaults['slug'])),
            'meta_title' => trim((string) ($page['meta_title'] ?? $defaults['meta_title'])),
            'meta_description' => trim((string) ($page['meta_description'] ?? $defaults['meta_description'])),
            'title' => trim((string) ($page['title'] ?? $defaults['title'])),
            'image' => trim((string) ($page['image'] ?? $defaults['image'])),
            'image_alt' => trim((string) ($page['image_alt'] ?? $defaults['image_alt'])),
            'gallery_images' => array_values(array_slice(array_filter(
                array_map(
                    fn ($image): string => trim((string) $image),
                    is_array($page['gallery_images'] ?? null) ? $page['gallery_images'] : []
                ),
                fn (string $image): bool => $image !== ''
            ), 0, 6)),
            'event_date' => trim((string) ($page['event_date'] ?? $defaults['event_date'])),
            'heading_two' => trim((string) ($page['heading_two'] ?? $defaults['heading_two'])),
            'delivery_heading' => trim((string) ($page['delivery_heading'] ?? $defaults['delivery_heading'])) ?: 'Delivery',
            'delivery_description' => trim((string) ($page['delivery_description'] ?? $defaults['delivery_description'])),
            'type' => trim((string) ($page['type'] ?? $defaults['type'])) ?: 'Post',
            'description' => trim((string) ($page['description'] ?? $defaults['description'])),
            'created_at' => trim((string) ($page['created_at'] ?? $defaults['created_at'])),
            'updated_at' => trim((string) ($page['updated_at'] ?? $defaults['updated_at'])),
        ];
    }
}
