<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Absensi</title>
    @include('manager.partials.page_styles')
    <style>
        .form-panel { border-radius: 8px; background: linear-gradient(135deg, #8b5530, #2b140c); color: #fff6e8; padding: 20px; box-shadow: 0 20px 50px rgba(60, 32, 18, .18); }
        .attendance-form { display: grid; gap: 14px; max-width: 620px; }
        .field { display: grid; gap: 7px; }
        .field label { font-weight: 900; font-size: 13px; text-transform: uppercase; }
        .field input, .field select { width: 100%; box-sizing: border-box; border: 1px solid #d9b48b; border-radius: 8px; background: #fffdfa; color: #2b1c15; padding: 11px 12px; font: inherit; font-weight: 800; }
        .form-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 6px; }
        .form-btn { border: 0; border-radius: 7px; background: #fff6e8; color: var(--brown-dark); padding: 11px 15px; font: inherit; font-weight: 900; cursor: pointer; text-decoration: none; }
        .form-btn.secondary { border: 1px solid rgba(255, 246, 232, .28); background: rgba(255, 246, 232, .12); color: #fff6e8; }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                <section class="hero-card">
                    <p class="hero-eyebrow">Manager Operasional</p>
                    <h1>Tambah Absensi</h1>
                    <p class="hero-subtitle">Input manual catatan absensi jika data perlu dilengkapi oleh manager.</p>
                </section>

                <section class="form-panel">
                    <form class="attendance-form" method="post" action="{{ route('absensi.store') }}">
                        @csrf
                        <div class="field">
                            <label for="id_user">Karyawan</label>
                            <select id="id_user" name="id_user" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id_user }}" @selected(old('id_user') == $user->id_user)>{{ $user->name }} - Level {{ $user->level }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="tanggal">Tanggal</label>
                            <input id="tanggal" type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" required>
                        </div>
                        <div class="field">
                            <label for="jam_masuk">Jam Masuk</label>
                            <input id="jam_masuk" type="time" name="jam_masuk" value="{{ old('jam_masuk') }}">
                        </div>
                        <div class="field">
                            <label for="jam_keluar">Jam Pulang</label>
                            <input id="jam_keluar" type="time" name="jam_keluar" value="{{ old('jam_keluar') }}">
                        </div>
                        <div class="field">
                            <label for="status">Status</label>
                            <select id="status" name="status" required>
                                @foreach (['hadir' => 'Hadir', 'terlambat' => 'Terlambat', 'izin' => 'Izin', 'sakit' => 'Sakit', 'tidak_hadir' => 'Tidak Hadir'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', 'hadir') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-actions">
                            <button class="form-btn" type="submit">Simpan</button>
                            <a class="form-btn secondary" href="{{ route('absensi.index') }}">Kembali</a>
                        </div>
                    </form>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
