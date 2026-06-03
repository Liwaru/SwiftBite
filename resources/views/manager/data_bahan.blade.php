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
                            <h1 class="hero-title">Data Bahan</h1>
                            <p class="hero-subtitle">Kelola stok bahan baku bakery agar chef dan owner bisa memantau kondisi bahan dengan jelas.</p>
                        </div>
                    </section>

                    <section class="summary-grid">
                        <article class="summary-card">
                            <div class="summary-label">Total Bahan</div>
                            <div class="summary-value">{{ number_format($ingredientSummary['total']) }}</div>
                            <div class="summary-note">Bahan baku terdaftar</div>
                        </article>
                        <article class="summary-card is-accent">
                            <div class="summary-label">Stok Aman</div>
                            <div class="summary-value">{{ number_format($ingredientSummary['aman']) }}</div>
                            <div class="summary-note">Di atas batas minimum</div>
                        </article>
                        <article class="summary-card">
                            <div class="summary-label">Bahan Menipis</div>
                            <div class="summary-value">{{ number_format($ingredientSummary['menipis']) }}</div>
                            <div class="summary-note">Perlu diperhatikan</div>
                        </article>
                        <article class="summary-card">
                            <div class="summary-label">Bahan Habis</div>
                            <div class="summary-value">{{ number_format($ingredientSummary['habis']) }}</div>
                            <div class="summary-note">Perlu restock</div>
                        </article>
                    </section>

                    <section class="table-card">
                        <div class="table-header">
                            <div>
                                <div class="table-title">Manajemen Bahan</div>
                                <div class="table-subtitle">Pantau stok, batas minimum, dan penggunaan bahan hari ini.</div>
                            </div>
                        </div>

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

                        @if ($ingredients->isEmpty())
                            <div class="empty-state">Belum ada bahan baku yang terdaftar.</div>
                        @else
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Nama Bahan</th>
                                            <th>Stok</th>
                                            <th>Minimum</th>
                                            <th>Dipakai Hari Ini</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ingredients as $ingredient)
                                            <tr>
                                                <td>
                                                    <div class="user-name">{{ $ingredient->nama_bahan }}</div>
                                                    <div class="user-meta">ID Bahan: {{ $ingredient->id_bahan }}</div>
                                                </td>
                                                <td>{{ number_format($ingredient->stok, 2, ',', '.') }} {{ $ingredient->satuan }}</td>
                                                <td>{{ number_format($ingredient->stok_minimum, 2, ',', '.') }} {{ $ingredient->satuan }}</td>
                                                <td>{{ number_format($ingredient->used_today ?? 0, 2, ',', '.') }} {{ $ingredient->satuan }}</td>
                                                <td><span class="stock-badge-status {{ $ingredient->status_type }}">{{ $ingredient->status_label }}</span></td>
                                                <td>
                                                    <div class="action-group">
                                                        <button type="button" class="row-action js-open-modal" data-modal="edit-ingredient-{{ $ingredient->id_bahan }}">Edit</button>
                                                        <button type="button" class="row-action js-open-modal" data-modal="delete-ingredient-{{ $ingredient->id_bahan }}">Hapus</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </section>
                </div>

                <div class="modal-shell" id="modal-create-ingredient" aria-hidden="true">
                    <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalCreateIngredientTitle">
                        <div class="modal-header">
                            <div>
                                <div class="modal-title" id="modalCreateIngredientTitle">Tambah Bahan</div>
                                <div class="modal-subtitle">Masukkan bahan baku beserta stok awal dan batas minimum.</div>
                            </div>
                            <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                        </div>

                        <form method="POST" action="{{ route('manager.ingredients.store') }}" class="modal-form">
                            @csrf
                            <input type="hidden" name="modal_id" value="create-ingredient">
                            <div class="field-group">
                                <label for="ingredientName">Nama Bahan</label>
                                <input id="ingredientName" type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Tepung Terigu" required>
                            </div>
                            <div class="field-group">
                                <label for="ingredientStock">Stok</label>
                                <input id="ingredientStock" type="number" name="stock" value="{{ old('stock') }}" min="0" max="99999" step="0.01" placeholder="Contoh: 20" required>
                            </div>
                            <div class="field-group">
                                <label for="ingredientUnit">Satuan</label>
                                <input id="ingredientUnit" type="text" name="unit" value="{{ old('unit', 'kg') }}" maxlength="20" placeholder="kg, gram, liter, pcs" required>
                            </div>
                            <div class="field-group">
                                <label for="ingredientMinimum">Stok Minimum</label>
                                <input id="ingredientMinimum" type="number" name="minimum_stock" value="{{ old('minimum_stock') }}" min="0" max="99999" step="0.01" placeholder="Contoh: 5" required>
                            </div>
                            <div class="modal-actions">
                                <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                <button type="submit" class="submit-btn">Simpan Bahan</button>
                            </div>
                        </form>
                    </div>
                </div>

                @foreach ($ingredients as $ingredient)
                    <div class="modal-shell" id="modal-edit-ingredient-{{ $ingredient->id_bahan }}" aria-hidden="true">
                        <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalEditIngredientTitle{{ $ingredient->id_bahan }}">
                            <div class="modal-header">
                                <div>
                                    <div class="modal-title" id="modalEditIngredientTitle{{ $ingredient->id_bahan }}">Edit Bahan</div>
                                    <div class="modal-subtitle">Perbarui stok dan batas minimum {{ $ingredient->nama_bahan }}.</div>
                                </div>
                                <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                            </div>

                            <form method="POST" action="{{ route('manager.ingredients.update', $ingredient) }}" class="modal-form">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="modal_id" value="edit-ingredient-{{ $ingredient->id_bahan }}">
                                <div class="field-group">
                                    <label for="editIngredientName{{ $ingredient->id_bahan }}">Nama Bahan</label>
                                    <input id="editIngredientName{{ $ingredient->id_bahan }}" type="text" name="name" value="{{ old('modal_id') === 'edit-ingredient-' . $ingredient->id_bahan ? old('name', $ingredient->nama_bahan) : $ingredient->nama_bahan }}" required>
                                </div>
                                <div class="field-group">
                                    <label for="editIngredientStock{{ $ingredient->id_bahan }}">Stok</label>
                                    <input id="editIngredientStock{{ $ingredient->id_bahan }}" type="number" name="stock" value="{{ old('modal_id') === 'edit-ingredient-' . $ingredient->id_bahan ? old('stock', $ingredient->stok) : $ingredient->stok }}" min="0" max="99999" step="0.01" required>
                                </div>
                                <div class="field-group">
                                    <label for="editIngredientUnit{{ $ingredient->id_bahan }}">Satuan</label>
                                    <input id="editIngredientUnit{{ $ingredient->id_bahan }}" type="text" name="unit" value="{{ old('modal_id') === 'edit-ingredient-' . $ingredient->id_bahan ? old('unit', $ingredient->satuan) : $ingredient->satuan }}" maxlength="20" required>
                                </div>
                                <div class="field-group">
                                    <label for="editIngredientMinimum{{ $ingredient->id_bahan }}">Stok Minimum</label>
                                    <input id="editIngredientMinimum{{ $ingredient->id_bahan }}" type="number" name="minimum_stock" value="{{ old('modal_id') === 'edit-ingredient-' . $ingredient->id_bahan ? old('minimum_stock', $ingredient->stok_minimum) : $ingredient->stok_minimum }}" min="0" max="99999" step="0.01" required>
                                </div>
                                <div class="modal-actions">
                                    <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                    <button type="submit" class="submit-btn">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="modal-shell" id="modal-delete-ingredient-{{ $ingredient->id_bahan }}" aria-hidden="true">
                        <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalDeleteIngredientTitle{{ $ingredient->id_bahan }}">
                            <div class="modal-header">
                                <div>
                                    <div class="modal-title" id="modalDeleteIngredientTitle{{ $ingredient->id_bahan }}">Hapus Bahan</div>
                                    <div class="modal-subtitle">Bahan {{ $ingredient->nama_bahan }} akan dihapus dari data bahan.</div>
                                </div>
                                <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                            </div>

                            <form method="POST" action="{{ route('manager.ingredients.destroy', $ingredient) }}" class="modal-form">
                                @csrf
                                @method('DELETE')
                                <div class="modal-actions">
                                    <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                    <button type="submit" class="submit-btn">Hapus Bahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </main>
        </div>
    </div>
    @include('manager.partials.page_scripts')
</body>
</html>
