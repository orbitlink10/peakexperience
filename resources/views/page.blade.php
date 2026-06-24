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
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('story-home.css') }}">
    <style>
        @font-face{font-family:"GT Walsheim";src:url("https://www.storyevents.co.uk/wp-content/themes/primary-theme/assets/fonts/gt-walsheim/GT-Walsheim-Light.woff2") format("woff2");font-weight:300;font-style:normal;font-display:swap}
        @font-face{font-family:"GT Walsheim";src:url("https://www.storyevents.co.uk/wp-content/themes/primary-theme/assets/fonts/gt-walsheim/GT-Walsheim-Regular.woff2") format("woff2");font-weight:400;font-style:normal;font-display:swap}
        @font-face{font-family:"GT Walsheim";src:url("https://www.storyevents.co.uk/wp-content/themes/primary-theme/assets/fonts/gt-walsheim/GT-Walsheim-Medium.woff2") format("woff2");font-weight:500;font-style:normal;font-display:swap}
        html{scroll-behavior:smooth}
        body.story-page{--page-image-gutter:clamp(18px,2.1vw,38px);margin:0;background:#fff;color:#333;font-family:"GT Walsheim",Helvetica,Arial,sans-serif;font-size:18px;line-height:26px;-webkit-font-smoothing:antialiased;text-rendering:optimizeLegibility}
        .story-page *{box-sizing:border-box}
        .story-page main img,.story-page .se-footer-brand img,.story-page .se-footer-group img{display:block;max-width:100%;height:auto}
        .block{position:relative}.block--dark{background:#7a7e81;color:#eee}.block--light{background:#fff;color:#333}.block--colored{background:#10808f;color:#fff}
        .hero{position:relative;overflow:hidden;width:calc(100% - (var(--page-image-gutter) * 2));min-height:calc(100vh - 132px);margin:0 auto;border-radius:16px;font-size:27px;line-height:27px;font-weight:300;text-wrap:balance}
        .hero__bg,.bg{position:absolute;top:0;left:0;width:100%;height:100%}
        .bg--embed{overflow:hidden}.u-bg-cover{background-size:cover;background-position:center 40%;background-repeat:no-repeat}
        .hero__bg img,.u-bg-cover img{width:100%;height:100%;object-fit:cover;margin:0;filter:saturate(.95) contrast(1.02)}
        .bg--opacity{background:#000}
        .block__padding{position:relative;z-index:2;width:100%;max-width:1200px;margin:0 auto;padding:90px 24px}
        .block__hero-height{min-height:inherit}.u-flex-column-middle{display:flex;flex-direction:column;align-items:center;justify-content:center}
        .hero__body{width:100%;max-width:520px;margin:0 auto 30px;text-align:center;opacity:1}
        .hero__preheading{display:block;margin:0 auto 18px;text-transform:uppercase;font-size:14px;line-height:18px;font-weight:500;letter-spacing:.08em;color:#fff}
        .theme-se .theme-title,.story-page .theme-title{font-family:"GT Walsheim",Helvetica,Arial,sans-serif;font-weight:400;text-transform:uppercase}
        .hero__body .hero__title{margin:0;color:#fff;font-size:60px;line-height:56px;letter-spacing:0;opacity:1!important}
        .hero__hr{display:block;width:72px;height:1px;border:0;margin:24px auto;background:#fff;color:#fff}
        .hero__copy{max-width:460px;margin:0 auto;color:#fff;font-size:27px;line-height:27px;font-weight:300}
        .hero__copy p{margin:0}
        .btn-anchor.js-page-down{position:absolute;left:50%;bottom:32px;z-index:3;display:flex;align-items:center;justify-content:center;width:62px;height:62px;margin-left:-31px;border:1px solid currentColor;border-radius:50%;color:#fff;text-decoration:none}
        .btn-anchor.js-page-down span{width:13px;height:13px;border-right:2px solid currentColor;border-bottom:2px solid currentColor;transform:rotate(45deg) translate(-2px,-2px)}
        .b-intro .block__padding{max-width:1320px;padding-top:112px;padding-bottom:48px}
        .block-head{position:relative;width:min(90%,760px);margin:0 auto 45px;text-align:center;text-wrap:balance;opacity:1}
        .b-intro .block-head{display:grid;grid-template-columns:minmax(260px,.72fr) minmax(0,1fr);gap:clamp(48px,8vw,140px);width:100%;margin:0;align-items:start;text-align:left;text-wrap:normal}
        .block-head__eyebrow{display:block;margin:0 0 18px;text-transform:uppercase;font-size:13px;line-height:18px;font-weight:500;letter-spacing:.12em;color:#10808f}
        .block-head__title{margin:0 0 22px;color:#333;font-family:"GT Walsheim",Helvetica,Arial,sans-serif;font-size:44px;line-height:44px;font-weight:400;text-transform:uppercase}
        .b-intro .block-head__title{margin:0;color:#5a5557;font-size:clamp(36px,3.4vw,54px);line-height:1.08;font-weight:500;text-transform:none;text-wrap:balance}
        .block-head__subtitle{margin:0 auto 28px;color:#333;font-size:27px;line-height:32px;font-weight:300}
        .b-intro .block-head__subtitle{margin:0 0 34px;color:#6a6365;font-size:clamp(26px,2.15vw,36px);line-height:1.3;text-wrap:balance}
        .block-head__body{color:#333;font-size:18px;line-height:30px}
        .b-intro .block-head__body{color:#5f595b;font-size:20px;line-height:32px}
        .block-head__body p{margin:0 0 18px}
        .block-head__body p:last-child{margin-bottom:0}
        .block-copy{display:grid;justify-items:start}
        .btn,.se-btn{display:inline-flex;align-items:center;justify-content:center;gap:18px;min-width:164px;min-height:64px;border:0;border-radius:9px;padding:0 30px;color:#777;background:#f0f1f2;text-decoration:none;text-transform:uppercase;font-size:14px;line-height:18px;font-weight:500;letter-spacing:.04em}
        .btn::after{content:"\2192";font-size:24px;line-height:1}
        .btn:hover,.se-btn:hover{background:#333;color:#fff}
        .b-intro__buttons{margin-top:34px;text-align:left}.b-gallery-masonry{padding:0;background:#fff}
        .b-gallery-masonry .block__padding{max-width:none;padding:0 var(--page-image-gutter) 96px}
        .gmasonry__wrap{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:8px;width:100%;margin:0 auto;padding:0}
        .gmasonry__item{position:relative;overflow:hidden;min-height:clamp(260px,23vw,430px);border-radius:9px;background:#ddd}
        .gmasonry__item img{width:100%;height:100%;object-fit:cover;margin:0}
        .gmasonry__download{display:none}
        .b-section.block--dark{background:#7a7e81;color:#fff}
        .b-section .block__padding{padding-top:96px;padding-bottom:96px;text-align:center}
        .block-head__prefix{margin:0 0 12px;text-transform:uppercase;font-size:14px;line-height:18px;font-weight:500;letter-spacing:.08em;color:#10808f}
        .block--dark .block-head__prefix{color:#fff}
        .block--dark .block-head__title,.block--dark .block-head__subtitle{color:#fff}
        .row{display:flex;flex-wrap:wrap;justify-content:center;width:100%;max-width:1200px;margin:0 auto}
        .body--section{width:33.333%;padding:0 18px;text-align:center}
        .body__media{height:86px;margin:0 auto 24px;display:flex;align-items:center;justify-content:center}
        .body__media img{width:auto;max-width:190px;max-height:86px;margin:0 auto;object-fit:contain}
        .body__copy{color:#fff;font-size:16px;line-height:24px}
        .btn--xs{min-width:0;min-height:42px;margin-top:20px;font-size:12px}
        .se-footer-brand{background:#10808f;color:#fff;text-align:center}
        .se-footer-brand .block__padding,.se-footer-brand .se-block-padding{padding:76px 24px}
        .se-footer-inner{display:grid;justify-items:center;gap:24px}
        .se-footer-logo img{max-width:420px;max-height:110px;filter:brightness(0) invert(1);margin:0 auto}
        .se-footer-logo strong{font-size:54px;line-height:54px;font-weight:400;text-transform:uppercase}
        .se-footer-social,.se-footer-contact{display:flex;flex-wrap:wrap;justify-content:center;gap:12px 24px}
        .se-footer-social a,.se-footer-contact a{color:#fff;text-decoration:none;text-transform:uppercase;font-size:14px;line-height:18px}
        .se-footer-group{background:#f4f4f4;color:#333}
        .se-footer-group .se-block-padding{width:min(1200px,calc(100% - 48px));margin:0 auto;padding:54px 0}
        .se-footer-columns{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:30px}
        .se-footer-columns div{display:grid;gap:8px}.se-footer-columns h3{margin:0 0 8px;text-transform:uppercase;font-size:18px}.se-footer-columns a{color:#333;text-decoration:none}
        .story-page .whatsapp-widget{position:fixed;right:22px;bottom:22px;z-index:999;display:grid;justify-items:end;gap:14px;pointer-events:none}
        .story-page .whatsapp-widget-label,.story-page .whatsapp-widget-button{pointer-events:auto}
        .story-page .whatsapp-widget-button{display:inline-flex;align-items:center;justify-content:center;width:84px;height:84px;min-width:84px;min-height:84px;border-radius:50%}
        .story-page .whatsapp-widget-icon{display:block;width:35px;height:35px;max-width:35px;max-height:35px;flex:0 0 35px}
        @media(max-width:899px){.b-intro .block-head{grid-template-columns:1fr;gap:30px}.b-intro .block__padding{padding-top:76px;padding-bottom:36px}.gmasonry__wrap{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:799px){.hero__body .hero__title{font-size:53px;line-height:50px}.hero__copy{font-size:24px;line-height:28px}.se-footer-columns{grid-template-columns:1fr}.body--section{width:100%;margin-bottom:34px}.block-head__title{font-size:38px;line-height:42px}}
        @media(max-width:599px){body.story-page{--page-image-gutter:14px;font-size:16px;line-height:24px}.hero{min-height:72vh;border-radius:12px}.hero__body .hero__title{font-size:40px;line-height:36px}.hero__copy{font-size:21px;line-height:25px}.block__padding{padding:70px 18px}.b-intro .block-head__subtitle{font-size:23px;line-height:28px}.b-intro .block-head__body{font-size:17px;line-height:27px}.gmasonry__wrap{grid-template-columns:1fr}.b-gallery-masonry .block__padding{padding:0 var(--page-image-gutter) 70px}.gmasonry__item{min-height:230px}}
    </style>
</head>
<body id="top" class="story-page theme-se">
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
                        <li><a class="nav-link--caret" href="{{ route('home') }}#services">What We Do</a></li>
                        <li><a href="{{ route('home') }}#proof">Our Work</a></li>
                        <li><a href="{{ route('home') }}#services">Our Services</a></li>
                        <li><a href="{{ route('home') }}#process">Our Stories</a></li>
                        <li><a class="nav-link--caret" href="{{ route('home') }}#intro">About Us</a></li>
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
                            <li><a href="{{ route('home') }}#services">What We Do</a></li>
                            <li><a href="{{ route('home') }}#proof">Our Work</a></li>
                            <li><a href="{{ route('home') }}#services">Our Services</a></li>
                            <li><a href="{{ route('home') }}#process">Our Stories</a></li>
                            <li><a href="{{ route('home') }}#intro">About Us</a></li>
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

    <main id="main">
        <section class="hero block block--dark block--has-bg">
            <div class="hero__bg">
                <div class="bg bg--embed">
                    @if ($heroImage !== '')
                        <div class="bg u-bg-cover">
                            <img src="{{ $heroImage }}" alt="{{ $page['image_alt'] !== '' ? $page['image_alt'] : $page['title'] }}">
                        </div>
                    @endif
                    <div class="bg bg--opacity" style="opacity:0.25"></div>
                </div>
            </div>

            <div class="block__padding block__hero-height u-flex-column-middle">
                <div class="hero__body">
                    <span class="hero__preheading">{{ strtoupper($page['type']) }}</span>
                    <h1 class="hero__title theme-title">{{ $page['title'] }}</h1>
                    <hr class="hero__hr">
                    @if ($page['meta_description'] !== '')
                        <div class="hero__copy u-no-margin-content">
                            <p>{{ $page['meta_description'] }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <a class="btn-anchor js-page-down" href="#content" aria-label="Scroll to page content"><span></span></a>
        </section>

        <section class="b-intro block block--light" id="content">
            <div class="block__padding">
                <div class="block-head u-max-width-med">
                    <div class="block-heading">
                        <span class="block-head__eyebrow">{{ $page['type'] }}</span>
                        <h2 class="block-head__title">{{ $page['heading_two'] }}</h2>
                    </div>

                    <div class="block-copy">
                        @if ($page['meta_description'] !== '')
                            <p class="block-head__subtitle">{{ $page['meta_description'] }}</p>
                        @endif
                        <div class="block-head__body u-no-margin-content">
                            {!! $page['description'] !!}
                        </div>

                        <div class="block-cta u-text-center b-intro__buttons">
                            <a class="btn btn-centred" href="{{ route('home') }}#contact">Enquire Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @if (count($galleryImages) > 0)
            <section class="b-gallery-masonry block block--light">
                <div class="block__padding js-gmasonry">
                    <div class="gmasonry__wrap">
                    @foreach ($galleryImages as $index => $image)
                        <div class="gmasonry__item col col-sm-6 col-lg-4 col--gmasonry">
                            <div data-gmasonry-slide="{{ $index }}" data-gmasonry-filters="false">
                                <img src="{{ $image }}" alt="{{ $page['image_alt'] !== '' ? $page['image_alt'] : $page['title'] }}">
                            </div>
                            <a class="gmasonry__download" href="{{ $image }}" download>Download</a>
                        </div>
                    @endforeach
                    </div>
                </div>
            </section>
        @endif

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
    </div>

    <script src="{{ asset('story-home.js') }}" defer></script>
</body>
</html>
