<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Homepage Editor | PeakExperience</title>
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
            min-height: 112px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.3rem;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: var(--ink);
            white-space: nowrap;
        }

        .brand-icon {
            width: 66px;
            height: 66px;
            border-radius: 12px;
            border: 4px solid var(--brand);
            color: var(--brand);
            display: grid;
            place-items: center;
            font-weight: 800;
            font-size: 1.85rem;
            line-height: 1;
        }

        .brand-word {
            font-size: clamp(2.35rem, 1.2rem + 1.8vw, 2.8rem);
            font-weight: 700;
            letter-spacing: 0.01em;
            line-height: 0.88;
        }

        .brand-sub {
            display: block;
            font-size: 0.82rem;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: #7e8da8;
            font-weight: 700;
            margin-top: 0.42rem;
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

        .error-banner {
            border: 1px solid #f1ccd0;
            background: #fff3f4;
            color: #9a2f3a;
            border-radius: 12px;
            padding: 0.75rem 0.9rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            display: grid;
            gap: 0.25rem;
        }

        .page-title {
            font-size: clamp(2rem, 3.6vw, 3rem);
            margin-bottom: 0.3rem;
            line-height: 1;
        }

        .page-subtitle {
            color: #3f5c82;
            font-size: 1.03rem;
            margin-bottom: 1.8rem;
        }

        .section-title {
            font-size: clamp(1.45rem, 2vw, 2.1rem);
            margin-bottom: 0.4rem;
        }

        .section-subtitle {
            color: #3f5c82;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .mini-btn {
            border: 1px solid #b7c4d8;
            border-radius: 10px;
            background: #eef2f9;
            color: #1f3558;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.58rem 1rem;
            cursor: pointer;
            margin-bottom: 1.15rem;
        }

        .list {
            display: grid;
            gap: 1rem;
            margin-bottom: 1.2rem;
        }

        .card {
            border: 1px solid var(--line);
            background: #f7f9fc;
            border-radius: 18px;
            padding: 1rem;
        }

        .fields {
            display: grid;
            grid-template-columns: 1fr 0.45fr 1.2fr;
            gap: 1rem;
            align-items: end;
            margin-bottom: 0.9rem;
        }

        .fields.two {
            grid-template-columns: 1fr 1fr;
        }

        .field label {
            display: block;
            font-size: 0.95rem;
            font-weight: 600;
            color: #2c476d;
            margin-bottom: 0.35rem;
        }

        .field input,
        .field textarea,
        .field select {
            width: 100%;
            border: 1px solid #b7c4d8;
            border-radius: 10px;
            padding: 0.73rem 0.8rem;
            font: inherit;
            color: var(--ink);
            background: #ffffff;
        }

        .field textarea {
            resize: vertical;
            min-height: 62px;
        }

        .remove-btn {
            border: 1px solid #f2a4ab;
            color: #d90e1d;
            background: #fff;
            border-radius: 10px;
            padding: 0.52rem 1rem;
            font-weight: 700;
            cursor: pointer;
            font-size: 0.95rem;
            margin-top: 0.35rem;
        }

        .save-btn {
            border: 0;
            background: var(--brand);
            color: #fff;
            border-radius: 12px;
            font-weight: 800;
            font-size: 1.05rem;
            padding: 0.86rem 1.8rem;
            cursor: pointer;
            box-shadow: 0 10px 22px rgba(228, 151, 0, 0.25);
            margin-top: 0.75rem;
        }

        .divider {
            border-top: 1px solid #d6deea;
            margin: 1.2rem 0;
        }

        .current-image {
            display: block;
            margin-top: 0.35rem;
            color: #4d6182;
            font-size: 0.85rem;
            overflow-wrap: anywhere;
        }

        .logo-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(220px, 0.85fr);
            gap: 1rem;
            align-items: start;
        }

        .logo-preview-wrap {
            border: 1px dashed #b7c4d8;
            border-radius: 14px;
            background: #fff;
            padding: 1rem;
            min-height: 180px;
            display: grid;
            place-items: center;
            gap: 0.75rem;
        }

        .logo-preview-wrap img {
            max-width: 100%;
            max-height: 120px;
            object-fit: contain;
        }

        .logo-preview-empty {
            color: #6e83a3;
            font-size: 0.92rem;
            text-align: center;
            line-height: 1.6;
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            margin-top: 0.85rem;
            color: #2c476d;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .checkbox-row input {
            width: auto;
        }

        @media (max-width: 1480px) {
            .app-shell {
                grid-template-columns: 300px 1fr;
                gap: 1.25rem;
            }
        }

        @media (max-width: 1120px) {
            .site-header-inner {
                align-items: flex-start;
                padding: 0.9rem 0;
                min-height: auto;
                flex-wrap: wrap;
            }

            .brand-icon {
                width: 58px;
                height: 58px;
                font-size: 1.65rem;
            }

            .brand-word {
                font-size: 2.2rem;
            }

            .brand-sub {
                font-size: 0.76rem;
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

            .fields {
                grid-template-columns: 1fr;
            }

            .fields.two {
                grid-template-columns: 1fr;
            }

            .logo-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 780px) {
            .brand {
                gap: 0.8rem;
            }

            .brand-icon {
                width: 52px;
                height: 52px;
                font-size: 1.45rem;
            }

            .brand-word {
                font-size: 1.95rem;
            }

            .brand-sub {
                font-size: 0.7rem;
            }

            .header-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .menu {
                grid-template-columns: repeat(2, minmax(0, 1fr));
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

        @if ($errors->any())
            <div class="error-banner">
                @foreach ($errors->all() as $error)
                    <span>{{ $error }}</span>
                @endforeach
            </div>
        @endif

        @php
            $logoValue = old('logo', $logo);
            $currentLogoUrl = \App\Support\HomepageContent::assetUrl(
                (string) data_get($logoValue ?? [], 'path', data_get($logoValue ?? [], 'url', ''))
            );
            $whatWeDoValues = old('what_we_do', $whatWeDo);
            $ourProcessValues = old('our_process', $ourProcess);
        @endphp

        <h1 class="page-title">Homepage</h1>
        <p class="page-subtitle">Manage the homepage logo, What We Do cards, and Our Process steps.</p>

        <form method="POST" action="{{ route('admin.homepage.update') }}" enctype="multipart/form-data" id="homepage-form">
            @csrf

            <section>
                <h2 class="section-title">Logo</h2>
                <p class="section-subtitle">Upload the brand logo used for the homepage header and hero. Prefer a transparent PNG or SVG-like artwork exported as PNG.</p>

                <div class="card">
                    <div class="logo-grid">
                        <div class="field">
                            <label>Upload Logo</label>
                            <input type="file" name="logo_file" accept="image/*">
                            <label class="checkbox-row">
                                <input type="checkbox" name="logo_remove" value="1" @checked(old('logo_remove'))>
                                <span>Remove current logo</span>
                            </label>
                        </div>

                        <div class="logo-preview-wrap">
                            @if ($currentLogoUrl !== '')
                                <img src="{{ $currentLogoUrl }}" alt="Current homepage logo">
                                <small class="current-image">Current: {{ $currentLogoUrl }}</small>
                            @else
                                <p class="logo-preview-empty">No homepage logo uploaded yet. The site will continue using the text fallback until a logo is added.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <div class="divider"></div>

            <section>
                <h2 class="section-title">What We Do</h2>
                <p class="section-subtitle">These appear as cards on the homepage. Add, remove, and reorder as needed.</p>

                <button type="button" class="mini-btn" id="add-what-item">Add Item</button>

                <div class="list" id="what-we-do-list" data-list="what_we_do">
                    @foreach ($whatWeDoValues as $index => $item)
                        <article class="card" data-item>
                            <div class="fields">
                                <div class="field">
                                    <label>Title</label>
                                    <input type="text" value="{{ $item['title'] ?? '' }}" data-name="title" required>
                                </div>
                                <div class="field">
                                    <label>Icon</label>
                                    <select data-name="icon" required>
                                        @foreach ($iconOptions as $option)
                                            <option value="{{ $option }}" @selected(($item['icon'] ?? '') === $option)>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <label>Text</label>
                                    <input type="text" value="{{ $item['text'] ?? '' }}" data-name="text" required>
                                </div>
                            </div>

                            <div class="fields two">
                                <div class="field">
                                    <label>Link URL (optional)</label>
                                    <input type="url" value="{{ $item['link_url'] ?? '' }}" placeholder="https://..." data-name="link_url">
                                </div>
                                <div class="field">
                                    <label>Image (optional)</label>
                                    <input type="file" accept="image/*" data-name="image_file">
                                    @if (!empty($item['image']))
                                        <small class="current-image">Current: {{ $item['image'] }}</small>
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" value="{{ $item['image'] ?? '' }}" data-name="image">
                            <button type="button" class="remove-btn" data-remove>Remove</button>
                        </article>
                    @endforeach
                </div>
            </section>

            <div class="divider"></div>

            <section>
                <h2 class="section-title">Our Process</h2>
                <p class="section-subtitle">Add or edit the four high-level steps shown on the homepage.</p>

                <button type="button" class="mini-btn" id="add-process-step">Add Step</button>

                <div class="list" id="our-process-list" data-list="our_process">
                    @foreach ($ourProcessValues as $index => $step)
                        <article class="card" data-item>
                            <div class="fields two">
                                <div class="field">
                                    <label>Step Title</label>
                                    <input type="text" value="{{ $step['title'] ?? '' }}" data-name="title" required>
                                </div>
                                <div class="field">
                                    <label>Step Text</label>
                                    <input type="text" value="{{ $step['text'] ?? '' }}" data-name="text" required>
                                </div>
                            </div>

                            <button type="button" class="remove-btn" data-remove>Remove</button>
                        </article>
                    @endforeach
                </div>
            </section>

            <button type="submit" class="save-btn">Save Homepage</button>
        </form>
    </section>
</main>

<template id="what-item-template">
    <article class="card" data-item>
        <div class="fields">
            <div class="field">
                <label>Title</label>
                <input type="text" data-name="title" required>
            </div>
            <div class="field">
                <label>Icon</label>
                <select data-name="icon" required>
                    @foreach ($iconOptions as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>Text</label>
                <input type="text" data-name="text" required>
            </div>
        </div>

        <div class="fields two">
            <div class="field">
                <label>Link URL (optional)</label>
                <input type="url" placeholder="https://..." data-name="link_url">
            </div>
            <div class="field">
                <label>Image (optional)</label>
                <input type="file" accept="image/*" data-name="image_file">
            </div>
        </div>

        <input type="hidden" value="" data-name="image">
        <button type="button" class="remove-btn" data-remove>Remove</button>
    </article>
</template>

<template id="process-step-template">
    <article class="card" data-item>
        <div class="fields two">
            <div class="field">
                <label>Step Title</label>
                <input type="text" data-name="title" required>
            </div>
            <div class="field">
                <label>Step Text</label>
                <input type="text" data-name="text" required>
            </div>
        </div>

        <button type="button" class="remove-btn" data-remove>Remove</button>
    </article>
</template>

<script>
    (function () {
        const whatList = document.getElementById('what-we-do-list');
        const processList = document.getElementById('our-process-list');
        const whatTemplate = document.getElementById('what-item-template');
        const processTemplate = document.getElementById('process-step-template');

        function reindex(list) {
            const prefix = list.dataset.list;
            list.querySelectorAll('[data-item]').forEach((item, itemIndex) => {
                item.querySelectorAll('[data-name]').forEach((field) => {
                    const key = field.dataset.name;
                    field.name = `${prefix}[${itemIndex}][${key}]`;
                });
            });
        }

        function attachRemoveHandlers(scope) {
            scope.querySelectorAll('[data-remove]').forEach((button) => {
                if (button.dataset.bound === '1') {
                    return;
                }

                button.dataset.bound = '1';
                button.addEventListener('click', () => {
                    const list = button.closest('[data-list]');
                    const item = button.closest('[data-item]');

                    if (!list || !item) {
                        return;
                    }

                    if (list.querySelectorAll('[data-item]').length <= 1) {
                        return;
                    }

                    item.remove();
                    reindex(list);
                });
            });
        }

        document.getElementById('add-what-item').addEventListener('click', () => {
            const fragment = whatTemplate.content.cloneNode(true);
            whatList.appendChild(fragment);
            attachRemoveHandlers(whatList);
            reindex(whatList);
        });

        document.getElementById('add-process-step').addEventListener('click', () => {
            const fragment = processTemplate.content.cloneNode(true);
            processList.appendChild(fragment);
            attachRemoveHandlers(processList);
            reindex(processList);
        });

        attachRemoveHandlers(document);
        reindex(whatList);
        reindex(processList);
    })();
</script>
</body>
</html>
