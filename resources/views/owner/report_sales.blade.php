<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Penjualan</title>
    @include('owner.partials.report_styles')
    <style>
        .sales-page .hero-card { margin-bottom: 12px; padding: 18px 22px; }
        .sales-page .hero-title { font-size: clamp(28px, 3vw, 40px); }
        .sales-page .hero-subtitle { max-width: 680px; font-size: 14px; }
        .sales-stats {
            gap: 10px;
            margin-bottom: 12px;
        }
        .sales-stat {
            min-height: 82px;
            padding: 14px;
            align-content: center;
        }
        .sales-stat strong {
            order: 1;
            font-size: clamp(24px, 2.4vw, 34px);
            line-height: 1;
        }
        .sales-stat span {
            order: 2;
            margin-top: 6px;
            font-size: 12px;
        }
        .sales-grid {
            gap: 10px;
        }
        .sales-panel {
            padding: 13px 14px;
            box-shadow: 0 10px 24px rgba(39, 20, 13, .1);
        }
        .sales-panel h2 {
            margin-bottom: 10px;
            font-size: 18px;
        }
        .sales-compact-list {
            gap: 6px;
        }
        .sales-compact-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            min-height: 34px;
            padding: 7px 0;
            border-top: 1px solid rgba(255, 246, 232, .14);
            font-weight: 900;
        }
        .sales-compact-row:first-child {
            border-top: 0;
            padding-top: 0;
        }
        .sales-count-badge {
            display: inline-grid;
            place-items: center;
            min-width: 34px;
            height: 26px;
            border-radius: 999px;
            background: #fff6e8;
            color: var(--brown-dark);
            padding: 0 8px;
            font-size: 12px;
            font-weight: 900;
        }
        .sales-top-panel {
            margin-top: 10px !important;
        }
        .sales-top-list {
            display: grid;
            gap: 7px;
        }
        .sales-rank-row {
            display: grid;
            grid-template-columns: 30px minmax(0, 1fr) auto;
            gap: 10px;
            align-items: center;
            padding-top: 7px;
            border-top: 1px solid rgba(255, 246, 232, .14);
            font-weight: 900;
        }
        .sales-rank-row:first-child {
            border-top: 0;
            padding-top: 0;
        }
        .sales-rank-number {
            display: inline-grid;
            place-items: center;
            width: 26px;
            height: 26px;
            border-radius: 999px;
            background: rgba(255, 246, 232, .14);
            color: #fff8ed;
            font-size: 12px;
        }
        .sales-detail-section {
            margin-top: 10px !important;
        }
        .sales-detail-panel {
            padding: 16px;
        }
        .sales-detail-panel h2 {
            font-size: 20px;
        }
        .sales-page .empty-state {
            padding: 4px 0;
        }
        .sales-visual {
            display: grid;
            grid-template-columns: 190px minmax(0, 1fr);
            gap: 18px;
            align-items: center;
            margin-top: 14px;
            padding: 18px;
            border: 1px solid rgba(255, 246, 232, .16);
            border-radius: 8px;
            background: rgba(255, 246, 232, .055);
        }
        .sales-donut {
            --completed-part: 0%;
            width: 154px;
            aspect-ratio: 1;
            justify-self: center;
            border-radius: 50%;
            background:
                radial-gradient(circle at center, rgba(90, 50, 31, .98) 0 48%, transparent 49%),
                conic-gradient(#fff6e8 0 var(--completed-part), #f2bd84 var(--completed-part) 100%);
            box-shadow: inset 0 0 0 1px rgba(255, 246, 232, .18);
        }
        .sales-empty-visual {
            min-height: 154px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: rgba(255, 246, 232, .08);
            color: rgba(255, 246, 232, .72);
            text-align: center;
            font-size: 12px;
            font-weight: 900;
            line-height: 1.4;
        }
        .sales-summary-chart {
            display: grid;
            gap: 12px;
        }
        .sales-summary-row {
            display: grid;
            grid-template-columns: 132px minmax(120px, 1fr) auto;
            gap: 12px;
            align-items: center;
            color: rgba(255, 246, 232, .82);
            font-weight: 900;
        }
        .sales-summary-track {
            height: 10px;
            border-radius: 999px;
            background: rgba(255, 246, 232, .12);
            overflow: hidden;
        }
        .sales-summary-fill {
            display: block;
            height: 100%;
            min-width: 0;
            border-radius: inherit;
            background: #fff6e8;
        }
        .sales-summary-fill.secondary {
            background: #f2bd84;
        }
        .sales-note {
            margin-top: 4px;
            color: rgba(255, 246, 232, .72);
            font-size: 12px;
            font-weight: 800;
            line-height: 1.5;
        }
        @media (max-width: 980px) {
            .sales-visual {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 760px) {
            .sales-summary-row {
                grid-template-columns: 1fr;
                gap: 7px;
            }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')
        <div class="content-with-sidebar">
            <main class="sales-page">
                <section class="hero-card">
                    <div class="eyebrow">Owner Report</div>
                    <h1 class="hero-title">Laporan Penjualan</h1>
                    <p class="hero-subtitle">Pantau total pesanan, produk terjual, pendapatan, status pesanan, menu terlaris, pembayaran, dan detail transaksi.</p>
                </section>

                <section class="stats sales-stats">
                    <article class="stat-card sales-stat"><strong>{{ number_format($summary['total_orders']) }}</strong><span>Total Pesanan</span></article>
                    <article class="stat-card sales-stat"><strong>{{ number_format($summary['total_products_sold']) }}</strong><span>Produk Terjual</span></article>
                    <article class="stat-card sales-stat"><strong>Rp{{ number_format($summary['total_revenue'], 0, ',', '.') }}</strong><span>Pendapatan</span></article>
                    <article class="stat-card sales-stat"><strong>Rp{{ number_format($summary['average_transaction'], 0, ',', '.') }}</strong><span>Rata-rata Transaksi</span></article>
                </section>

                <section class="panel chart-panel" id="grafik-penjualan">
                    <div class="chart-head">
                        <div>
                            <div class="chart-title">{{ $salesChart['title'] }}</div>
                            <div class="chart-subtitle">{{ $salesChart['subtitle'] }}</div>
                        </div>
                        <div class="chart-actions">
                            <form method="GET" action="{{ route('owner.sales') }}" class="chart-period-form">
                                <select name="chart_period" class="chart-select">
                                    @foreach ($chartPeriodOptions as $value => $label)
                                        <option value="{{ $value }}" @selected($chartPeriod === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <a class="chart-action-btn" href="{{ route('owner.sales', ['chart_period' => $chartPeriod, 'export' => 'excel']) }}">Export Excel</a>
                            <a class="chart-action-btn" href="{{ route('owner.sales', ['chart_period' => $chartPeriod, 'export' => 'pdf']) }}">Export PDF</a>
                            <button type="button" class="chart-action-btn print-btn" onclick="window.print()">Print</button>
                        </div>
                    </div>
                    @php
                        $totalOrders = (int) $summary['total_orders'];
                        $completedOrders = (int) ($statusSummary['Selesai'] ?? 0);
                        $completedPercent = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;
                        $maxSalesCount = max(1, $summary['total_orders'], $summary['total_products_sold'], $completedOrders);
                    @endphp
                    <div class="sales-visual" aria-label="Ringkasan visual penjualan">
                        @if ($totalOrders > 0)
                            <div class="sales-donut" style="--completed-part: {{ $completedPercent }}%;" title="Selesai {{ $completedPercent }}% | Belum selesai {{ 100 - $completedPercent }}%"></div>
                        @else
                            <div class="sales-empty-visual">Belum ada data penjualan pada periode ini.</div>
                        @endif
                        <div class="sales-summary-chart">
                            <div class="sales-summary-row">
                                <span>Total Pesanan</span>
                                <div class="sales-summary-track">
                                    <span class="sales-summary-fill" style="width: {{ $totalOrders > 0 ? max(4, ($totalOrders / $maxSalesCount) * 100) : 0 }}%;"></span>
                                </div>
                                <strong>{{ number_format($totalOrders) }}</strong>
                            </div>
                            <div class="sales-summary-row">
                                <span>Pesanan Selesai</span>
                                <div class="sales-summary-track">
                                    <span class="sales-summary-fill" style="width: {{ $completedOrders > 0 ? max(4, ($completedOrders / $maxSalesCount) * 100) : 0 }}%;"></span>
                                </div>
                                <strong>{{ number_format($completedOrders) }}</strong>
                            </div>
                            <div class="sales-summary-row">
                                <span>Produk Terjual</span>
                                <div class="sales-summary-track">
                                    <span class="sales-summary-fill secondary" style="width: {{ $summary['total_products_sold'] > 0 ? max(4, ($summary['total_products_sold'] / $maxSalesCount) * 100) : 0 }}%;"></span>
                                </div>
                                <strong>{{ number_format($summary['total_products_sold']) }}</strong>
                            </div>
                            <div class="sales-summary-row">
                                <span>Pendapatan</span>
                                <div class="sales-summary-track">
                                    <span class="sales-summary-fill secondary" style="width: {{ $summary['total_revenue'] > 0 ? 100 : 0 }}%;"></span>
                                </div>
                                <strong>Rp{{ number_format($summary['total_revenue'], 0, ',', '.') }}</strong>
                            </div>
                            @if ($totalOrders < 3)
                                <p class="sales-note">Data masih terbatas untuk visualisasi tren. Ringkasan ini menampilkan komposisi pesanan dan penjualan pada periode yang dipilih.</p>
                            @else
                                <p class="sales-note">Ringkasan komposisi penjualan pada periode {{ strtolower($chartPeriodOptions[$chartPeriod]) }}.</p>
                            @endif
                        </div>
                    </div>
                    <div class="chart-legend">
                        <span class="legend-item"><span class="legend-dot"></span>Selesai</span>
                        <span class="legend-item"><span class="legend-dot expense"></span>Belum selesai / produk</span>
                    </div>
                </section>

                <section class="report-grid sales-grid">
                    <div class="panel sales-panel">
                        <div class="panel-head">
                            <h2>Ringkasan Status Pesanan</h2>
                            <div class="period-note">{{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</div>
                        </div>
                        <div class="sales-compact-list">
                            @foreach ($statusSummary as $status => $total)
                                <div class="sales-compact-row">
                                    <span>{{ $status }}</span>
                                    <strong class="sales-count-badge">{{ number_format($total) }}</strong>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="panel sales-panel">
                        <h2>Metode Pembayaran</h2>
                        <div class="sales-compact-list">
                            @foreach ($paymentSummary as $method => $total)
                                <div class="sales-compact-row">
                                    <span>{{ $method }}</span>
                                    <strong class="sales-count-badge">{{ number_format($total) }}</strong>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <section class="report-grid single sales-top-panel">
                    <div class="panel sales-panel">
                        <h2>Top 5 Menu Terlaris</h2>
                        @if ($topMenus->isEmpty())
                            <p class="empty-state">Belum ada data penjualan.</p>
                        @else
                            <div class="sales-top-list">
                                @foreach ($topMenus as $menu)
                                    <div class="sales-rank-row">
                                        <span class="sales-rank-number">{{ $loop->iteration }}</span>
                                        <span>{{ $menu->nama_menu }}</span>
                                        <strong>{{ number_format($menu->total_qty) }}</strong>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>

                <section class="report-grid single sales-detail-section">
                    <div class="panel sales-detail-panel">
                        <h2>Detail Transaksi</h2>
                        @if ($transactions->isEmpty())
                            <p class="empty-state">Belum ada transaksi pada periode ini.</p>
                        @else
                            <div class="table-wrap">
                                <table class="summary-table">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Invoice</th>
                                            <th>Meja</th>
                                            <th>Jumlah Item</th>
                                            <th>Metode Bayar</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->created_at?->format('d/m/Y') ?? '-' }}</td>
                                                <td>{{ $transaction->kode_pesanan }}</td>
                                                <td>{{ $transaction->diningTable?->nama_meja ?? '-' }}</td>
                                                <td>{{ number_format($transaction->total_items ?? 0) }} Item</td>
                                                <td>{{ match ($transaction->metode_pembayaran) {
                                                    'cash' => 'Tunai',
                                                    'qris' => 'QRIS',
                                                    'dana' => 'Dana',
                                                    'ovo' => 'OVO',
                                                    'gopay' => 'GoPay',
                                                    'ewallet' => 'E-Wallet',
                                                    default => $transaction->metode_pembayaran ? ucfirst($transaction->metode_pembayaran) : '-',
                                                } }}</td>
                                                <td>Rp{{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                                                <td><span class="status neutral">{{ ucfirst($transaction->status) }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if ($transactions->hasPages())
                                <div class="pagination-wrap">
                                    <div class="pagination">
                                        @if ($transactions->onFirstPage())
                                            <span class="page-link page-disabled">Prev</span>
                                        @else
                                            <a class="page-link" href="{{ $transactions->previousPageUrl() }}">Prev</a>
                                        @endif

                                        <span class="page-current">{{ $transactions->currentPage() }}</span>

                                        @if ($transactions->hasMorePages())
                                            <a class="page-link" href="{{ $transactions->nextPageUrl() }}">Next</a>
                                        @else
                                            <span class="page-link page-disabled">Next</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </section>
            </main>
        </div>
    </div>
    <script>
        const salesScrollKey = 'swiftbite.sales.scrollY';
        const chartPeriodForm = document.querySelector('.chart-period-form');
        const chartPeriodSelect = document.querySelector('.chart-period-form .chart-select');

        if (chartPeriodForm && chartPeriodSelect) {
            chartPeriodSelect.addEventListener('change', () => {
                sessionStorage.setItem(salesScrollKey, String(window.scrollY));
                chartPeriodForm.submit();
            });
        }

        window.addEventListener('load', () => {
            const storedScroll = sessionStorage.getItem(salesScrollKey);

            if (storedScroll !== null) {
                sessionStorage.removeItem(salesScrollKey);
                window.scrollTo(0, Number(storedScroll));
            }
        });
    </script>
</body>
</html>
