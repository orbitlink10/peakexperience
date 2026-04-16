@extends('layouts.admin')

@section('title', 'Homepage Editor | Peak Experience')
@section('badge', 'Homepage')

@section('content')
    @php
        $logoValue = old('logo', $logo);
        $currentLogoUrl = \App\Support\HomepageContent::assetUrl(
            (string) data_get($logoValue ?? [], 'path', data_get($logoValue ?? [], 'url', ''))
        );
        $sectionImageValues = old('section_images', $sectionImages ?? []);
        $sectionImageFields = [
            'hero' => [
                'title' => 'Hero Image',
                'description' => 'Used as the hero fallback when no video is set.',
            ],
            'intro' => [
                'title' => 'Peak Experience Approach',
                'description' => 'This is the image beside the "The Peak Experience approach" copy.',
            ],
            'services' => [
                'title' => 'Our Services',
                'description' => 'This image appears in the homepage services showcase.',
            ],
            'proof' => [
                'title' => 'Our Work',
                'description' => 'This image appears in the homepage "Our Work" section.',
            ],
        ];
        $heroVideoValue = old('hero_video', $heroVideo ?? ['url' => '']);
        $whatWeDoValues = old('what_we_do', $whatWeDo);
        $ourProcessValues = old('our_process', $ourProcess);
        $heroVideoUrl = trim((string) data_get($heroVideoValue, 'url', ''));
    @endphp

    <div class="space-y-6">
        @if (session('status'))
            <div class="admin-alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="admin-alert-error space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="admin-panel">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-600">Content Management</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl">Homepage Settings</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                Manage the homepage logo, section images, hero video, What We Do cards, and Our Process steps.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.homepage.update') }}" enctype="multipart/form-data" id="homepage-form" class="space-y-6">
            @csrf

            <section class="admin-panel space-y-5">
                <div>
                    <h2 class="text-xl font-semibold tracking-tight text-slate-950">Logo</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Upload the brand logo used for the homepage header and hero. Prefer a transparent PNG or SVG-like artwork exported as PNG.
                    </p>
                </div>

                <div class="grid items-start gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(260px,320px)]">
                    <div>
                        <label for="logo_file" class="admin-label">Upload Logo</label>
                        <input id="logo_file" type="file" name="logo_file" accept="image/*" class="admin-file-input">

                        <label class="mt-4 inline-flex items-center gap-3 text-sm font-medium text-slate-700">
                            <input type="checkbox" name="logo_remove" value="1" @checked(old('logo_remove')) class="admin-checkbox">
                            <span>Remove current logo</span>
                        </label>
                    </div>

                    <div class="flex min-h-44 min-w-0 items-center justify-center overflow-hidden rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4">
                        @if ($currentLogoUrl !== '')
                            <div class="grid w-full min-w-0 gap-3 text-center">
                                <div class="flex min-h-28 items-center justify-center overflow-hidden rounded-xl border border-slate-200 bg-white p-3">
                                    <img src="{{ $currentLogoUrl }}" alt="Current homepage logo" class="max-h-24 max-w-full object-contain">
                                </div>
                                <p class="break-all text-xs leading-5 text-slate-500">Current: {{ $currentLogoUrl }}</p>
                            </div>
                        @else
                            <p class="max-w-xs break-words text-center text-sm leading-6 text-slate-500">
                                No homepage logo uploaded yet. The site will continue using the text fallback until a logo is added.
                            </p>
                        @endif
                    </div>
                </div>
            </section>

            <section class="admin-panel space-y-5">
                <div>
                    <h2 class="text-xl font-semibold tracking-tight text-slate-950">Section Images</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Upload the main images used on the homepage sections directly from this screen.
                    </p>
                </div>

                <div class="grid gap-5 xl:grid-cols-2">
                    @foreach ($sectionImageFields as $key => $field)
                        @php
                            $currentSectionImage = \App\Support\HomepageContent::assetUrl(
                                (string) data_get($sectionImageValues, $key . '.path', '')
                            );
                        @endphp
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <div class="grid items-start gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(240px,280px)]">
                                <div>
                                    <label class="admin-label">{{ $field['title'] }}</label>
                                    <input type="hidden" name="section_images[{{ $key }}][path]" value="{{ data_get($sectionImageValues, $key . '.path', '') }}">
                                    <input type="file" name="section_images[{{ $key }}][file]" accept="image/*" class="admin-file-input">
                                    <p class="admin-help">{{ $field['description'] }}</p>

                                    <label class="mt-4 inline-flex items-center gap-3 text-sm font-medium text-slate-700">
                                        <input
                                            type="checkbox"
                                            name="section_images[{{ $key }}][remove]"
                                            value="1"
                                            @checked(old('section_images.' . $key . '.remove'))
                                            class="admin-checkbox"
                                        >
                                        <span>Remove current image</span>
                                    </label>
                                </div>

                                <div class="flex min-h-40 min-w-0 items-center justify-center overflow-hidden rounded-2xl border border-dashed border-slate-300 bg-white p-4">
                                    @if ($currentSectionImage !== '')
                                        <div class="grid w-full min-w-0 gap-3 text-center">
                                            <div class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                                <img src="{{ $currentSectionImage }}" alt="{{ $field['title'] }}" class="h-40 w-full object-cover">
                                            </div>
                                            <p class="break-all text-xs leading-5 text-slate-500">Current: {{ $currentSectionImage }}</p>
                                        </div>
                                    @else
                                        <p class="max-w-xs break-words text-center text-sm leading-6 text-slate-500">
                                            No image uploaded yet. The homepage will use its built-in fallback until one is added here.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="admin-panel space-y-5">
                <div>
                    <h2 class="text-xl font-semibold tracking-tight text-slate-950">Hero Video</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Paste a YouTube, Vimeo, or direct video URL to replace the hero image with a video. If this is empty, the first What We Do link will still be used when it points to a video.
                    </p>
                </div>

                <div>
                    <label for="hero_video_url" class="admin-label">Video URL</label>
                    <input
                        id="hero_video_url"
                        type="url"
                        name="hero_video[url]"
                        value="{{ $heroVideoUrl }}"
                        placeholder="https://..."
                        class="admin-input"
                    >
                </div>
            </section>

            <section class="admin-panel space-y-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold tracking-tight text-slate-950">What We Do</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            These appear as cards on the homepage. Each card links to an internal service writeup page. Add, remove, and reorder as needed.
                        </p>
                    </div>

                    <button type="button" class="admin-btn-secondary w-full sm:w-auto" id="add-what-item">Add Item</button>
                </div>

                <div class="space-y-4" id="what-we-do-list" data-list="what_we_do">
                    @foreach ($whatWeDoValues as $index => $item)
                        <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5" data-item>
                            <div class="grid gap-4 xl:grid-cols-3">
                                <div>
                                    <label class="admin-label">Title</label>
                                    <input type="text" value="{{ $item['title'] ?? '' }}" data-name="title" required class="admin-input">
                                </div>

                                <div>
                                    <label class="admin-label">Icon</label>
                                    <select data-name="icon" required class="admin-select">
                                        @foreach ($iconOptions as $option)
                                            <option value="{{ $option }}" @selected(($item['icon'] ?? '') === $option)>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="admin-label">Text</label>
                                    <textarea data-name="text" required class="admin-textarea">{{ $item['text'] ?? '' }}</textarea>
                                </div>
                            </div>

                            <div class="mt-4 grid gap-4 xl:grid-cols-2">
                                <div>
                                    <label class="admin-label">Media URL for hero fallback (optional)</label>
                                    <input
                                        type="url"
                                        value="{{ $item['link_url'] ?? '' }}"
                                        placeholder="https://..."
                                        data-name="link_url"
                                        class="admin-input"
                                    >
                                </div>

                                <div>
                                    <label class="admin-label">Image (optional)</label>
                                    <input type="file" accept="image/*" data-name="image_file" class="admin-file-input">
                                    @if (!empty($item['image']))
                                        <p class="admin-help">Current: {{ $item['image'] }}</p>
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" value="{{ $item['image'] ?? '' }}" data-name="image">

                            <div class="mt-4 flex justify-end">
                                <button type="button" class="admin-btn-secondary" data-remove>Remove</button>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="admin-panel space-y-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold tracking-tight text-slate-950">Our Process</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Add or edit the four high-level steps shown on the homepage.
                        </p>
                    </div>

                    <button type="button" class="admin-btn-secondary w-full sm:w-auto" id="add-process-step">Add Step</button>
                </div>

                <div class="space-y-4" id="our-process-list" data-list="our_process">
                    @foreach ($ourProcessValues as $index => $step)
                        <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5" data-item>
                            <div class="grid gap-4 xl:grid-cols-2">
                                <div>
                                    <label class="admin-label">Step Title</label>
                                    <input type="text" value="{{ $step['title'] ?? '' }}" data-name="title" required class="admin-input">
                                </div>

                                <div>
                                    <label class="admin-label">Step Text</label>
                                    <textarea data-name="text" required class="admin-textarea">{{ $step['text'] ?? '' }}</textarea>
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end">
                                <button type="button" class="admin-btn-secondary" data-remove>Remove</button>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <div class="admin-panel flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm leading-6 text-slate-500">
                    Save to publish the latest homepage content and image selections.
                </p>
                <button type="submit" class="admin-btn-primary w-full sm:w-auto">Save Homepage</button>
            </div>
        </form>
    </div>

    <template id="what-item-template">
        <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5" data-item>
            <div class="grid gap-4 xl:grid-cols-3">
                <div>
                    <label class="admin-label">Title</label>
                    <input type="text" data-name="title" required class="admin-input">
                </div>

                <div>
                    <label class="admin-label">Icon</label>
                    <select data-name="icon" required class="admin-select">
                        @foreach ($iconOptions as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="admin-label">Text</label>
                    <textarea data-name="text" required class="admin-textarea"></textarea>
                </div>
            </div>

            <div class="mt-4 grid gap-4 xl:grid-cols-2">
                <div>
                    <label class="admin-label">Media URL for hero fallback (optional)</label>
                    <input type="url" placeholder="https://..." data-name="link_url" class="admin-input">
                </div>

                <div>
                    <label class="admin-label">Image (optional)</label>
                    <input type="file" accept="image/*" data-name="image_file" class="admin-file-input">
                </div>
            </div>

            <input type="hidden" value="" data-name="image">

            <div class="mt-4 flex justify-end">
                <button type="button" class="admin-btn-secondary" data-remove>Remove</button>
            </div>
        </article>
    </template>

    <template id="process-step-template">
        <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5" data-item>
            <div class="grid gap-4 xl:grid-cols-2">
                <div>
                    <label class="admin-label">Step Title</label>
                    <input type="text" data-name="title" required class="admin-input">
                </div>

                <div>
                    <label class="admin-label">Step Text</label>
                    <textarea data-name="text" required class="admin-textarea"></textarea>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="button" class="admin-btn-secondary" data-remove>Remove</button>
            </div>
        </article>
    </template>
@endsection

@push('scripts')
    <script>
        (function () {
            const whatList = document.getElementById('what-we-do-list');
            const processList = document.getElementById('our-process-list');
            const whatTemplate = document.getElementById('what-item-template');
            const processTemplate = document.getElementById('process-step-template');

            function reindex(list) {
                const prefix = list.dataset.list;
                list.querySelectorAll('[data-item]').forEach((item, itemIndex) => {
                    item.querySelectorAll('[data-name]').forEach((field) => {
                        const key = field.dataset.name;
                        field.name = `${prefix}[${itemIndex}][${key}]`;
                    });
                });
            }

            function attachRemoveHandlers(scope) {
                scope.querySelectorAll('[data-remove]').forEach((button) => {
                    if (button.dataset.bound === '1') {
                        return;
                    }

                    button.dataset.bound = '1';
                    button.addEventListener('click', () => {
                        const list = button.closest('[data-list]');
                        const item = button.closest('[data-item]');

                        if (!list || !item) {
                            return;
                        }

                        if (list.querySelectorAll('[data-item]').length <= 1) {
                            return;
                        }

                        item.remove();
                        reindex(list);
                    });
                });
            }

            document.getElementById('add-what-item').addEventListener('click', () => {
                const fragment = whatTemplate.content.cloneNode(true);
                whatList.appendChild(fragment);
                attachRemoveHandlers(whatList);
                reindex(whatList);
            });

            document.getElementById('add-process-step').addEventListener('click', () => {
                const fragment = processTemplate.content.cloneNode(true);
                processList.appendChild(fragment);
                attachRemoveHandlers(processList);
                reindex(processList);
            });

            attachRemoveHandlers(document);
            reindex(whatList);
            reindex(processList);
        })();
    </script>
@endpush
