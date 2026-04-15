@php
    $fallbackImages = [
        'staging' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1400&q=80',
        'event-production' => 'https://images.unsplash.com/photo-1540317580384-e5d43867caa6?auto=format&fit=crop&w=1400&q=80',
        'media-solutions' => 'https://images.unsplash.com/photo-1591115765373-5207764f72e7?auto=format&fit=crop&w=1400&q=80',
        'exhibition-solutions' => 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?auto=format&fit=crop&w=1400&q=80',
    ];

    $contactEmail = trim((string) ($contactEmail ?? ''));
    $hasContactEmail = filled($contactEmail);
    $contactPhones = is_array($contactPhones ?? null) ? $contactPhones : [];
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
                        <a href="#top">Back To Top</a>
                        <span>Live event delivery across Kenya</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="{{ asset('story-home.js') }}" defer></script>
</body>
</html>
