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

                            $packageSections = [
                                [
                                    'title' => 'Promo',
                                    'description' => 'Kelola promo bundling yang tampil sebelum paket dan menu reguler.',
                                    'items' => $promoPackageItems,
                                    'add_label' => '+ Tambah Promo',
                                    'empty' => 'Belum ada promo.',
                                ],
                                [
                                    'title' => 'Paket',
                                    'description' => 'Kelola paket bundling makanan dan minuman.',
                                    'items' => $regularPackageItems,
                                    'add_label' => '+ Tambah Paket',
                                    'empty' => 'Belum ada paket.',
                                ],
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
                                <div class="summary-label">Total Paket</div>
                                <div class="summary-value">{{ number_format($menuSummary['paket']) }}</div>
                                <div class="summary-note">Promo bundling aktif</div>
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
                            @foreach ($packageSections as $packageSection)
                                <section class="section-card">
                                    <div class="section-head">
                                        <div>
                                            <div class="section-title-row">
                                                <div class="section-title">{{ $packageSection['title'] }}</div>
                                                <div class="section-meta">{{ $packageSection['items']->count() }} {{ $packageSection['title'] }}</div>
                                            </div>
                                            <div class="section-subtitle">{{ $packageSection['description'] }}</div>
                                        </div>
                                        <div class="section-actions">
                                            <button type="button" class="section-add-btn js-open-modal" data-modal="create-package">
                                                {{ $packageSection['add_label'] }}
                                            </button>
                                        </div>
                                    </div>

                                    @if ($packageSection['items']->isEmpty())
                                        <div class="empty-state">{{ $packageSection['empty'] }}</div>
                                    @else
                                        <div class="menu-carousel">
                                            <button type="button" class="menu-carousel-btn js-menu-scroll" data-direction="-1" aria-label="Geser {{ strtolower($packageSection['title']) }} ke kiri">&lsaquo;</button>

                                            <div class="menu-rail">
                                                @foreach ($packageSection['items'] as $package)
                                                    @php
                                                        $todayDate = today();
                                                        $packageStatusLabel = $package->status === 'tersedia' ? 'Aktif' : 'Nonaktif';
                                                        if ($package->status === 'tersedia' && $package->ends_at && $package->ends_at->lt($todayDate)) {
                                                            $packageStatusLabel = 'Berakhir';
                                                        } elseif ($package->status === 'tersedia' && $package->starts_at && $package->starts_at->gt($todayDate)) {
                                                            $packageStatusLabel = 'Terjadwal';
                                                        }
                                                        $packagePeriodLabel = $package->starts_at || $package->ends_at
                                                            ? ($package->starts_at?->format('d M Y') ?? 'Awal') . ' - ' . ($package->ends_at?->format('d M Y') ?? 'Tanpa akhir')
                                                            : 'Tampil selalu';
                                                    @endphp

                                                    <article class="menu-card package-card" data-menu-name="{{ $package->nama_paket }}">
                                                        <div class="menu-thumb">
                                                            @if ($package->foto)
                                                                <img src="{{ asset($package->foto) }}" alt="{{ $package->nama_paket }}" draggable="false">
                                                            @else
                                                                <span class="package-thumb-fallback">{{ $package->nama_paket }}</span>
                                                            @endif
                                                        </div>

                                                        <div class="menu-card-body">
                                                            <div class="menu-card-name">{{ $package->nama_paket }}</div>
                                                            @if ($package->deskripsi)
                                                                <div class="menu-card-description">{{ $package->deskripsi }}</div>
                                                            @endif
                                                            <div class="package-lines">
                                                                @foreach ($package->items as $packageItem)
                                                                    <div>{{ $packageItem->qty }} {{ $packageItem->menuItem?->nama_menu ?? 'Menu' }} tetap</div>
                                                                @endforeach
                                                                @foreach ($package->choices as $choice)
                                                                    <div>{{ $choice->qty }} {{ strtolower($choice->category) }} bebas dipilih customer</div>
                                                                @endforeach
                                                            </div>
                                                            <div class="menu-card-description">Periode: {{ $packagePeriodLabel }}</div>
                                                            <div class="menu-card-price">Rp{{ number_format($package->harga, 0, ',', '.') }}</div>
                                                            <div><span class="status-badge">{{ $packageStatusLabel }}</span></div>
                                                        </div>

                                                        <div class="menu-card-actions">
                                                            <button
                                                                type="button"
                                                                class="row-action js-open-modal js-edit-package"
                                                                data-modal="edit-package"
                                                                data-action="{{ route('manager.packages.update', $package) }}"
                                                                data-name="{{ $package->nama_paket }}"
                                                                data-description="{{ $package->deskripsi }}"
                                                                data-price="{{ (int) $package->harga }}"
                                                                data-status="{{ $package->status }}"
                                                                data-starts-at="{{ $package->starts_at?->toDateString() }}"
                                                                data-ends-at="{{ $package->ends_at?->toDateString() }}"
                                                                data-photo="{{ $package->foto ? asset($package->foto) : '' }}"
                                                                data-items='@json($package->items->mapWithKeys(fn ($item) => [$item->id_menu => $item->qty]))'
                                                                data-choices='@json($package->choices->mapWithKeys(fn ($choice) => [$choice->category => $choice->qty]))'
                                                            >Edit</button>
                                                            <form method="POST" action="{{ route('manager.packages.destroy', $package) }}" class="package-delete-form" onsubmit="return confirm('Hapus paket ini?')">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit" class="row-action">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </article>
                                                @endforeach
                                            </div>

                                            <button type="button" class="menu-carousel-btn js-menu-scroll" data-direction="1" aria-label="Geser {{ strtolower($packageSection['title']) }} ke kanan">&rsaquo;</button>
                                        </div>
                                    @endif
                                </section>
                            @endforeach

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
                                                                <img src="{{ asset($menu->foto) }}" alt="{{ $menu->nama_menu }}" draggable="false">
                                                            @else
                                                                {{ $initial }}
                                                            @endif
                                                        </div>

                                                        <div class="menu-card-body">
                                                            <div class="menu-card-name">{{ $menu->nama_menu }}</div>
                                                            <div class="menu-card-price">Rp{{ number_format($menu->harga, 0, ',', '.') }}</div>
                                                            <div class="menu-card-stock">Stok {{ $menu->stok }}</div>
                                                            <div><span class="status-badge">{{ $statusLabel }}</span></div>
                                                        </div>

                                                        <div class="menu-card-actions">
                                                            <button
                                                                type="button"
                                                                class="row-action js-open-modal js-edit-menu"
                                                                data-modal="edit-menu"
                                                                data-action="{{ route('manager.menus.update', $menu) }}"
                                                                data-name="{{ $menu->nama_menu }}"
                                                                data-barcode="{{ $menu->barcode }}"
                                                                data-description="{{ $menu->deskripsi }}"
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
                                    <input type="hidden" name="modal_id" value="create-menu">
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

                                    @php
                                        $createInputMode = old('barcode') ? 'barcode' : 'manual';
                                    @endphp
                                    <div class="menu-input-mode">
                                        <button type="button" class="menu-input-mode-btn {{ $createInputMode === 'manual' ? 'active' : '' }}" data-create-menu-mode="manual">Manual</button>
                                        <button type="button" class="menu-input-mode-btn {{ $createInputMode === 'barcode' ? 'active' : '' }}" data-create-menu-mode="barcode">Lewat Barcode</button>
                                    </div>

                                    <div class="field-group js-create-barcode-field" @if ($createInputMode !== 'barcode') hidden @endif>
                                        <label for="createMenuBarcode">Barcode</label>
                                        <div class="barcode-input-wrap">
                                            <input id="createMenuBarcode" type="text" name="barcode" value="{{ old('barcode') }}" maxlength="80" inputmode="numeric" autocomplete="off" placeholder="Scan barcode produk">
                                            <button type="button" class="qr-open-btn js-open-qr" data-target="createMenuBarcode" aria-label="Buka scanner QR">
                                                <i class="bi bi-qr-code" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="field-group">
                                        <label for="createMenuName">Nama <span class="js-menu-category-label">Makanan</span></label>
                                        <input id="createMenuName" type="text" name="name" value="{{ old('name') }}" maxlength="20" placeholder="Maksimal 20 karakter" required>
                                    </div>

                                    <div class="field-group">
                                        <label for="createMenuDescription">Deskripsi</label>
                                        <textarea id="createMenuDescription" name="description" maxlength="300" rows="3" placeholder="Opsional, maksimal 300 karakter">{{ old('description') }}</textarea>
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

                        <div class="modal-shell" id="modal-create-package" aria-hidden="true">
                            <div class="modal-dialog menu-create-dialog package-create-dialog" role="dialog" aria-modal="true" aria-labelledby="modalCreatePackageTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalCreatePackageTitle">Tambah Paket Promo</div>
                                        <div class="modal-subtitle">Buat paket bundling dari makanan dan minuman yang tersedia.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>

                                <form method="POST" action="{{ route('manager.packages.store') }}" class="modal-form package-form" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="modal_id" value="create-package">

                                    <div class="field-group">
                                        <label for="createPackagePhoto">Gambar Paket</label>
                                        <input id="createPackagePhoto" type="file" name="photo" accept="image/png,image/jpeg,image/webp">
                                    </div>

                                    <div class="field-group">
                                        <label for="createPackageName">Nama Paket</label>
                                        <input id="createPackageName" type="text" name="name" value="{{ old('name') }}" maxlength="40" placeholder="Contoh: Paket Hemat Pagi" required>
                                    </div>

                                    <div class="field-group">
                                        <label for="createPackageDescription">Deskripsi Paket</label>
                                        <textarea id="createPackageDescription" name="description" maxlength="300" rows="3" placeholder="Opsional, maksimal 300 karakter">{{ old('description') }}</textarea>
                                    </div>

                                    <div class="package-builder js-package-builder">
                                        <div class="package-selected-head">
                                            <span>Isi Tetap Dari Manager</span>
                                            <span class="package-selected-count js-package-selected-count">0 Menu</span>
                                        </div>
                                        <div class="package-selected-list js-package-selected-list">
                                            <span class="package-selected-empty">Belum ada menu dipilih.</span>
                                        </div>
                                        <button type="button" class="package-add-menu-btn js-package-picker-toggle">+ Tambah Menu</button>

                                        <div class="package-picker js-package-picker-panel">
                                            <input type="search" class="package-search js-package-search" placeholder="Cari menu..." aria-label="Cari menu paket">
                                        @foreach ($availablePackageMenuItems->groupBy(fn ($menu) => $menu->category) as $category => $items)
                                            <div class="package-picker-group">
                                                <div class="package-picker-title">{{ $category }}</div>
                                                @foreach ($items as $menu)
                                                    <label class="package-picker-row" for="packageCheck{{ $menu->getKey() }}" data-menu-name="{{ strtolower($menu->nama_menu) }}" data-menu-category="{{ strtolower($menu->category) }}">
                                                        <input id="packageCheck{{ $menu->getKey() }}" type="checkbox" class="js-package-check" data-menu-id="{{ $menu->getKey() }}">
                                                        <span class="package-picker-info">
                                                            <strong>{{ $menu->nama_menu }}</strong>
                                                            <em>{{ $menu->category }} � Rp{{ number_format($menu->harga, 0, ',', '.') }}</em>
                                                        </span>
                                                        <input type="number" name="items[{{ $menu->getKey() }}]" class="package-qty js-package-qty" data-menu-id="{{ $menu->getKey() }}" data-menu-name="{{ $menu->nama_menu }}" value="{{ old('items.' . $menu->getKey(), 0) }}" min="0" max="99" aria-label="Jumlah {{ $menu->nama_menu }}">
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endforeach
                                        </div>
                                    </div>

                                    <div class="package-choice-box">
                                        <div class="package-selected-head">
                                            <span>Pilihan Bebas Yang Diizinkan Manager</span>
                                        </div>
                                        <div class="package-choice-grid">
                                            <label>
                                                Minuman bebas
                                                <input type="number" name="choice_categories[Minuman]" value="{{ old('choice_categories.Minuman', 0) }}" min="0" max="20">
                                            </label>
                                            <label>
                                                Makanan bebas
                                                <input type="number" name="choice_categories[Makanan]" value="{{ old('choice_categories.Makanan', 0) }}" min="0" max="20">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="field-group">
                                        <label for="createPackagePrice">Harga Paket</label>
                                        <input id="createPackagePrice" type="number" name="price" value="{{ old('price') }}" min="0" max="500000" step="1000" placeholder="Contoh: 25000" required>
                                    </div>

                                    <div class="field-group package-permanent-row">
                                        <input type="hidden" name="is_permanent" value="0">
                                        <label class="package-toggle-line">
                                            <input type="checkbox" name="is_permanent" value="1" class="js-package-permanent" @checked(old('is_permanent', '1'))>
                                            Tampilkan selalu
                                        </label>
                                    </div>

                                    <div class="package-choice-grid js-package-period" hidden>
                                        <label>
                                            Tanggal Mulai
                                            <input type="date" name="starts_at" value="{{ old('starts_at') }}">
                                        </label>
                                        <label>
                                            Tanggal Berakhir
                                            <input type="date" name="ends_at" value="{{ old('ends_at') }}">
                                        </label>
                                    </div>

                                    <div class="modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                        <button type="submit" class="submit-btn">Simpan Paket</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="modal-shell" id="modal-edit-package" aria-hidden="true">
                            <div class="modal-dialog menu-create-dialog package-create-dialog" role="dialog" aria-modal="true" aria-labelledby="modalEditPackageTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalEditPackageTitle">Edit Paket Promo</div>
                                        <div class="modal-subtitle">Perbarui gambar, nama, isi paket, harga, dan status.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>

                                <form method="POST" action="#" class="modal-form package-form js-package-edit-form" enctype="multipart/form-data">
                                    @csrf
                                    @method('patch')
                                    <input type="hidden" name="modal_id" value="edit-package">

                                    <div class="field-group">
                                        <label for="editPackagePhoto">Gambar Paket</label>
                                        <input id="editPackagePhoto" type="file" name="photo" class="js-edit-package-photo" accept="image/png,image/jpeg,image/webp">
                                    </div>

                                    <div class="field-group">
                                        <label for="editPackageName">Nama Paket</label>
                                        <input id="editPackageName" type="text" name="name" class="js-edit-package-name" maxlength="40" required>
                                    </div>

                                    <div class="field-group">
                                        <label for="editPackageDescription">Deskripsi Paket</label>
                                        <textarea id="editPackageDescription" name="description" class="js-edit-package-description" maxlength="300" rows="3" placeholder="Opsional, maksimal 300 karakter"></textarea>
                                    </div>

                                    <div class="package-builder js-package-builder">
                                        <div class="package-selected-head">
                                            <span>Isi Tetap Dari Manager</span>
                                            <span class="package-selected-count js-package-selected-count">0 Menu</span>
                                        </div>
                                        <div class="package-selected-list js-package-selected-list">
                                            <span class="package-selected-empty">Belum ada menu dipilih.</span>
                                        </div>
                                        <button type="button" class="package-add-menu-btn js-package-picker-toggle">+ Tambah Menu</button>

                                        <div class="package-picker js-package-picker-panel">
                                            <input type="search" class="package-search js-package-search" placeholder="Cari menu..." aria-label="Cari menu paket">
                                        @foreach ($availablePackageMenuItems->groupBy(fn ($menu) => $menu->category) as $category => $items)
                                            <div class="package-picker-group">
                                                <div class="package-picker-title">{{ $category }}</div>
                                                @foreach ($items as $menu)
                                                    <label class="package-picker-row" for="editPackageCheck{{ $menu->getKey() }}" data-menu-name="{{ strtolower($menu->nama_menu) }}" data-menu-category="{{ strtolower($menu->category) }}">
                                                        <input id="editPackageCheck{{ $menu->getKey() }}" type="checkbox" class="js-package-check" data-menu-id="{{ $menu->getKey() }}">
                                                        <span class="package-picker-info">
                                                            <strong>{{ $menu->nama_menu }}</strong>
                                                            <em>{{ $menu->category }} � Rp{{ number_format($menu->harga, 0, ',', '.') }}</em>
                                                        </span>
                                                        <input type="number" name="items[{{ $menu->getKey() }}]" class="package-qty js-package-qty js-edit-package-qty" data-menu-id="{{ $menu->getKey() }}" data-menu-name="{{ $menu->nama_menu }}" value="0" min="0" max="99" aria-label="Jumlah {{ $menu->nama_menu }}">
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endforeach
                                        </div>
                                    </div>

                                    <div class="package-choice-box">
                                        <div class="package-selected-head">
                                            <span>Pilihan Bebas Yang Diizinkan Manager</span>
                                        </div>
                                        <div class="package-choice-grid">
                                            <label>
                                                Minuman bebas
                                                <input type="number" name="choice_categories[Minuman]" class="js-edit-package-choice" data-category="Minuman" value="0" min="0" max="20">
                                            </label>
                                            <label>
                                                Makanan bebas
                                                <input type="number" name="choice_categories[Makanan]" class="js-edit-package-choice" data-category="Makanan" value="0" min="0" max="20">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="field-group">
                                        <label for="editPackagePrice">Harga Paket</label>
                                        <input id="editPackagePrice" type="number" name="price" class="js-edit-package-price" min="0" max="500000" step="1000" required>
                                    </div>

                                    <div class="field-group package-permanent-row">
                                        <input type="hidden" name="is_permanent" value="0">
                                        <label class="package-toggle-line">
                                            <input type="checkbox" name="is_permanent" value="1" class="js-package-permanent js-edit-package-permanent">
                                            Tampilkan selalu
                                        </label>
                                    </div>

                                    <div class="package-choice-grid js-package-period" hidden>
                                        <label>
                                            Tanggal Mulai
                                            <input type="date" name="starts_at" class="js-edit-package-starts-at">
                                        </label>
                                        <label>
                                            Tanggal Berakhir
                                            <input type="date" name="ends_at" class="js-edit-package-ends-at">
                                        </label>
                                    </div>

                                    <div class="field-group">
                                        <label for="editPackageStatus">Status</label>
                                        <select id="editPackageStatus" name="status" class="js-edit-package-status" required>
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
                                        <label for="editMenuBarcode">Barcode</label>
                                        <div class="barcode-input-wrap">
                                            <input id="editMenuBarcode" type="text" name="barcode" maxlength="80" inputmode="numeric" class="js-edit-menu-barcode" placeholder="Opsional">
                                            <button type="button" class="qr-open-btn js-open-qr" data-target="editMenuBarcode" aria-label="Buka scanner QR">
                                                <i class="bi bi-qr-code" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="field-group">
                                        <label for="editMenuDescription">Deskripsi</label>
                                        <textarea id="editMenuDescription" name="description" maxlength="300" rows="3" placeholder="Opsional, maksimal 300 karakter"></textarea>
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
                                            <div class="detail-label">Barcode</div>
                                            <div class="detail-value">{{ $menu->barcode ?: '-' }}</div>
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
