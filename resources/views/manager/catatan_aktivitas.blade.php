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
                                <div class="eyebrow">Manager Monitoring</div>
                                <h1 class="hero-title">Catatan Aktivitas</h1>
                                <p class="hero-subtitle">Pantau aktivitas semua role dan perubahan data penting pada sistem SwiftBite Morning Bakery.</p>
                            </div>
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

                        <section class="table-card">
                            <div class="table-header">
                                <div>
                                    <div class="table-title">{{ $tab === 'data' ? 'Data Perubahan' : 'Aktivitas Pengguna' }}</div>
                                    <div class="table-subtitle">{{ $tab === 'data' ? 'Riwayat tambah, edit, hapus, dan pemulihan data.' : 'Riwayat aktivitas dari customer, waiter, cashier, manager, dan owner.' }}</div>
                                </div>
                            </div>

                            <div class="activity-tabs">
                                <a class="activity-tab {{ $tab === 'activity' ? 'active' : '' }}" href="{{ route('manager.page', ['section' => 'activity']) }}">Catatan Aktivitas</a>
                                <a class="activity-tab {{ $tab === 'data' ? 'active' : '' }}" href="{{ route('manager.page', ['section' => 'activity', 'tab' => 'data']) }}">Data Perubahan</a>
                            </div>

                            @if ($tab === 'activity')
                                <form class="filter-form" method="GET" action="{{ route('manager.page', ['section' => 'activity']) }}">
                                    <div class="filter-field">
                                        <label for="activityRoleFilter">Filter Role</label>
                                        <select id="activityRoleFilter" name="role">
                                            @foreach ($activityRoleOptions as $roleOption)
                                                <option value="{{ $roleOption }}" @selected($activityRole === $roleOption)>
                                                    {{ $roleOption === 'semua' ? 'Semua Role' : $roleOption }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="filter-actions">
                                        <button class="filter-btn" type="submit">Terapkan</button>
                                        <a class="filter-link" href="{{ route('manager.page', ['section' => 'activity']) }}">Reset</a>
                                    </div>
                                </form>

                                <div class="table-wrap">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Waktu</th>
                                                <th>Dari</th>
                                                <th>Pengguna</th>
                                                <th>Aktivitas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($activityLogs as $log)
                                                <tr>
                                                    <td>{{ $log->created_at?->format('d M Y H:i') }}</td>
                                                    <td><span class="pill">{{ $log->role }}</span></td>
                                                    <td>{{ $log->user_name ?: '-' }}</td>
                                                    <td>{{ $log->activity }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="4" class="empty-state">Belum ada catatan aktivitas.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @if ($activityLogs->hasPages())
                                    <div class="pagination-wrap">
                                        <div class="pagination" aria-label="Pagination aktivitas">
                                            <span class="pagination-info">Halaman {{ $activityLogs->currentPage() }} dari {{ $activityLogs->lastPage() }}</span>

                                            @if ($activityLogs->onFirstPage())
                                                <span class="page-disabled">Prev</span>
                                            @else
                                                <a class="page-link" href="{{ $activityLogs->previousPageUrl() }}">Prev</a>
                                            @endif

                                            @foreach ($activityLogs->getUrlRange(1, $activityLogs->lastPage()) as $page => $url)
                                                @if ($page === $activityLogs->currentPage())
                                                    <span class="page-current">{{ $page }}</span>
                                                @else
                                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                @endif
                                            @endforeach

                                            @if ($activityLogs->hasMorePages())
                                                <a class="page-link" href="{{ $activityLogs->nextPageUrl() }}">Next</a>
                                            @else
                                                <span class="page-disabled">Next</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @else
                                <form class="filter-form" method="GET" action="{{ route('manager.page', ['section' => 'activity']) }}">
                                    <input type="hidden" name="tab" value="data">
                                    <div class="filter-field">
                                        <label for="dataChangeFilter">Filter Aksi</label>
                                        <select id="dataChangeFilter" name="change">
                                            @foreach ($dataChangeOptions as $changeOption)
                                                <option value="{{ $changeOption }}" @selected($changeFilter === $changeOption)>
                                                    {{ $changeOption === 'semua' ? 'Semua' : $changeOption }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="filter-actions">
                                        <button class="filter-btn" type="submit">Terapkan</button>
                                        <a class="filter-link" href="{{ route('manager.page', ['section' => 'activity', 'tab' => 'data']) }}">Reset</a>
                                    </div>
                                </form>

                                <div class="table-wrap">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Waktu</th>
                                                <th>Aksi</th>
                                                <th>Data</th>
                                                <th>Nama Data</th>
                                                <th>Oleh</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($dataChanges as $change)
                                                <tr>
                                                    <td>{{ $change->created_at?->format('d M Y H:i') }}</td>
                                                    <td class="change-action">{{ $change->action }}</td>
                                                    <td>{{ $change->data_type }}</td>
                                                    <td>{{ $change->data_name }}</td>
                                                    <td>{{ $change->actor_role }}{{ $change->actor_name ? ' - '.$change->actor_name : '' }}</td>
                                                    <td>
                                                        @if ($change->restored_at)
                                                            <span class="restore-badge">Dipulihkan</span>
                                                        @else
                                                            <span class="pill">{{ $change->action === 'Hapus' ? 'Terhapus' : 'Aktif' }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (! $change->restored_at && in_array($change->data_type, ['Menu', 'User', 'Meja'], true))
                                                            <form method="POST" action="{{ route('manager.activity.restore', $change) }}">
                                                                @csrf
                                                                <button class="restore-btn" type="submit">{{ $change->action === 'Hapus' ? 'Pulihkan' : 'Kembalikan' }}</button>
                                                            </form>
                                                        @else
                                                            <span class="muted-text">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="7" class="empty-state">Belum ada data perubahan.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @if ($dataChanges->hasPages())
                                    <div class="pagination-wrap">
                                        <div class="pagination" aria-label="Pagination data perubahan">
                                            <span class="pagination-info">Halaman {{ $dataChanges->currentPage() }} dari {{ $dataChanges->lastPage() }}</span>

                                            @if ($dataChanges->onFirstPage())
                                                <span class="page-disabled">Prev</span>
                                            @else
                                                <a class="page-link" href="{{ $dataChanges->previousPageUrl() }}">Prev</a>
                                            @endif

                                            @foreach ($dataChanges->getUrlRange(1, $dataChanges->lastPage()) as $page => $url)
                                                @if ($page === $dataChanges->currentPage())
                                                    <span class="page-current">{{ $page }}</span>
                                                @else
                                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                @endif
                                            @endforeach

                                            @if ($dataChanges->hasMorePages())
                                                <a class="page-link" href="{{ $dataChanges->nextPageUrl() }}">Next</a>
                                            @else
                                                <span class="page-disabled">Next</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </section>
                    </div>
            </main>
        </div>
    </div>
    @include('manager.partials.page_scripts')
</body>
</html>
