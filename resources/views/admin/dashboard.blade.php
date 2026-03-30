<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gallery Dashboard | PeakExperience</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --page-bg: #f2f4f8;
            --paper: #ffffff;
            --ink: #0f1b33;
            --muted: #4b5f7e;
            --line: #d8dfeb;
            --brand: #e49700;
            --danger: #ef7a84;
            --navy: #1a2440;
            --navy-2: #202e4f;
            --shadow: 0 20px 36px rgba(15, 27, 51, 0.08);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            background: var(--page-bg);
            color: var(--ink);
            font-family: 'Manrope', sans-serif;
        }

        .site-header {
            background: var(--paper);
            border-bottom: 1px solid var(--line);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .site-header-inner {
            width: min(1780px, 94vw);
            min-height: 96px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.3rem;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            text-decoration: none;
            color: var(--ink);
            white-space: nowrap;
        }

        .brand-icon {
            width: 52px;
            height: 52px;
            border-radius: 10px;
            border: 3px solid var(--brand);
            color: var(--brand);
            display: grid;
            place-items: center;
            font-weight: 800;
            font-size: 1.45rem;
            line-height: 1;
        }

        .brand-word {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            line-height: 0.9;
        }

        .brand-sub {
            display: block;
            font-size: 0.72rem;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: #7e8da8;
            font-weight: 700;
            margin-top: 0.35rem;
        }

        .top-nav {
            display: flex;
            align-items: center;
            gap: clamp(0.8rem, 1.6vw, 1.9rem);
            flex-wrap: wrap;
            justify-content: center;
        }

        .top-nav a {
            text-decoration: none;
            color: #3f516d;
            font-weight: 600;
            font-size: 0.94rem;
        }

        .top-nav a:hover,
        .top-nav a.active {
            color: #1b2d49;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            white-space: nowrap;
        }

        .quote-btn {
            text-decoration: none;
            color: #fff;
            background: var(--brand);
            border-radius: 12px;
            padding: 0.9rem 1.35rem;
            font-weight: 800;
            box-shadow: 0 10px 20px rgba(228, 151, 0, 0.25);
        }

        .dashboard-badge {
            color: #2f4263;
            font-weight: 700;
            font-size: 1rem;
        }

        .app-shell {
            width: min(1780px, 94vw);
            margin: 2rem auto;
            display: grid;
            grid-template-columns: 420px 1fr;
            gap: 2rem;
            align-items: start;
        }

        .sidebar {
            background: linear-gradient(170deg, var(--navy) 0%, #18253f 100%);
            border-radius: 20px;
            color: #dbe7ff;
            padding: 1.4rem;
            box-shadow: var(--shadow);
            position: sticky;
            top: 116px;
        }

        .sidebar-title {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            color: #8fa5c8;
            margin-bottom: 1rem;
        }

        .menu {
            list-style: none;
            display: grid;
            gap: 0.35rem;
            max-height: calc(100vh - 280px);
            overflow-y: auto;
            padding-right: 0.2rem;
        }

        .menu li a {
            display: block;
            text-decoration: none;
            color: #c8d6f1;
            padding: 0.88rem 1rem;
            border-radius: 11px;
            font-size: 1.05rem;
            font-weight: 600;
        }

        .menu li a:hover {
            background: #233153;
            color: #fff;
        }

        .menu li a.active {
            background: #223153;
            color: #fff;
        }

        .sidebar-foot {
            border-top: 1px solid rgba(219, 231, 255, 0.15);
            margin-top: 1rem;
            padding-top: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.7rem;
        }

        .admin-meta {
            font-size: 0.82rem;
            color: #aebddd;
        }

        .logout-btn {
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 9px;
            color: #fff;
            background: transparent;
            padding: 0.48rem 0.8rem;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.84rem;
        }

        .content {
            min-width: 0;
        }

        .status-banner {
            border: 1px solid #cdd8e7;
            background: #ecf2fb;
            color: #314b73;
            border-radius: 12px;
            padding: 0.75rem 0.9rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.35rem;
        }

        .page-head h1 {
            font-size: clamp(2rem, 3.4vw, 3rem);
            margin-bottom: 0.3rem;
            line-height: 1;
        }

        .page-head p {
            color: #48607f;
            font-size: 1.04rem;
        }

        .add-btn {
            text-decoration: none;
            background: var(--brand);
            color: #fff;
            font-weight: 800;
            padding: 0.84rem 1.18rem;
            border-radius: 11px;
            box-shadow: 0 10px 20px rgba(228, 151, 0, 0.25);
            margin-top: 0.35rem;
            display: inline-block;
        }

        .gallery-frame {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .tip-bar {
            padding: 1rem 1.4rem;
            color: #60789a;
            border-bottom: 1px solid var(--line);
            background: #fafcff;
            font-size: 0.97rem;
        }

        .action-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.8rem;
            padding: 0.9rem 1.4rem;
            border-bottom: 1px solid var(--line);
        }

        .select-all {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            color: #334f73;
            font-size: 1.02rem;
            font-weight: 600;
        }

        .select-all input {
            width: 23px;
            height: 23px;
            accent-color: #27456e;
            cursor: pointer;
        }

        .delete-btn {
            background: var(--danger);
            color: #fff;
            border: 0;
            border-radius: 11px;
            padding: 0.7rem 1rem;
            font-weight: 800;
            cursor: pointer;
            font-size: 1rem;
        }

        .gallery-grid {
            padding: 1.3rem;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
        }

        .card {
            position: relative;
            border-radius: 14px;
            overflow: hidden;
            min-height: 238px;
            background: #d8dde9;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.2);
        }

        .card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .card-check {
            position: absolute;
            top: 0.7rem;
            left: 0.7rem;
            z-index: 2;
            width: 22px;
            height: 22px;
            accent-color: #2c4a72;
        }

        .card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(10, 18, 32, 0) 58%, rgba(10, 18, 32, 0.8));
        }

        .caption {
            position: absolute;
            left: 0.7rem;
            right: 0.7rem;
            bottom: 0.65rem;
            color: #fff;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 0.5rem;
            font-size: 0.82rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .caption .title {
            max-width: 85%;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .caption .index {
            opacity: 0.95;
            white-space: nowrap;
        }

        @media (max-width: 1480px) {
            .app-shell {
                grid-template-columns: 300px 1fr;
                gap: 1.25rem;
            }

            .gallery-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 1120px) {
            .site-header-inner {
                align-items: flex-start;
                padding: 0.9rem 0;
                min-height: auto;
                flex-wrap: wrap;
            }

            .top-nav {
                width: 100%;
                justify-content: flex-start;
                overflow-x: auto;
                flex-wrap: nowrap;
                padding-bottom: 0.25rem;
            }

            .app-shell {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
            }

            .menu {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                max-height: none;
            }

            .gallery-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 780px) {
            .page-head {
                flex-direction: column;
            }

            .header-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .menu {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .gallery-grid {
                grid-template-columns: 1fr;
            }

            .action-row {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
<header class="site-header">
    <div class="site-header-inner">
        <a class="brand" href="{{ url('/') }}">
            <span class="brand-icon">8</span>
            <span>
                <span class="brand-word">peak audio</span>
                <span class="brand-sub">Excellent Clarity</span>
            </span>
        </a>

        <nav class="top-nav" aria-label="Top navigation">
            @foreach ($publicNav as $item)
                <a href="{{ $item['href'] }}" class="{{ $item['label'] === 'Home' ? 'active' : '' }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="header-actions">
            <a href="#" class="quote-btn">Get a Quote</a>
            <span class="dashboard-badge">Dashboard</span>
        </div>
    </div>
</header>

<main class="app-shell">
    <aside class="sidebar">
        <h2 class="sidebar-title">ADMIN</h2>

        <ul class="menu">
            @foreach ($sidebarItems as $item)
                <li>
                    <a href="{{ $item['href'] }}" class="{{ $item['active'] ? 'active' : '' }}">{{ $item['label'] }}</a>
                </li>
            @endforeach
        </ul>

        <div class="sidebar-foot">
            <p class="admin-meta">Logged in: {{ $adminName }}<br>{{ $today }}</p>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </aside>

    <section class="content">
        @if (session('status'))
            <div class="status-banner">{{ session('status') }}</div>
        @endif

        <header class="page-head">
            <div>
                <h1>Gallery</h1>
                <p>Manage images displayed on the Gallery page.</p>
            </div>
            <a href="#" class="add-btn">Add Image</a>
        </header>

        <section class="gallery-frame">
            <div class="tip-bar">Tip: drag cards to reorder. Order saves automatically.</div>

            <div class="action-row">
                <label class="select-all">
                    <input type="checkbox" name="select-all">
                    <span>Select all</span>
                </label>
                <button class="delete-btn" type="button">Delete selected</button>
            </div>

            <div class="gallery-grid">
                @foreach ($galleryItems as $index => $item)
                    <article class="card">
                        <input type="checkbox" class="card-check" name="gallery-item-{{ $index + 1 }}">
                        <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}">
                        <div class="caption">
                            <span class="title">{{ $item['title'] }}</span>
                            <span class="index">#{{ $index + 1 }}</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </section>
</main>
</body>
</html>
