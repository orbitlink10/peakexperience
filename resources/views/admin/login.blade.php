<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | PeakExperience</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #1f2530;
            --fog: #6b7380;
            --canvas: #f4f2ec;
            --paper: #ffffff;
            --accent: #d0692b;
            --forest: #1f5c4d;
            --line: #dde0e5;
            --shadow: 0 24px 45px rgba(31, 37, 48, 0.16);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at 20% 20%, rgba(31, 92, 77, 0.18), transparent 48%),
                radial-gradient(circle at 90% 10%, rgba(208, 105, 43, 0.2), transparent 45%),
                linear-gradient(180deg, #f2efe8 0%, #f8fafb 100%);
            font-family: 'Manrope', sans-serif;
            color: var(--ink);
            display: grid;
            place-items: center;
            padding: 1rem;
        }

        .shell {
            width: min(950px, 100%);
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: var(--paper);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .brand-pane {
            background:
                linear-gradient(145deg, rgba(17, 22, 30, 0.78), rgba(17, 22, 30, 0.52)),
                url('https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=1400&q=80') center/cover no-repeat;
            color: #ffffff;
            padding: clamp(1.8rem, 4vw, 2.4rem);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 560px;
        }

        .eyebrow {
            display: inline-block;
            width: fit-content;
            font-size: 0.72rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            padding: 0.35rem 0.6rem;
            border: 1px solid rgba(255, 255, 255, 0.45);
            border-radius: 999px;
            margin-bottom: 1rem;
        }

        .brand-pane h1 {
            font-family: 'Barlow Condensed', sans-serif;
            text-transform: uppercase;
            font-size: clamp(2.1rem, 5vw, 3.3rem);
            letter-spacing: 0.02em;
            line-height: 0.95;
            margin-bottom: 1rem;
        }

        .brand-pane p {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.7;
            max-width: 390px;
        }

        .panel-note {
            font-size: 0.88rem;
            color: rgba(255, 255, 255, 0.86);
        }

        .form-pane {
            padding: clamp(1.8rem, 4vw, 2.6rem);
        }

        .title {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: clamp(2rem, 5vw, 2.7rem);
            text-transform: uppercase;
            letter-spacing: 0.02em;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: var(--fog);
            margin-bottom: 1.7rem;
            line-height: 1.6;
        }

        .alert {
            border-radius: 10px;
            padding: 0.75rem 0.9rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            font-size: 0.92rem;
        }

        .alert-status {
            background: #edf7f4;
            border-color: #cce7de;
            color: #1d604e;
        }

        .alert-error {
            background: #fff2ef;
            border-color: #f4ccc5;
            color: #9a2b2b;
        }

        .field {
            margin-bottom: 1rem;
        }

        .field label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
            color: #273243;
        }

        .field input {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 0.78rem 0.82rem;
            font-size: 0.96rem;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .field input:focus {
            border-color: var(--forest);
            box-shadow: 0 0 0 3px rgba(31, 92, 77, 0.17);
        }

        .submit {
            width: 100%;
            border: 0;
            border-radius: 10px;
            padding: 0.85rem 1rem;
            font-size: 0.96rem;
            font-weight: 700;
            background: linear-gradient(135deg, #d0692b, #bd5719);
            color: #ffffff;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-top: 0.5rem;
        }

        .submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 24px rgba(208, 105, 43, 0.3);
        }

        .hint {
            margin-top: 1rem;
            padding: 0.8rem;
            border-radius: 10px;
            background: #f8f8f8;
            border: 1px solid #eceff3;
            color: #4f5968;
            font-size: 0.88rem;
            line-height: 1.6;
        }

        .back {
            display: inline-block;
            margin-top: 1rem;
            color: #4f5968;
            text-decoration: none;
            font-size: 0.89rem;
            font-weight: 600;
        }

        .back:hover {
            color: var(--accent);
        }

        @media (max-width: 860px) {
            .shell {
                grid-template-columns: 1fr;
            }

            .brand-pane {
                min-height: 300px;
            }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="brand-pane">
            <div>
                <span class="eyebrow">PeakExperience Admin</span>
                <h1>System Control Room</h1>
                <p>Manage event operations, monitor platform status, and keep planning teams aligned from one secure dashboard.</p>
            </div>
            <p class="panel-note">PeakExperience Event Planning | Nairobi, Kenya</p>
        </section>

        <section class="form-pane">
            <h2 class="title">Admin Sign In</h2>
            <p class="subtitle">Use your administrator credentials to access system management tools.</p>

            @if (session('status'))
                <div class="alert alert-status">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="field">
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username">
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password">
                </div>

                <button class="submit" type="submit">Open Dashboard</button>
            </form>

            <div class="hint">
                Demo credentials configured:<br>
                <strong>Username:</strong> admin<br>
                <strong>Password:</strong> admin123
            </div>

            <a class="back" href="{{ url('/') }}">Back to homepage</a>
        </section>
    </main>
</body>
</html>
