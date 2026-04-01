@php
    $showcaseImages = [
        'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1400&q=80',
        'https://images.unsplash.com/photo-1591115765373-5207764f72e7?auto=format&fit=crop&w=1400&q=80',
        'https://images.unsplash.com/photo-1540317580384-e5d43867caa6?auto=format&fit=crop&w=1400&q=80',
    ];

    $fallbacks = [
        'linear-gradient(135deg, #5a5353 0%, #7a7e81 100%)',
        'linear-gradient(135deg, #10808f 0%, #5a5353 100%)',
        'linear-gradient(135deg, #e8ff70 0%, #d5ec5f 100%)',
        'linear-gradient(135deg, #dfe2e5 0%, #f7f8f9 100%)',
    ];

    $sectors = ['Conferences', 'Brand launches', 'Exhibitions', 'Award nights', 'Hybrid events', 'Roadshows'];
    $operatingPillars = [
        [
            'title' => 'Creative direction stays connected to delivery.',
            'text' => 'Concept, staging, media, and show flow are shaped as one experience rather than a chain of separate suppliers.',
        ],
        [
            'title' => 'Venue realities are handled early.',
            'text' => 'Room layout, access windows, audience flow, and technical limits are planned before they become costly surprises.',
        ],
        [
            'title' => 'Show-day decisions stay calm and clear.',
            'text' => 'Our production rhythm is built to reduce confusion when timing, talent, and audience attention matter most.',
        ],
    ];
    $footprint = ['Nairobi', 'Destination venues', 'Corporate campuses', 'Exhibition halls', 'Ballrooms', 'Outdoor builds'];
    $contactEmail = (string) config('mail.from.address');
    $hasContactEmail = filled($contactEmail) && $contactEmail !== 'hello@example.com';
    $logoUrl = \App\Support\HomepageContent::assetUrl(
        (string) data_get($logo ?? [], 'path', data_get($logo ?? [], 'url', ''))
    );
    $hasLogo = filled($logoUrl);

    $resolvedImages = [];
    foreach ($whatWeDo as $index => $item) {
        $resolvedImages[$index] = ! empty($item['image'])
            ? \App\Support\HomepageContent::assetUrl((string) $item['image'])
            : $showcaseImages[$index % count($showcaseImages)];
    }

    $heroPrimary = $whatWeDo[0] ?? [
        'title' => 'Creative Event Production',
        'icon' => 'Production',
        'text' => 'Story-led live event delivery for brands, teams, and audiences that expect polish.',
        'link_url' => '',
        'image' => '',
    ];
    $heroSecondary = $whatWeDo[1] ?? $heroPrimary;
    $heroImage = $resolvedImages[0] ?? $showcaseImages[0];
    $secondaryImage = $resolvedImages[1] ?? $showcaseImages[1];
    $ambientImage = $resolvedImages[2] ?? $showcaseImages[2];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PeakExperience | Creative Event Production in Kenya</title>
    <meta name="description" content="PeakExperience delivers conferences, launches, exhibitions, and live brand events with creative production, staging, and show-day precision across Kenya.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('story-home.css') }}">
