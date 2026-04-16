<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | Peak Experience</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.16),_transparent_28%),radial-gradient(circle_at_bottom_right,_rgba(15,23,42,0.16),_transparent_26%)] px-4 py-10">
        <main class="mx-auto grid max-w-6xl overflow-hidden rounded-[28px] border border-white/60 bg-white shadow-2xl shadow-slate-950/10 lg:grid-cols-[1.05fr_0.95fr]">
            <section class="relative flex min-h-[320px] flex-col justify-between overflow-hidden bg-slate-950 px-8 py-10 text-white sm:px-10 lg:min-h-[620px] lg:px-12 lg:py-12">
                <div class="absolute inset-0 bg-[linear-gradient(160deg,rgba(15,23,42,0.82),rgba(15,23,42,0.45)),url('https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=1400&q=80')] bg-cover bg-center"></div>

                <div class="relative z-10">
                    <span class="inline-flex rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-white/80">
                        Peak Experience Admin
                    </span>

                    <h1 class="mt-6 max-w-md text-4xl font-semibold uppercase tracking-tight text-white sm:text-5xl">
                        System Control Room
                    </h1>

                    <p class="mt-6 max-w-md text-sm leading-7 text-white/80 sm:text-base">
                        Manage event operations, monitor platform status, and keep planning teams aligned from one secure dashboard.
                    </p>
                </div>

                <p class="relative z-10 text-sm font-medium uppercase tracking-[0.22em] text-white/60">
                    Peak Experience Event Planning | Nairobi, Kenya
                </p>
            </section>

            <section class="p-8 sm:p-10 lg:p-12">
                <div class="max-w-md">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-600">Secure Access</p>
                    <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl">Admin Sign In</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600 sm:text-base">
                        Use your administrator credentials to access system management tools.
                    </p>

                    @if (session('status'))
                        <div class="admin-alert-success mt-6">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="admin-alert-error mt-4">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.submit') }}" class="mt-8 space-y-5">
                        @csrf

                        <div>
                            <label for="username" class="admin-label">Username</label>
                            <input
                                id="username"
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                autofocus
                                autocomplete="username"
                                class="admin-input"
                            >
                        </div>

                        <div>
                            <label for="password" class="admin-label">Password</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                class="admin-input"
                            >
                        </div>

                        <button class="admin-btn-primary w-full" type="submit">Open Dashboard</button>
                    </form>

                    <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm leading-7 text-slate-600">
                        <p class="font-semibold text-slate-950">Demo credentials</p>
                        <p class="mt-2">
                            <strong>Username:</strong> admin<br>
                            <strong>Password:</strong> admin123
                        </p>
                    </div>

                    <a href="{{ url('/') }}" class="mt-6 inline-flex items-center text-sm font-semibold text-slate-600 transition hover:text-amber-600">
                        Back to homepage
                    </a>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
