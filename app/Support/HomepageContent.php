<?php

namespace App\Support;

use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Schema;

class HomepageContent
{
    /**
     * @return array{
     *     logo: array{url:string,path:string},
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
            ->whereIn('key', ['logo', 'what_we_do', 'our_process'])
            ->get()
            ->keyBy('key');

        foreach (['logo', 'what_we_do', 'our_process'] as $key) {
            if ($settings->has($key) && is_array($settings[$key]->value)) {
                $defaults[$key] = $settings[$key]->value;
            }
        }

        return $defaults;
    }

    /**
     * @param  array{
     *     logo: array{url:string,path:string},
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
}
