@php
    $fallbackImages = [
        'staging' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1400&q=80',
        'event-production' => 'https://images.unsplash.com/photo-1505236858219-8359eb29e329?auto=format&fit=crop&w=1400&q=80',
        'media-solutions' => 'https://images.unsplash.com/photo-1591115765373-5207764f72e7?auto=format&fit=crop&w=1400&q=80',
        'exhibition-solutions' => 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?auto=format&fit=crop&w=1400&q=80',
    ];

    $contactEmail = trim((string) ($contactEmail ?? ''));
    $hasContactEmail = filled($contactEmail);
    $contactPhones = is_array($contactPhones ?? null) ? $contactPhones : [];
    $socialLinks = is_array($socialLinks ?? null) ? $socialLinks : [];
    $whatsappUrl = trim((string) ($whatsappUrl ?? ''));
    $hasWhatsapp = filled($whatsappUrl);
    $navPages = array_values(array_filter(
        is_array($navPages ?? null) ? $navPages : [],
        fn ($item) => is_array($item) && filled($item['title'] ?? '') && filled($item['slug'] ?? '')
    ));
    $logoUrl = \App\Support\HomepageContent::assetUrl(
        (string) data_get($logo ?? [], 'path', data_get($logo ?? [], 'url', ''))
    );
    $hasLogo = filled($logoUrl);
    $serviceSlug = \Illuminate\Support\Str::slug((string) ($service['title'] ?? ''));
    $serviceImage = ! empty($service['image'])
        ? \App\Support\HomepageContent::assetUrl((string) $service['image'])
        : ($fallbackImages[$serviceSlug] ?? reset($fallbackImages));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $service['title'] }} | Peak Experience</title>
    <meta name="description" content="{{ $service['text'] }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('story-home.css') }}">
