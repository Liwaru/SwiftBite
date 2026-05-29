<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page['title'] }}</title>
    <style>
        :root { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        body { margin: 0; background: #ffffff; color: #2b1c15; }
        .app-shell .content-with-sidebar { background: #ffffff !important; }
        main { width: 100%; box-sizing: border-box; padding: 34px 30px 56px; }
        h1, p { margin: 0; }
        h1 { font-size: clamp(30px, 4vw, 44px); margin-bottom: 10px; }
        .muted { color: #7a5a46; line-height: 1.5; }
        .panel { max-width: 820px; margin-top: 24px; background: #fff6e8; border: 1px solid #e1ad73; border-radius: 8px; padding: 18px; box-shadow: 0 12px 30px rgba(39, 20, 13, .12); }
        @media (max-width: 760px) { main { padding: 24px 16px 44px; } }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                <h1>{{ $page['title'] }}</h1>
                <p class="muted">{{ $page['description'] }}</p>
                <section class="panel">
                    <p class="muted">Halaman ini sudah disiapkan untuk fitur manager.</p>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