</head>
<body id="top">
    <div class="page-shell">
        <header class="site-header">
            <div class="wrap header-row">
                <a class="brand" href="#top" aria-label="PeakExperience home">
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
                        <li><a class="nav-link--caret" href="#services">What We Do</a></li>
                        <li><a href="#proof">Our Work</a></li>
                        <li><a href="#services">Our Services</a></li>
                        <li><a href="#process">Our Stories</a></li>
                        <li><a class="nav-link--caret" href="#intro">About Us</a></li>
                    </ul>
                </nav>

                <div class="header-utility">
                    <a class="button button-nav-cta" href="#contact">Enquire</a>
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
                            <li><a href="#services">What We Do</a></li>
                            <li><a href="#proof">Our Work</a></li>
                            <li><a href="#services">Our Services</a></li>
                            <li><a href="#process">Our Stories</a></li>
                            <li><a href="#intro">About Us</a></li>
                            <li><a href="#contact">Enquire</a></li>
                        </ul>
                    </nav>

                    <div class="nav-meta">
                        @if ($hasContactEmail)
                            <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <main>
            <section class="hero">
                <div class="wrap">
                    <div class="hero-stage reveal">
                        <img class="hero-stage-media" src="{{ $heroImage }}" alt="Peak Experience event production showcase">

                        <div class="hero-stage-copy">
                            @if ($hasLogo)
                                <img class="hero-stage-logo" src="{{ $logoUrl }}" alt="Peak Experience">
                            @else
                                <h1 class="hero-stage-title">Peak Experience</h1>
                                <span class="hero-stage-dots" aria-hidden="true">
                                    <i></i>
                                    <i></i>
                                    <i></i>
                                </span>
                            @endif
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
                <div class="wrap section-layout">
                    <div class="section-rail">
                        <strong>01</strong>
                        <span>The PeakExperience Approach</span>
                    </div>

                    <div class="section-copy">
                        <div>
                            <span class="section-prefix">Story-led event management</span>
                            <h2>Every arrival, transition, and show moment should feel intentional.</h2>
                        </div>

                        <p>PeakExperience combines creative direction, staging, media systems, and venue execution so audiences experience one coherent story instead of a room full of disconnected details.</p>

                        <div class="signal-grid">
                            @foreach ($operatingPillars as $pillar)
                                <article class="signal-card">
                                    <strong>{{ $pillar['title'] }}</strong>
                                    <p>{{ $pillar['text'] }}</p>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <section class="section" id="services">
                <div class="wrap section-layout">
                    <div class="section-rail">
                        <strong>02</strong>
                        <span>What We Do</span>
                    </div>

                    <div class="service-stack">
                        <div class="section-copy">
                            <div>
                                <span class="section-prefix">Tools to shape the experience</span>
                                <h2>Creative production capabilities built for polished, immersive events.</h2>
                            </div>

                            <p>From the first concept note to the final breakdown, each service supports the same goal: a live experience that feels composed, branded, and technically steady.</p>
                        </div>

                        <div class="service-grid">
                            @foreach ($whatWeDo as $item)
                                @php
                                    $background = $fallbacks[$loop->index % count($fallbacks)];
                                    $serviceImage = $resolvedImages[$loop->index] ?? $showcaseImages[$loop->index % count($showcaseImages)];
                                @endphp
                                <article class="service-card {{ $loop->first ? 'service-card--featured' : '' }}">
                                    <div class="service-media" @if (empty($item['image'])) style="background: {{ $background }};" @endif>
                                        <img src="{{ $serviceImage }}" alt="{{ $item['title'] }}">
                                        <span class="service-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>

                                    <div class="service-body">
                                        <span class="section-prefix">Capability</span>
                                        <h3>{{ $item['title'] }}</h3>
                                        <p>{{ $item['text'] }}</p>
                                        @if (! empty($item['link_url']))
                                            <a class="service-link" href="{{ $item['link_url'] }}">Explore More</a>
                                        @else
                                            <span class="service-link">Production Ready</span>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="kpi-grid">
                            <article class="kpi-card">
                                <span class="kpi-label">Capabilities</span>
                                <strong>{{ str_pad((string) count($whatWeDo), 2, '0', STR_PAD_LEFT) }}</strong>
                                <p>Core service pillars covering staging, production, media systems, and exhibition environments.</p>
                            </article>
                            <article class="kpi-card">
                                <span class="kpi-label">Workflow</span>
                                <strong>{{ str_pad((string) count($ourProcess), 2, '0', STR_PAD_LEFT) }}</strong>
                                <p>A defined project rhythm from concept and setup through show-day operation and review.</p>
                            </article>
                            <article class="kpi-card">
                                <span class="kpi-label">Coverage</span>
                                <strong>Kenya</strong>
                                <p>Built for Nairobi venues, destination briefs, mobile setups, and high-stakes corporate spaces.</p>
                            </article>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section" id="proof">
                <div class="wrap section-layout">
                    <div class="section-rail">
                        <strong>03</strong>
                        <span>Why PeakExperience</span>
                    </div>

                    <div class="spotlight">
                        <article class="spotlight-panel">
                            <img src="{{ $ambientImage }}" alt="">
                            <div class="spotlight-copy">
                                <span class="section-prefix">Designed for important moments</span>
                                <h2>When the room matters, every detail needs a production spine.</h2>
                                <p>PeakExperience keeps creative direction, venue logic, timing, and technical delivery aligned so the audience sees one polished experience instead of backstage complexity.</p>

                                <ul class="detail-list" aria-label="Event types">
                                    @foreach ($sectors as $sector)
                                        <li>{{ $sector }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </article>

                        <div class="pillar-stack">
                            @foreach ($operatingPillars as $pillar)
                                <article class="pillar-card">
                                    <strong>{{ $pillar['title'] }}</strong>
                                    <p>{{ $pillar['text'] }}</p>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <section class="section" id="process">
                <div class="wrap section-layout">
                    <div class="section-rail">
                        <strong>04</strong>
                        <span>Our Process</span>
                    </div>

                    <div class="process-shell">
                        <div class="section-copy">
                            <div>
                                <span class="section-prefix">Clear checkpoints</span>
                                <h2>From first brief to final cue, the delivery path stays visible.</h2>
                            </div>

                            <p class="process-lead">Each phase of the project is designed to reduce ambiguity, tighten approvals, and keep setup and show-day decisions easier to manage.</p>
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
                        <p>Share the event type, city, venue context, audience size, and target date. That is enough to map the right production approach and the next planning decisions.</p>

                        <div class="contact-actions">
                            @if ($hasContactEmail)
                                <a class="button button-primary" href="mailto:{{ $contactEmail }}">Email Your Brief</a>
                            @endif
                            <a class="button button-secondary" href="#services">Review Services</a>
                        </div>
                    </div>

                    <aside class="brief-card">
                        <h3>Recommended brief details</h3>

                        <div class="brief-fields">
                            <article class="brief-field">
                                <strong>Event Type</strong>
                                <span>Conference, launch, exhibition, gala, hybrid event, or branded activation.</span>
                            </article>
                            <article class="brief-field">
                                <strong>Location</strong>
                                <span>Venue name, city, or destination so planning starts with the real setup conditions.</span>
                            </article>
                            <article class="brief-field">
                                <strong>Audience Size</strong>
                                <span>Guest count, audience flow, and room expectations help define staging and systems.</span>
                            </article>
                            <article class="brief-field">
                                <strong>Timing</strong>
                                <span>Target date, installation window, and any show-critical milestones.</span>
                            </article>
                        </div>

                        <p class="brief-note">Ideal for conferences, launches, exhibitions, corporate celebrations, and other live experiences where the technical delivery needs to feel effortless to the audience.</p>
                    </aside>
                </div>
            </section>
        </main>

        <footer class="footer">
            <div class="wrap">
                <div class="footer-panel">
                    <div class="footer-copy">
                        <span class="footer-kicker">PeakExperience</span>
                        <strong>Creative production for live experiences that need polish.</strong>
                        <p>Event staging, media systems, exhibition builds, and disciplined show-day delivery for brands and organisers across Kenya.</p>
                    </div>

                    <nav class="footer-links" aria-label="Footer navigation">
                        <a href="#intro">About</a>
                        <a href="#services">Services</a>
                        <a href="#proof">Why Us</a>
                        <a href="#process">Process</a>
                        <a href="#contact">Enquiry</a>
                    </nav>

                    <div class="footer-links">
                        @if ($hasContactEmail)
                            <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                        @endif
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
