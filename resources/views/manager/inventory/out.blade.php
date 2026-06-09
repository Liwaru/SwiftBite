<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Barang Keluar</title>
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
                        <div class="eyebrow">Manager Inventori</div>
                        <h1 class="hero-title">Barang Keluar</h1>
                        <p class="hero-subtitle">Catat bahan yang keluar karena rusak, expired, hilang, atau koreksi stok.</p>
                    </div>
                </section>

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

                <section class="table-card">
                    <div class="table-header">
                        <div>
                            <div class="table-title">Tambah Barang Keluar</div>
                            <div class="table-subtitle">Stok bahan akan berkurang otomatis setelah disimpan.</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('manager.ingredient-out.store') }}" class="inventory-form">
                        @csrf

                        <div class="inventory-grid">
                            <div class="filter-field field-wide">
                                <label>Bahan / Barang</label>
                                <select name="id_bahan" required>
                                    <option value="">Pilih bahan</option>
                                    @foreach ($ingredients as $ingredient)
                                        <option value="{{ $ingredient->id_bahan }}">
                                            {{ $ingredient->nama_bahan }}
                                            - stok {{ number_format($ingredient->stok, 2, ',', '.') }} {{ $ingredient->satuan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="filter-field">
                                <label>Jumlah Keluar</label>
                                <input type="number" name="qty" min="0.01" step="0.01" placeholder="Contoh: 2" required>
                            </div>

                            <div class="filter-field">
                                <label>Alasan</label>
                                <select name="reason" required>
                                    <option value="">Pilih alasan</option>
                                    <option value="Rusak">Rusak</option>
                                    <option value="Expired">Expired</option>
                                    <option value="Hilang">Hilang</option>
                                    <option value="Koreksi Stok">Koreksi Stok</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div class="filter-field">
                                <label>Keterangan</label>
                                <input type="text" name="note" maxlength="255" placeholder="Contoh: Susu expired">
                            </div>

                            <div class="inventory-submit">
                                <button class="filter-btn" type="submit">Simpan Barang Keluar</button>
                            </div>
                        </div>
                    </form>
                </section>

                <section class="table-card">
                    <div class="table-header">
                        <div>
                            <div class="table-title">Riwayat Barang Keluar</div>
                            <div class="table-subtitle">Daftar bahan yang pernah dikurangi dari stok.</div>
                        </div>
                    </div>

                    <div class="table-wrap inventory-table-wrap">
                        <table class="inventory-table">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Barang</th>
                                    <th>Jumlah Keluar</th>
                                    <th>Alasan</th>
                                    <th>Keterangan</th>
                                    <th>Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ingredientOuts as $item)
                                    <tr>
                                        <td>{{ $item->created_at?->format('d M Y H:i') }}</td>
                                        <td><strong>{{ $item->ingredient?->nama_bahan ?? '-' }}</strong></td>
                                        <td>
                                            {{ number_format($item->qty, 2, ',', '.') }}
                                            {{ $item->ingredient?->satuan }}
                                        </td>
                                        <td>{{ $item->reason ?: '-' }}</td>
                                        <td>{{ $item->note ?: '-' }}</td>
                                        <td>{{ $item->actor_name ?: '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="empty-state">Belum ada barang keluar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($ingredientOuts->hasPages())
                        <div class="pagination-wrap">
                            {{ $ingredientOuts->links() }}
                        </div>
                    @endif
                </section>
            </div>
        </main>
    </div>
</div>

<style>
    .inventory-form {
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 12px;
        padding: 16px;
        background: rgba(255, 255, 255, 0.04);
    }

    .inventory-grid {
        display: grid;
        grid-template-columns: 1.4fr 0.6fr 0.8fr 1fr auto;
        gap: 12px;
        align-items: end;
    }

    .inventory-submit {
        display: flex;
        align-items: end;
    }

    .inventory-submit .filter-btn {
        height: 48px;
        white-space: nowrap;
        padding-inline: 20px;
    }

    .inventory-table th,
    .inventory-table td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .inventory-table th:nth-child(5),
    .inventory-table td:nth-child(5) {
        white-space: normal;
        min-width: 220px;
    }

    .inventory-table-wrap {
        border-radius: 12px;
        overflow-x: auto;
    }

    @media (max-width: 1200px) {
        .inventory-grid {
            grid-template-columns: 1fr 1fr;
        }

        .field-wide {
            grid-column: span 2;
        }

        .inventory-submit {
            grid-column: span 2;
        }

        .inventory-submit .filter-btn {
            width: 100%;
        }
    }

    @media (max-width: 640px) {
        .inventory-grid {
            grid-template-columns: 1fr;
        }

        .field-wide,
        .inventory-submit {
            grid-column: span 1;
        }
    }
</style>

@include('manager.partials.page_scripts')
</body>
</html>