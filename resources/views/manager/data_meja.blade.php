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
                                <h1 class="hero-title">Data Meja</h1>
                                <p class="hero-subtitle">Kelola meja dan QR Code yang langsung membuka daftar makanan dan minuman customer.</p>
                            </div>
                        </section>

                        <section class="summary-grid">
                            <article class="summary-card">
                                <div class="summary-label">Total Meja</div>
                                <div class="summary-value">{{ number_format($tableSummary['total']) }}</div>
                                <div class="summary-note">Meja yang terdaftar</div>
                            </article>
                            <article class="summary-card is-accent">
                                <div class="summary-label">Meja Aktif</div>
                                <div class="summary-value">{{ number_format($tableSummary['aktif']) }}</div>
                                <div class="summary-note">Bisa dipakai customer</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Meja Nonaktif</div>
                                <div class="summary-value">{{ number_format($tableSummary['nonaktif']) }}</div>
                                <div class="summary-note">Sedang tidak digunakan</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Pesanan Hari Ini</div>
                                <div class="summary-value">{{ number_format($tableSummary['today_orders']) }}</div>
                                <div class="summary-note">Pesanan dari customer</div>
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

                        <section class="section-card">
                            <div class="section-head">
                                <div>
                                    <div class="section-title-row">
                                        <div class="section-title">Daftar Meja</div>
                                        <div class="section-meta">{{ $tables->count() }} Meja</div>
                                    </div>
                                    <div class="section-subtitle">Customer cukup scan QR untuk langsung masuk ke menu makanan dan minuman.</div>
                                </div>
                                <div class="section-actions">
                                    <button type="button" class="section-add-btn js-open-modal" data-modal="create-table">+ Tambah Meja</button>
                                </div>
                            </div>

                            @if ($tables->isEmpty())
                                <div class="empty-state">Belum ada data meja.</div>
                            @else
                                <div class="table-grid">
                                    @foreach ($tables as $table)
                                        @php
                                            $tableActive = ! in_array($table->status, ['nonaktif', 'inactive'], true);
                                            $tableUrl = route('customer.menu', $table->qr_token);
                                            $tablePath = parse_url($tableUrl, PHP_URL_PATH) ?: $tableUrl;
                                        @endphp

                                        <article class="table-card-item">
                                            <div class="table-card-head">
                                                <div class="table-card-title">{{ $table->nama_meja }}</div>
                                                <div class="table-more-wrap">
                                                    <button type="button" class="table-more-btn js-table-more" aria-label="Menu {{ $table->nama_meja }}">â‹®</button>
                                                    <div class="table-more-menu">
                                                        <form method="POST" action="{{ route('manager.tables.destroy', $table) }}" class="js-delete-table-form" data-table-name="{{ $table->nama_meja }}">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="table-menu-action">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="qr-box">
                                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(108)->margin(1)->generate($tableUrl) !!}
                                            </div>
                                            <div class="table-link">{{ $tablePath }}</div>
                                            <div><span class="status-badge table-status {{ $tableActive ? '' : 'inactive' }}">{{ $tableActive ? 'Aktif' : 'Nonaktif' }}</span></div>
                                            <div class="table-card-actions">
                                                <button
                                                    type="button"
                                                    class="row-action js-open-modal js-table-qr"
                                                    data-modal="table-qr"
                                                    data-name="{{ $table->nama_meja }}"
                                                    data-url="{{ $tableUrl }}"
                                                >Lihat QR</button>
                                                <button
                                                    type="button"
                                                    class="row-action js-open-modal js-edit-table"
                                                    data-modal="edit-table"
                                                    data-action="{{ route('manager.tables.update', $table) }}"
                                                    data-name="{{ $table->nama_meja }}"
                                                    data-status="{{ $tableActive ? 'aktif' : 'nonaktif' }}"
                                                >Edit</button>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @endif
                        </section>

                        <div class="modal-shell" id="modal-create-table" aria-hidden="true">
                            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalCreateTableTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalCreateTableTitle">Tambah Meja</div>
                                        <div class="modal-subtitle">Buat meja baru dan token QR akan dibuat otomatis.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>
                                <form method="POST" action="{{ route('manager.tables.store') }}" class="modal-form">
                                    @csrf
                                    <input type="hidden" name="modal_id" value="create-table">
                                    <div class="field-group">
                                        <label for="createTableName">Nama Meja</label>
                                        <input id="createTableName" type="text" name="name" value="{{ old('modal_id') === 'create-table' ? old('name') : '' }}" maxlength="7" placeholder="Contoh: Meja 8" required>
                                    </div>
                                    <div class="field-group">
                                        <label for="createTableStatus">Status</label>
                                        <select id="createTableStatus" name="status" required>
                                            <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                                            <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                                        </select>
                                    </div>
                                    <div class="modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                        <button type="submit" class="submit-btn">Simpan Meja</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="modal-shell" id="modal-edit-table" aria-hidden="true">
                            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalEditTableTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalEditTableTitle">Edit Meja</div>
                                        <div class="modal-subtitle">Perbarui nama dan status meja.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>
                                <form method="POST" action="#" class="modal-form js-table-edit-form">
                                    @csrf
                                    @method('patch')
                                    <input type="hidden" name="modal_id" value="edit-table">
                                    <div class="field-group">
                                        <label for="editTableName">Nama Meja</label>
                                        <input id="editTableName" type="text" name="name" maxlength="7" placeholder="Contoh: Meja 8" required>
                                    </div>
                                    <div class="field-group">
                                        <label for="editTableStatus">Status</label>
                                        <select id="editTableStatus" name="status" required>
                                            <option value="aktif">Aktif</option>
                                            <option value="nonaktif">Nonaktif</option>
                                        </select>
                                    </div>
                                    <div class="modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                        <button type="submit" class="submit-btn">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="modal-shell" id="modal-table-qr" aria-hidden="true">
                            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalTableQrTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalTableQrTitle">QR Meja</div>
                                        <div class="modal-subtitle js-table-qr-subtitle">Scan QR untuk langsung membuka menu customer.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>
                                <div class="modal-form">
                                    <div class="qr-preview js-table-qr-preview"></div>
                                    <div class="qr-url js-table-qr-url">-</div>
                                    <div class="modal-actions qr-modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Tutup</button>
                                        <button type="button" class="submit-btn js-download-table-qr">Download PNG</button>
                                        <button type="button" class="submit-btn js-print-table-qr">Cetak QR</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-shell" id="modal-delete-table" aria-hidden="true">
                            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalDeleteTableTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalDeleteTableTitle">Hapus Meja?</div>
                                        <div class="modal-subtitle">Meja yang dihapus tidak akan tampil di Data Meja.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>
                                <div class="modal-form">
                                    <div class="stock-modal-summary">
                                        <div class="stock-modal-product js-delete-table-name">-</div>
                                        <div class="stock-modal-current">Pastikan meja ini memang tidak digunakan lagi.</div>
                                    </div>
                                    <div class="modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                        <button type="button" class="submit-btn js-confirm-delete-table">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </main>
        </div>
    </div>
    @include('manager.partials.page_scripts')
</body>
</html>
