<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Manager</title>
    <style>
        :root { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        body { margin: 0; background: #ffffff; color: #2b1c15; }
        .app-shell .content-with-sidebar { background: #ffffff !important; }
        main { width: 100%; min-width: 0; box-sizing: border-box; padding: clamp(22px, 2.4vw, 34px) clamp(16px, 2.3vw, 30px) 56px; }
        h1, h2, p { margin: 0; }
        h1 { font-size: clamp(30px, 4vw, 44px); margin-bottom: 22px; }
        h2 { font-size: 22px; margin-bottom: 14px; }
        .muted { color: #7a5a46; line-height: 1.5; }
        .hero-card {
            min-width: 0;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 16px;
            padding: 22px;
            border-radius: 8px;
            background: linear-gradient(135deg, #9a6239, #27140d);
            color: #fff8ed;
            box-shadow: 0 16px 38px rgba(39, 20, 13, .13);
        }
        .eyebrow { margin-bottom: 8px; color: rgba(255, 248, 237, .76); font-size: 12px; font-weight: 900; letter-spacing: .04em; text-transform: uppercase; }
        .hero-title { margin: 0; font-size: clamp(32px, 4vw, 46px); line-height: 1.05; }
        .hero-subtitle { max-width: 720px; margin-top: 10px; color: rgba(255, 248, 237, .82); line-height: 1.55; }
        .stats { min-width: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 14px; margin-bottom: 18px; }
        .stat-card, .panel {
            min-width: 0;
            background: linear-gradient(135deg, rgba(154, 98, 57, .94), rgba(90, 50, 31, .98) 52%, rgba(39, 20, 13, .98));
            border: 1px solid rgba(255, 246, 232, .22);
            border-radius: 8px;
            box-shadow: 0 12px 30px rgba(39, 20, 13, .18);
            color: #fff6e8;
        }
        .stat-card { display: grid; gap: 7px; padding: 16px; }
        .stat-card span, .panel .muted { color: rgba(255, 246, 232, .76); }
        .stat-card span { font-size: 13px; font-weight: 800; }
        .stat-card strong { font-size: 28px; line-height: 1.1; overflow-wrap: anywhere; }
        .dashboard-row { display: block; min-width: 0; }
        .panel { max-width: 100%; padding: 18px; overflow-x: auto; overflow-wrap: anywhere; scrollbar-width: thin; -ms-overflow-style: auto; }
        .panel::-webkit-scrollbar { display: block; width: 8px; height: 8px; }
        .panel::-webkit-scrollbar-thumb { background: rgba(255, 246, 232, .34); border-radius: 999px; }
        .summary-total { display: flex; justify-content: space-between; gap: 12px; align-items: center; margin-bottom: 14px; padding: 12px 14px; border-radius: 8px; background: rgba(255, 246, 232, .1); font-weight: 900; }
        .summary-total strong { font-size: 24px; }
        .order-breakdown { display: grid; gap: 10px; }
        .order-row { display: flex; justify-content: space-between; gap: 12px; align-items: center; border-top: 1px solid rgba(255, 246, 232, .16); padding-top: 10px; font-weight: 900; }
        .order-row:first-child { border-top: 0; padding-top: 0; }
        .status-name { display: inline-flex; gap: 8px; align-items: center; }
        .status-dot { width: 10px; height: 10px; border-radius: 999px; }
        .status-dot.waiting { background: #f2c94c; }
        .status-dot.processing { background: #73a8ff; }
        .status-dot.done { background: #8bd17c; }
        .status-dot.cancelled { background: #ef6f61; }
        .summary-table { width: 100%; min-width: min(520px, 100vw); border-collapse: collapse; overflow: hidden; border-radius: 8px; }
        .summary-table th, .summary-table td { padding: 13px 14px; border-top: 1px solid rgba(255, 246, 232, .16); text-align: left; }
        .summary-table th { background: rgba(255, 246, 232, .1); color: rgba(255, 246, 232, .78); font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .summary-table td:last-child { text-align: right; font-weight: 900; }
        @media (max-width: 980px) { .hero-card { align-items: flex-start; flex-direction: column; } }
        @media (max-width: 760px) { main { padding: 24px 16px 44px; } .hero-card { align-items: flex-start; flex-direction: column; } .stats { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                <section class="hero-card">
                    <div>
                        <div class="eyebrow">Manager Operasional</div>
                        <h1 class="hero-title">Dashboard Manager</h1>
                        <p class="hero-subtitle">Pantau kondisi operasional SwiftBite Morning Bakery, mulai dari menu, meja QR, user aktif, sampai status pesanan hari ini.</p>
                    </div>
                </section>

                <section class="stats" aria-label="Statistik operasional">
                    <article class="stat-card">
                        <span>Total Menu</span>
                        <strong>{{ $stats['total_menu'] }}</strong>
                    </article>
                    <article class="stat-card">
                        <span>Total Meja QR</span>
                        <strong>{{ $stats['total_tables'] }}</strong>
                    </article>
                    <article class="stat-card">
                        <span>Pesanan Hari Ini</span>
                        <strong>{{ $stats['today_orders'] }}</strong>
                    </article>
                    <article class="stat-card">
                        <span>User Aktif</span>
                        <strong>{{ $stats['active_users'] }}</strong>
                    </article>
                </section>

                <section class="dashboard-row">
                    <div class="panel">
                        <h2>Ringkasan Pesanan Hari Ini</h2>
                        <table class="summary-table">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td><span class="status-name"><span class="status-dot waiting"></span>Menunggu</span></td><td>{{ $stats['orders_today']['menunggu'] }}</td></tr>
                                <tr><td><span class="status-name"><span class="status-dot processing"></span>Diproses</span></td><td>{{ $stats['orders_today']['diproses'] }}</td></tr>
                                <tr><td><span class="status-name"><span class="status-dot done"></span>Selesai</span></td><td>{{ $stats['orders_today']['selesai'] }}</td></tr>
                                <tr><td><span class="status-name"><span class="status-dot cancelled"></span>Dibatalkan</span></td><td>{{ $stats['orders_today']['dibatalkan'] }}</td></tr>
                                <tr><td><strong>Total Pesanan</strong></td><td>{{ $stats['today_orders'] }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </section>

            </main>
        </div>
    </div>
</body>
</html>
