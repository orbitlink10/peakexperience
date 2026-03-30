@php
    $fallbacks = [
        'linear-gradient(135deg, #10213d 0%, #1f4f82 52%, #59a5d8 100%)',
        'linear-gradient(135deg, #3b1307 0%, #8f3f13 48%, #f2ab46 100%)',
        'linear-gradient(135deg, #0d3a37 0%, #157d72 52%, #7ce0d1 100%)',
        'linear-gradient(135deg, #311536 0%, #754089 52%, #d18ee5 100%)',
    ];

    $showcaseImages = [
        'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1400&q=80',
        'https://images.unsplash.com/photo-1591115765373-5207764f72e7?auto=format&fit=crop&w=1400&q=80',
        'https://images.unsplash.com/photo-1540317580384-e5d43867caa6?auto=format&fit=crop&w=1400&q=80',
    ];

    $sectors = ['Corporate summits', 'Award nights', 'Product launches', 'Hybrid conferences', 'Roadshows', 'Exhibitions'];
    $operatingPillars = [
        [
            'title' => 'End-to-end production',
            'text' => 'Planning, staging, media, and execution stay connected from the first brief to final breakdown.',
        ],
        [
            'title' => 'Venue-first planning',
            'text' => 'Every setup is shaped around the room, the audience flow, the schedule, and the technical constraints.',
        ],
        [
            'title' => 'Show-day control',
            'text' => 'The delivery approach is built to reduce confusion and keep teams aligned when the event goes live.',
        ],
    ];
    $contactEmail = (string) config('mail.from.address');
    $hasContactEmail = filled($contactEmail) && $contactEmail !== 'hello@example.com';
    $leadProcess = $ourProcess[0] ?? ['title' => 'Concept', 'text' => 'We shape the event narrative, technical plan, and delivery path before build begins.'];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PeakExperience | Event Production in Kenya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Syne:wght@500;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--bg:#081120;--bg2:#101c33;--paper:#fffaf1;--paper2:#f6efe3;--ink:#10192f;--muted:#5a6578;--accent:#f08c2c;--teal:#42d2bd;--line:rgba(16,25,47,.1);--shadow:0 24px 60px rgba(10,18,35,.16);--radius:24px}
        *{box-sizing:border-box;margin:0;padding:0}html{scroll-behavior:smooth}body{font-family:'Manrope',sans-serif;color:var(--ink);background:radial-gradient(circle at top left,rgba(240,140,44,.08),transparent 24%),linear-gradient(180deg,var(--paper) 0,#f5f1e8 100%)}img{display:block;max-width:100%}a{color:inherit}section[id]{scroll-margin-top:7rem}
        .container{width:min(1180px,92vw);margin:0 auto}.topbar{border-bottom:1px solid rgba(255,255,255,.1);color:rgba(255,250,241,.78);font-size:.83rem}.topbar .container,.header .container,.footer .container{display:flex;align-items:center;justify-content:space-between;gap:1rem}.topbar .container{min-height:2.8rem}.topbar strong{letter-spacing:.14em;text-transform:uppercase}.topbar span{color:rgba(255,250,241,.62)}
        .header{position:sticky;top:0;z-index:20;background:rgba(8,17,32,.94);backdrop-filter:blur(14px);border-bottom:1px solid rgba(255,255,255,.08)}.header .container{min-height:5.3rem}.brand{display:inline-flex;align-items:center;gap:.9rem;text-decoration:none;color:#fffaf1}.brand-mark{width:2.9rem;height:2.9rem;border-radius:1rem;display:grid;place-items:center;background:linear-gradient(140deg,rgba(240,140,44,.92),rgba(66,210,189,.8));color:#081120;font:800 1.2rem 'Syne',sans-serif;box-shadow:0 18px 35px rgba(240,140,44,.28)}.brand strong{display:block;font:800 1.28rem 'Syne',sans-serif;line-height:1}.brand span{display:block;margin-top:.2rem;color:rgba(255,250,241,.62);font-size:.72rem;letter-spacing:.18em;text-transform:uppercase;font-weight:700}
        .nav{display:flex;gap:1.4rem;flex-wrap:wrap;justify-content:center}.nav a{text-decoration:none;color:rgba(255,250,241,.84);font-weight:600;font-size:.95rem}.nav a:hover,.nav a:focus-visible{color:#fff}.pill,.btn{display:inline-flex;align-items:center;justify-content:center;text-decoration:none;border-radius:999px;font-weight:800}.pill{padding:.88rem 1.18rem;background:linear-gradient(135deg,#ffd49d,#f08c2c);color:#081120;box-shadow:0 18px 35px rgba(240,140,44,.2)}
        .hero{padding:3.8rem 0 4.8rem;position:relative;overflow:hidden;margin-bottom:4.5rem;background:radial-gradient(circle at top right,rgba(240,140,44,.16),transparent 24%),radial-gradient(circle at bottom left,rgba(66,210,189,.12),transparent 28%),linear-gradient(135deg,var(--bg) 0,var(--bg2) 58%,#183456 100%)}.hero:before,.hero:after{content:"";position:absolute;border-radius:999px;filter:blur(8px)}.hero:before{width:14rem;height:14rem;background:rgba(240,140,44,.18);top:2rem;right:min(8vw,5rem)}.hero:after{width:11rem;height:11rem;background:rgba(66,210,189,.18);left:min(6vw,3rem);bottom:3rem}.hero-grid,.proof,.cta{display:grid;grid-template-columns:minmax(0,1.05fr) minmax(320px,.95fr);gap:clamp(1.5rem,4vw,3.5rem);align-items:center;position:relative;z-index:1}.eyebrow,.label{display:inline-flex;align-items:center;gap:.55rem;color:#ffd49d;font-size:.8rem;font-weight:800;text-transform:uppercase;letter-spacing:.2em;margin-bottom:1rem}.label{color:#c76316}.eyebrow:before,.label:before{content:"";width:2.4rem;height:1px;background:currentColor;opacity:.6}
        .hero h1,.section h2,.cta h2{font-family:'Syne',sans-serif;letter-spacing:-.05em;line-height:1.04}.hero h1{max-width:10ch;color:#fffaf1;font-size:clamp(2.9rem,4.7vw,4.4rem);margin-bottom:1.1rem}.hero p{max-width:38rem;color:rgba(255,250,241,.76);font-size:clamp(1rem,1.8vw,1.14rem);line-height:1.75;margin-bottom:1.8rem}.actions,.tags,.stats,.services,.process-grid,.cta-list,.footer-links{display:flex;flex-wrap:wrap;gap:.9rem}.btn{padding:.95rem 1.28rem;transition:transform .18s ease}.btn:hover,.btn:focus-visible{transform:translateY(-1px)}.btn-primary{background:linear-gradient(135deg,#ffd49d,#f08c2c);color:#081120;box-shadow:0 20px 32px rgba(240,140,44,.22)}.btn-secondary{color:#fffaf1;border:1px solid rgba(255,255,255,.22);background:rgba(255,255,255,.06)}
        .tags span,.sector-list span,.cta-list span{padding:.68rem .95rem;border-radius:999px;font-size:.88rem;font-weight:700}.tags span{color:rgba(255,250,241,.84);background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.1)}
        .stage{position:relative;padding:1rem;border-radius:32px;background:linear-gradient(165deg,rgba(255,255,255,.14),rgba(255,255,255,.04));border:1px solid rgba(255,255,255,.12);box-shadow:var(--shadow)}.stage-grid{display:grid;grid-template-columns:1.15fr .85fr;grid-template-rows:1.3fr 1fr;gap:1rem;min-height:31rem}.shot{position:relative;border-radius:22px;overflow:hidden;background:#18253f}.shot img{width:100%;height:100%;object-fit:cover}.shot:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(9,16,30,.05),rgba(9,16,30,.78))}.shot-main{grid-row:span 2}.shot-copy{position:absolute;left:1.2rem;right:1.2rem;bottom:1.2rem;z-index:1;color:#fffaf1}.shot-copy span{display:inline-flex;padding:.45rem .72rem;border-radius:999px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.16);font-size:.76rem;font-weight:800;text-transform:uppercase;letter-spacing:.18em;margin-bottom:.8rem}.shot-copy strong{display:block;font-size:clamp(1.3rem,2vw,1.7rem);line-height:1.2}.float{position:absolute;right:-1.2rem;max-width:15rem;padding:1rem 1.05rem;border-radius:20px;background:rgba(255,250,241,.96);box-shadow:var(--shadow)}.float.top{top:1.4rem}.float.bottom{bottom:1.4rem}.float span{display:block;color:#6a7080;font-size:.78rem;font-weight:800;text-transform:uppercase;letter-spacing:.16em;margin-bottom:.45rem}.float strong{display:block;font-size:1.08rem;line-height:1.3;margin-bottom:.35rem}.float p{color:var(--muted);font-size:.9rem;line-height:1.55}
        .stats{margin-top:1rem;display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:1rem}.stat{background:rgba(255,250,241,.95);border:1px solid rgba(16,25,47,.08);border-radius:20px;padding:1.15rem;box-shadow:var(--shadow)}.stat strong{display:block;font:800 clamp(1.7rem,4vw,2.3rem) 'Syne',sans-serif;line-height:1;margin-bottom:.45rem}.stat p{color:var(--muted);font-size:.92rem;line-height:1.6}
        main{padding:0 0 5rem}.section{margin-bottom:5.4rem}.section-head{display:grid;grid-template-columns:minmax(0,1fr) minmax(280px,.78fr);gap:1.5rem;align-items:end;margin-bottom:1.7rem}.section h2,.cta h2{font-size:clamp(1.95rem,3.2vw,2.95rem)}.section-head p,.cta p{color:var(--muted);line-height:1.7}
        .services{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1.2rem}.card,.process-step,.panel,.advantage,.cta{box-shadow:var(--shadow)}.card{overflow:hidden;border-radius:var(--radius);background:rgba(255,255,255,.88);border:1px solid rgba(16,25,47,.08)}.card-media{position:relative;min-height:15rem;overflow:hidden}.card-media img{width:100%;height:100%;object-fit:cover}.card-media:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(7,13,24,.05),rgba(7,13,24,.5))}.card-fallback{display:grid;place-items:end start;padding:1.1rem}.card-fallback strong{position:relative;z-index:1;color:rgba(255,250,241,.92);font:800 clamp(1.6rem,3vw,2.1rem) 'Syne',sans-serif;line-height:.95;letter-spacing:-.05em;max-width:8ch}.chip,.index{position:absolute;z-index:1;display:inline-flex;align-items:center;justify-content:center;border-radius:999px;backdrop-filter:blur(10px)}.chip{top:1rem;left:1rem;padding:.58rem .88rem;font-size:.77rem;font-weight:800;text-transform:uppercase;letter-spacing:.14em;color:#fffaf1;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18)}.index{right:1rem;bottom:1rem;width:2.9rem;height:2.9rem;background:rgba(255,250,241,.88);color:#081120;font:800 1rem 'Syne',sans-serif}.card-body{padding:1.3rem}.card-body h3{font-size:1.34rem;line-height:1.2;margin-bottom:.65rem}.card-body p{color:var(--muted);line-height:1.7}.link{display:inline-flex;align-items:center;gap:.45rem;margin-top:1rem;text-decoration:none;color:#113663;font-weight:800}.link:after{content:"\2192"}.link.static{color:#c76316}
        .proof{align-items:stretch}.panel,.advantage{border-radius:32px;padding:clamp(1.4rem,3vw,2rem)}.panel{position:relative;overflow:hidden;min-height:24rem;background:linear-gradient(135deg,rgba(8,17,32,.96),rgba(17,44,72,.92)),url('{{ $showcaseImages[2] }}') center/cover no-repeat;color:#fffaf1}.panel:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(8,17,32,.12),rgba(8,17,32,.68))}.panel-copy,.sector-list{position:relative;z-index:1}.panel-copy h3{max-width:14ch;font:800 clamp(1.95rem,3.1vw,2.8rem) 'Syne',sans-serif;line-height:1.06;letter-spacing:-.04em;margin:.7rem 0 .95rem}.panel-copy p{max-width:32rem;color:rgba(255,250,241,.76);line-height:1.7}.sector-list{display:flex;flex-wrap:wrap;gap:.7rem;margin-top:1.4rem}.sector-list span{background:rgba(255,255,255,.11);border:1px solid rgba(255,255,255,.16);color:#fffaf1}.advantage{background:rgba(255,255,255,.92);border:1px solid rgba(16,25,47,.08);display:grid;gap:1rem}.advantage article{padding:1rem 0;border-bottom:1px solid rgba(16,25,47,.08)}.advantage article:first-child{padding-top:0}.advantage article:last-child{padding-bottom:0;border-bottom:0}.advantage strong{display:block;font-size:1.1rem;line-height:1.3;margin-bottom:.45rem}.advantage p{color:var(--muted);line-height:1.65}
        .process-grid{position:relative;display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:1rem}.process-grid:before{content:"";position:absolute;left:0;right:0;top:1.55rem;height:1px;background:linear-gradient(90deg,rgba(240,140,44,.2),rgba(66,210,189,.35),rgba(16,25,47,.12))}.process-step{position:relative;z-index:1;border-radius:26px;padding:1.35rem 1.2rem;background:rgba(255,255,255,.95);border:1px solid rgba(16,25,47,.08)}.process-step span{width:3.1rem;height:3.1rem;margin-bottom:1rem;display:inline-grid;place-items:center;border-radius:999px;background:linear-gradient(135deg,#ffd49d,#f08c2c);color:#081120;font:800 1rem 'Syne',sans-serif;box-shadow:0 14px 28px rgba(240,140,44,.22)}.process-step h3{font-size:1.15rem;line-height:1.3;margin-bottom:.65rem}.process-step p{color:var(--muted);font-size:.95rem;line-height:1.7}
        .cta{grid-template-columns:minmax(0,1fr) auto;padding:clamp(1.5rem,4vw,2.4rem);border-radius:32px;background:radial-gradient(circle at top right,rgba(255,212,157,.25),transparent 28%),linear-gradient(135deg,#091425 0%,#173452 55%,#165d56 100%);color:#fffaf1}.cta p{color:rgba(255,250,241,.78);max-width:40rem}.cta h2{color:#fffaf1;margin-bottom:.9rem}.cta-list{margin-top:1.2rem}.cta-list span{background:rgba(255,255,255,.09);border:1px solid rgba(255,255,255,.14);color:#fffaf1}.cta-actions{display:flex;flex-direction:column;gap:.85rem;min-width:13rem}
        .footer{border-top:1px solid rgba(16,25,47,.08);padding:2rem 0 3rem;background:rgba(255,250,241,.5)}.footer-brand{display:grid;gap:.35rem}.footer-brand strong{font:800 1.18rem 'Syne',sans-serif;line-height:1}.footer-brand span{color:var(--muted);font-size:.92rem}.footer-links{gap:.9rem 1.2rem}.footer-links a{text-decoration:none;color:#26415f;font-weight:700}
        @media (max-width:1100px){.topbar .container,.header .container,.footer .container{flex-wrap:wrap;justify-content:center;text-align:center;padding:.8rem 0}.hero-grid,.proof,.cta,.section-head{grid-template-columns:1fr}.hero h1,.hero p{max-width:none}.float{right:1rem}.stats,.process-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.cta-actions{flex-direction:row;flex-wrap:wrap;min-width:0}}
        @media (max-width:820px){.nav{gap:.85rem 1rem}.stage-grid,.services,.stats,.process-grid{grid-template-columns:1fr}.stage-grid{grid-template-rows:auto;min-height:auto}.shot-main{grid-row:span 1}.shot{min-height:14rem}.float{position:static;max-width:none;margin-top:1rem}.process-grid:before{display:none}}
        @media (max-width:560px){.topbar{display:none}.hero h1{font-size:2.35rem}.actions,.cta-actions{flex-direction:column}.btn,.pill{width:100%}}
        .hero-copy{max-width:39rem}
        .stat{position:relative;overflow:hidden;min-height:100%}
        .stat::before{content:"";position:absolute;top:0;left:1.15rem;width:3.2rem;height:4px;border-radius:999px;background:linear-gradient(135deg,#ffd49d,#f08c2c)}
        .stat-label{display:block;color:#728093;font-size:.74rem;font-weight:800;letter-spacing:.16em;text-transform:uppercase;margin-bottom:.7rem}
        .section-head.compact{margin-bottom:1.35rem}
        .overview{margin-bottom:5.4rem}
        .overview-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem}
        .overview-card{position:relative;overflow:hidden;padding:1.45rem;border-radius:28px;background:linear-gradient(180deg,rgba(255,255,255,.95),rgba(255,255,255,.84));border:1px solid rgba(16,25,47,.08);box-shadow:var(--shadow)}
        .overview-card::before{content:"";position:absolute;inset:0 auto auto 0;width:100%;height:1px;background:linear-gradient(90deg,rgba(240,140,44,.32),rgba(66,210,189,.18),transparent)}
        .overview-card strong{display:block;font-size:1.08rem;line-height:1.35;color:var(--ink);margin-bottom:.6rem}
        .overview-card p{color:var(--muted);line-height:1.72}
        .card{display:flex;flex-direction:column}
        .card-body{display:flex;flex-direction:column;gap:.75rem;min-height:15rem}
        .card-body p{flex:1}
        .process-section{padding:2rem;border-radius:32px;background:linear-gradient(180deg,rgba(255,255,255,.74),rgba(255,255,255,.94));border:1px solid rgba(16,25,47,.08);box-shadow:var(--shadow)}
        .process-section .section-head{margin-bottom:1.9rem}
        .cta-note{margin-top:1rem;color:rgba(255,250,241,.74);font-size:.92rem;line-height:1.6}
        @media (max-width:1100px){.overview-grid{grid-template-columns:1fr}.process-section{padding:1.5rem}}
    </style>
</head>
<body id="top">
    <div class="topbar">
        <div class="container">
            <strong>Live event production across Kenya</strong>
            <span>Staging, media systems, exhibition builds, and show-day execution for brands that cannot afford flat experiences.</span>
        </div>
    </div>

    <header class="header">
        <div class="container">
            <a class="brand" href="#top" aria-label="PeakExperience home">
                <span class="brand-mark">PX</span>
                <span>
                    <strong>PeakExperience</strong>
                    <span>Production and Event Delivery</span>
                </span>
            </a>

            <nav class="nav" aria-label="Primary">
                <a href="#services">Services</a>
                <a href="#proof">Why Us</a>
                <a href="#process">Process</a>
                <a href="#contact">Next Step</a>
            </nav>

            <a class="pill" href="#contact">Start A Brief</a>
        </div>
    </header>

    <section class="hero">
        <div class="container hero-grid">
            <div class="hero-copy">
                <span class="eyebrow">PeakExperience Event Production</span>
                <h1>Professional event production for conferences, launches, exhibitions, and live brand experiences.</h1>
                <p>PeakExperience supports organisers and brands with disciplined staging, media systems, and on-site delivery that keeps every part of the event clearer, steadier, and more polished.</p>

                <div class="actions">
                    <a href="#contact" class="btn btn-primary">Request A Brief</a>
                    <a href="#services" class="btn btn-secondary">View Services</a>
                </div>

                <div class="tags" aria-label="Key services">
                    @foreach (array_slice($whatWeDo, 0, min(4, count($whatWeDo))) as $item)
                        <span>{{ $item['icon'] ?: $item['title'] }}</span>
                    @endforeach
                </div>
            </div>

            <div aria-hidden="true">
                <div class="stage">
                    <div class="stage-grid">
                        <div class="shot shot-main">
                            <img src="{{ $showcaseImages[0] }}" alt="">
                            <div class="shot-copy">
                                <span>Signature delivery</span>
                                <strong>Conference staging, media, and audience-facing details designed as one system.</strong>
                            </div>
                        </div>
                        <div class="shot"><img src="{{ $showcaseImages[1] }}" alt=""></div>
                        <div class="shot"><img src="{{ $showcaseImages[2] }}" alt=""></div>
                    </div>
                </div>

                <div class="float top">
                    <span>Production stack</span>
                    <strong>{{ $whatWeDo[0]['title'] ?? 'Stage delivery' }}</strong>
                    <p>{{ $whatWeDo[0]['text'] ?? 'Structured production support for live event environments.' }}</p>
                </div>

                <div class="float bottom">
                    <span>First checkpoint</span>
                    <strong>{{ $leadProcess['title'] }}</strong>
                    <p>{{ $leadProcess['text'] }}</p>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="stats">
                <article class="stat">
                    <span class="stat-label">Capabilities</span>
                    <strong>{{ str_pad((string) count($whatWeDo), 2, '0', STR_PAD_LEFT) }}</strong>
                    <p>Core production capabilities covering staging, event systems, media, and exhibition support.</p>
                </article>
                <article class="stat">
                    <span class="stat-label">Workflow</span>
                    <strong>{{ str_pad((string) count($ourProcess), 2, '0', STR_PAD_LEFT) }}</strong>
                    <p>Structured project steps from planning and setup through execution and review.</p>
                </article>
                <article class="stat">
                    <span class="stat-label">Coverage</span>
                    <strong>KE</strong>
                    <p>Built for Nairobi venues, destination setups, and mobile event environments across Kenya.</p>
                </article>
                <article class="stat">
                    <span class="stat-label">Response</span>
                    <strong>48H</strong>
                    <p>Fast first-step direction for active briefs that need a practical production path quickly.</p>
                </article>
            </div>
        </div>
    </section>

    <main>
        <section class="overview section container" aria-labelledby="overview-title">
            <div class="section-head compact">
                <div>
                    <span class="label">How We Work</span>
                    <h2 id="overview-title">Operational clarity without the usual event chaos.</h2>
                </div>
                <p>Strong event work depends on more than equipment. The delivery model has to stay coordinated across planning, venue setup, technical execution, and show-day decision making.</p>
            </div>

            <div class="overview-grid">
                @foreach ($operatingPillars as $pillar)
                    <article class="overview-card">
                        <strong>{{ $pillar['title'] }}</strong>
                        <p>{{ $pillar['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="section container" id="services">
            <div class="section-head">
                <div>
                    <span class="label">What We Do</span>
                    <h2>Production services built for reliable event delivery.</h2>
                </div>
                <p>Each service card below still uses your admin-managed content, but the layout now presents it in a cleaner commercial structure with better rhythm and clearer scanning.</p>
            </div>

            <div class="services">
                @foreach ($whatWeDo as $item)
                    @php
                        $background = $fallbacks[$loop->index % count($fallbacks)];
                    @endphp
                    <article class="card">
                        @if (!empty($item['image']))
                            <div class="card-media">
                                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}">
                                <span class="chip">{{ $item['icon'] }}</span>
                                <span class="index">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        @else
                            <div class="card-media card-fallback" style="background: {{ $background }};">
                                <span class="chip">{{ $item['icon'] }}</span>
                                <strong>{{ $item['title'] }}</strong>
                                <span class="index">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        @endif

                        <div class="card-body">
                            <h3>{{ $item['title'] }}</h3>
                            <p>{{ $item['text'] }}</p>
                            @if (!empty($item['link_url']))
                                <a class="link" href="{{ $item['link_url'] }}">Learn more</a>
                            @else
                                <span class="link static">Execution-ready support</span>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="section container" id="proof">
            <div class="proof">
                <article class="panel">
                    <div class="panel-copy">
                        <span class="label">Why PeakExperience</span>
                        <h3>Designed for moments where the room, the brand, and the timing all matter.</h3>
                        <p>Good event work is not just decor and equipment. It is audience flow, technical confidence, visual discipline, and crews who can keep delivery stable when the day gets noisy.</p>
                    </div>

                    <div class="sector-list" aria-label="Event types">
                        @foreach ($sectors as $sector)
                            <span>{{ $sector }}</span>
                        @endforeach
                    </div>
                </article>

                <aside class="advantage">
                    <article>
                        <strong>One production spine</strong>
                        <p>Creative direction, staging, media, and venue execution stay aligned under one delivery rhythm.</p>
                    </article>
                    <article>
                        <strong>Built for high-stakes rooms</strong>
                        <p>We design for launches, conferences, and branded experiences where timing and presentation quality matter.</p>
                    </article>
                    <article>
                        <strong>Clear decision points</strong>
                        <p>Our process removes guesswork so approvals, setup windows, and show-day transitions stay predictable.</p>
                    </article>
                </aside>
            </div>
        </section>

        <section class="section container process-section" id="process">
            <div class="section-head">
                <div>
                    <span class="label">Our Process</span>
                    <h2>Clear checkpoints from first brief to show day.</h2>
                </div>
                <p>The process section remains fully dynamic, but now it reads like a real production timeline with stronger sequencing and cleaner mobile behavior.</p>
            </div>

            <div class="process-grid">
                @foreach ($ourProcess as $step)
                    <article class="process-step">
                        <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3>{{ $step['title'] }}</h3>
                        <p>{{ $step['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="container" id="contact">
            <div class="cta">
                <div>
                    <span class="label">Next Step</span>
                    <h2>Bring the brief. We will shape the production path.</h2>
                    <p>Share the event type, city, venue context, audience size, and target date. That is enough to outline the right production approach and the next planning priorities.</p>
                    <div class="cta-list" aria-label="Brief checklist">
                        <span>Event type</span>
                        <span>Venue or city</span>
                        <span>Audience size</span>
                        <span>Target date</span>
                    </div>
                    <p class="cta-note">Ideal for conferences, launches, exhibitions, gala setups, and hybrid events.</p>
                </div>

                <div class="cta-actions">
                    <a href="#services" class="btn btn-primary">Review Services</a>
                    @if ($hasContactEmail)
                        <a href="mailto:{{ $contactEmail }}" class="btn btn-secondary">Send Your Brief</a>
                    @else
                        <a href="#top" class="btn btn-secondary">Back To Top</a>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-brand">
                <strong>PeakExperience</strong>
                <span>Event production, staging, media, and exhibition support for live experiences in Kenya.</span>
            </div>

            <nav class="footer-links" aria-label="Footer">
                <a href="#services">Services</a>
                <a href="#proof">Why Us</a>
                <a href="#process">Process</a>
                <a href="#contact">Next Step</a>
                @if ($hasContactEmail)
                    <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                @endif
            </nav>
        </div>
    </footer>
</body>
</html>
