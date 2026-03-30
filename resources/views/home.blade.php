<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PeakExperience | Event Planning in Kenya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #1f2530;
            --slate: #2f3a4a;
            --paper: #ffffff;
            --ember: #d0692b;
            --forest: #1f5c4d;
            --line: #d7d8d9;
            --shadow: 0 20px 45px rgba(21, 29, 36, 0.16);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Manrope', sans-serif;
            color: var(--ink);
            background: linear-gradient(180deg, #f7f4ee 0%, #ffffff 30%, #f8f9fa 100%);
        }

        .announcement {
            background: #282a2f;
            color: #fff;
            text-align: center;
            padding: 0.9rem 1rem;
            font-weight: 600;
            letter-spacing: 0.04em;
        }

        .announcement a {
            color: #ffd9bf;
            text-decoration: none;
            border-bottom: 1px solid rgba(255, 217, 191, 0.5);
        }

        header {
            background: rgba(255, 255, 255, 0.97);
            border-bottom: 1px solid #ececec;
            position: sticky;
            top: 0;
            z-index: 5;
            backdrop-filter: blur(7px);
        }

        .container {
            width: min(1200px, 92vw);
            margin: 0 auto;
        }

        .nav-shell {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            min-height: 86px;
        }

        .brand {
            min-width: 210px;
        }

        .brand .kicker {
            font-size: 0.77rem;
            letter-spacing: 0.24em;
            text-transform: uppercase;
            color: var(--ember);
            font-weight: 700;
        }

        .brand .title {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: clamp(2rem, 3vw, 2.7rem);
            line-height: 0.9;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        nav {
            display: flex;
            flex: 1;
            justify-content: center;
            gap: clamp(0.8rem, 2vw, 2rem);
            flex-wrap: wrap;
        }

        nav a {
            text-decoration: none;
            color: #2f3640;
            font-weight: 600;
            font-size: 0.98rem;
            white-space: nowrap;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .cta {
            border: 1px solid var(--ink);
            padding: 0.76rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            color: var(--ink);
            font-weight: 700;
            background: #fff;
        }

        .search {
            display: flex;
            align-items: center;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 0 0.8rem;
            min-width: 215px;
            background: #fff;
        }

        .search input {
            border: 0;
            outline: none;
            font-family: inherit;
            font-size: 0.95rem;
            padding: 0.72rem 0;
            width: 100%;
            background: transparent;
        }

        .hero {
            min-height: calc(100vh - 150px);
            background:
                linear-gradient(100deg, rgba(16, 24, 33, 0.76) 20%, rgba(16, 24, 33, 0.35) 56%, rgba(16, 24, 33, 0.05) 100%),
                url('https://images.unsplash.com/photo-1608496601160-80e4f0f15719?auto=format&fit=crop&w=2100&q=80') center/cover no-repeat;
            display: flex;
            align-items: flex-end;
            padding: min(6.5vw, 5rem) 0;
        }

        .hero-content {
            width: min(720px, 90vw);
            color: #fff;
        }

        .hero-label {
            display: inline-block;
            background: rgba(217, 105, 43, 0.9);
            padding: 0.45rem 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            font-size: 0.74rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero h1 {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: clamp(2.5rem, 7vw, 5rem);
            line-height: 0.95;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: clamp(1rem, 2vw, 1.2rem);
            max-width: 620px;
            line-height: 1.65;
            margin-bottom: 2rem;
            color: rgba(255, 255, 255, 0.92);
        }

        .hero-actions {
            display: flex;
            gap: 0.9rem;
            flex-wrap: wrap;
        }

        .btn {
            text-decoration: none;
            font-weight: 700;
            padding: 0.85rem 1.25rem;
            border-radius: 9px;
            color: #fff;
        }

        .btn-primary {
            background: var(--ember);
        }

        .btn-secondary {
            border: 1px solid rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.08);
        }

        .stats {
            background: #fff;
            border-radius: 14px;
            box-shadow: var(--shadow);
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
            padding: 1.3rem;
            margin-top: -2.6rem;
            position: relative;
            z-index: 2;
        }

        .stat h3 {
            font-size: clamp(1.5rem, 4vw, 2rem);
            color: var(--forest);
            margin-bottom: 0.4rem;
        }

        .stat p {
            color: #5a6572;
            font-size: 0.93rem;
        }

        main {
            padding: 4.3rem 0;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .section-head h2 {
            font-family: 'Barlow Condensed', sans-serif;
            text-transform: uppercase;
            font-size: clamp(1.95rem, 5vw, 3.1rem);
            color: #1e2633;
        }

        .section-head p {
            color: #5c6672;
            max-width: 580px;
            line-height: 1.6;
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 4rem;
        }

        .service-card {
            background: linear-gradient(160deg, #fff 0%, #f7f7f7 100%);
            border: 1px solid #ededed;
            border-radius: 14px;
            overflow: hidden;
        }

        .service-image {
            width: 100%;
            height: 190px;
            object-fit: cover;
            display: block;
            border-bottom: 1px solid #ececec;
        }

        .service-body {
            padding: 1rem 1.1rem 1.2rem;
        }

        .service-icon {
            font-size: 0.76rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            font-weight: 800;
            color: var(--ember);
            margin-bottom: 0.35rem;
        }

        .service-card h3 {
            margin-bottom: 0.55rem;
            color: #1f2631;
            font-size: 1.2rem;
        }

        .service-card p {
            color: #596575;
            line-height: 1.65;
            font-size: 0.94rem;
            margin-bottom: 0.75rem;
        }

        .service-card a {
            color: var(--forest);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .process-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.9rem;
            margin-bottom: 4rem;
        }

        .process-step {
            background: #fff;
            border: 1px solid #eceff1;
            border-radius: 12px;
            padding: 1.2rem;
            position: relative;
        }

        .process-index {
            position: absolute;
            top: 0.8rem;
            right: 0.9rem;
            font-size: 0.88rem;
            color: #88a;
            font-weight: 700;
        }

        .process-step h3 {
            color: #1e2633;
            margin-bottom: 0.55rem;
            font-size: 1.15rem;
        }

        .process-step p {
            color: #5d6775;
            font-size: 0.93rem;
            line-height: 1.6;
        }

        .final-banner {
            border-radius: 14px;
            background: linear-gradient(115deg, #1c2837, #1f5c4d);
            color: #fff;
            padding: clamp(1.4rem, 4vw, 2.4rem);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .final-banner h3 {
            font-family: 'Barlow Condensed', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            font-size: clamp(1.8rem, 4vw, 2.4rem);
            margin-bottom: 0.45rem;
        }

        .final-banner p {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            max-width: 630px;
        }

        footer {
            padding: 2rem 0 3.3rem;
            color: #5d6775;
            text-align: center;
            font-size: 0.93rem;
        }

        @media (max-width: 1080px) {
            .nav-shell {
                flex-wrap: wrap;
                justify-content: center;
                padding: 1rem 0;
            }

            .brand,
            nav,
            .header-actions {
                width: 100%;
                justify-content: center;
                text-align: center;
            }

            .search {
                max-width: 310px;
                width: 100%;
            }

            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .process-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 760px) {
            .hero {
                min-height: 520px;
                align-items: center;
            }

            .service-grid,
            .process-grid,
            .stats {
                grid-template-columns: 1fr;
            }

            .section-head {
                align-items: flex-start;
                flex-direction: column;
            }

            .cta {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="announcement">
        <a href="#">ENTER YOUR DOORWAY TO EXTRAORDINARY EVENTS IN KENYA</a>
    </div>

    <header>
        <div class="container nav-shell">
            <div class="brand">
                <div class="kicker">Discover</div>
                <div class="title">PeakExperience</div>
            </div>

            <nav>
                <a href="#">Weddings</a>
                <a href="#">Corporate Events</a>
                <a href="#">Conferences</a>
                <a href="#">Destinations</a>
                <a href="#">Vendors</a>
                <a href="#">Blog</a>
            </nav>

            <div class="header-actions">
                <a class="cta" href="#">Plan Your Event</a>
                <label class="search" aria-label="Search">
                    <input type="text" placeholder="Search venues or services...">
                    <span>&#128269;</span>
                </label>
            </div>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <span class="hero-label">Kenya Event Planners</span>
                <h1>Professional Event Planning Across Kenya</h1>
                <p>
                    From Nairobi boardrooms to Diani beachfront ceremonies, PeakExperience designs and executes premium events with local insight, trusted vendors, and detail-first coordination.
                </p>
                <div class="hero-actions">
                    <a href="#" class="btn btn-primary">Get a Custom Proposal</a>
                    <a href="#" class="btn btn-secondary">View Event Portfolio</a>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <section class="stats">
            <article class="stat">
                <h3>300+</h3>
                <p>Events delivered across Kenya since 2019</p>
            </article>
            <article class="stat">
                <h3>47</h3>
                <p>Partner venues in Nairobi, Naivasha, Mombasa and beyond</p>
            </article>
            <article class="stat">
                <h3>98%</h3>
                <p>Client satisfaction from post-event survey reports</p>
            </article>
            <article class="stat">
                <h3>24/7</h3>
                <p>Production and logistics support during event week</p>
            </article>
        </section>
    </div>

    <main>
        <section class="container">
            <div class="section-head">
                <h2>What We Do</h2>
                <p>Explore our core service pillars tailored for conferences, launches, weddings, and destination experiences in Kenya.</p>
            </div>

            <div class="service-grid">
                @foreach ($whatWeDo as $item)
                    <article class="service-card">
                        @if (!empty($item['image']))
                            <img class="service-image" src="{{ $item['image'] }}" alt="{{ $item['title'] }}">
                        @endif
                        <div class="service-body">
                            <p class="service-icon">{{ $item['icon'] }}</p>
                            <h3>{{ $item['title'] }}</h3>
                            <p>{{ $item['text'] }}</p>
                            @if (!empty($item['link_url']))
                                <a href="{{ $item['link_url'] }}">Learn More</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="section-head">
                <h2>Our Process</h2>
                <p>A simple framework that keeps planning clear from first conversation to post-event review.</p>
            </div>

            <div class="process-grid">
                @foreach ($ourProcess as $index => $step)
                    <article class="process-step">
                        <span class="process-index">0{{ $index + 1 }}</span>
                        <h3>{{ $step['title'] }}</h3>
                        <p>{{ $step['text'] }}</p>
                    </article>
                @endforeach
            </div>

            <div class="final-banner">
                <div>
                    <h3>Ready To Host A Standout Event?</h3>
                    <p>Share your event date, city, and goals. Our planning team will send a tailored concept and execution plan within 48 hours.</p>
                </div>
                <a href="#" class="btn btn-primary">Book Consultation</a>
            </div>
        </section>
    </main>

    <footer>
        PeakExperience Event Planning | Nairobi, Kenya | +254 700 000 000
    </footer>
</body>
</html>
