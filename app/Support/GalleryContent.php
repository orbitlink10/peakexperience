<?php

namespace App\Support;

use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Schema;

class GalleryContent
{
    /**
     * @return array<int, array{title:string,image:string}>
     */
    public static function load(): array
    {
        $defaults = self::defaults();

        if (! Schema::hasTable('homepage_settings')) {
            return $defaults;
        }

        $setting = HomepageSetting::query()->where('key', 'gallery')->first();
        if (! is_array($setting?->value)) {
            return $defaults;
        }

        $items = [];
        foreach ($setting->value as $item) {
            if (! is_array($item)) {
                continue;
            }

            $items[] = [
                'title' => trim((string) ($item['title'] ?? '')),
                'image' => trim((string) ($item['image'] ?? '')),
            ];
        }

        return $items;
    }

    /**
     * @param  array<int, array{title:string,image:string}>  $items
     */
    public static function save(array $items): void
    {
        HomepageSetting::query()->updateOrCreate(
            ['key' => 'gallery'],
            ['value' => array_values($items)]
        );
    }

    /**
     * @return array<int, array{title:string,image:string}>
     */
    public static function defaults(): array
    {
        return [
            [
                'title' => 'Reception Dinner - Africa Fintech Forum',
                'image' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1400&q=80',
            ],
            [
                'title' => 'Stage Render - TowerXchange',
                'image' => 'https://images.unsplash.com/photo-1591115765373-5207764f72e7?auto=format&fit=crop&w=1400&q=80',
            ],
            [
                'title' => 'TowerXchange Stage Installation',
                'image' => 'https://images.unsplash.com/photo-1540317580384-e5d43867caa6?auto=format&fit=crop&w=1400&q=80',
            ],
            [
                'title' => 'ITW Africa Stage Installation',
                'image' => 'https://images.unsplash.com/photo-1511795409834-432f31197d88?auto=format&fit=crop&w=1400&q=80',
            ],
            [
                'title' => 'Forum Backdrop Setup',
                'image' => 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?auto=format&fit=crop&w=1400&q=80',
            ],
            [
                'title' => 'Conference Hall Lighting',
                'image' => 'https://images.unsplash.com/photo-1505236858219-8359eb29e329?auto=format&fit=crop&w=1400&q=80',
            ],
            [
                'title' => 'Expo Booth Delivery',
                'image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1400&q=80',
            ],
            [
                'title' => 'Crew and Installation Team',
                'image' => 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=1400&q=80',
            ],
        ];
    }
}
