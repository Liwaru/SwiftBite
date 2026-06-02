<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Produk</title>
    @include('owner.partials.report_styles')
</head>
<body>
    <div class="app-shell">
        @include('header')
        <div class="content-with-sidebar">
            <main>
                <section class="hero-card">
                    <div class="eyebrow">Owner Report</div>
                    <h1 class="hero-title">Laporan Produk</h1>
                    <p class="hero-subtitle">Pantau performa menu dari jumlah terjual, pendapatan per produk, dan distribusi kategori.</p>
                </section>

                <section class="panel filter-panel">
                    <form method="GET" action="{{ route('owner.products') }}" class="filter-form">
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
                        <a class="export-btn" href="{{ route('owner.products', array_merge(request()->except('export'), ['export' => 'pdf'])) }}">Export PDF</a>
                        <a class="export-btn" href="{{ route('owner.products', array_merge(request()->except('export'), ['export' => 'excel'])) }}">Export Excel</a>
                    </form>
                </section>

                <section class="stats">
                    <article class="stat-card"><span>Total Produk Terjual</span><strong>{{ number_format($summary['total_products_sold']) }}</strong></article>
                    <article class="stat-card"><span>Total Menu Aktif</span><strong>{{ number_format($summary['total_active_menu']) }}</strong></article>
                    <article class="stat-card"><span>Produk Terlaris</span><strong>{{ $summary['best_product'] }}</strong></article>
                    <article class="stat-card"><span>Produk Terendah</span><strong>{{ $summary['lowest_product'] }}</strong></article>
                </section>

                <section class="report-grid">
                    <div class="panel">
                        <div class="panel-head">
                            <h2>Top 5 Produk Terlaris</h2>
                            <div class="period-note">{{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</div>
                        </div>
                        @if ($topProducts->isEmpty())
                            <p class="empty-state">Belum ada produk terjual pada periode ini.</p>
                        @else
                            <div class="list-stack">
                                @foreach ($topProducts as $product)
                                    <div class="row">
                                        <span>{{ $product->nama_menu }}</span>
                                        <strong>{{ number_format($product->total_sold) }}</strong>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="panel">
                        <h2>Top 5 Produk Kurang Diminati</h2>
                        @if ($lowProducts->isEmpty())
                            <p class="empty-state">Belum ada data produk.</p>
                        @else
                            <div class="list-stack">
                                @foreach ($lowProducts as $product)
                                    <div class="row">
                                        <span>{{ $product->nama_menu }}</span>
                                        <strong>{{ number_format($product->total_sold) }}</strong>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>

                <section class="report-grid" style="margin-top: 16px;">
                    <div class="panel">
                        <h2>Pendapatan per Produk</h2>
                        @if ($revenueByProduct->isEmpty())
                            <p class="empty-state">Belum ada pendapatan produk pada periode ini.</p>
                        @else
                            <div class="list-stack">
                                @foreach ($revenueByProduct as $product)
                                    <div class="row">
                                        <span>{{ $product->nama_menu }}</span>
                                        <strong>Rp{{ number_format($product->total_revenue, 0, ',', '.') }}</strong>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="panel">
                        <h2>Distribusi Kategori</h2>
                        @if ($categoryDistribution->isEmpty())
                            <p class="empty-state">Belum ada data kategori.</p>
                        @else
                            <div class="list-stack">
                                @foreach ($categoryDistribution as $category)
                                    <div class="row">
                                        <div>
                                            <strong>{{ $category['category'] }}</strong>
                                            <div class="bar-track"><div class="bar-fill" style="width: {{ max(4, $category['percentage']) }}%;"></div></div>
                                        </div>
                                        <strong>{{ number_format($category['total_sold']) }} Terjual</strong>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>

                <section class="report-grid single" style="margin-top: 16px;">
                    <div class="panel">
                        <h2>Detail Produk</h2>
                        @if ($productRows->isEmpty())
                            <p class="empty-state">Belum ada produk yang terdaftar.</p>
                        @else
                            <div class="table-wrap">
                                <table class="summary-table">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Kategori</th>
                                            <th>Harga</th>
                                            <th>Jumlah Terjual</th>
                                            <th>Pendapatan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productRows as $product)
                                            <tr>
                                                <td>{{ $product->nama_menu }}</td>
                                                <td>{{ $product->nama_kategori }}</td>
                                                <td>Rp{{ number_format($product->harga, 0, ',', '.') }}</td>
                                                <td>{{ number_format($product->total_sold) }}</td>
                                                <td>Rp{{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                                                <td><span class="status neutral">{{ $product->status === 'tersedia' ? 'Aktif' : 'Habis' }}</span></td>
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
