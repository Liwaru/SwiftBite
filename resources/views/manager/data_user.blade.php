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
                    @php
                        $roleLabels = [
                            1 => 'Waiter',
                            2 => 'Cashier',
                            3 => 'Manager',
                            4 => 'Owner',
                        ];

                        $roleClasses = [
                            1 => 'waiter',
                            2 => 'cashier',
                            3 => 'manager',
                            4 => 'owner',
                        ];
                    @endphp

                    <div class="page-shell">
                        <section class="hero-card">
                            <div>
                                <div class="eyebrow">Manager Operasional</div>
                                <h1 class="hero-title">Data User</h1>
                                <p class="hero-subtitle">Kelola akun pengguna SwiftBite, lihat role yang digunakan, dan pantau user yang aktif di sistem bakery.</p>
                            </div>
                        </section>

                        <section class="summary-grid">
                            <article class="summary-card">
                                <div class="summary-label">Total User</div>
                                <div class="summary-value">{{ number_format($summary['total_user']) }}</div>
                                <div class="summary-note">Semua akun yang terdaftar</div>
                            </article>
                            <article class="summary-card is-accent">
                                <div class="summary-label">Waiter</div>
                                <div class="summary-value">{{ number_format($summary['waiter']) }}</div>
                                <div class="summary-note">Akun pengantaran pesanan</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Cashier</div>
                                <div class="summary-value">{{ number_format($summary['cashier']) }}</div>
                                <div class="summary-note">Akun kasir operasional</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Pengelola</div>
                                <div class="summary-value">{{ number_format($summary['pengelola']) }}</div>
                                <div class="summary-note">Manager dan owner</div>
                            </article>
                        </section>

                        <section class="table-card">
                            <div class="table-header">
                                <div>
                                    <div class="table-title">Manajemen User</div>
                                    <div class="table-subtitle">Menampilkan {{ number_format($users->total()) }} user berdasarkan filter aktif.</div>
                                </div>
                                <div class="table-header-actions">
                                    <button type="button" class="action-btn primary js-open-modal" data-modal="create-user">
                                        <span>Tambah User</span>
                                    </button>
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

                            <form method="GET" action="{{ route('manager.page', 'users') }}" class="filter-form">
                                <div class="filter-field">
                                    <label for="searchUser">Search User</label>
                                    <input id="searchUser" type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari nama, username, atau email...">
                                </div>
                                <div class="filter-field">
                                    <label for="roleFilter">Filter Role</label>
                                    <select id="roleFilter" name="role">
                                        <option value="semua" @selected($filters['role'] === 'semua')>Semua Role</option>
                                        @foreach ($roleOptions as $level => $label)
                                            <option value="{{ $level }}" @selected($filters['role'] === (string) $level)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="filter-actions">
                                    <button type="submit" class="filter-btn">
                                        <span>Terapkan</span>
                                    </button>
                                    <a href="{{ route('manager.page', 'users') }}" class="filter-link">
                                        <span>Reset</span>
                                    </a>
                                </div>
                            </form>

                            @if ($users->count() === 0)
                                <div class="empty-state">Belum ada data user yang cocok dengan filter saat ini.</div>
                            @else
                                <div class="table-wrap">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Dari</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $user)
                                                @php
                                                    $level = (int) $user->level;
                                                @endphp

                                                <tr>
                                                    <td>
                                                        <div class="user-name">{{ $user->name }}</div>
                                                        <div class="user-meta">ID User: {{ $user->id_user ?? $user->id }}</div>
                                                    </td>
                                                    <td>{{ $user->username ?? $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <span class="pill {{ $roleClasses[$level] ?? '' }}">{{ $roleLabels[$level] ?? 'Unknown' }}</span>
                                                    </td>
                                                    <td><span class="status-badge">Aktif</span></td>
                                                    <td>
                                                        <div class="action-group">
                                                            <button type="button" class="row-action js-open-modal" data-modal="edit-user-{{ $user->getKey() }}">Edit</button>
                                                            <button type="button" class="row-action js-open-modal" data-modal="detail-user-{{ $user->getKey() }}">Detail</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if ($users->hasPages())
                                    <div class="pagination-wrap">
                                        <div class="pagination">
                                            <span class="pagination-info">Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}</span>

                                            @if ($users->onFirstPage())
                                                <span class="page-link page-disabled">Prev</span>
                                            @else
                                                <a class="page-link" href="{{ $users->previousPageUrl() }}">Prev</a>
                                            @endif

                                            @foreach ($users->getUrlRange(1, $users->lastPage()) as $pageNumber => $url)
                                                @if ($pageNumber === $users->currentPage())
                                                    <span class="page-current">{{ $pageNumber }}</span>
                                                @else
                                                    <a class="page-link" href="{{ $url }}">{{ $pageNumber }}</a>
                                                @endif
                                            @endforeach

                                            @if ($users->hasMorePages())
                                                <a class="page-link" href="{{ $users->nextPageUrl() }}">Next</a>
                                            @else
                                                <span class="page-link page-disabled">Next</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </section>

                        @foreach ($users as $user)
                            @php
                                $level = (int) $user->level;
                                $editModalId = 'edit-user-' . $user->getKey();
                                $detailModalId = 'detail-user-' . $user->getKey();
                            @endphp

                            <div class="modal-shell" id="modal-{{ $editModalId }}" aria-hidden="true">
                                <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalEditUserTitle{{ $user->getKey() }}">
                                    <div class="modal-header">
                                        <div>
                                            <div class="modal-title" id="modalEditUserTitle{{ $user->getKey() }}">Edit User</div>
                                            <div class="modal-subtitle">Perbarui akun {{ $user->name }}. Nama user otomatis mengikuti username.</div>
                                        </div>
                                        <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                    </div>

                                    <form method="POST" action="{{ route('manager.users.update', $user) }}" class="modal-form">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="modal_id" value="{{ $editModalId }}">

                                        <div class="field-group">
                                            <label for="editUsername{{ $user->getKey() }}">Username</label>
                                            <input id="editUsername{{ $user->getKey() }}" type="text" name="username" value="{{ old('modal_id') === $editModalId ? old('username', $user->username ?? $user->name) : ($user->username ?? $user->name) }}" minlength="3" maxlength="15" required>
                                        </div>

                                        <div class="field-group">
                                            <label for="editPassword{{ $user->getKey() }}">Password Baru</label>
                                            <input id="editPassword{{ $user->getKey() }}" type="password" name="password" minlength="6" maxlength="20" placeholder="Kosongkan jika tidak diubah">
                                        </div>

                                        <div class="field-group">
                                            <label for="editRole{{ $user->getKey() }}">Role</label>
                                            <select id="editRole{{ $user->getKey() }}" name="level" required>
                                                <option value="1" @selected((string) (old('modal_id') === $editModalId ? old('level', $user->level) : $user->level) === '1')>Waiter</option>
                                                <option value="2" @selected((string) (old('modal_id') === $editModalId ? old('level', $user->level) : $user->level) === '2')>Cashier</option>
                                                <option value="3" @selected((string) (old('modal_id') === $editModalId ? old('level', $user->level) : $user->level) === '3')>Manager</option>
                                                <option value="4" @selected((string) (old('modal_id') === $editModalId ? old('level', $user->level) : $user->level) === '4')>Owner</option>
                                            </select>
                                        </div>

                                        <div class="modal-actions">
                                            <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                            <button type="submit" class="submit-btn">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="modal-shell" id="modal-{{ $detailModalId }}" aria-hidden="true">
                                <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalDetailUserTitle{{ $user->getKey() }}">
                                    <div class="modal-header">
                                        <div>
                                            <div class="modal-title" id="modalDetailUserTitle{{ $user->getKey() }}">Detail User</div>
                                            <div class="modal-subtitle">Informasi akun {{ $user->name }}.</div>
                                        </div>
                                        <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                    </div>

                                    <div class="detail-list">
                                        <div class="detail-row">
                                            <div class="detail-label">Nama</div>
                                            <div class="detail-value">{{ $user->name }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Username</div>
                                            <div class="detail-value">{{ $user->username ?? $user->name }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Email</div>
                                            <div class="detail-value">{{ $user->email }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Role</div>
                                            <div class="detail-value">{{ $roleLabels[$level] ?? 'Unknown' }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Status</div>
                                            <div class="detail-value">Aktif</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Tanggal Dibuat</div>
                                            <div class="detail-value">{{ $user->created_at?->format('d M Y H:i') ?? '-' }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Terakhir Diperbarui</div>
                                            <div class="detail-value">{{ $user->updated_at?->format('d M Y H:i') ?? '-' }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Login Terakhir</div>
                                            <div class="detail-value">-</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="modal-shell" id="modal-create-user" aria-hidden="true">
                        <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalCreateUserTitle">
                            <div class="modal-header">
                                <div>
                                    <div class="modal-title" id="modalCreateUserTitle">Tambah User</div>
                                    <div class="modal-subtitle">Buat akun baru untuk waiter atau cashier. Nama user otomatis mengikuti username.</div>
                                </div>
                                <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                            </div>

                            <form method="POST" action="{{ route('manager.users.store') }}" class="modal-form">
                                @csrf
                                <input type="hidden" name="modal_id" value="create-user">
                                <div class="field-group">
                                    <label for="createUsername">Username</label>
                                    <input id="createUsername" type="text" name="username" value="{{ old('username') }}" minlength="3" maxlength="15" placeholder="Maksimal 15 karakter" required>
                                </div>

                                <div class="field-group">
                                    <label for="createPassword">Password</label>
                                    <input id="createPassword" type="password" name="password" minlength="6" maxlength="20" placeholder="6-20 karakter" required>
                                </div>

                                <div class="field-group">
                                    <label for="createRole">Role</label>
                                    <select id="createRole" name="level" required>
                                        <option value="" disabled @selected(old('level') === null)>Pilih role</option>
                                        <option value="1" @selected(old('level') === '1')>Waiter</option>
                                        <option value="2" @selected(old('level') === '2')>Cashier</option>
                                    </select>
                                </div>

                                <div class="modal-actions">
                                    <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                    <button type="submit" class="submit-btn">Simpan User</button>
                                </div>
                            </form>
                        </div>
                    </div>
            </main>
        </div>
    </div>
    @include('manager.partials.page_scripts')
</body>
</html>
