<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Produk</title>
    @include('owner.partials.report_styles')
    <style>
        .product-filter-form {
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 14px;
            align-items: end;
        }

        .product-filter-controls {
            display: grid;
            grid-template-columns: minmax(145px, .72fr) minmax(180px, 1fr) minmax(180px, 1fr) minmax(118px, auto);
            gap: 10px;
            min-width: 0;
        }

        .product-filter-controls .filter-btn {
            width: 100%;
            min-width: 118px;
            min-height: 46px;
            height: 46px;
            box-sizing: border-box;
            padding: 0 12px;
        }

        .product-export-menu {
            position: relative;
            min-width: 132px;
        }

        .product-export-toggle {
            width: 100%;
            min-height: 46px;
            height: 46px;
            border: 1px solid rgba(255, 246, 232, .58);
            border-radius: 8px;
            background: transparent;
            color: #fff8ed;
            padding: 0 14px;
            font: inherit;
            font-weight: 900;
            cursor: pointer;
            white-space: nowrap;
        }

        .product-export-toggle:hover,
        .product-export-menu.open .product-export-toggle {
            background: #fff6e8;
            color: var(--brown-dark);
        }

        .product-export-dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            z-index: 20;
            width: 170px;
            display: none;
            gap: 5px;
            padding: 8px;
            border: 1px solid #e1ad73;
            border-radius: 8px;
            background: #fff6e8;
            box-shadow: 0 18px 34px rgba(39, 20, 13, .24);
        }

        .product-export-menu.open .product-export-dropdown {
            display: grid;
        }

        .product-export-dropdown .export-btn {
            width: 100%;
            min-height: 40px;
            justify-content: flex-start;
            border: 0;
            background: transparent;
            color: var(--brown-dark);
            padding: 9px 10px;
        }

        .product-export-dropdown .export-btn:hover {
            background: #f4e3cd;
        }

        .product-export-dropdown button.export-btn {
            font: inherit;
            font-weight: 900;
        }

        @media (max-width: 1180px) {
            .product-filter-form {
                grid-template-columns: 1fr;
            }

            .product-export-menu {
                justify-self: end;
            }
        }

        @media (max-width: 760px) {
            .product-filter-form,
            .product-filter-controls {
                grid-template-columns: 1fr;
            }

            .product-export-menu {
                justify-self: stretch;
            }

            .product-export-dropdown {
                left: 0;
                right: 0;
                width: auto;
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
                    <h1 class="hero-title">Laporan Produk</h1>
                    <p class="hero-subtitle">Pantau performa menu dari jumlah terjual, pendapatan per produk, dan distribusi kategori.</p>
                </section>

                <section class="panel filter-panel">
                    <form method="GET" action="{{ route('owner.products') }}" class="filter-form product-filter-form">
                        <div class="product-filter-controls">
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
                        </div>
                        <div class="product-export-menu" id="productExportMenu">
                            <button type="button" class="product-export-toggle" id="productExportToggle" aria-expanded="false" aria-controls="productExportDropdown">Export ▾</button>
                            <div class="product-export-dropdown" id="productExportDropdown">
                                <a class="export-btn" href="{{ route('owner.products', array_merge(request()->except('export'), ['export' => 'excel'])) }}">Export Excel</a>
                                <a class="export-btn" href="{{ route('owner.products', array_merge(request()->except('export'), ['export' => 'pdf'])) }}">Export PDF</a>
                                <button type="button" class="export-btn" onclick="window.print()">Print</button>
                            </div>
                        </div>
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
    <script>
        (function () {
            const menu = document.getElementById('productExportMenu');
            const toggle = document.getElementById('productExportToggle');

            if (!menu || !toggle) {
                return;
            }

            function closeMenu() {
                menu.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }

            toggle.addEventListener('click', function () {
                const isOpen = menu.classList.toggle('open');
                toggle.setAttribute('aria-expanded', String(isOpen));
            });

            document.addEventListener('click', function (event) {
                if (!menu.contains(event.target)) {
                    closeMenu();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeMenu();
                }
            });
        })();
    </script>
</body>
</html>
