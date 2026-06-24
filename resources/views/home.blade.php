@php
    $showcaseImages = [
        'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1400&q=80',
        'https://images.unsplash.com/photo-1591115765373-5207764f72e7?auto=format&fit=crop&w=1400&q=80',
        'https://images.unsplash.com/photo-1505236858219-8359eb29e329?auto=format&fit=crop&w=1400&q=80',
    ];

    $fallbacks = [
        'linear-gradient(135deg, #273243 0%, #4c5a6a 100%)',
        'linear-gradient(135deg, #1f5c4d 0%, #273243 100%)',
        'linear-gradient(135deg, #d0692b 0%, #bd5719 100%)',
        'linear-gradient(135deg, #efe7dc 0%, #faf6ef 100%)',
    ];

    $sectors = ['Conferences', 'Brand launches', 'Exhibitions', 'Award nights', 'Hybrid events', 'Roadshows'];
    $footprint = ['Outdoor builds'];
    $eventTypeOptions = [
        'Conference',
        'Brand Launch',
        'Exhibition',
        'Award Night',
        'Hybrid Event',
        'Roadshow',
        'Corporate Celebration',
        'Other',
    ];
    $contactEmail = trim((string) ($contactEmail ?? ''));
    $hasContactEmail = filled($contactEmail);
    $contactPhones = is_array($contactPhones ?? null) ? $contactPhones : [];
    $socialLinks = is_array($socialLinks ?? null) ? $socialLinks : [];
    $whatsappUrl = trim((string) ($whatsappUrl ?? ''));
    $hasWhatsapp = filled($whatsappUrl);
    $paymentUrl = trim((string) ($paymentUrl ?? ''));
    $paymentLabel = trim((string) ($paymentLabel ?? 'Make Payment'));
    $hasPaymentUrl = filled($paymentUrl);
    $navPages = array_values(array_filter(
        is_array($navPages ?? null) ? $navPages : [],
        fn ($item) => is_array($item) && filled($item['title'] ?? '') && filled($item['slug'] ?? '')
    ));
    $logoUrl = \App\Support\HomepageContent::assetUrl(
        (string) data_get($logo ?? [], 'path', data_get($logo ?? [], 'url', ''))
    );
    $hasLogo = filled($logoUrl);
    $sectionImages = is_array($sectionImages ?? null) ? $sectionImages : [];

    $resolvedImages = [];
    foreach ($whatWeDo as $index => $item) {
        $resolvedImages[$index] = ! empty($item['image'])
            ? \App\Support\HomepageContent::assetUrl((string) $item['image'])
            : $showcaseImages[$index % count($showcaseImages)];
    }

    $heroImage = \App\Support\HomepageContent::assetUrl((string) data_get($sectionImages, 'hero.path', ''));
    if ($heroImage === '') {
        $heroImage = $showcaseImages[0];
    }
    $heroVideoSource = \App\Support\HomepageContent::videoSource(
        (string) data_get($heroVideo ?? [], 'url', '')
    );
    if ($heroVideoSource['type'] === '') {
        $heroVideoSource = \App\Support\HomepageContent::videoSource(
            (string) data_get($whatWeDo, '0.link_url', '')
        );
    }
    $introImage = \App\Support\HomepageContent::assetUrl((string) data_get($sectionImages, 'intro.path', ''));
    if ($introImage === '') {
        $introImage = $resolvedImages[1] ?? $showcaseImages[1];
    }
    $serviceShowcaseImage = \App\Support\HomepageContent::assetUrl((string) data_get($sectionImages, 'services.path', ''));
    if ($serviceShowcaseImage === '') {
        $serviceShowcaseImage = $resolvedImages[0] ?? $showcaseImages[2];
    }
    $serviceShowcaseVideo = \App\Support\HomepageContent::videoSource((string) data_get($sectionImages, 'services.video_path', ''));
    $ambientImage = \App\Support\HomepageContent::assetUrl((string) data_get($sectionImages, 'proof.path', ''));
    if ($ambientImage === '') {
        $ambientImage = $resolvedImages[2] ?? $showcaseImages[2];
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peak Experience | Creative Event Production in Kenya</title>
    <meta name="description" content="Peak Experience delivers conferences, launches, exhibitions, and live brand events with creative production, staging, and show-day precision across Kenya.">
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
                <a class="brand" href="#top" aria-label="Peak Experience home">
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
                            <a class="nav-link--caret" href="#services">What We Do</a>
                            @if (count($navPages) > 0)
                                <ul class="nav-dropdown" aria-label="What We Do pages">
                                    @foreach ($navPages as $navPage)
                                        <li><a href="{{ route('pages.show', ['page' => $navPage['slug']]) }}">{{ $navPage['title'] }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                        <li><a href="#proof">Our Work</a></li>
                        <li><a href="#services">Our Services</a></li>
                        <li><a href="#process">Our Stories</a></li>
                        <li><a class="nav-link--caret" href="#intro">About Us</a></li>
                    </ul>
                </nav>

                <div class="header-utility">
                    <a class="button button-nav-cta" href="#contact">Contact Us</a>
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
                                <a href="#services">What We Do</a>
                                @if (count($navPages) > 0)
                                    <ul class="nav-mobile-children" aria-label="What We Do pages">
                                        @foreach ($navPages as $navPage)
                                            <li><a href="{{ route('pages.show', ['page' => $navPage['slug']]) }}">{{ $navPage['title'] }}</a></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                            <li><a href="#proof">Our Work</a></li>
                            <li><a href="#services">Our Services</a></li>
                            <li><a href="#process">Our Stories</a></li>
                            <li><a href="#intro">About Us</a></li>
                            <li><a href="#contact">Contact Us</a></li>
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
            <section class="hero">
                <div class="wrap">
                    <div class="hero-stage reveal">
                        @if ($heroVideoSource['type'] === 'file')
                            <video class="hero-stage-media" autoplay muted loop playsinline preload="metadata" aria-hidden="true">
                                <source src="{{ $heroVideoSource['url'] }}">
                            </video>
                        @elseif (in_array($heroVideoSource['type'], ['youtube', 'vimeo'], true))
                            <div class="hero-stage-video" aria-hidden="true">
                                <iframe
                                    class="hero-stage-embed"
                                    src="{{ $heroVideoSource['url'] }}"
                                    title=""
                                    tabindex="-1"
                                    allow="autoplay; encrypted-media; picture-in-picture"
                                    referrerpolicy="strict-origin-when-cross-origin"
                                ></iframe>
                            </div>
                        @else
                            <img class="hero-stage-media" src="{{ $heroImage }}" alt="Peak Experience event production showcase">
                        @endif

                        <div class="hero-stage-copy">
                            <h1 class="hero-stage-title">Peak Experience</h1>
                            <span class="hero-stage-dots" aria-hidden="true">
                                <i></i>
                                <i></i>
                                <i></i>
                            </span>
                            <p>Kenya's Creative Event Agency</p>
                        </div>

                        <a class="hero-scroll" href="#intro" aria-label="Scroll to the next section">
                            <span></span>
                        </a>
                    </div>
                </div>
            </section>

            <section class="strip" aria-label="Event categories">
                <div class="wrap strip-overflow">
                    <ul class="strip-track">
                        @for ($i = 0; $i < 2; $i++)
                            @foreach ($footprint as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                            @foreach ($sectors as $sector)
                                <li>{{ $sector }}</li>
                            @endforeach
                        @endfor
                    </ul>
                </div>
            </section>

            <section class="section" id="intro">
                <div class="wrap intro-showcase reveal">
                    <figure class="intro-media">
                        <img src="{{ $introImage }}" alt="Peak Experience event production setup">
                    </figure>

                    <div class="intro-copy">
                        <div>
                            <span class="section-prefix">Story-led event management</span>
                            <h2>The Peak Experience approach</h2>
                        </div>

                        <p>Peak Experience combines creative direction, staging, media systems, and venue execution so audiences experience one coherent story instead of a room full of disconnected details. From venue planning and show flow to media support and live delivery, every layer is built to feel polished, calm, and intentional from the first arrival to the final cue.</p>

                        <a class="button button-primary intro-button" href="#proof">Discover Our Work</a>
                    </div>
                </div>
            </section>

            <section class="section section-services" id="services">
                <div class="wrap service-showcase reveal reveal-delay-1">
                    <div class="service-showcase-copy">
                        <div>
                            <span class="section-prefix">Tools to craft any experience</span>
                            <h2>Our Services</h2>
                        </div>

                        <p>We deliver seamless event production, staging, media systems, and exhibition support through one coordinated team. From the first planning session to live show-day execution, every service is shaped to keep your event polished, engaging, and technically steady.</p>
                        <a class="button button-primary service-showcase-button" href="#services-detail">Explore Services</a>
                    </div>

                    <figure class="service-showcase-media">
                        @if ($serviceShowcaseVideo['type'] === 'file')
                            <video class="service-showcase-video" autoplay muted loop playsinline preload="metadata" aria-hidden="true">
                                <source src="{{ $serviceShowcaseVideo['url'] }}">
                            </video>
                        @elseif (in_array($serviceShowcaseVideo['type'], ['youtube', 'vimeo'], true))
                            <div class="service-showcase-video-frame" aria-hidden="true">
                                <div class="service-showcase-video-cover">
                                    <iframe
                                        class="service-showcase-video-embed"
                                        src="{{ $serviceShowcaseVideo['url'] }}"
                                        title=""
                                        tabindex="-1"
                                        allow="autoplay; encrypted-media; picture-in-picture"
                                        referrerpolicy="strict-origin-when-cross-origin"
                                    ></iframe>
                                </div>
                            </div>
                        @else
                            <img src="{{ $serviceShowcaseImage }}" alt="Peak Experience team planning event services">
                        @endif
                    </figure>
                </div>

                <div class="wrap service-stack service-stack--detail" id="services-detail">
                    <div class="section-copy service-section-copy">
                        <div>
                            <span class="section-prefix">Capabilities built for live delivery</span>
                            <h2>Services that carry the idea all the way to the audience.</h2>
                        </div>

                        <p>Each service is designed to work with the others, so your venue, visuals, sound, staging, and audience journey feel like one complete experience rather than separate moving parts.</p>
                    </div>

                    <div class="service-grid">
                        @foreach ($whatWeDo as $item)
                            @php
                                $background = $fallbacks[$loop->index % count($fallbacks)];
                                $serviceImage = $resolvedImages[$loop->index] ?? $showcaseImages[$loop->index % count($showcaseImages)];
                            @endphp
                            <article class="service-card">
                                <div class="service-media" @if (empty($item['image'])) style="background: {{ $background }};" @endif>
                                    <img src="{{ $serviceImage }}" alt="{{ $item['title'] }}">
                                    <span class="service-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>

                                <div class="service-body">
                                    <h3>{{ $item['title'] }}</h3>
                                    <p>{{ $item['text'] }}</p>
                                    <a class="service-link" href="{{ route('services.show', ['service' => $item['slug']]) }}" aria-label="Explore more about {{ $item['title'] }}">Explore More</a>
                                </div>
                            </article>
                        @endforeach
                    </div>

                </div>
            </section>

            <section class="section section-proof" id="proof">
                <div class="wrap">
                    <article class="proof-stage reveal reveal-delay-2">
                        <img class="proof-stage-media" src="{{ $ambientImage }}" alt="Peak Experience event production showcase">

                        <div class="proof-stage-copy">
                            <span class="proof-stage-kicker">Innovative. Impactful. Seamless.</span>
                            <h2>Our Work</h2>
                            <p>Explore the conferences, launches, exhibitions, executive forums, and hybrid productions Peak Experience has shaped across Kenya. Every brief is translated into an environment that feels polished, immersive, and tightly executed from the first guest arrival to the final cue.</p>
                            <a class="button proof-stage-button" href="#process">Discover More</a>
                        </div>
                    </article>
                </div>
            </section>

            <section class="section" id="process">
                <div class="wrap section-layout">
                    <div class="section-rail">
                        <strong>04</strong>
                        <span>Our Process</span>
                    </div>

                    <div class="process-shell">
                        <div class="process-intro">
                            <div class="section-copy process-copy">
                                <div>
                                    <span class="section-prefix">Clear checkpoints</span>
                                    <h2>From first brief to final cue, the delivery path stays visible.</h2>
                                </div>
                            </div>

                            <aside class="process-summary">
                                <span class="process-summary-label">Why it works</span>
                                <p class="process-lead">Each phase of the project is designed to reduce ambiguity, tighten approvals, and keep setup and show-day decisions easier to manage.</p>
                            </aside>
                        </div>

                        <div class="process-grid">
                            @foreach ($ourProcess as $step)
                                <article class="process-card">
                                    <span class="process-index">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    <h3>{{ $step['title'] }}</h3>
                                    <p>{{ $step['text'] }}</p>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <section class="section" id="contact">
                <div class="wrap contact-shell">
                    <div class="contact-copy">
                        <span class="section-prefix">Start your enquiry</span>
                        <h2>Bring the brief. We will shape the production path around it.</h2>
                        <p>Complete the enquiry form with your contact details and the event context. Your message will be forwarded directly to Peak Experience so the team can respond with the right next steps.</p>

                        <div class="contact-actions">
                            @if (! empty($contactPhones))
                                <a class="button button-secondary" href="tel:{{ $contactPhones[0]['dial'] }}">Call Us</a>
                            @endif
                            @if ($hasWhatsapp)
                                <a class="button button-whatsapp" href="{{ $whatsappUrl }}" target="_blank" rel="noreferrer">WhatsApp Us</a>
                            @endif
                            <a class="button button-secondary" href="#services">Review Services</a>
                            @if ($hasPaymentUrl)
                                <a class="button button-secondary" href="{{ $paymentUrl }}" target="_blank" rel="noreferrer">{{ $paymentLabel }}</a>
                            @endif
                        </div>

                        <div class="contact-details" aria-label="Contact details">
                            @if ($hasContactEmail)
                                <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                            @endif
                            @foreach ($contactPhones as $phone)
                                <a href="tel:{{ $phone['dial'] }}">{{ $phone['display'] }}</a>
                            @endforeach
                        </div>
                    </div>

                    <aside class="brief-card contact-form-card">
                        <div class="contact-form-header">
                            <h3>Project Enquiry</h3>
                            <p class="contact-form-note">Share the project details below and Peak Experience will review the brief and respond with the right next step.</p>
                        </div>

                        @if (session('contact_status'))
                            <div class="form-alert form-alert--success">{{ session('contact_status') }}</div>
                        @endif

                        @if (session('contact_error'))
                            <div class="form-alert form-alert--error">{{ session('contact_error') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="form-alert form-alert--error">Please review the highlighted fields and submit the enquiry again.</div>
                        @endif

                        <form class="contact-form" method="POST" action="{{ route('contact.submit') }}" novalidate>
                            @csrf

                            <div class="contact-field-grid">
                                <div class="contact-field">
                                    <label for="contact-first-name">First Name <span>*</span></label>
                                    <input id="contact-first-name" class="contact-input @error('first_name') is-invalid @enderror" type="text" name="first_name" value="{{ old('first_name') }}" autocomplete="given-name" required>
                                    @error('first_name')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="contact-field">
                                    <label for="contact-last-name">Last Name <span>*</span></label>
                                    <input id="contact-last-name" class="contact-input @error('last_name') is-invalid @enderror" type="text" name="last_name" value="{{ old('last_name') }}" autocomplete="family-name" required>
                                    @error('last_name')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="contact-field">
                                    <label for="contact-organization">Company <span>*</span></label>
                                    <input id="contact-organization" class="contact-input @error('organization') is-invalid @enderror" type="text" name="organization" value="{{ old('organization') }}" autocomplete="organization" required>
                                    @error('organization')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="contact-field">
                                    <label for="contact-phone">Phone Number <span>*</span></label>
                                    <input id="contact-phone" class="contact-input @error('phone') is-invalid @enderror" type="text" name="phone" value="{{ old('phone') }}" autocomplete="tel" inputmode="tel" required>
                                    @error('phone')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="contact-field">
                                    <label for="contact-email">Email <span>*</span></label>
                                    <input id="contact-email" class="contact-input @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
                                    @error('email')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="contact-field">
                                    <label for="contact-date-of-event">Date of Event <span>*</span></label>
                                    <input id="contact-date-of-event" class="contact-input @error('date_of_event') is-invalid @enderror" type="date" name="date_of_event" value="{{ old('date_of_event') }}" required>
                                    @error('date_of_event')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="contact-field">
                                    <label for="contact-guest-count">Number of Guests</label>
                                    <input id="contact-guest-count" class="contact-input @error('guest_count') is-invalid @enderror" type="text" name="guest_count" value="{{ old('guest_count') }}" inputmode="numeric">
                                    @error('guest_count')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="contact-field">
                                    <label for="contact-event-type">Type of Event <span>*</span></label>
                                    <select id="contact-event-type" class="contact-input contact-select @error('event_type') is-invalid @enderror" name="event_type" required>
                                        <option value="">Please select</option>
                                        @foreach ($eventTypeOptions as $option)
                                            <option value="{{ $option }}" @selected(old('event_type') === $option)>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                    @error('event_type')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="contact-field contact-field--full">
                                    <label for="contact-venue">Venue / Location <span>*</span></label>
                                    <input id="contact-venue" class="contact-input @error('venue') is-invalid @enderror" type="text" name="venue" value="{{ old('venue') }}" required>
                                    @error('venue')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="contact-field contact-field--full">
                                <label for="contact-additional-info">Additional Info</label>
                                <textarea id="contact-additional-info" class="contact-input contact-textarea @error('additional_info') is-invalid @enderror" name="additional_info" rows="7">{{ old('additional_info') }}</textarea>
                                @error('additional_info')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="contact-consent @error('consent') is-invalid @enderror">
                                <label class="contact-checkbox">
                                    <input type="checkbox" name="consent" value="1" @checked(old('consent')) required>
                                    <span>I agree to be contacted by Peak Experience regarding this enquiry and understand that these details will be used to prepare a response.</span>
                                </label>
                                @error('consent')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="contact-form-actions">
                                <button class="button button-primary" type="submit">Send Enquiry</button>
                                @if ($hasPaymentUrl)
                                    <a class="button button-secondary" href="{{ $paymentUrl }}" target="_blank" rel="noreferrer">{{ $paymentLabel }}</a>
                                @endif
                            </div>
                        </form>
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
                        <a href="#intro">About</a>
                        <a href="#services">Services</a>
                        <a href="#proof">Why Us</a>
                        <a href="#process">Process</a>
                        <a href="#contact">Contact Us</a>
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
    @if ($errors->any() || session('contact_status') || session('contact_error'))
        <script>
            window.addEventListener('load', () => {
                document.getElementById('contact')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        </script>
    @endif
</body>
</html>
