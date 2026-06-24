@php
    $contactEmail = trim((string) ($contactEmail ?? ''));
    $hasContactEmail = filled($contactEmail);
    $contactPhones = is_array($contactPhones ?? null) ? $contactPhones : [];
    $socialLinks = is_array($socialLinks ?? null) ? $socialLinks : [];
    $whatsappUrl = trim((string) ($whatsappUrl ?? ''));
    $hasWhatsapp = filled($whatsappUrl);
    $logoUrl = \App\Support\HomepageContent::assetUrl(
        (string) data_get($logo ?? [], 'path', data_get($logo ?? [], 'url', ''))
    );
    $hasLogo = filled($logoUrl);
    $pageImage = \App\Support\HomepageContent::assetUrl((string) ($page['image'] ?? ''));
    $fallbackHero = \App\Support\HomepageContent::assetUrl((string) data_get($sectionImages ?? [], 'hero.path', ''));
    $fallbackIntro = \App\Support\HomepageContent::assetUrl((string) data_get($sectionImages ?? [], 'intro.path', ''));
    $fallbackProof = \App\Support\HomepageContent::assetUrl((string) data_get($sectionImages ?? [], 'proof.path', ''));
    $heroImage = $pageImage !== '' ? $pageImage : ($fallbackHero !== '' ? $fallbackHero : $fallbackIntro);
    $galleryImages = array_values(array_filter([$pageImage, $fallbackHero, $fallbackIntro, $fallbackProof]));
    if ($heroImage !== '' && count($galleryImages) < 5) {
        $galleryImages = array_pad($galleryImages, 5, $heroImage);
    }
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
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('story-home.css') }}">
</head>
<body id="top" class="story-page">
    <header class="se-header">
        <a class="se-logo" href="{{ route('home') }}" aria-label="Peak Experience home">
            @if ($hasLogo)
                <img src="{{ $logoUrl }}" alt="Peak Experience logo">
            @else
                <span>Peak</span>
            @endif
        </a>

        <nav class="se-nav" aria-label="Primary navigation">
            <ul>
                <li><a href="{{ route('home') }}#services">What We Do</a></li>
                <li><a href="{{ route('home') }}#proof">Our Work</a></li>
                <li><a href="{{ route('home') }}#intro">About Us</a></li>
                <li><a href="{{ route('home') }}#contact">Contact</a></li>
            </ul>
        </nav>

        <a class="se-enquire-button" href="{{ route('home') }}#contact">Enquire</a>
    </header>

    <main id="main">
        <section class="se-hero block block--dark block--has-bg">
            @if ($heroImage !== '')
                <img class="se-hero__bg" src="{{ $heroImage }}" alt="{{ $page['image_alt'] !== '' ? $page['image_alt'] : $page['title'] }}">
            @endif
            <div class="se-hero__shade"></div>
            <div class="se-hero__content">
                <p class="se-breadcrumb"><a href="{{ route('home') }}">Home</a> / <span>{{ $page['type'] }}</span></p>
                <h1 class="theme-title">{{ $page['title'] }}</h1>
                @if ($page['meta_description'] !== '')
                    <p>{{ $page['meta_description'] }}</p>
                @endif
            </div>
            <a class="se-scroll" href="#content" aria-label="Scroll to page content"><span></span></a>
        </section>

        <section class="se-intro block block--light" id="content">
            <div class="se-block-padding">
                <div class="se-content-narrow">
                    <h2>{{ $page['heading_two'] }}</h2>
                    <div class="se-rich-text">
                        {!! $page['description'] !!}
                    </div>
                    <a class="se-btn se-btn-centred" href="{{ route('home') }}#contact">Enquire Now</a>
                </div>
            </div>
        </section>

        @if (count($galleryImages) > 0)
            <section class="se-gallery block">
                <div class="se-gallery-grid">
                    @foreach ($galleryImages as $index => $image)
                        <figure class="se-gallery-item se-gallery-item--{{ $index + 1 }}">
                            <img src="{{ $image }}" alt="{{ $page['image_alt'] !== '' ? $page['image_alt'] : $page['title'] }}">
                        </figure>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="se-related block block--dark">
            <div class="se-block-padding">
                <div class="se-section-head">
                    <h2>More From Peak Experience</h2>
                    <p>Creative event production shaped around venue, audience, timing, and the story your brand needs to tell.</p>
                </div>

                <div class="se-card-row">
                    <article class="se-story-card">
                        <span>01</span>
                        <h3>Planning</h3>
                        <p>We turn event requirements into a clear production plan with the right technical, creative, and operational details.</p>
                    </article>
                    <article class="se-story-card">
                        <span>02</span>
                        <h3>Production</h3>
                        <p>Lighting, audio, staging, media, and build elements are coordinated into one polished live experience.</p>
                    </article>
                    <article class="se-story-card">
                        <span>03</span>
                        <h3>Delivery</h3>
                        <p>Our team manages show-day execution so every guest touchpoint feels composed, responsive, and memorable.</p>
                    </article>
                </div>
            </div>
        </section>
    </main>

    <footer class="se-footer-brand block block--colored">
        <div class="se-block-padding">
            <div class="se-footer-inner">
                <div class="se-footer-logo">
                    @if ($hasLogo)
                        <img src="{{ $logoUrl }}" alt="Peak Experience logo">
                    @else
                        <strong>Peak Experience</strong>
                    @endif
                </div>

                <nav class="se-footer-social" aria-label="Social links">
                    @foreach ($socialLinks as $socialLink)
                        <a href="{{ $socialLink['url'] }}" target="_blank" rel="noreferrer">{{ $socialLink['label'] }}</a>
                    @endforeach
                    @if ($hasWhatsapp)
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noreferrer">WhatsApp</a>
                    @endif
                </nav>

                <div class="se-footer-contact">
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

    <footer class="se-footer-group block block--light">
        <div class="se-block-padding">
            <div class="se-footer-columns">
                <div>
                    <h3>What We Do</h3>
                    <a href="{{ route('home') }}#services">Event Production</a>
                    <a href="{{ route('home') }}#services">Audio Systems</a>
                    <a href="{{ route('home') }}#services">Media</a>
                </div>
                <div>
                    <h3>Company</h3>
                    <a href="{{ route('home') }}#intro">About Us</a>
                    <a href="{{ route('home') }}#proof">Our Work</a>
                    <a href="{{ route('home') }}#contact">Contact</a>
                </div>
                <div>
                    <h3>Enquiries</h3>
                    <a href="{{ route('home') }}#contact">Start a Brief</a>
                    @if ($hasContactEmail)
                        <a href="mailto:{{ $contactEmail }}">Email Us</a>
                    @endif
                </div>
            </div>
        </div>
    </footer>

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
</body>
</html>
