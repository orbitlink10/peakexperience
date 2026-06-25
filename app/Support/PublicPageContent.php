<?php

namespace App\Support;

use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Schema;

class PublicPageContent
{
    public static function ourWork(): array
    {
        return self::setting('public_our_work_page', self::ourWorkDefaults());
    }

    public static function saveOurWork(array $content): void
    {
        self::saveSetting('public_our_work_page', self::normalizePage($content, self::ourWorkDefaults()));
    }

    public static function services(): array
    {
        return self::setting('public_services_page', self::servicesDefaults());
    }

    public static function saveServices(array $content): void
    {
        $defaults = self::servicesDefaults();
        $normalized = self::normalizePage($content, $defaults);
        $normalized['cards'] = array_values(array_map(
            fn (array $card): array => self::normalizeCard($card),
            array_filter($content['cards'] ?? [], 'is_array')
        ));

        if ($normalized['cards'] === []) {
            $normalized['cards'] = $defaults['cards'];
        }

        self::saveSetting('public_services_page', $normalized);
    }

    private static function setting(string $key, array $defaults): array
    {
        if (! Schema::hasTable('homepage_settings')) {
            return $defaults;
        }

        $setting = HomepageSetting::query()->where('key', $key)->first();
        if (! is_array($setting?->value)) {
            return $defaults;
        }

        $value = self::normalizePage($setting->value, $defaults);
        if (isset($defaults['cards'])) {
            $value['cards'] = array_values(array_map(
                fn (array $card): array => self::normalizeCard($card),
                array_filter($setting->value['cards'] ?? [], 'is_array')
            ));

            if ($value['cards'] === []) {
                $value['cards'] = $defaults['cards'];
            }
        }

        return $value;
    }

    private static function saveSetting(string $key, array $value): void
    {
        HomepageSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    private static function normalizePage(array $content, array $defaults): array
    {
        return array_merge($defaults, [
            'eyebrow' => trim((string) ($content['eyebrow'] ?? $defaults['eyebrow'])),
            'title' => trim((string) ($content['title'] ?? $defaults['title'])),
            'description' => trim((string) ($content['description'] ?? $defaults['description'])),
        ]);
    }

    private static function normalizeCard(array $card): array
    {
        return [
            'title' => trim((string) ($card['title'] ?? '')),
            'description' => trim((string) ($card['description'] ?? '')),
            'image' => trim((string) ($card['image'] ?? '')),
            'image_alt' => trim((string) ($card['image_alt'] ?? '')),
        ];
    }

    private static function ourWorkDefaults(): array
    {
        return [
            'eyebrow' => 'Peak Experience Case Studies',
            'title' => 'Our Work',
            'description' => 'Explore the live moments Peak Experience has shaped for conferences, exhibitions, brand experiences, and corporate events across Kenya.',
        ];
    }

    private static function servicesDefaults(): array
    {
        return [
            'eyebrow' => 'Tools to create any experience',
            'title' => 'Our Services',
            'description' => 'We provide seamless, end-to-end event planning, handling every detail from concept to completion. From venue sourcing and event design to AV production, logistics, and virtual streaming, we ensure flawless execution.',
            'cards' => [
                [
                    'title' => 'AV Production & Set Build',
                    'description' => 'We transform spaces with seamless coordination of AV, LED screens, projection, custom lighting, themed sets, and props.',
                    'image' => '',
                    'image_alt' => 'Live event AV production and stage lighting',
                ],
                [
                    'title' => 'Content Development',
                    'description' => 'We provide multi-platform content that helps you effectively communicate your message to employees, customers, and clients.',
                    'image' => '',
                    'image_alt' => 'Event content planning meeting',
                ],
                [
                    'title' => 'Event Design & Theming',
                    'description' => 'We design immersive environments that bring your brand to life and transform your vision into unforgettable experiences.',
                    'image' => '',
                    'image_alt' => 'Themed event environment with production lighting',
                ],
                [
                    'title' => 'Venue Sourcing',
                    'description' => 'We identify and coordinate venues that match your audience, format, production needs, and guest experience.',
                    'image' => '',
                    'image_alt' => 'Event venue planning session',
                ],
                [
                    'title' => 'Technical Production',
                    'description' => 'We manage sound, lighting, staging, video, streaming, and show flow with precision from setup to live delivery.',
                    'image' => '',
                    'image_alt' => 'Technical event production equipment',
                ],
                [
                    'title' => 'Logistics & Guest Experience',
                    'description' => 'We coordinate supplier movement, guest journeys, schedules, and on-site teams so every detail feels effortless.',
                    'image' => '',
                    'image_alt' => 'Outdoor event logistics and guest experience',
                ],
            ],
        ];
    }
}
