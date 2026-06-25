@php
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
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Our Work | Peak Experience</title>
    <meta name="description" content="Explore Peak Experience case studies across conferences, exhibitions, brand experiences, and live event production in Kenya.">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('story-home.css') }}">
    <style>
        .work-page-main{background:#f7f3ec}
        .work-page-hero{padding:clamp(70px,8vw,120px) 0 48px}
        .work-page-kicker{display:block;margin-bottom:16px;color:#0f766e;font-size:14px;font-weight:800;letter-spacing:.22em;text-transform:uppercase}
        .work-page-hero h1{max-width:860px;margin:0;color:#202633;font-size:clamp(54px,8vw,118px);line-height:.92;letter-spacing:0;text-transform:uppercase}
        .work-page-hero p{max-width:720px;margin:28px 0 0;color:#667085;font-size:clamp(20px,2vw,30px);line-height:1.35}
        .work-list-section{padding:0 0 clamp(72px,8vw,120px)}
        .work-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
        .work-card{overflow:hidden;border-radius:8px;background:#fff;color:#202633;text-decoration:none;box-shadow:0 18px 45px rgba(32,38,51,.08)}
        .work-card-media{aspect-ratio:4/3;background:#d9d9d9}
        .work-card-media img{width:100%;height:100%;object-fit:cover}
        .work-card-placeholder{display:flex;align-items:center;justify-content:center;width:100%;height:100%;color:#8b929c;font-size:13px;font-weight:800;letter-spacing:.18em;text-transform:uppercase}
        .work-card-body{padding:24px}
        .work-card-body h2{margin:0;color:#202633;font-size:clamp(23px,2vw,32px);line-height:1.05}
        .work-card-body p{margin:14px 0 0;color:#667085;font-size:17px;line-height:1.55}
        .work-empty{border:1px solid rgba(32,38,51,.12);border-radius:8px;background:#fff;padding:48px;color:#667085;font-size:20px}
        @media(max-width:980px){.work-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:640px){.work-grid{grid-template-columns:1fr}.work-page-hero{padding-top:48px}.work-card-body{padding:20px}}
    </style>
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
                        <li><a href="{{ route('our-work') }}">Our Work</a></li>
                        <li><a href="{{ route('home') }}#services">Our Services</a></li>
                        <li><a href="{{ route('home') }}#process">Our Stories</a></li>
                        <li><a href="{{ route('home') }}#intro">About Us</a></li>
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
                            <li><a href="{{ route('our-work') }}">Our Work</a></li>
                            <li><a href="{{ route('home') }}#services">Our Services</a></li>
                            <li><a href="{{ route('home') }}#process">Our Stories</a></li>
                            <li><a href="{{ route('home') }}#intro">About Us</a></li>
                            <li><a href="{{ route('home') }}#contact">Contact Us</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <main class="work-page-main">
            <section class="work-page-hero">
                <div class="wrap">
                    <span class="work-page-kicker">Peak Experience Case Studies</span>
                    <h1>Our Work</h1>
                    <p>Explore the live moments Peak Experience has shaped for conferences, exhibitions, brand experiences, and corporate events across Kenya.</p>
                </div>
            </section>

            <section class="work-list-section">
                <div class="wrap">
                    @if (count($posts) > 0)
                        <div class="work-grid">
                            @foreach ($posts as $post)
                                @php
                                    $imageUrl = \App\Support\HomepageContent::assetUrl((string) ($post['image'] ?? ''));
                                @endphp
                                <a class="work-card" href="{{ route('pages.show', ['page' => $post['slug']]) }}">
                                    <div class="work-card-media">
                                        @if ($imageUrl !== '')
                                            <img src="{{ $imageUrl }}" alt="{{ $post['image_alt'] !== '' ? $post['image_alt'] : $post['title'] }}">
                                        @else
                                            <div class="work-card-placeholder">Case Study</div>
                                        @endif
                                    </div>
                                    <div class="work-card-body">
                                        <h2>{{ $post['title'] }}</h2>
                                        @if ($post['meta_description'] !== '')
                                            <p>{{ \Illuminate\Support\Str::limit($post['meta_description'], 150) }}</p>
                                        @elseif ($post['description'] !== '')
                                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($post['description']), 150) }}</p>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="work-empty">Case studies will be published here once they are added in the dashboard.</div>
                    @endif
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
                        <a href="{{ route('our-work') }}">Our Work</a>
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
