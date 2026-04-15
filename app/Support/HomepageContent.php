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

    /**
     * @param  array<int, array{title:string,icon:string,text:string,link_url:string,image:string}>  $services
     * @return array<int, array{
     *     title:string,
     *     icon:string,
     *     text:string,
     *     link_url:string,
     *     image:string,
     *     slug:string,
     *     article:array{
     *         eyebrow:string,
     *         headline:string,
     *         intro:string,
     *         sections:array<int, array{title:string,text:string}>,
     *         highlights:array<int, string>
     *     }
     * }>
     */
    public static function services(array $services): array
    {
        $catalog = [];
        $usedSlugs = [];

        foreach (array_values($services) as $item) {
            $title = trim((string) ($item['title'] ?? 'Service'));
            $text = trim((string) ($item['text'] ?? ''));

            $catalog[] = [
                'title' => $title,
                'icon' => trim((string) ($item['icon'] ?? '')),
                'text' => $text,
                'link_url' => trim((string) ($item['link_url'] ?? '')),
                'image' => trim((string) ($item['image'] ?? '')),
                'slug' => self::uniqueServiceSlug($title, $usedSlugs),
                'article' => self::serviceArticle($title, $text),
            ];
        }

        return $catalog;
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

    /**
     * @param  array<int, string>  $usedSlugs
     */
    private static function uniqueServiceSlug(string $title, array &$usedSlugs): string
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = 'service';
        }

        $slug = $base;
        $suffix = 2;

        while (in_array($slug, $usedSlugs, true)) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        $usedSlugs[] = $slug;

        return $slug;
    }

    /**
     * @return array{
     *     eyebrow:string,
     *     headline:string,
     *     intro:string,
     *     sections:array<int, array{title:string,text:string}>,
     *     highlights:array<int, string>
     * }
     */
    private static function serviceArticle(string $title, string $summary): array
    {
        $presets = self::serviceArticlePresets();
        $key = Str::slug($title);

        return $presets[$key] ?? self::genericServiceArticle($title, $summary);
    }

    /**
     * @return array<string, array{
     *     eyebrow:string,
     *     headline:string,
     *     intro:string,
     *     sections:array<int, array{title:string,text:string}>,
     *     highlights:array<int, string>
     * }>
     */
    private static function serviceArticlePresets(): array
    {
        return [
            'staging' => [
                'eyebrow' => 'Service Writeup',
                'headline' => 'Stages built around sightlines, speaker movement, and the visual weight the room needs.',
                'intro' => 'Peak Experience treats staging as both a technical platform and the visual center of the event. The goal is to make the platform feel proportionate to the room, dependable under pressure, and clear from the first row to the back of the audience.',
                'sections' => [
                    [
                        'title' => 'Stage planning starts with the room',
                        'text' => 'We shape the platform around venue dimensions, audience sightlines, screen positions, speaker entrances, and the level of brand presence the event calls for. That keeps the stage from feeling either undersized or unnecessarily heavy for the space.',
                    ],
                    [
                        'title' => 'Build details are coordinated early',
                        'text' => 'Decking, fascia, stairs, access routes, technical trim, and scenic finishes are resolved before build day so the platform works cleanly with lighting, audio, and media systems. The result is a stage that looks deliberate and supports the show practically.',
                    ],
                    [
                        'title' => 'Show-day readiness matters as much as the look',
                        'text' => 'A strong stage has to stay calm under live use. We account for presenter movement, quick resets, backstage flow, and cue positions so the platform continues to perform once the audience is in the room.',
                    ],
                ],
                'highlights' => [
                    'Platform sizing and room-fit planning',
                    'Stage finishes, fascia, and branded scenic treatment',
                    'Speaker access, backstage movement, and safety checks',
                    'Integration with screens, lighting, and audio positions',
                ],
            ],
            'event-production' => [
                'eyebrow' => 'Service Writeup',
                'headline' => 'Production management that keeps the concept, crew, and live show moving in one direction.',
                'intro' => 'Event production sits at the center of delivery. Peak Experience uses it to align schedules, technical systems, suppliers, and live cues so the audience experiences one smooth event instead of many separate departments working beside each other.',
                'sections' => [
                    [
                        'title' => 'Pre-production removes avoidable friction',
                        'text' => 'We map the event flow, technical requirements, supplier dependencies, installation timing, and show-critical approvals early. That creates a clear working plan before the venue becomes busy and decisions become expensive.',
                    ],
                    [
                        'title' => 'Departments work from one live plan',
                        'text' => 'Audio, lighting, media, stage management, venue operations, and client-side stakeholders all need one shared rhythm. We coordinate the crew, cueing, and communications so everyone is responding to the same show logic.',
                    ],
                    [
                        'title' => 'Operational calm is part of the product',
                        'text' => 'Strong production control is felt in the pace of the day. It reduces guesswork, shortens reaction time, and keeps technical issues from spilling into the guest experience or the presenter experience.',
                    ],
                ],
                'highlights' => [
                    'Run-of-show and milestone planning',
                    'Technical coordination across all live departments',
                    'Supplier, venue, and crew alignment',
                    'Cue management and show-day operational control',
                ],
            ],
            'media-solutions' => [
                'eyebrow' => 'Service Writeup',
                'headline' => 'Media systems designed so the message stays visible, legible, and connected across the full experience.',
                'intro' => 'Media support is more than putting content on a screen. Peak Experience plans display systems around audience clarity, content timing, room layout, and any live or hybrid outputs the event needs to carry.',
                'sections' => [
                    [
                        'title' => 'Screen strategy is shaped by audience view',
                        'text' => 'We look at screen placement, scale, brightness, and content format so visuals remain readable in the real venue conditions. That includes the relationship between the stage picture, side screens, confidence feeds, and branded visuals.',
                    ],
                    [
                        'title' => 'Content playback and live feeds must stay controlled',
                        'text' => 'Media playback, live camera feeds, presentation switching, streaming layers, and recording outputs need disciplined routing. We plan the media chain to keep transitions clean and to reduce the risk of visible technical drift during the show.',
                    ],
                    [
                        'title' => 'Hybrid reach should still feel intentional',
                        'text' => 'When an event extends beyond the room, the media system needs to support both the in-person audience and remote viewers without weakening either experience. We design the setup so the story still feels coherent on every channel.',
                    ],
                ],
                'highlights' => [
                    'LED, projection, and presentation display planning',
                    'Playback, switching, and live content coordination',
                    'Streaming, relay, and recording support',
                    'Visual systems matched to room conditions and audience flow',
                ],
            ],
            'exhibition-solutions' => [
                'eyebrow' => 'Service Writeup',
                'headline' => 'Exhibition environments built to attract attention, hold traffic, and communicate the brand clearly.',
                'intro' => 'Exhibition work has to win attention quickly and then support meaningful engagement once visitors arrive. Peak Experience designs branded spaces that balance visual impact, traffic flow, messaging, and practical build requirements.',
                'sections' => [
                    [
                        'title' => 'The stand starts with visitor movement',
                        'text' => 'We consider how attendees approach, pause, enter, and circulate through the space so the stand feels inviting instead of congested. Layout decisions are shaped by product display, staff interaction, and the conversation points the brand wants to emphasize.',
                    ],
                    [
                        'title' => 'Fabrication and branding need one language',
                        'text' => 'Structures, finishes, print surfaces, lighting accents, and media placements all have to reinforce the same identity. We bring those layers together so the stand looks resolved rather than assembled from unrelated parts.',
                    ],
                    [
                        'title' => 'The build has to work under venue pressure',
                        'text' => 'Exhibition venues often have tight installation windows and strict operating rules. We plan around those realities so setup, finishing, and handover stay efficient without compromising the quality of the final space.',
                    ],
                ],
                'highlights' => [
                    'Stand layout and visitor-flow planning',
                    'Branded fabrication and finish coordination',
                    'Display zones for products, screens, and conversations',
                    'Installation planning for tight venue timelines',
                ],
            ],
        ];
    }

    /**
     * @return array{
     *     eyebrow:string,
     *     headline:string,
     *     intro:string,
     *     sections:array<int, array{title:string,text:string}>,
     *     highlights:array<int, string>
     * }
     */
    private static function genericServiceArticle(string $title, string $summary): array
    {
        $summary = $summary !== ''
            ? $summary
            : 'This service is planned around the venue, the audience, and the delivery pressure that comes with live events.';

        return [
            'eyebrow' => 'Service Writeup',
            'headline' => $title . ' planned to support the message, the room, and the live delivery on the day.',
            'intro' => $summary,
            'sections' => [
                [
                    'title' => 'What the service covers',
                    'text' => $summary,
                ],
                [
                    'title' => 'How the work is shaped',
                    'text' => 'Peak Experience develops the service around venue realities, technical dependencies, timing, and audience expectations so it supports the full event rather than operating in isolation.',
                ],
                [
                    'title' => 'Why it matters on show day',
                    'text' => 'The real test is how the service performs once guests arrive, presenters step in, and timing becomes critical. The objective is dependable delivery that keeps the experience polished and easy to navigate.',
                ],
            ],
            'highlights' => [
                'Planning aligned to venue conditions and guest flow',
                'Technical coordination with the wider event setup',
                'Delivery support shaped for live show-day pressure',
            ],
        ];
    }
}
