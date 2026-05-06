@php
    $contactEmail = trim((string) ($contactEmail ?? ''));
    $hasContactEmail = filled($contactEmail);
    $contactPhones = is_array($contactPhones ?? null) ? $contactPhones : [];
    $logoUrl = \App\Support\HomepageContent::assetUrl(
        (string) data_get($logo ?? [], 'path', data_get($logo ?? [], 'url', ''))
    );
    $hasLogo = filled($logoUrl);
    $pageImage = \App\Support\HomepageContent::assetUrl((string) ($page['image'] ?? ''));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page['meta_title'] !== '' ? $page['meta_title'] : $page['title'] }}</title>
    @if ($page['meta_description'] !== '')
        <meta name="description" content="{{ $page['meta_description'] }}">
    @endif
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
                        <img class="brand-logo" src="{{ $logoUrl }}" alt="Peak Experience logo">
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
            </div>
        </header>

        <main>
            <section class="section">
                <div class="wrap service-article-shell">
                    <div class="service-article-copy">
                        <a class="back-link" href="{{ route('home') }}">Back Home</a>
                        <span class="section-prefix">{{ $page['type'] }}</span>
                        <h1>{{ $page['title'] }}</h1>
                        <p>{{ $page['meta_description'] }}</p>
                    </div>

                    @if ($pageImage !== '')
                        <figure class="service-article-media">
                            <img src="{{ $pageImage }}" alt="{{ $page['image_alt'] !== '' ? $page['image_alt'] : $page['title'] }}">
                        </figure>
                    @endif
                </div>
            </section>

            <section class="section">
                <div class="wrap section-layout">
                    <div class="section-rail">
                        <strong>{{ strtoupper(substr($page['type'], 0, 1)) }}</strong>
                        <span>{{ $page['type'] }}</span>
                    </div>

                    <div class="page-content-shell">
                        <div class="section-copy process-copy">
                            <div>
                                <span class="section-prefix">Published content</span>
                                <h2>{{ $page['heading_two'] }}</h2>
                            </div>
                        </div>

                        <div class="page-content-body">
                            {!! $page['description'] !!}
                        </div>
                    </div>
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
                        <a href="{{ route('home') }}#intro">About</a>
                        <a href="{{ route('home') }}#services">Services</a>
                        <a href="{{ route('home') }}#proof">Our Work</a>
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
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
