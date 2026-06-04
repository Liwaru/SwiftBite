<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page['title'] }}</title>
    @include('manager.partials.page_styles')
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                    <div class="page-shell">
                        <section class="hero-card">
                            <div>
                                <div class="eyebrow">Manager Operasional</div>
                                <h1 class="hero-title">Stok Produk</h1>
                                <p class="hero-subtitle">Pantau dan kelola jumlah stok makanan dan minuman SwiftBite Morning Bakery.</p>
                            </div>
                        </section>

                        @php
                            $renderStockSection = function ($title, $description, $items) {
                                return compact('title', 'description', 'items');
                            };

                            $stockSections = [
                                $renderStockSection('Makanan', 'Kelola stok produk makanan dan bakery.', $foodStockItems),
                                $renderStockSection('Minuman', 'Kelola stok produk minuman.', $drinkStockItems),
                            ];
                        @endphp

                        <section class="summary-grid">
                            <article class="summary-card">
                                <div class="summary-label">Total Produk</div>
                                <div class="summary-value">{{ number_format($stockSummary['total_produk']) }}</div>
                                <div class="summary-note">Produk yang memiliki stok</div>
                            </article>
                            <article class="summary-card is-accent">
                                <div class="summary-label">Stok Aman</div>
                                <div class="summary-value">{{ number_format($stockSummary['stok_aman']) }}</div>
                                <div class="summary-note">Lebih dari 5 pcs</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Stok Menipis</div>
                                <div class="summary-value">{{ number_format($stockSummary['stok_menipis']) }}</div>
                                <div class="summary-note">1 sampai 5 pcs</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Stok Habis</div>
                                <div class="summary-value">{{ number_format($stockSummary['stok_habis']) }}</div>
                                <div class="summary-note">0 pcs</div>
                            </article>
                        </section>

                        @if (session('success') || $errors->any())
                            <div class="feedback-stack">
                                @if (session('success'))
                                    <div class="success-banner">{{ session('success') }}</div>
                                @endif

                                @if ($errors->any())
                                    <div class="error-banner">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="menu-sections">
                            @foreach ($stockSections as $stockSection)
                                <section class="section-card">
                                    <div class="section-head">
                                        <div>
                                            <div class="section-title-row">
                                                <div class="section-title">{{ $stockSection['title'] }}</div>
                                                <div class="section-meta">{{ $stockSection['items']->count() }} Produk</div>
                                            </div>
                                            <div class="section-subtitle">{{ $stockSection['description'] }}</div>
                                        </div>
                                    </div>

                                    @if ($stockSection['items']->isEmpty())
                                        <div class="empty-state">Belum ada produk {{ strtolower($stockSection['title']) }}.</div>
                                    @else
                                        <div class="menu-carousel">
                                            <button type="button" class="menu-carousel-btn js-menu-scroll" data-direction="-1" aria-label="Geser stok {{ $stockSection['title'] }} ke kiri">&lsaquo;</button>

                                            <div class="menu-rail">
                                                @foreach ($stockSection['items'] as $menu)
                                                    @php
                                                        $stock = (int) $menu->stok;
                                                        $stockStatus = $stock <= 0 ? 'Habis' : ($stock <= 5 ? 'Menipis' : 'Aman');
                                                        $stockClass = $stock <= 0 ? 'empty' : ($stock <= 5 ? 'low' : 'safe');
                                                        $initial = strtoupper(substr($menu->nama_menu, 0, 1));
                                                    @endphp

                                                    <article class="menu-card">
                                                        <div class="menu-thumb">
                                                            @if ($menu->foto)
                                                                <img src="{{ asset($menu->foto) }}" alt="{{ $menu->nama_menu }}" draggable="false">
                                                            @else
                                                                {{ $initial }}
                                                            @endif
                                                        </div>

                                                        <div class="menu-card-body">
                                                            <div class="menu-card-name">{{ $menu->nama_menu }}</div>
                                                            <div class="stock-current">
                                                                <div class="stock-current-label">Stok Saat Ini</div>
                                                                <div class="stock-current-value">{{ number_format($stock) }} pcs</div>
                                                                <div><span class="stock-badge-status {{ $stockClass }}">{{ $stockStatus }}</span></div>
                                                            </div>
                                                        </div>

                                                        <div class="menu-card-actions stock-actions">
                                                            <button
                                                                type="button"
                                                                class="row-action js-open-modal js-stock-menu"
                                                                data-modal="stock-menu"
                                                                data-action="{{ route('manager.stock.update', $menu) }}"
                                                                data-name="{{ $menu->nama_menu }}"
                                                                data-stock="{{ $stock }}"
                                                            >Kelola Stok</button>
                                                        </div>
                                                    </article>
                                                @endforeach
                                            </div>

                                            <button type="button" class="menu-carousel-btn js-menu-scroll" data-direction="1" aria-label="Geser stok {{ $stockSection['title'] }} ke kanan">&rsaquo;</button>
                                        </div>
                                    @endif
                                </section>
                            @endforeach
                        </div>

                        <div class="modal-shell" id="modal-stock-menu" aria-hidden="true">
                            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalStockMenuTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalStockMenuTitle">Kelola Stok</div>
                                        <div class="modal-subtitle js-stock-menu-subtitle">Perbarui jumlah stok produk.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>

                                <form method="POST" action="#" class="modal-form js-stock-form">
                                    @csrf
                                    @method('patch')

                                    <div class="stock-modal-summary">
                                        <div class="stock-modal-product js-stock-product-name">-</div>
                                        <div class="stock-modal-current">Stok saat ini: <span class="js-stock-current">0</span> pcs</div>
                                    </div>

                                    <div class="field-group">
                                        <label>Jenis Perubahan</label>
                                        <div class="stock-change-options">
                                            <label class="stock-change-option" for="stockChangeAdd">
                                                <input id="stockChangeAdd" type="radio" name="change_type" value="add" checked>
                                                <span><b>+</b><em>Tambah Stok</em></span>
                                            </label>
                                            <label class="stock-change-option" for="stockChangeSubtract">
                                                <input id="stockChangeSubtract" type="radio" name="change_type" value="subtract">
                                                <span><b>-</b><em>Kurangi Stok</em></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="field-group">
                                        <label for="stockAmountInput">Jumlah Perubahan</label>
                                        <input id="stockAmountInput" type="number" name="amount" min="1" max="999" step="1" placeholder="Contoh: 5" required>
                                    </div>

                                    <div class="field-group">
                                        <label for="stockNote">Keterangan (Opsional)</label>
                                        <input id="stockNote" type="text" name="note" maxlength="120" placeholder="Contoh: Restock pagi, penyesuaian stok">
                                    </div>

                                    <div class="modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                        <button type="submit" class="submit-btn">Simpan Stok</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
            </main>
        </div>
    </div>
    @include('manager.partials.page_scripts')
</body>
</html>