</head>
<body id="top">
    <div class="page-shell">
        <header class="site-header">
            <div class="wrap header-row">
                <a class="brand" href="{{ route('home') }}" aria-label="Peak Experience home">
                    @if ($hasLogo)
                        <img class="brand-logo" src="{{ $logoUrl }}" alt="Peak Experience">
                    @else
                        <span class="brand-copy">
                            <strong>Peak Experience</strong>
                            <span class="brand-dots" aria-hidden="true">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                        </span>
                    @endif
                </a>

                <nav class="site-nav" aria-label="Primary">
                    <ul>
                        <li class="nav-item--dropdown">
                            <a class="nav-link--caret" href="{{ route('home') }}#services">What We Do</a>
                            @if (count($navPages) > 0)
                                <ul class="nav-dropdown" aria-label="What We Do pages">
                                    @foreach ($navPages as $navPage)
                                        <li><a href="{{ route('pages.show', ['page' => $navPage['slug']]) }}">{{ $navPage['title'] }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                        <li><a href="{{ route('home') }}#services">Our Services</a></li>
                        <li><a href="{{ route('home') }}#proof">Our Work</a></li>
                        <li><a href="{{ route('home') }}#process">Our Stories</a></li>
                        <li><a href="{{ route('home') }}#contact">Contact Us</a></li>
                    </ul>
                </nav>

                <div class="header-utility">
                    <a class="button button-nav-cta" href="{{ route('home') }}#contact">Contact Us</a>
                </div>

                <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="mobile-nav" data-nav-toggle>
                    <span class="nav-toggle-box" aria-hidden="true">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </div>

            <div class="wrap">
                <div class="nav-panel" id="mobile-nav" data-nav-panel>
                    <nav aria-label="Mobile">
                        <ul>
                            <li>
                                <a href="{{ route('home') }}#services">What We Do</a>
                                @if (count($navPages) > 0)
                                    <ul class="nav-mobile-children" aria-label="What We Do pages">
                                        @foreach ($navPages as $navPage)
                                            <li><a href="{{ route('pages.show', ['page' => $navPage['slug']]) }}">{{ $navPage['title'] }}</a></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                            <li><a href="{{ route('home') }}#services">Our Services</a></li>
                            <li><a href="{{ route('home') }}#proof">Our Work</a></li>
                            <li><a href="{{ route('home') }}#process">Our Stories</a></li>
                            <li><a href="{{ route('home') }}#contact">Contact Us</a></li>
                        </ul>
                    </nav>

                    <div class="nav-meta">
                        @if ($hasContactEmail)
                            <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                        @endif
                        @foreach ($contactPhones as $phone)
                            <a href="tel:{{ $phone['dial'] }}">{{ $phone['display'] }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </header>

        <main>
            <section class="section service-article-hero">
                <div class="wrap service-article-shell reveal">
                    <div class="service-article-copy">
                        <a class="back-link" href="{{ route('home') }}#services-detail">Back to Services</a>
                        <span class="section-prefix">{{ $service['article']['eyebrow'] }}</span>
                        <h1>{{ $service['title'] }}</h1>
                        <p class="service-article-headline">{{ $service['article']['headline'] }}</p>
                        <p class="service-article-summary">{{ $service['text'] }}</p>

                        <div class="service-article-actions">
                            @if ($hasContactEmail)
                                <a class="button button-primary" href="mailto:{{ $contactEmail }}">Email Us</a>
                            @endif
                            @if (! empty($contactPhones))
                                <a class="button button-secondary" href="tel:{{ $contactPhones[0]['dial'] }}">Call Us</a>
                            @endif
                            @if ($hasWhatsapp)
                                <a class="button button-whatsapp" href="{{ $whatsappUrl }}" target="_blank" rel="noreferrer">WhatsApp Us</a>
                            @endif
                        </div>
                    </div>

                    <figure class="service-article-media">
                        <img src="{{ $serviceImage }}" alt="{{ $service['title'] }}">
                    </figure>
                </div>
            </section>

            <section class="section service-article-content">
                <div class="wrap service-article-grid reveal reveal-delay-1">
                    <article class="article-body">
                        <p class="article-intro">{{ $service['article']['intro'] }}</p>

                        @foreach ($service['article']['sections'] as $section)
                            <section class="article-section">
                                <h2>{{ $section['title'] }}</h2>
                                <p>{{ $section['text'] }}</p>
                            </section>
                        @endforeach
                    </article>

                    <aside class="article-sidebar">
                        <div class="article-sidebar-block">
                            <h2>What This Includes</h2>

                            <ul class="article-highlight-list">
                                @foreach ($service['article']['highlights'] as $highlight)
                                    <li>{{ $highlight }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="article-sidebar-block article-contact-block">
                            <h2>Talk To Peak Experience</h2>
                            <p>Share the venue, audience size, and event date and we will map the right production path.</p>

                            <div class="article-contact-list">
                                @if ($hasContactEmail)
                                    <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                                @endif
                                @foreach ($contactPhones as $phone)
                                    <a href="tel:{{ $phone['dial'] }}">{{ $phone['display'] }}</a>
                                @endforeach
                            </div>
                        </div>
                    </aside>
                </div>
            </section>
        </main>

        <footer class="footer">
            <div class="wrap">
                <div class="footer-panel">
                    <div class="footer-copy">
                        <span class="footer-kicker">Peak Experience</span>
                        <strong>Creative production for live experiences that need polish.</strong>
                        <p>Event staging, media systems, exhibition builds, and disciplined show-day delivery for brands and organisers across Kenya.</p>
                    </div>

                    <nav class="footer-links" aria-label="Footer navigation">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('home') }}#services">Services</a>
                        <a href="{{ route('home') }}#process">Process</a>
                        <a href="{{ route('home') }}#contact">Contact Us</a>
                    </nav>

                    <div class="footer-links">
                        @if ($hasContactEmail)
                            <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                        @endif
                        @foreach ($contactPhones as $phone)
                            <a href="tel:{{ $phone['dial'] }}">{{ $phone['display'] }}</a>
                        @endforeach
                        @foreach ($socialLinks as $socialLink)
                            <a href="{{ $socialLink['url'] }}" target="_blank" rel="noreferrer">{{ $socialLink['label'] }}</a>
                        @endforeach
                        @if ($hasWhatsapp)
                            <a href="{{ $whatsappUrl }}" target="_blank" rel="noreferrer">WhatsApp</a>
                        @endif
                        <a href="#top">Back To Top</a>
                        <span>Live event delivery across Kenya</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @if ($hasWhatsapp)
        <div class="whatsapp-widget" aria-label="WhatsApp chat widget">
            <span class="whatsapp-widget-label">WhatsApp Peak Experience</span>
            <a class="whatsapp-widget-button" href="{{ $whatsappUrl }}" target="_blank" rel="noreferrer" aria-label="Chat with Peak Experience on WhatsApp">
                <svg class="whatsapp-widget-icon" viewBox="0 0 32 32" aria-hidden="true" focusable="false">
                    <path fill="currentColor" d="M19.11 17.36c-.26-.13-1.53-.76-1.77-.85-.24-.09-.41-.13-.58.13-.17.26-.67.85-.82 1.02-.15.17-.3.2-.56.07-.26-.13-1.09-.4-2.08-1.27-.77-.68-1.29-1.51-1.44-1.77-.15-.26-.02-.4.11-.53.12-.12.26-.3.39-.45.13-.15.17-.26.26-.43.09-.17.04-.33-.02-.46-.07-.13-.58-1.4-.8-1.92-.21-.5-.42-.43-.58-.44h-.5c-.17 0-.45.07-.69.33-.24.26-.91.89-.91 2.16 0 1.27.93 2.5 1.06 2.67.13.17 1.83 2.79 4.43 3.92.62.27 1.11.43 1.49.55.63.2 1.21.17 1.66.1.51-.08 1.53-.63 1.75-1.24.22-.61.22-1.13.15-1.24-.07-.11-.24-.17-.5-.3Z"/>
                    <path fill="currentColor" d="M27.29 15.22c0 6.23-5.06 11.29-11.29 11.29-1.98 0-3.92-.52-5.63-1.5L4.71 26.5l1.53-5.49a11.2 11.2 0 0 1-1.53-5.79C4.71 9 9.77 3.94 16 3.94s11.29 5.06 11.29 11.28Zm-11.29-9.39c-5.18 0-9.39 4.21-9.39 9.39 0 1.82.52 3.59 1.5 5.11l.21.32-.91 3.25 3.33-.88.31.18a9.36 9.36 0 0 0 4.95 1.41c5.18 0 9.39-4.21 9.39-9.39 0-5.18-4.21-9.39-9.39-9.39Z"/>
                </svg>
            </a>
        </div>
    @endif

    <script src="{{ asset('story-home.js') }}" defer></script>
</body>
</html>
