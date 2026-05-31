<!DOCTYPE html>
<html lang="id">
<head>
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
                                <h1 class="hero-title">Data Menu</h1>
                                <p class="hero-subtitle">Kelola makanan dan minuman yang tersedia di SwiftBite Morning Bakery.</p>
                            </div>
                        </section>

                        @php
                            $renderMenuSection = function ($title, $description, $items) {
                                return compact('title', 'description', 'items');
                            };

                            $menuSections = [
                                $renderMenuSection('Makanan', 'Daftar menu makanan dan bakery yang tersedia.', $foodMenuItems),
                                $renderMenuSection('Minuman', 'Daftar minuman yang tersedia.', $drinkMenuItems),
                            ];
                        @endphp

                        <section class="summary-grid">
                            <article class="summary-card">
                                <div class="summary-label">Total Menu</div>
                                <div class="summary-value">{{ number_format($menuSummary['total_menu']) }}</div>
                                <div class="summary-note">Semua menu terdaftar</div>
                            </article>
                            <article class="summary-card is-accent">
                                <div class="summary-label">Total Makanan</div>
                                <div class="summary-value">{{ number_format($menuSummary['makanan']) }}</div>
                                <div class="summary-note">Roti, pastry, dan dessert</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Total Minuman</div>
                                <div class="summary-value">{{ number_format($menuSummary['minuman']) }}</div>
                                <div class="summary-note">Kopi dan non-kopi</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Menu Aktif</div>
                                <div class="summary-value">{{ number_format($menuSummary['aktif']) }}</div>
                                <div class="summary-note">Menu yang tersedia</div>
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
                            @foreach ($menuSections as $menuSection)
                                <section class="section-card">
                                    <div class="section-head">
                                        <div>
                                            <div class="section-title-row">
                                                <div class="section-title">{{ $menuSection['title'] }}</div>
                                                <div class="section-meta">{{ $menuSection['items']->count() }} Menu</div>
                                            </div>
                                            <div class="section-subtitle">{{ $menuSection['description'] }}</div>
                                        </div>
                                        <div class="section-actions">
                                            <button type="button" class="section-add-btn js-open-modal" data-modal="create-menu" data-category="{{ $menuSection['title'] }}">
                                                + Tambah {{ $menuSection['title'] }}
                                            </button>
                                            <button type="button" class="section-add-btn section-secondary-btn section-manage-btn js-toggle-menu-manage">
                                                Kelola Menu
                                            </button>
                                        </div>
                                    </div>

                                    @if ($menuSection['items']->isEmpty())
                                        <div class="empty-state">Belum ada menu {{ strtolower($menuSection['title']) }}.</div>
                                    @else
                                        <form method="POST" action="{{ route('manager.menus.destroy') }}" class="js-bulk-delete-form">
                                            @csrf
                                            @method('delete')

                                            <div class="bulk-toolbar">
                                                <span><span class="js-selected-count">0</span> menu dipilih</span>
                                                <div class="bulk-actions">
                                                    <button type="button" class="bulk-cancel-btn js-cancel-menu-manage">Batal</button>
                                                    <button type="submit" class="bulk-delete-btn">Hapus Terpilih</button>
                                                </div>
                                            </div>

                                        <div class="menu-carousel">
                                            <button type="button" class="menu-carousel-btn js-menu-scroll" data-direction="-1" aria-label="Geser {{ $menuSection['title'] }} ke kiri">&lsaquo;</button>

                                            <div class="menu-rail">
                                                @foreach ($menuSection['items'] as $menu)
                                                    @php
                                                        $statusLabel = $menu->status === 'tersedia' ? 'Aktif' : 'Nonaktif';
                                                        $initial = strtoupper(substr($menu->nama_menu, 0, 1));
                                                    @endphp

                                                    <article class="menu-card" data-menu-id="{{ $menu->getKey() }}" data-menu-name="{{ $menu->nama_menu }}" data-menu-category="{{ $menu->category }}" data-menu-price="Rp{{ number_format($menu->harga, 0, ',', '.') }}">
                                                        <input type="checkbox" class="menu-select-control js-menu-select" name="menu_ids[]" value="{{ $menu->getKey() }}" aria-label="Pilih {{ $menu->nama_menu }}">
                                                        <div class="menu-thumb">
                                                            @if ($menu->foto)
                                                                <img src="{{ asset($menu->foto) }}" alt="{{ $menu->nama_menu }}">
                                                            @else
                                                                {{ $initial }}
                                                            @endif
                                                        </div>

                                                        <div class="menu-card-body">
                                                            <div class="menu-card-name">{{ $menu->nama_menu }}</div>
                                                            <div class="menu-card-price">Rp{{ number_format($menu->harga, 0, ',', '.') }}</div>
                                                            <div><span class="status-badge">{{ $statusLabel }}</span></div>
                                                        </div>

                                                        <div class="menu-card-actions">
                                                            <button
                                                                type="button"
                                                                class="row-action js-open-modal js-edit-menu"
                                                                data-modal="edit-menu"
                                                                data-action="{{ route('manager.menus.update', $menu) }}"
                                                                data-name="{{ $menu->nama_menu }}"
                                                                data-price="{{ (int) $menu->harga }}"
                                                                data-status="{{ $menu->status }}"
                                                                data-photo="{{ $menu->foto ? asset($menu->foto) : '' }}"
                                                            >Edit</button>
                                                            <button type="button" class="row-action js-single-delete-menu" data-menu-id="{{ $menu->getKey() }}">Hapus</button>
                                                        </div>
                                                    </article>
                                                @endforeach
                                            </div>

                                            <button type="button" class="menu-carousel-btn js-menu-scroll" data-direction="1" aria-label="Geser {{ $menuSection['title'] }} ke kanan">&rsaquo;</button>
                                        </div>
                                        </form>
                                    @endif
                                </section>
                            @endforeach
                        </div>

                        <div class="modal-shell" id="modal-create-menu" aria-hidden="true">
                            <div class="modal-dialog menu-create-dialog" role="dialog" aria-modal="true" aria-labelledby="modalCreateMenuTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalCreateMenuTitle">Tambah Menu</div>
                                        <div class="modal-subtitle" id="modalCreateMenuSubtitle">Tambahkan menu baru ke SwiftBite Morning Bakery.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>

                                <form method="POST" action="{{ route('manager.menus.store') }}" class="modal-form js-menu-create-form">
                                    @csrf
                                    <input type="hidden" name="category" id="createMenuCategory" value="Makanan">
                                    <input type="hidden" name="image_data" class="js-cropped-image">

                                    <div class="field-group">
                                        <label for="createMenuPhoto">Gambar Menu</label>
                                        <input id="createMenuPhoto" type="file" class="js-crop-input" accept="image/png,image/jpeg,image/webp">
                                    </div>

                                    <div class="crop-panel">
                                        <div class="crop-box js-crop-box">
                                            <div class="crop-empty js-crop-empty">Pilih gambar, lalu geser area ini untuk menentukan crop.</div>
                                            <img class="crop-image js-crop-image" alt="Preview crop menu">
                                        </div>
                                        <div class="crop-tools">
                                            <label for="createMenuZoom">Zoom Gambar</label>
                                            <input id="createMenuZoom" type="range" class="js-crop-zoom" min="1" max="2.5" step="0.01" value="1" disabled>
                                        </div>
                                    </div>

                                    <div class="field-group">
                                        <label for="createMenuName">Nama <span class="js-menu-category-label">Makanan</span></label>
                                        <input id="createMenuName" type="text" name="name" value="{{ old('name') }}" maxlength="20" placeholder="Maksimal 20 karakter" required>
                                    </div>

                                    <div class="field-group">
                                        <label for="createMenuPrice">Harga</label>
                                        <input id="createMenuPrice" type="number" name="price" value="{{ old('price') }}" min="0" max="50000" step="1000" placeholder="Maksimal 50000" required>
                                    </div>

                                    <div class="modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                        <button type="submit" class="submit-btn">Simpan Menu</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('manager.menus.destroy') }}" class="js-single-delete-form" hidden>
                            @csrf
                            @method('delete')
                            <input type="hidden" name="menu_ids[]" class="js-single-delete-id">
                        </form>

                        <div class="modal-shell" id="modal-edit-menu" aria-hidden="true">
                            <div class="modal-dialog menu-create-dialog" role="dialog" aria-modal="true" aria-labelledby="modalEditMenuTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalEditMenuTitle">Edit Menu</div>
                                        <div class="modal-subtitle">Perbarui gambar, nama, harga, dan status menu.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>

                                <form method="POST" action="#" class="modal-form js-menu-edit-form">
                                    @csrf
                                    @method('patch')
                                    <input type="hidden" name="image_data" class="js-edit-cropped-image">

                                    <div class="field-group">
                                        <label for="editMenuPhoto">Gambar Menu</label>
                                        <input id="editMenuPhoto" type="file" class="js-edit-crop-input" accept="image/png,image/jpeg,image/webp">
                                    </div>

                                    <div class="crop-panel">
                                        <div class="crop-box js-edit-crop-box">
                                            <div class="crop-empty js-edit-crop-empty">Gambar saat ini akan tetap dipakai jika tidak memilih gambar baru.</div>
                                            <img class="crop-image js-edit-crop-image" alt="Preview crop menu">
                                        </div>
                                        <div class="crop-tools">
                                            <label for="editMenuZoom">Zoom Gambar</label>
                                            <input id="editMenuZoom" type="range" class="js-edit-crop-zoom" min="1" max="2.5" step="0.01" value="1" disabled>
                                        </div>
                                    </div>

                                    <div class="field-group">
                                        <label for="editMenuName">Nama Menu</label>
                                        <input id="editMenuName" type="text" name="name" maxlength="20" placeholder="Maksimal 20 karakter" required>
                                    </div>

                                    <div class="field-group">
                                        <label for="editMenuPrice">Harga</label>
                                        <input id="editMenuPrice" type="number" name="price" min="0" max="50000" step="1000" placeholder="Maksimal 50000" required>
                                    </div>

                                    <div class="field-group">
                                        <label for="editMenuStatus">Status</label>
                                        <select id="editMenuStatus" name="status" required>
                                            <option value="tersedia">Aktif</option>
                                            <option value="habis">Nonaktif</option>
                                        </select>
                                    </div>

                                    <div class="modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                        <button type="submit" class="submit-btn">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="modal-shell" id="modal-confirm-delete-menu" aria-hidden="true">
                            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalConfirmDeleteMenuTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalConfirmDeleteMenuTitle">Hapus Menu?</div>
                                        <div class="modal-subtitle">Pastikan menu yang dipilih memang ingin dihapus.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>

                                <div class="detail-list">
                                    <div class="summary-total" style="margin-bottom: 0;">
                                        <span><span class="js-delete-menu-count">0</span> menu akan dihapus.</span>
                                    </div>

                                    <div class="table-wrap">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Nama Menu</th>
                                                    <th>Kategori</th>
                                                    <th>Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody class="js-delete-menu-list"></tbody>
                                        </table>
                                    </div>

                                    <div class="summary-total js-delete-menu-more" style="display: none; margin-top: 10px;">
                                        <span></span>
                                    </div>

                                    <div class="modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                        <button type="button" class="submit-btn js-confirm-delete-menu">Hapus Terpilih</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @foreach ($foodMenuItems->concat($drinkMenuItems) as $menu)
                            @php
                                $initial = strtoupper(substr($menu->nama_menu, 0, 1));
                                $statusLabel = $menu->status === 'tersedia' ? 'Aktif' : 'Nonaktif';
                                $totalSold = (int) ($menu->total_sold ?? 0);
                            @endphp

                            <div class="modal-shell" id="modal-detail-menu-{{ $menu->getKey() }}" aria-hidden="true">
                                <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalDetailMenuTitle{{ $menu->getKey() }}">
                                    <div class="modal-header">
                                        <div>
                                            <div class="modal-title" id="modalDetailMenuTitle{{ $menu->getKey() }}">Detail Menu</div>
                                            <div class="modal-subtitle">Informasi lengkap menu {{ $menu->nama_menu }}.</div>
                                        </div>
                                        <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                    </div>

                                    <div class="detail-list">
                                        <div class="menu-detail-thumb">{{ $initial }}</div>
                                        <div class="detail-row">
                                            <div class="detail-label">Nama Menu</div>
                                            <div class="detail-value">{{ $menu->nama_menu }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Kategori</div>
                                            <div class="detail-value">{{ $menu->category }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Harga</div>
                                            <div class="detail-value">Rp{{ number_format($menu->harga, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Stok Produk</div>
                                            <div class="detail-value">{{ $menu->stok }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Total Terjual</div>
                                            <div class="detail-value">{{ $totalSold }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Status</div>
                                            <div class="detail-value">{{ $statusLabel }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Deskripsi</div>
                                            <div class="detail-value">{{ $menu->deskripsi ?: '-' }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Tanggal Dibuat</div>
                                            <div class="detail-value">{{ $menu->created_at?->format('d M Y H:i') ?? '-' }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Tanggal Diperbarui</div>
                                            <div class="detail-value">{{ $menu->updated_at?->format('d M Y H:i') ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
            </main>
        </div>
    </div>
    @include('manager.partials.page_scripts')
</body>
</html>
