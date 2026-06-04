<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Keuangan</title>
    @include('owner.partials.report_styles')
    <style>
        .finance-visual {
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

        .finance-donut {
            --income-part: 0%;
            width: 154px;
            aspect-ratio: 1;
            justify-self: center;
            border-radius: 50%;
            background:
                radial-gradient(circle at center, rgba(90, 50, 31, .98) 0 48%, transparent 49%),
                conic-gradient(#fff6e8 0 var(--income-part), #f2bd84 var(--income-part) 100%);
            box-shadow: inset 0 0 0 1px rgba(255, 246, 232, .18);
        }

        .finance-empty-visual {
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

        .finance-summary-chart {
            display: grid;
            gap: 12px;
        }

        .finance-summary-row {
            display: grid;
            grid-template-columns: 128px minmax(120px, 1fr) auto;
            gap: 12px;
            align-items: center;
            color: rgba(255, 246, 232, .82);
            font-weight: 900;
        }

        .finance-summary-track {
            height: 10px;
            border-radius: 999px;
            background: rgba(255, 246, 232, .12);
            overflow: hidden;
        }

        .finance-summary-fill {
            display: block;
            height: 100%;
            min-width: 0;
            border-radius: inherit;
        }

        .finance-summary-fill.income { background: #fff6e8; }
        .finance-summary-fill.expense { background: #f2bd84; }

        .finance-note {
            margin-top: 4px;
            color: rgba(255, 246, 232, .72);
            font-size: 12px;
            font-weight: 800;
            line-height: 1.5;
        }

        @media (max-width: 760px) {
            .finance-visual {
                grid-template-columns: 1fr;
            }

            .finance-summary-row {
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
            <main>
                <section class="hero-card">
                    <div class="eyebrow">Owner Report</div>
                    <h1 class="hero-title">Laporan Keuangan</h1>
                    <p class="hero-subtitle">Pantau pemasukan, pengeluaran bahan, laba bersih, dan detail keuangan SwiftBite.</p>
                </section>

                <section class="stats">
                    <article class="stat-card"><span>Total Pemasukan</span><strong>Rp{{ number_format($summary['total_income'], 0, ',', '.') }}</strong></article>
                    <article class="stat-card"><span>Total Pengeluaran</span><strong>Rp{{ number_format($summary['total_expense'], 0, ',', '.') }}</strong></article>
                    <article class="stat-card"><span>Laba Bersih</span><strong>Rp{{ number_format($summary['net_profit'], 0, ',', '.') }}</strong></article>
                    <article class="stat-card"><span>Transaksi</span><strong>{{ number_format($summary['transactions']) }}</strong></article>
                </section>

                <section class="panel chart-panel">
                    <div class="chart-head">
                        <div>
                            <div class="chart-title">{{ $financeChart['title'] }}</div>
                            <div class="chart-subtitle">{{ $financeChart['subtitle'] }}</div>
                        </div>
                        <div class="chart-actions">
                            <form method="GET" action="{{ route('owner.finance') }}" class="chart-period-form">
                                <select name="chart_period" class="chart-select">
                                    @foreach ($chartPeriodOptions as $value => $label)
                                        <option value="{{ $value }}" @selected($chartPeriod === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <a class="chart-action-btn" href="{{ route('owner.finance', ['chart_period' => $chartPeriod, 'export' => 'excel']) }}">Export Excel</a>
                            <a class="chart-action-btn" href="{{ route('owner.finance', ['chart_period' => $chartPeriod, 'export' => 'pdf']) }}">Export PDF</a>
                            <button type="button" class="chart-action-btn print-btn" onclick="window.print()">Print</button>
                        </div>
                    </div>
                    @php
                        $incomeTotal = (float) $summary['total_income'];
                        $expenseTotal = (float) $summary['total_expense'];
                        $financeTotal = $incomeTotal + $expenseTotal;
                        $incomePercent = $financeTotal > 0 ? round(($incomeTotal / $financeTotal) * 100, 1) : 0;
                        $expensePercent = $financeTotal > 0 ? round(($expenseTotal / $financeTotal) * 100, 1) : 0;
                        $maxFinanceValue = max(1, $incomeTotal, $expenseTotal);
                    @endphp
                    <div class="finance-visual" aria-label="Ringkasan visual keuangan">
                        @if ($financeTotal > 0)
                            <div class="finance-donut" style="--income-part: {{ $incomePercent }}%;" title="Pemasukan {{ $incomePercent }}% | Pengeluaran {{ $expensePercent }}%"></div>
                        @else
                            <div class="finance-empty-visual">Belum ada data keuangan pada periode ini.</div>
                        @endif
                        <div class="finance-summary-chart">
                            <div class="finance-summary-row">
                                <span>Pemasukan</span>
                                <div class="finance-summary-track">
                                    <span class="finance-summary-fill income" style="width: {{ $incomeTotal > 0 ? max(4, ($incomeTotal / $maxFinanceValue) * 100) : 0 }}%;"></span>
                                </div>
                                <strong>Rp{{ number_format($incomeTotal, 0, ',', '.') }}</strong>
                            </div>
                            <div class="finance-summary-row">
                                <span>Pengeluaran</span>
                                <div class="finance-summary-track">
                                    <span class="finance-summary-fill expense" style="width: {{ $expenseTotal > 0 ? max(4, ($expenseTotal / $maxFinanceValue) * 100) : 0 }}%;"></span>
                                </div>
                                <strong>Rp{{ number_format($expenseTotal, 0, ',', '.') }}</strong>
                            </div>
                            <div class="finance-summary-row">
                                <span>Laba Bersih</span>
                                <div class="finance-summary-track">
                                    <span class="finance-summary-fill {{ $summary['net_profit'] >= 0 ? 'income' : 'expense' }}" style="width: {{ abs($summary['net_profit']) > 0 ? max(4, (abs($summary['net_profit']) / $maxFinanceValue) * 100) : 0 }}%;"></span>
                                </div>
                                <strong>Rp{{ number_format($summary['net_profit'], 0, ',', '.') }}</strong>
                            </div>
                            @if ($summary['transactions'] < 3)
                                <p class="finance-note">Data masih terbatas untuk visualisasi tren. Ringkasan ini menampilkan komposisi pemasukan, pengeluaran, dan laba bersih pada periode yang dipilih.</p>
                            @else
                                <p class="finance-note">Ringkasan komposisi keuangan pada periode {{ strtolower($chartPeriodOptions[$chartPeriod]) }}.</p>
                            @endif
                        </div>
                    </div>
                    <div class="chart-legend">
                        <span class="legend-item"><span class="legend-dot"></span>Pemasukan</span>
                        <span class="legend-item"><span class="legend-dot expense"></span>Pengeluaran</span>
                    </div>
                </section>

                <section class="report-grid">
                    <div class="panel">
                        <div class="panel-head">
                            <h2>Pemasukan vs Pengeluaran</h2>
                            <div class="period-note">{{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</div>
                        </div>
                        <div class="list-stack">
                            @foreach ($comparison as $label => $amount)
                                <div class="row">
                                    <span>{{ $label }}</span>
                                    <strong>Rp{{ number_format($amount, 0, ',', '.') }}</strong>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="panel">
                        <h2>Metode Pembayaran</h2>
                        <div class="list-stack">
                            @foreach ($paymentSummary as $method => $total)
                                <div class="row">
                                    <span>{{ $method }}</span>
                                    <strong>{{ number_format($total) }}</strong>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <section class="report-grid single" style="margin-top: 16px;">
                    <div class="panel">
                        <h2>Pengeluaran Terbesar</h2>
                        @if ($topExpenses->isEmpty())
                            <p class="empty-state">Belum ada pengeluaran bahan pada periode ini.</p>
                        @else
                            <div class="table-wrap">
                                <table class="summary-table">
                                    <thead>
                                        <tr>
                                            <th>Bahan</th>
                                            <th>Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topExpenses as $expense)
                                            <tr>
                                                <td>{{ $expense->nama_bahan }}</td>
                                                <td>Rp{{ number_format($expense->total_expense, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </section>

                <section class="report-grid single" style="margin-top: 16px;">
                    <div class="panel">
                        <h2>Detail Keuangan</h2>
                        @if ($financialRows->isEmpty())
                            <p class="empty-state">Belum ada data keuangan pada periode ini.</p>
                        @else
                            <div class="table-wrap">
                                <table class="summary-table">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jenis</th>
                                            <th>Keterangan</th>
                                            <th>Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($financialRows as $row)
                                            <tr>
                                                <td>{{ $row['date']?->format('d/m/Y') ?? '-' }}</td>
                                                <td><span class="status neutral">{{ $row['type'] }}</span></td>
                                                <td>{{ $row['description'] }}</td>
                                                <td>Rp{{ number_format($row['amount'], 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </section>
            </main>
        </div>
    </div>
    <script>
        const financeScrollKey = 'swiftbite.finance.scrollY';
        const chartPeriodForm = document.querySelector('.chart-period-form');
        const chartPeriodSelect = document.querySelector('.chart-period-form .chart-select');

        if (chartPeriodForm && chartPeriodSelect) {
            chartPeriodSelect.addEventListener('change', () => {
                sessionStorage.setItem(financeScrollKey, String(window.scrollY));
                chartPeriodForm.submit();
            });
        }

        window.addEventListener('load', () => {
            const storedScroll = sessionStorage.getItem(financeScrollKey);

            if (storedScroll !== null) {
                sessionStorage.removeItem(financeScrollKey);
                window.scrollTo(0, Number(storedScroll));
            }
        });
    </script>
</body>
</html>
