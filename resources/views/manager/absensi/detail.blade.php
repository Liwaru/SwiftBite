<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Absensi</title>
    @include('manager.partials.page_styles')
    <style>
        .detail-panel { border-radius: 8px; background: linear-gradient(135deg, #8b5530, #2b140c); color: #fff6e8; padding: 20px; box-shadow: 0 20px 50px rgba(60, 32, 18, .18); }
        .detail-list { display: grid; gap: 12px; max-width: 720px; }
        .detail-row { display: grid; grid-template-columns: 140px minmax(0, 1fr); gap: 14px; border-bottom: 1px solid rgba(255, 246, 232, .18); padding-bottom: 10px; }
        .detail-row span { color: rgba(255, 246, 232, .72); font-weight: 900; }
        .detail-row strong { overflow-wrap: anywhere; }
        .photo-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; margin-top: 18px; }
        .photo-card { border: 1px solid rgba(255, 246, 232, .22); border-radius: 8px; padding: 12px; background: rgba(255, 246, 232, .08); }
        .photo-card h2 { margin: 0 0 10px; font-size: 16px; }
        .photo-box { min-height: 180px; display: grid; place-items: center; border: 1px dashed rgba(255, 246, 232, .34); border-radius: 8px; color: rgba(255, 246, 232, .72); font-weight: 900; overflow: hidden; }
        .photo-box img { width: 100%; height: 240px; object-fit: cover; display: block; }
        .detail-actions { margin-top: 18px; }
        .detail-btn { display: inline-flex; border-radius: 7px; background: #fff6e8; color: var(--brown-dark); padding: 11px 15px; font-weight: 900; text-decoration: none; }
        @media (max-width: 640px) {
            .detail-row, .photo-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                <section class="hero-card">
                    <p class="hero-eyebrow">Manager Operasional</p>
                    <h1>Detail Absensi</h1>
                    <p class="hero-subtitle">Ringkasan data absensi dan foto verifikasi karyawan.</p>
                </section>

                <section class="detail-panel">
                    <div class="detail-list">
                        <div class="detail-row"><span>Nama</span><strong>{{ $absensi->user->name ?? '-' }}</strong></div>
                        <div class="detail-row"><span>Role</span><strong>{{ [1 => 'Waiter', 2 => 'Baker', 3 => 'Kasir', 4 => 'Manager', 5 => 'Owner'][(int) ($absensi->user->level ?? 0)] ?? '-' }}</strong></div>
                        <div class="detail-row"><span>Tanggal</span><strong>{{ $absensi->tanggal ? \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('d F Y') : '-' }}</strong></div>
                        <div class="detail-row"><span>Jam Masuk</span><strong>{{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '-' }}</strong></div>
                        <div class="detail-row"><span>Jam Pulang</span><strong>{{ $absensi->jam_keluar ? \Carbon\Carbon::parse($absensi->jam_keluar)->format('H:i') : '-' }}</strong></div>
                        <div class="detail-row"><span>Status</span><strong>{{ ucfirst(str_replace('_', ' ', $absensi->status ?: 'hadir')) }}</strong></div>
                    </div>

                    <div class="photo-grid">
                        <div class="photo-card">
                            <h2>Foto Absen Masuk</h2>
                            <div class="photo-box">
                                @if ($absensi->foto_masuk)
                                    <img src="{{ asset('storage/' . ltrim($absensi->foto_masuk, '/')) }}" alt="Foto absen masuk">
                                @else
                                    Belum tersedia
                                @endif
                            </div>
                        </div>
                        <div class="photo-card">
                            <h2>Foto Absen Pulang</h2>
                            <div class="photo-box">
                                @if ($absensi->foto_pulang)
                                    <img src="{{ asset('storage/' . ltrim($absensi->foto_pulang, '/')) }}" alt="Foto absen pulang">
                                @else
                                    Belum tersedia
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="detail-actions">
                        <a class="detail-btn" href="{{ route('absensi.index') }}">Kembali</a>
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
