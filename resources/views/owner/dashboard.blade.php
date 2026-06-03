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
        main { width: 100%; min-width: 0; box-sizing: border-box; padding: clamp(22px, 2.4vw, 34px) clamp(16px, 2.3vw, 30px) 56px; }
        h1, h2, p { margin: 0; }
        .hero-card, .stat-card, .panel { min-width: 0; border-radius: 8px; box-shadow: 0 16px 38px rgba(39, 20, 13, .13); }
        .hero-card { margin-bottom: 16px; padding: 22px; background: linear-gradient(135deg, var(--brown-light), var(--brown-dark)); color: #fff8ed; }
        .eyebrow { margin-bottom: 8px; color: rgba(255, 248, 237, .76); font-size: 12px; font-weight: 900; letter-spacing: .04em; text-transform: uppercase; }
        .hero-title { font-size: clamp(32px, 4vw, 46px); line-height: 1.05; }
        .hero-subtitle { max-width: 760px; margin-top: 10px; color: rgba(255, 248, 237, .82); line-height: 1.55; }
        .stats { min-width: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 14px; margin-bottom: 18px; }
        .stat-card, .panel { background: linear-gradient(135deg, rgba(154, 98, 57, .94), rgba(90, 50, 31, .98) 52%, rgba(39, 20, 13, .98)); border: 1px solid rgba(255, 246, 232, .22); color: var(--cream); }
        .stat-card { display: grid; gap: 7px; padding: 16px; }
        .stat-card span, .muted { color: rgba(255, 246, 232, .76); }
        .stat-card span { font-size: 13px; font-weight: 800; }
        .stat-card strong { font-size: 28px; line-height: 1.1; overflow-wrap: anywhere; }
        .report-grid { min-width: 0; display: grid; grid-template-columns: minmax(0, 1.15fr) minmax(min(320px, 100%), .85fr); gap: 16px; align-items: start; }
        .report-column { min-width: 0; display: grid; gap: 16px; }
        .panel { max-width: 100%; padding: 18px; }
        .panel h2 { margin-bottom: 14px; font-size: 22px; }
        .panel { min-width: 0; overflow-x: auto; overflow-wrap: anywhere; scrollbar-width: thin; -ms-overflow-style: auto; }
        .panel::-webkit-scrollbar { display: block; width: 8px; height: 8px; }
        .panel::-webkit-scrollbar-thumb { background: rgba(255, 246, 232, .34); border-radius: 999px; }
        .summary-table { width: 100%; min-width: min(520px, 100vw); border-collapse: collapse; overflow: hidden; border-radius: 8px; }
        .summary-table th, .summary-table td { padding: 13px 14px; border-top: 1px solid rgba(255, 246, 232, .16); text-align: left; }
        .summary-table th { background: rgba(255, 246, 232, .1); color: rgba(255, 246, 232, .78); font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .summary-table td:last-child { text-align: right; font-weight: 900; }
        .list-stack { display: grid; gap: 10px; }
        .rank-row, .payment-row, .revenue-row { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 12px; align-items: center; padding-top: 10px; border-top: 1px solid rgba(255, 246, 232, .16); font-weight: 900; }
        .rank-row:first-child, .payment-row:first-child, .revenue-row:first-child { border-top: 0; padding-top: 0; }
        .rank-name { display: flex; gap: 9px; align-items: center; min-width: 0; }
        .rank-number { display: inline-grid; place-items: center; width: 28px; height: 28px; border-radius: 999px; background: rgba(255, 246, 232, .14); color: #fff8ed; font-size: 12px; }
        .bar-track { height: 8px; border-radius: 999px; background: rgba(255, 246, 232, .14); overflow: hidden; margin-top: 7px; }
        .bar-fill { height: 100%; border-radius: inherit; background: #fff6e8; }
        .empty-state { color: rgba(255, 246, 232, .76); font-weight: 800; line-height: 1.5; }
        @media (max-width: 1050px) { .report-grid { grid-template-columns: 1fr; } }
        @media (max-width: 760px) { main { padding: 24px 16px 44px; } .stats, .report-grid { grid-template-columns: 1fr; } }
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
                    <p class="hero-subtitle">Pantau performa bisnis SwiftBite Morning Bakery dari pendapatan, transaksi, menu terlaris, dan pola pembayaran pelanggan.</p>
                </section>

                <section class="stats" aria-label="Statistik owner">
                    <article class="stat-card"><span>Pendapatan Hari Ini</span><strong>Rp{{ number_format($stats['today_revenue'], 0, ',', '.') }}</strong></article>
                    <article class="stat-card"><span>Total Pesanan Hari Ini</span><strong>{{ $stats['today_orders'] }}</strong></article>
                    <article class="stat-card"><span>Rata-rata Transaksi</span><strong>Rp{{ number_format($stats['average_transaction'], 0, ',', '.') }}</strong></article>
                    <article class="stat-card"><span>Menu Terlaris</span><strong>{{ $stats['best_seller'] }}</strong></article>
                </section>

                <section class="report-grid">
                    <div class="report-column">
                        <div class="panel">
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
                        </div>

                        <div class="panel">
                            <h2>Top 3 Menu Terlaris</h2>
                            @if ($topMenusToday->isEmpty())
                                <p class="empty-state">Belum ada menu terjual hari ini.</p>
                            @else
                                <div class="list-stack">
                                    @foreach ($topMenusToday as $menu)
                                        <div class="rank-row">
                                            <span class="rank-name"><span class="rank-number">{{ $loop->iteration }}</span>{{ $menu->nama_menu }}</span>
                                            <strong>{{ (int) $menu->total_qty }}</strong>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="report-column">
                        <div class="panel">
                            <h2>Pendapatan Minggu Ini</h2>
                            <div class="list-stack">
                                @php
                                    $maxRevenue = max(1, $weeklyRevenueDays->max('revenue'));
                                @endphp
                                @foreach ($weeklyRevenueDays as $day)
                                    <div class="revenue-row">
                                        <div>
                                            <strong>{{ $day['label'] }}</strong>
                                            <div class="bar-track"><div class="bar-fill" style="width: {{ max(4, ($day['revenue'] / $maxRevenue) * 100) }}%;"></div></div>
                                        </div>
                                        <strong>Rp{{ number_format($day['revenue'], 0, ',', '.') }}</strong>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="panel">
                            <h2>Metode Pembayaran</h2>
                            <div class="list-stack">
                                @foreach ($paymentSummary as $method => $total)
                                    <div class="payment-row">
                                        <span>{{ $method }}</span>
                                        <strong>{{ $total }}</strong>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
