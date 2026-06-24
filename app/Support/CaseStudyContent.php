<?php

namespace App\Support;

use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Schema;

class CaseStudyContent
{
    /**
     * @return array<int, array{
     *     id:string,
     *     slug:string,
     *     title:string,
     *     image:string,
     *     image_alt:string,
     *     status:string,
     *     order:int,
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

        $setting = HomepageSetting::query()->where('key', 'case_studies')->first();
        if (! is_array($setting?->value)) {
            return [];
        }

        $items = array_values(array_map(
            fn (array $caseStudy): array => self::normalize($caseStudy),
            array_filter($setting->value, 'is_array')
        ));

        usort($items, fn (array $a, array $b): int => $a['order'] <=> $b['order']);

        return $items;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function published(): array
    {
        return array_values(array_filter(
            self::load(),
            fn (array $caseStudy): bool => $caseStudy['status'] === 'Active'
        ));
    }

    /**
     * @param  array<int, array<string, mixed>>  $caseStudies
     */
    public static function save(array $caseStudies): void
    {
        HomepageSetting::query()->updateOrCreate(
            ['key' => 'case_studies'],
            ['value' => array_values(array_map(fn (array $caseStudy): array => self::normalize($caseStudy), $caseStudies))]
        );
    }

    /**
     * @return array{
     *     id:string,
     *     slug:string,
     *     title:string,
     *     image:string,
     *     image_alt:string,
     *     status:string,
     *     order:int,
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
            'title' => '',
            'image' => '',
            'image_alt' => '',
            'status' => 'Active',
            'order' => 0,
            'description' => '',
            'created_at' => '',
            'updated_at' => '',
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findById(string $id): ?array
    {
        foreach (self::load() as $caseStudy) {
            if ($caseStudy['id'] === $id) {
                return $caseStudy;
            }
        }

        return null;
    }

    private static function normalize(array $caseStudy): array
    {
        $defaults = self::defaults();
        $status = trim((string) ($caseStudy['status'] ?? $defaults['status']));
        if (! in_array($status, ['Active', 'Draft'], true)) {
            $status = 'Active';
        }

        return [
            'id' => trim((string) ($caseStudy['id'] ?? $defaults['id'])),
            'slug' => trim((string) ($caseStudy['slug'] ?? $defaults['slug'])),
            'title' => trim((string) ($caseStudy['title'] ?? $defaults['title'])),
            'image' => trim((string) ($caseStudy['image'] ?? $defaults['image'])),
            'image_alt' => trim((string) ($caseStudy['image_alt'] ?? $defaults['image_alt'])),
            'status' => $status,
            'order' => (int) ($caseStudy['order'] ?? $defaults['order']),
            'description' => trim((string) ($caseStudy['description'] ?? $defaults['description'])),
            'created_at' => trim((string) ($caseStudy['created_at'] ?? $defaults['created_at'])),
            'updated_at' => trim((string) ($caseStudy['updated_at'] ?? $defaults['updated_at'])),
        ];
    }
}
