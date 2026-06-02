<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Bahan</title>
    @include('owner.partials.report_styles')
</head>
<body>
    <div class="app-shell">
        @include('header')
        <div class="content-with-sidebar">
            <main>
                <section class="hero-card">
                    <div class="eyebrow">Owner Report</div>
                    <h1 class="hero-title">Laporan Bahan</h1>
                    <p class="hero-subtitle">Pantau persediaan, penggunaan, bahan menipis, dan riwayat pergerakan bahan baku.</p>
                </section>

                <section class="panel filter-panel">
                    <form method="GET" action="{{ route('owner.ingredients') }}" class="filter-form">
                        <div class="filter-field">
                            <label for="period">Periode</label>
                            <select id="period" name="period">
                                @foreach ($periodOptions as $value => $label)
                                    <option value="{{ $value }}" @selected($period === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-field">
                            <label for="startDate">Tanggal Mulai</label>
                            <input id="startDate" type="date" name="start_date" value="{{ request('start_date', $startDate->toDateString()) }}">
                        </div>
                        <div class="filter-field">
                            <label for="endDate">Tanggal Akhir</label>
                            <input id="endDate" type="date" name="end_date" value="{{ request('end_date', $endDate->toDateString()) }}">
                        </div>
                        <button type="submit" class="filter-btn">Terapkan</button>
                        <a class="export-btn" href="{{ route('owner.ingredients', array_merge(request()->except('export'), ['export' => 'pdf'])) }}">Export PDF</a>
                        <a class="export-btn" href="{{ route('owner.ingredients', array_merge(request()->except('export'), ['export' => 'excel'])) }}">Export Excel</a>
                    </form>
                </section>

                <section class="stats">
                    <article class="stat-card"><span>Total Bahan</span><strong>{{ number_format($summary['total']) }}</strong></article>
                    <article class="stat-card"><span>Bahan Aman</span><strong>{{ number_format($summary['aman']) }}</strong></article>
                    <article class="stat-card"><span>Bahan Menipis</span><strong>{{ number_format($summary['menipis']) }}</strong></article>
                    <article class="stat-card"><span>Bahan Habis</span><strong>{{ number_format($summary['habis']) }}</strong></article>
                </section>

                <section class="report-grid">
                    <div class="panel">
                        <div class="panel-head">
                            <h2>Top Bahan Paling Banyak Digunakan</h2>
                            <div class="period-note">{{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</div>
                        </div>
                        @if ($topUsedIngredients->isEmpty())
                            <p class="empty-state">Belum ada bahan yang digunakan pada periode ini.</p>
                        @else
                            <div class="list-stack">
                                @foreach ($topUsedIngredients as $ingredient)
                                    <div class="row">
                                        <span>{{ $ingredient['nama_bahan'] }}</span>
                                        <strong>{{ number_format($ingredient['digunakan'], 2, ',', '.') }} {{ $ingredient['satuan'] }}</strong>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="panel">
                        <h2>Daftar Bahan Menipis</h2>
                        @if ($lowIngredients->isEmpty())
                            <p class="empty-state">Tidak ada bahan yang menipis atau habis.</p>
                        @else
                            <div class="list-stack">
                                @foreach ($lowIngredients as $ingredient)
                                    <div class="row">
                                        <span>{{ $ingredient->nama_bahan }}</span>
                                        <strong>{{ number_format($ingredient->stok, 2, ',', '.') }} {{ $ingredient->satuan }}</strong>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>

                <section class="report-grid single" style="margin-top: 16px;">
                    <div class="panel">
                        <h2>Ringkasan Penggunaan Bahan</h2>
                        <div class="table-wrap">
                            <table class="summary-table">
                                <thead>
                                    <tr>
                                        <th>Bahan</th>
                                        <th>Stok Awal</th>
                                        <th>Masuk</th>
                                        <th>Digunakan</th>
                                        <th>Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usageSummary as $row)
                                        <tr>
                                            <td>{{ $row['nama_bahan'] }}</td>
                                            <td>{{ number_format($row['stok_awal'], 2, ',', '.') }} {{ $row['satuan'] }}</td>
                                            <td>{{ number_format($row['masuk'], 2, ',', '.') }} {{ $row['satuan'] }}</td>
                                            <td>{{ number_format($row['digunakan'], 2, ',', '.') }} {{ $row['satuan'] }}</td>
                                            <td>{{ number_format($row['sisa'], 2, ',', '.') }} {{ $row['satuan'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section class="report-grid single" style="margin-top: 16px;">
                    <div class="panel">
                        <h2>Riwayat Pergerakan Bahan</h2>
                        @if ($movementRows->isEmpty())
                            <p class="empty-state">Belum ada pergerakan bahan pada periode ini.</p>
                        @else
                            <div class="table-wrap">
                                <table class="summary-table">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Bahan</th>
                                            <th>Jenis</th>
                                            <th>Jumlah</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($movementRows as $row)
                                            <tr>
                                                <td>{{ $row['date']?->format('d/m/Y') ?? '-' }}</td>
                                                <td>{{ $row['ingredient'] }}</td>
                                                <td><span class="status neutral">{{ $row['type'] }}</span></td>
                                                <td>{{ number_format($row['qty'], 2, ',', '.') }} {{ $row['unit'] }}</td>
                                                <td>{{ $row['note'] }}</td>
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
</body>
</html>
