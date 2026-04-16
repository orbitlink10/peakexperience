<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin | Peak Experience')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.12),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(59,130,246,0.08),_transparent_22%)]">
        <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
            <div class="mx-auto flex max-w-[1600px] items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('admin.gallery') }}" class="flex min-w-0 items-center gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-950 text-sm font-black tracking-[0.24em] text-white">PE</span>
                    <span class="min-w-0">
                        <span class="block truncate text-base font-semibold text-slate-950 sm:text-lg">Peak Experience</span>
                        <span class="block truncate text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-500">Admin Workspace</span>
                    </span>
                </a>

                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" class="admin-btn-secondary hidden sm:inline-flex">Open Site</a>
                    <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-amber-700">
                        @yield('badge', 'Dashboard')
                    </span>
                </div>
            </div>
        </header>

        <main class="mx-auto grid max-w-[1600px] gap-6 px-4 py-6 sm:px-6 lg:grid-cols-[300px_minmax(0,1fr)] lg:px-8">
            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div class="overflow-hidden rounded-2xl bg-slate-950 text-slate-100 shadow-xl shadow-slate-950/10 ring-1 ring-white/10">
                    <div class="border-b border-white/10 px-5 py-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Control Center</p>
                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-white">Admin</h2>
                    </div>

                    <nav class="px-3 py-3">
                        <ul class="space-y-1">
                            @foreach ($sidebarItems as $item)
                                @php
                                    $isPlaceholder = $item['href'] === '#';
                                    $linkClasses = $item['active']
                                        ? 'bg-white/10 text-white'
                                        : ($isPlaceholder
                                            ? 'cursor-default text-slate-500'
                                            : 'text-slate-300 hover:bg-white/5 hover:text-white');
                                @endphp
                                <li>
                                    <a
                                        href="{{ $item['href'] }}"
                                        @if ($isPlaceholder) aria-disabled="true" @endif
                                        class="block rounded-xl px-4 py-3 text-sm font-medium transition {{ $linkClasses }}"
                                    >
                                        {{ $item['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>

                    <div class="border-t border-white/10 px-5 py-4">
                        <p class="text-sm leading-6 text-slate-300">
                            Logged in as <span class="font-semibold text-white">{{ $adminName }}</span><br>
                            <span class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $today }}</span>
                        </p>

                        <form method="POST" action="{{ route('admin.logout') }}" class="mt-4">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10 focus:outline-none focus:ring-4 focus:ring-white/15"
                            >
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <section class="min-w-0">
                @yield('content')
            </section>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
