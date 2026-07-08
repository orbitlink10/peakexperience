<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin | Peak Experience')</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="admin-skin min-h-screen text-slate-900 antialiased">
    <div class="admin-layout">
        <aside class="main-sidebar">
            <div class="sticky-sidebar">
                <div class="sidebar">
                    <div class="user-panel sticky-panel">
                        <a href="{{ route('home') }}" class="text-decoration-none">
                            <h3>Peak Experience</h3>
                        </a>
                    </div>

                    <nav class="admin-nav" aria-label="Admin navigation">
                        <ul class="nav-sidebar">
                            @foreach ($sidebarItems as $item)
                                @php
                                    $itemIcon = match ($item['key']) {
                                        'overview' => 'fa-tachometer-alt',
                                        'services' => 'fa-tools',
                                        'case-study' => 'fa-briefcase',
                                        'posts' => 'fa-newspaper',
                                        'team' => 'fa-users',
                                        'gallery' => 'fa-photo-video',
                                        'sliders' => 'fa-images',
                                        'clients' => 'fa-user-friends',
                                        'invoices' => 'fa-file-invoice',
                                        'videos' => 'fa-video',
                                        'pages' => 'fa-edit',
                                        default => 'fa-circle',
                                    };
                                @endphp

                                @if ($loop->first)
                                    <li class="nav-item">
                                        <a href="{{ $item['href'] }}" class="nav-link {{ $item['active'] ? 'active' : '' }}" @if ($item['active'] && empty($item['children'])) aria-current="page" @endif>
                                            <i class="nav-icon fas {{ $itemIcon }}"></i>
                                            <p>{{ $item['label'] }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-header">Content Management</li>
                                @else
                                    <li class="nav-item">
                                        <a href="{{ $item['href'] }}" class="nav-link {{ $item['active'] ? 'active' : '' }}" @if ($item['active'] && empty($item['children'])) aria-current="page" @endif>
                                            <i class="nav-icon fas {{ $itemIcon }}"></i>
                                            <p>{{ $item['label'] }}</p>
                                        </a>

                                        @if (! empty($item['children']))
                                            <ul class="nav-treeview">
                                                @foreach ($item['children'] as $child)
                                                    @php
                                                        $childIcon = $child['key'] === 'homepage' ? 'fa-file-alt' : 'fa-envelope';
                                                    @endphp
                                                    <li class="nav-item">
                                                        <a href="{{ $child['href'] }}" class="nav-link nav-child {{ $child['active'] ? 'active' : '' }}" @if ($child['active']) aria-current="page" @endif>
                                                            <i class="nav-icon fas {{ $childIcon }}"></i>
                                                            <p>{{ $child['label'] }}</p>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </nav>

                    <form method="POST" action="{{ route('admin.logout') }}" class="sidebar-logout">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="content-wrapper">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
