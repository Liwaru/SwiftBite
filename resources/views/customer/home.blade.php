<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Pelanggan</title>
    <style>
        :root { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        body { margin: 0; background: #ffffff; color: #151515; }
        main { max-width: 780px; padding: 34px 30px 56px; }
        h1, p { margin: 0; }
        h1 { font-size: clamp(30px, 4vw, 44px); margin-bottom: 10px; }
        .muted { color: #6c6c6c; }
        .panel { margin-top: 24px; background: #ffffff; border: 1px solid #f0d4d7; border-radius: 8px; padding: 18px; box-shadow: 0 14px 34px rgba(169, 0, 16, .08); }
        @media (max-width: 760px) { main { padding: 24px 16px 44px; } }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                <p class="muted">Akun pelanggan</p>
                <h1>Dashboard Pelanggan</h1>
                <section class="panel">
                    <p class="muted">Silakan scan QR di meja untuk membuka menu dan membuat pesanan.</p>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
