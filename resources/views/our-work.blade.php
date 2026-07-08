@php
    $contactEmail = trim((string) ($contactEmail ?? ''));
    $hasContactEmail = filled($contactEmail);
    $contactPhones = is_array($contactPhones ?? null) ? $contactPhones : [];
    $socialLinks = is_array($socialLinks ?? null) ? $socialLinks : [];
    $whatsappUrl = trim((string) ($whatsappUrl ?? ''));
    $hasWhatsapp = filled($whatsappUrl);
    $navPages = [
        ['slug' => 'conferences', 'title' => 'Conferences'],
        ['slug' => 'brand-experiences', 'title' => 'Brand Experience'],
        ['slug' => 'exhibitions', 'title' => 'Exhibitions'],
    ];
    $caseStudyCategories = ['Conferences', 'Brand Experience', 'Exhibitions', 'Award Nights', 'Hybrid Events', 'Roadshows', 'Outdoor Builds'];
    $logoUrl = \App\Support\HomepageContent::assetUrl(
        (string) data_get($logo ?? [], 'path', data_get($logo ?? [], 'url', ''))
    );
    $hasLogo = filled($logoUrl);
    $pageContent = is_array($pageContent ?? null) ? $pageContent : [
        'eyebrow' => 'Peak Experience Case Studies',
        'title' => 'Our Work',
        'description' => 'Explore the live moments Peak Experience has shaped for conferences, exhibitions, brand experiences, and corporate events across Kenya.',
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.page-transition-head')
    <title>{{ $pageContent['title'] }} | Peak Experience</title>
    <meta name="description" content="{{ $pageContent['description'] }}">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('story-home.css') }}">
    <style>
        @font-face{font-family:"GT Walsheim";src:url("https://www.storyevents.co.uk/wp-content/themes/primary-theme/assets/fonts/gt-walsheim/GT-Walsheim-Light.woff2") format("woff2");font-weight:300;font-style:normal;font-display:swap}
        @font-face{font-family:"GT Walsheim";src:url("https://www.storyevents.co.uk/wp-content/themes/primary-theme/assets/fonts/gt-walsheim/GT-Walsheim-Regular.woff2") format("woff2");font-weight:400;font-style:normal;font-display:swap}
        @font-face{font-family:"GT Walsheim";src:url("https://www.storyevents.co.uk/wp-content/themes/primary-theme/assets/fonts/gt-walsheim/GT-Walsheim-Medium.woff2") format("woff2");font-weight:500;font-style:normal;font-display:swap}
        .work-page-main{background:#fff;font-family:"GT Walsheim",Helvetica,Arial,sans-serif}
        .work-page-hero{padding:clamp(92px,10vw,150px) 0 clamp(72px,9vw,128px)}
        .work-page-hero .wrap{display:grid;grid-template-columns:minmax(0,980px) minmax(220px,1fr);gap:clamp(44px,7vw,112px);align-items:start;width:min(1280px,calc(100% - 140px))}
        .work-page-kicker{display:block;margin-top:18px;color:#686264;font-size:clamp(24px,1.7vw,31px);font-weight:300;line-height:1.2;text-transform:uppercase}
        .work-page-hero h1{margin:0;color:#686264;font-size:clamp(52px,5.4vw,96px);font-weight:300;line-height:.92;letter-spacing:0;text-transform:uppercase}
        .work-page-hero p{max-width:980px;margin:34px 0 0;color:#686264;font-size:clamp(24px,1.85vw,34px);font-weight:300;line-height:1.32}
        .work-list-section{padding:clamp(54px,6vw,88px) 0 clamp(72px,8vw,120px)}
        .work-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
        .work-card{overflow:hidden;border-radius:8px;background:#fff;color:#202633;text-decoration:none;box-shadow:0 18px 45px rgba(32,38,51,.08)}
        .work-card-media{aspect-ratio:4/3;background:#d9d9d9}
        .work-card-media img{width:100%;height:100%;object-fit:cover}
        .work-card-placeholder{display:flex;align-items:center;justify-content:center;width:100%;height:100%;color:#8b929c;font-size:13px;font-weight:800;letter-spacing:.18em;text-transform:uppercase}
        .work-card-body{padding:24px}
        .work-card-body h2{margin:0;color:#202633;font-size:clamp(23px,2vw,32px);line-height:1.05}
        .work-card-body p{margin:14px 0 0;color:#667085;font-size:17px;line-height:1.55}
        .work-empty{border:1px solid rgba(32,38,51,.12);border-radius:8px;background:#fff;padding:48px;color:#667085;font-size:20px}
        @media(max-width:980px){.work-page-hero .wrap{grid-template-columns:1fr;width:min(1180px,calc(100% - 48px));gap:24px}.work-page-kicker{order:-1;margin-top:0}.work-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:640px){.work-grid{grid-template-columns:1fr}.work-page-hero{padding-top:58px}.work-page-hero .wrap{width:min(1180px,calc(100% - 32px))}.work-page-hero h1{font-size:48px}.work-page-hero p{font-size:24px}.work-page-kicker{font-size:22px}.work-card-body{padding:20px}}
    </style>
</head>
<body id="top">
    @include('partials.page-transition')
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
                        <li><a href="{{ route('our-services') }}">Our Services</a></li>
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
                            <li><a href="{{ route('our-services') }}">Our Services</a></li>
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
                    <div>
                        <h1>{{ $pageContent['title'] }}</h1>
                        <p>{{ $pageContent['description'] }}</p>
                    </div>
                    <span class="work-page-kicker">{{ $pageContent['eyebrow'] }}</span>
                </div>
            </section>

            <section class="strip" aria-label="Case study categories">
                <div class="wrap strip-overflow">
                    <ul class="strip-track">
                        @for ($i = 0; $i < 2; $i++)
                            @foreach ($caseStudyCategories as $category)
                                <li>{{ $category }}</li>
                            @endforeach
                        @endfor
                    </ul>
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
                        <a href="{{ route('our-work') }}">Our Work</a>
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
