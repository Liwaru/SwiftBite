<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Owner</title>
    <style>
        :root { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; --brown-dark: #27140d; --brown: #5a321f; --brown-light: #9a6239; --cream: #fff6e8; }
        body { margin: 0; background: #ffffff; color: #2b1c15; }
        .app-shell .content-with-sidebar { background: #ffffff !important; }
        main { width: 100%; box-sizing: border-box; padding: 34px 30px 56px; }
        h1, h2, p { margin: 0; }
        .hero-card, .stat-card, .panel { border-radius: 8px; box-shadow: 0 16px 38px rgba(39, 20, 13, .13); }
        .hero-card { margin-bottom: 16px; padding: 22px; background: linear-gradient(135deg, var(--brown-light), var(--brown-dark)); color: #fff8ed; }
        .eyebrow { margin-bottom: 8px; color: rgba(255, 248, 237, .76); font-size: 12px; font-weight: 900; letter-spacing: .04em; text-transform: uppercase; }
        .hero-title { font-size: clamp(32px, 4vw, 46px); line-height: 1.05; }
        .hero-subtitle { max-width: 720px; margin-top: 10px; color: rgba(255, 248, 237, .82); line-height: 1.55; }
        .stats { display: grid; grid-template-columns: repeat(4, minmax(160px, 1fr)); gap: 14px; margin-bottom: 18px; }
        .stat-card, .panel { background: linear-gradient(135deg, rgba(154, 98, 57, .94), rgba(90, 50, 31, .98) 52%, rgba(39, 20, 13, .98)); border: 1px solid rgba(255, 246, 232, .22); color: var(--cream); }
        .stat-card { display: grid; gap: 7px; padding: 16px; }
        .stat-card span { color: rgba(255, 246, 232, .76); font-size: 13px; font-weight: 800; }
        .stat-card strong { font-size: 28px; line-height: 1.1; }
        .panel { padding: 18px; }
        .panel h2 { margin-bottom: 14px; font-size: 22px; }
        .summary-table { width: 100%; border-collapse: collapse; overflow: hidden; border-radius: 8px; }
        .summary-table th, .summary-table td { padding: 13px 14px; border-top: 1px solid rgba(255, 246, 232, .16); text-align: left; }
        .summary-table th { background: rgba(255, 246, 232, .1); color: rgba(255, 246, 232, .78); font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .summary-table td:last-child { text-align: right; font-weight: 900; }
        @media (max-width: 980px) { .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 760px) { main { padding: 24px 16px 44px; } .stats { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                <section class="hero-card">
                    <div class="eyebrow">Owner Report</div>
                    <h1 class="hero-title">Dashboard Owner</h1>
                    <p class="hero-subtitle">Pantau ringkasan performa SwiftBite Morning Bakery dari sisi laporan utama dan kondisi transaksi hari ini.</p>
                </section>

                <section class="stats" aria-label="Statistik owner">
                    <article class="stat-card"><span>Pesanan Hari Ini</span><strong>{{ $stats['today_orders'] }}</strong></article>
                    <article class="stat-card"><span>Pendapatan Hari Ini</span><strong>Rp{{ number_format($stats['today_revenue'], 0, ',', '.') }}</strong></article>
                    <article class="stat-card"><span>Menu Aktif</span><strong>{{ $stats['active_menu'] }}</strong></article>
                    <article class="stat-card"><span>User Aktif</span><strong>{{ $stats['active_users'] }}</strong></article>
                </section>

                <section class="panel">
                    <h2>Ringkasan Transaksi Hari Ini</h2>
                    <table class="summary-table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Menunggu</td><td>{{ $stats['orders_today']['menunggu'] }}</td></tr>
                            <tr><td>Diproses</td><td>{{ $stats['orders_today']['diproses'] }}</td></tr>
                            <tr><td>Selesai</td><td>{{ $stats['orders_today']['selesai'] }}</td></tr>
                            <tr><td>Dibatalkan</td><td>{{ $stats['orders_today']['dibatalkan'] }}</td></tr>
                            <tr><td><strong>Total Pesanan</strong></td><td>{{ $stats['today_orders'] }}</td></tr>
                        </tbody>
                    </table>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
