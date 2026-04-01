<?php

namespace App\Support;

use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class HomepageContent
{
    /**
     * @return array{
     *     logo: array{url:string,path:string},
     *     hero_video: array{url:string},
     *     what_we_do: array<int, array{title:string,icon:string,text:string,link_url:string,image:string}>,
     *     our_process: array<int, array{title:string,text:string}>
     * }
     */
    public static function load(): array
    {
        $defaults = self::defaults();

        if (! Schema::hasTable('homepage_settings')) {
            return $defaults;
        }

        $settings = HomepageSetting::query()
            ->whereIn('key', ['logo', 'hero_video', 'what_we_do', 'our_process'])
            ->get()
            ->keyBy('key');

        foreach (['logo', 'hero_video', 'what_we_do', 'our_process'] as $key) {
            if ($settings->has($key) && is_array($settings[$key]->value)) {
                $defaults[$key] = $settings[$key]->value;
            }
        }

        return $defaults;
    }

    /**
     * @param  array{
     *     logo: array{url:string,path:string},
     *     hero_video: array{url:string},
     *     what_we_do: array<int, array{title:string,icon:string,text:string,link_url:string,image:string}>,
     *     our_process: array<int, array{title:string,text:string}>
     * }  $data
     */
    public static function save(array $data): void
    {
        HomepageSetting::query()->updateOrCreate(
            ['key' => 'logo'],
            ['value' => $data['logo']]
        );

        HomepageSetting::query()->updateOrCreate(
            ['key' => 'hero_video'],
            ['value' => $data['hero_video']]
        );

        HomepageSetting::query()->updateOrCreate(
            ['key' => 'what_we_do'],
            ['value' => array_values($data['what_we_do'])]
        );

        HomepageSetting::query()->updateOrCreate(
            ['key' => 'our_process'],
            ['value' => array_values($data['our_process'])]
        );
    }

    /**
     * @return array{
     *     logo: array{url:string,path:string},
     *     hero_video: array{url:string},
     *     what_we_do: array<int, array{title:string,icon:string,text:string,link_url:string,image:string}>,
     *     our_process: array<int, array{title:string,text:string}>
     * }
     */
    public static function defaults(): array
    {
        return [
            'logo' => [
                'url' => '',
                'path' => '',
            ],
            'hero_video' => [
                'url' => '',
            ],
            'what_we_do' => [
                [
                    'title' => 'Staging',
                    'icon' => 'Staging',
                    'text' => 'We design and construct functional, safe, and visually strong stages for corporate and entertainment events.',
                    'link_url' => '',
                    'image' => '',
                ],
                [
                    'title' => 'Event Production',
                    'icon' => 'Event Production',
                    'text' => 'Our event production team oversees every technical detail from sound and lights to show flow and execution.',
                    'link_url' => '',
                    'image' => '',
                ],
                [
                    'title' => 'Media Solutions',
                    'icon' => 'Media',
                    'text' => 'We help your message travel beyond the room through LED displays, streaming, and integrated content systems.',
                    'link_url' => '',
                    'image' => '',
                ],
                [
                    'title' => 'Exhibition Solutions',
                    'icon' => 'Exhibition',
                    'text' => 'We design and build custom exhibition stands and branded spaces that attract and engage attendees.',
                    'link_url' => '',
                    'image' => '',
                ],
            ],
            'our_process' => [
                [
                    'title' => 'Concept',
                    'text' => 'We collaborate closely with you to understand your goals, audience, and event vision.',
                ],
                [
                    'title' => 'Setup',
                    'text' => 'Our experienced team handles installation and system integration with precision and safety.',
                ],
                [
                    'title' => 'Execution',
                    'text' => 'On event day, our production crew manages all live operations for a smooth experience.',
                ],
                [
                    'title' => 'Post-Event Evaluation',
                    'text' => 'After the event, we conduct a comprehensive review and share actionable improvements.',
                ],
            ],
        ];
    }

    public static function assetUrl(?string $value): string
    {
        $path = self::storedPath($value);
        if ($path !== '') {
            return route('homepage.asset', ['path' => $path]);
        }

        return trim((string) $value);
    }

    public static function storedPath(?string $value): string
    {
        $value = trim((string) $value);
        if ($value === '' || Str::startsWith($value, ['http://', 'https://', 'data:'])) {
            return '';
        }

        if (preg_match('#/storage/(.+)$#', $value, $matches) === 1) {
            return ltrim((string) ($matches[1] ?? ''), '/');
        }

        if (Str::startsWith($value, 'storage/')) {
            return ltrim(Str::after($value, 'storage/'), '/');
        }

        return ltrim($value, '/');
    }

    /**
     * @return array{type:string,url:string}
     */
    public static function videoSource(?string $value): array
    {
        $value = trim((string) $value);
        if ($value === '') {
            return ['type' => '', 'url' => ''];
        }

        $youtubeId = self::youtubeVideoId($value);
        if ($youtubeId !== '') {
            return [
                'type' => 'youtube',
                'url' => 'https://www.youtube.com/embed/' . $youtubeId
                    . '?autoplay=1&mute=1&controls=0&loop=1&playlist=' . $youtubeId
                    . '&playsinline=1&rel=0&modestbranding=1&iv_load_policy=3',
            ];
        }

        $vimeoId = self::vimeoVideoId($value);
        if ($vimeoId !== '') {
            return [
                'type' => 'vimeo',
                'url' => 'https://player.vimeo.com/video/' . $vimeoId
                    . '?autoplay=1&muted=1&loop=1&background=1',
            ];
        }

        $assetUrl = self::assetUrl($value);
        $path = (string) parse_url($assetUrl, PHP_URL_PATH);
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (in_array($extension, ['mp4', 'webm', 'ogg', 'ogv', 'm4v', 'mov'], true)) {
            return ['type' => 'file', 'url' => $assetUrl];
        }

        return ['type' => '', 'url' => ''];
    }

    private static function youtubeVideoId(string $value): string
    {
        if (preg_match(
            '#(?:youtube\.com/(?:watch\?.*v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})#i',
            $value,
            $matches
        ) === 1) {
            return (string) ($matches[1] ?? '');
        }

        return '';
    }

    private static function vimeoVideoId(string $value): string
    {
        if (preg_match('#vimeo\.com/(?:video/)?([0-9]+)#i', $value, $matches) === 1) {
            return (string) ($matches[1] ?? '');
        }

        return '';
    }
}
