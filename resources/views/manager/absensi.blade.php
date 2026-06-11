<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page['title'] ?? 'Data Absensi' }}</title>
    @include('manager.partials.page_styles')
    <style>
        .attendance-table-wrap table { min-width: 820px; }
        .attendance-filter-form { display: grid; grid-template-columns: minmax(180px, 1.4fr) minmax(130px, .8fr) minmax(150px, .8fr) minmax(140px, .8fr) auto auto; gap: 10px; align-items: end; margin-bottom: 14px; }
        .attendance-filter-field { display: grid; gap: 6px; }
        .attendance-filter-field label { color: rgba(255, 246, 232, .76); font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .attendance-filter-field input,
        .attendance-filter-field select { width: 100%; box-sizing: border-box; border: 1px solid #d9b48b; border-radius: 8px; background: #fffdfa; color: #2b1c15; padding: 10px 11px; font: inherit; font-weight: 800; }
        .attendance-filter-action { min-height: 42px; border: 0; border-radius: 7px; background: #fff6e8; color: var(--brown-dark); padding: 10px 13px; font: inherit; font-weight: 900; cursor: pointer; text-align: center; text-decoration: none; }
        .attendance-filter-action.secondary { border: 1px solid rgba(255, 246, 232, .26); background: rgba(255, 246, 232, .14); color: #fff6e8; }
        .attendance-name { display: grid; gap: 3px; font-weight: 900; }
        .attendance-name span { color: rgba(255, 246, 232, .68); font-size: 12px; font-weight: 800; }
        .status-badge { display: inline-flex; align-items: center; gap: 7px; border-radius: 999px; padding: 7px 10px; font-size: 12px; font-weight: 900; }
        .status-badge::before { content: ""; width: 8px; height: 8px; border-radius: 999px; background: currentColor; }
        .status-badge.hadir { background: #e6ffd9; color: #2c642b; }
        .status-badge.terlambat { background: #fff0b8; color: #755000; }
        .status-badge.izin { background: #dff0ff; color: #225f91; }
        .status-badge.sakit { background: #f1e3ff; color: #68319a; }
        .status-badge.tidak-hadir { background: #ffe2dc; color: #7b2418; }
        .detail-btn { border: 0; border-radius: 7px; background: #fff6e8; color: var(--brown-dark); padding: 9px 12px; font: inherit; font-size: 13px; font-weight: 900; cursor: pointer; }
        .attendance-modal[hidden] { display: none; }
        .attendance-modal { position: fixed; inset: 0; z-index: 80; display: grid; place-items: center; padding: 18px; background: rgba(18, 10, 6, .62); }
        .attendance-dialog { width: min(100%, 560px); max-height: min(88vh, 760px); overflow: auto; border: 1px solid rgba(255, 246, 232, .22); border-radius: 8px; background: #fffaf2; color: #2b1c15; box-shadow: 0 24px 70px rgba(24, 13, 7, .38); }
        .attendance-dialog-head { display: flex; justify-content: space-between; gap: 12px; align-items: center; padding: 16px 18px; background: linear-gradient(135deg, #9a6239, #27140d); color: #fff6e8; }
        .attendance-dialog-head h2 { margin: 0; font-size: 20px; }
        .modal-close { width: 34px; height: 34px; border: 1px solid rgba(255, 246, 232, .28); border-radius: 7px; background: rgba(255, 246, 232, .14); color: #fff6e8; font: inherit; font-size: 20px; font-weight: 900; cursor: pointer; }
        .attendance-dialog-body { display: grid; gap: 16px; padding: 18px; }
        .attendance-dialog-foot { min-height: 16px; background: linear-gradient(135deg, #9a6239, #27140d); }
        .detail-list { display: grid; gap: 10px; }
        .detail-row { display: grid; grid-template-columns: 120px minmax(0, 1fr); gap: 12px; align-items: start; border-bottom: 1px solid #ead4ba; padding-bottom: 10px; }
        .detail-row:last-child { border-bottom: 0; padding-bottom: 0; }
        .detail-row span { color: #805438; font-weight: 900; }
        .detail-row strong { overflow-wrap: anywhere; }
        .verification-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
        .verification-card { display: grid; gap: 9px; border: 1px solid #ead4ba; border-radius: 8px; padding: 12px; background: #fff6e8; }
        .verification-card h3 { margin: 0; font-size: 15px; }
        .verification-image { min-height: 132px; display: grid; place-items: center; border: 1px dashed #c79b70; border-radius: 8px; background: #fffaf2; color: #805438; font-size: 13px; font-weight: 900; text-align: center; overflow: hidden; }
        .verification-image img { width: 100%; height: 220px; display: block; object-fit: cover; }
        @media (max-width: 640px) {
            .attendance-dialog { max-height: 92vh; }
            .attendance-filter-form { grid-template-columns: 1fr; }
            .detail-row { grid-template-columns: 1fr; gap: 3px; }
            .verification-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                @php
                    $roleLabels = [
                        1 => 'Waiter',
                        2 => 'Baker',
                        3 => 'Kasir',
                        4 => 'Manager',
                        5 => 'Owner',
                    ];

                    $statusLabels = [
                        'hadir' => 'Hadir',
                        'masuk' => 'Hadir',
                        'keluar' => 'Hadir',
                        'terlambat' => 'Terlambat',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'tidak_hadir' => 'Tidak Hadir',
                        'tidak hadir' => 'Tidak Hadir',
                    ];

                    $statusClass = fn ($status) => str_replace('_', '-', strtolower((string) ($status ?: 'hadir')));
                    $statusText = fn ($status) => $statusLabels[strtolower((string) $status)] ?? ucfirst((string) ($status ?: 'Hadir'));
                    $formatTime = fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('H:i') : '-';
                    $formatDateShort = fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '-';
                    $formatDateLong = fn ($value) => $value ? \Carbon\Carbon::parse($value)->translatedFormat('d F Y') : '-';
                    $durationText = function ($absensi) {
                        if (!$absensi->jam_masuk || !$absensi->jam_keluar) {
                            return '-';
                        }

                        $start = \Carbon\Carbon::parse($absensi->tanggal . ' ' . $absensi->jam_masuk);
                        $end = \Carbon\Carbon::parse($absensi->tanggal . ' ' . $absensi->jam_keluar);
                        $minutes = (int) $start->diffInMinutes($end);
                        $hours = intdiv($minutes, 60);
                        $remainingMinutes = $minutes % 60;

                        return trim($hours . ' Jam ' . $remainingMinutes . ' Menit');
                    };
                    $attendanceFilters = $attendanceFilters ?? [
                        'name' => request('name', ''),
                        'role' => request('role', 'semua'),
                        'date' => request('date', ''),
                        'status' => request('status', 'semua'),
                    ];
                @endphp

                <div class="page-shell">
                    <section class="hero-card">
                        <div>
                            <div class="eyebrow">Manager Operasional</div>
                            <h1 class="hero-title">Data Absensi</h1>
                            <p class="hero-subtitle">Lihat status kehadiran karyawan, jam kerja, dan detail verifikasi absensi.</p>
                        </div>
                    </section>

                    <section class="table-card">
                        <div class="table-header">
                            <div>
                                <div class="table-title">Riwayat Absensi</div>
                                <div class="table-subtitle">Menampilkan {{ number_format($absensis->total()) }} catatan absensi.</div>
                            </div>
                        </div>

                        <form class="attendance-filter-form" method="get" action="{{ route('manager.page', 'absensi') }}">
                            <div class="attendance-filter-field">
                                <label for="attendance_name">Nama</label>
                                <input id="attendance_name" type="search" name="name" value="{{ $attendanceFilters['name'] ?? '' }}" placeholder="Cari nama karyawan">
                            </div>
                            <div class="attendance-filter-field">
                                <label for="attendance_role">Role</label>
                                <select id="attendance_role" name="role">
                                    <option value="semua" {{ ($attendanceFilters['role'] ?? 'semua') === 'semua' ? 'selected' : '' }}>Semua Role</option>
                                    @foreach ([1 => 'Waiter', 2 => 'Baker', 3 => 'Kasir', 4 => 'Manager', 5 => 'Owner'] as $level => $label)
                                        <option value="{{ $level }}" {{ (string) ($attendanceFilters['role'] ?? 'semua') === (string) $level ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="attendance-filter-field">
                                <label for="attendance_date">Tanggal</label>
                                <input id="attendance_date" type="date" name="date" value="{{ $attendanceFilters['date'] ?? '' }}">
                            </div>
                            <div class="attendance-filter-field">
                                <label for="attendance_status">Status</label>
                                <select id="attendance_status" name="status">
                                    <option value="semua" {{ ($attendanceFilters['status'] ?? 'semua') === 'semua' ? 'selected' : '' }}>Semua Status</option>
                                    @foreach (['hadir' => 'Hadir', 'terlambat' => 'Terlambat', 'izin' => 'Izin', 'sakit' => 'Sakit', 'tidak_hadir' => 'Tidak Hadir'] as $value => $label)
                                        <option value="{{ $value }}" {{ ($attendanceFilters['status'] ?? 'semua') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="attendance-filter-action">Filter</button>
                            <a class="attendance-filter-action secondary" href="{{ route('manager.page', 'absensi') }}">Reset</a>
                        </form>

                        <div class="table-wrap attendance-table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Role</th>
                                        <th>Tanggal</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Pulang</th>
                                        <th>Durasi Kerja</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($absensis as $absensi)
                                        @php
                                            $role = $roleLabels[(int) ($absensi->user->level ?? 0)] ?? '-';
                                            $status = $statusText($absensi->status);
                                            $badgeClass = $statusClass($absensi->status);
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="attendance-name">
                                                    {{ $absensi->user->name ?? '-' }}
                                                    <span>{{ $absensi->user->email ?? $absensi->user->username ?? '-' }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $role }}</td>
                                            <td>{{ $formatDateShort($absensi->tanggal) }}</td>
                                            <td>{{ $formatTime($absensi->jam_masuk) }}</td>
                                            <td>{{ $formatTime($absensi->jam_keluar) }}</td>
                                            <td>{{ $durationText($absensi) }}</td>
                                            <td><span class="status-badge {{ $badgeClass }}">{{ $status }}</span></td>
                                            <td>
                                                <button type="button" class="detail-btn js-attendance-detail" data-modal="attendance-detail-{{ $absensi->id_absensi }}">Detail</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">Belum ada data absensi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($absensis->hasPages())
                            <div class="pagination-wrap">
                                {{ $absensis->links() }}
                            </div>
                        @endif
                    </section>
                </div>

                @foreach ($absensis as $absensi)
                    @php
                        $role = $roleLabels[(int) ($absensi->user->level ?? 0)] ?? '-';
                        $status = $statusText($absensi->status);
                        $badgeClass = $statusClass($absensi->status);
                        $requiredGesture = match ((int) ($absensi->user->level ?? 0)) {
                            1 => '1 jari',
                            2 => '2 jari',
                            3 => '3 jari',
                            default => 'hand gesture',
                        };
                    @endphp
                    <div class="attendance-modal" id="attendance-detail-{{ $absensi->id_absensi }}" hidden>
                        <div class="attendance-dialog" role="dialog" aria-modal="true" aria-labelledby="attendance-title-{{ $absensi->id_absensi }}">
                            <div class="attendance-dialog-head">
                                <h2 id="attendance-title-{{ $absensi->id_absensi }}">Detail Absensi</h2>
                                <button type="button" class="modal-close js-attendance-close" aria-label="Tutup detail">&times;</button>
                            </div>
                            <div class="attendance-dialog-body">
                                <div class="detail-list">
                                    <div class="detail-row"><span>Nama</span><strong>{{ $absensi->user->name ?? '-' }}</strong></div>
                                    <div class="detail-row"><span>Role</span><strong>{{ $role }}</strong></div>
                                    <div class="detail-row"><span>Tanggal</span><strong>{{ $formatDateLong($absensi->tanggal) }}</strong></div>
                                    <div class="detail-row"><span>Jam Masuk</span><strong>{{ $formatTime($absensi->jam_masuk) }}</strong></div>
                                    <div class="detail-row"><span>Jam Pulang</span><strong>{{ $formatTime($absensi->jam_keluar) }}</strong></div>
                                    <div class="detail-row"><span>Durasi Kerja</span><strong>{{ $durationText($absensi) }}</strong></div>
                                    <div class="detail-row"><span>Status</span><strong><span class="status-badge {{ $badgeClass }}">{{ $status }}</span></strong></div>
                                </div>

                                <div class="verification-grid">
                                    <div class="verification-card">
                                        <h3>Foto Absen Masuk</h3>
                                        <div class="verification-image">
                                            @if ($absensi->foto_masuk)
                                                <img src="{{ asset('storage/' . ltrim($absensi->foto_masuk, '/')) }}" alt="Foto absen masuk {{ $absensi->user->name ?? '' }}">
                                            @else
                                                Belum tersedia
                                            @endif
                                        </div>
                                        <div class="small">Selfie dan verifikasi {{ $requiredGesture }} saat masuk.</div>
                                    </div>
                                    <div class="verification-card">
                                        <h3>Foto Absen Pulang</h3>
                                        <div class="verification-image">
                                            @if ($absensi->foto_pulang)
                                                <img src="{{ asset('storage/' . ltrim($absensi->foto_pulang, '/')) }}" alt="Foto absen pulang {{ $absensi->user->name ?? '' }}">
                                            @else
                                                Belum tersedia
                                            @endif
                                        </div>
                                        <div class="small">Selfie dan verifikasi {{ $requiredGesture }} saat pulang.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="attendance-dialog-foot"></div>
                        </div>
                    </div>
                @endforeach
            </main>
        </div>
    </div>

    <script>
        document.querySelectorAll('.js-attendance-detail').forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.modal);
                if (modal) modal.hidden = false;
            });
        });

        document.querySelectorAll('.attendance-modal').forEach((modal) => {
            modal.addEventListener('click', (event) => {
                if (event.target === modal || event.target.closest('.js-attendance-close')) {
                    modal.hidden = true;
                }
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape') return;
            document.querySelectorAll('.attendance-modal:not([hidden])').forEach((modal) => {
                modal.hidden = true;
            });
        });
    </script>
</body>
</html>
