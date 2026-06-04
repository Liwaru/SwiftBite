<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Bahan</title>
    @include('owner.partials.report_styles')
    <style>
        .ingredient-filter-form {
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 14px;
            align-items: end;
        }

        .ingredient-filter-controls {
            display: grid;
            grid-template-columns: minmax(145px, .72fr) minmax(180px, 1fr) minmax(180px, 1fr) minmax(118px, auto);
            gap: 10px;
            align-items: end;
            min-width: 0;
        }

        .ingredient-filter-controls .filter-btn {
            width: 100%;
            min-width: 118px;
            min-height: 46px;
            height: 46px;
            align-self: end;
            box-sizing: border-box;
            padding: 0 12px;
        }

        .ingredient-export-menu {
            position: relative;
            min-width: 132px;
            align-self: end;
        }

        .ingredient-export-toggle {
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

        .ingredient-export-toggle:hover,
        .ingredient-export-menu.open .ingredient-export-toggle {
            background: #fff6e8;
            color: var(--brown-dark);
        }

        .ingredient-export-dropdown {
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

        .ingredient-export-menu.open .ingredient-export-dropdown {
            display: grid;
        }

        .ingredient-export-dropdown .export-btn {
            width: 100%;
            min-height: 40px;
            justify-content: flex-start;
            border: 0;
            background: transparent;
            color: var(--brown-dark);
            padding: 9px 10px;
        }

        .ingredient-export-dropdown .export-btn:hover {
            background: #f4e3cd;
        }

        .ingredient-export-dropdown button.export-btn {
            font: inherit;
            font-weight: 900;
        }

        @media (max-width: 1180px) {
            .ingredient-filter-form {
                grid-template-columns: 1fr;
            }

            .ingredient-export-menu {
                justify-self: end;
            }
        }

        @media (max-width: 760px) {
            .ingredient-filter-form,
            .ingredient-filter-controls {
                grid-template-columns: 1fr;
            }

            .ingredient-export-menu {
                justify-self: stretch;
            }

            .ingredient-export-dropdown {
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
                    <h1 class="hero-title">Laporan Bahan</h1>
                    <p class="hero-subtitle">Pantau persediaan, penggunaan, bahan menipis, dan riwayat pergerakan bahan baku.</p>
                </section>

                <section class="panel filter-panel">
                    <form method="GET" action="{{ route('owner.ingredients') }}" class="filter-form ingredient-filter-form">
                        <div class="ingredient-filter-controls">
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
                                <input id="startDate" type="date" name="start_date" value="{{ $startDate->toDateString() }}">
                            </div>
                            <div class="filter-field">
                                <label for="endDate">Tanggal Akhir</label>
                                <input id="endDate" type="date" name="end_date" value="{{ $endDate->toDateString() }}">
                            </div>
                            <button type="submit" class="filter-btn">Terapkan</button>
                        </div>
                        <div class="ingredient-export-menu" id="ingredientExportMenu">
                            <button type="button" class="ingredient-export-toggle" id="ingredientExportToggle" aria-expanded="false" aria-controls="ingredientExportDropdown">Export v</button>
                            <div class="ingredient-export-dropdown" id="ingredientExportDropdown">
                                <a class="export-btn" href="{{ route('owner.ingredients', array_merge(request()->except('export'), ['export' => 'excel'])) }}">Export Excel</a>
                                <a class="export-btn" href="{{ route('owner.ingredients', array_merge(request()->except('export'), ['export' => 'pdf'])) }}">Export PDF</a>
                                <button type="button" class="export-btn" onclick="window.print()">Print</button>
                            </div>
                        </div>
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
                        <h2>Top Bahan Paling Banyak Digunakan</h2>
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
                        <div class="panel-head">
                            <h2>Ringkasan Penggunaan Bahan</h2>
                            <div class="period-note">{{ $periodOptions[$period] ?? 'Periode' }}: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</div>
                        </div>
                        <div class="table-wrap">
                            <table class="summary-table">
                                <thead>
                                    <tr>
                                        <th>Bahan</th>
                                        <th>Stok Awal</th>
                                        <th>Masuk {{ $periodOptions[$period] ?? '' }}</th>
                                        <th>Digunakan {{ $periodOptions[$period] ?? '' }}</th>
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
    <script>
        (function () {
            const menu = document.getElementById('ingredientExportMenu');
            const toggle = document.getElementById('ingredientExportToggle');

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
