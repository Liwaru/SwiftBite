<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil | SwiftBite</title>
    <style>
        :root { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        body { margin: 0; background: #ffffff; color: #2b1c15; }
        main { width: 100%; max-width: 980px; box-sizing: border-box; margin: 0 auto; padding: 34px 30px 56px; }
        h1, h2, p { margin: 0; }
        h1 { font-size: clamp(30px, 4vw, 44px); margin-bottom: 8px; }
        h2 { font-size: 22px; margin-bottom: 10px; }
        .muted { color: #7a5a46; line-height: 1.55; }
        .topbar { margin-bottom: 22px; }
        .profile-card { display: grid; gap: 18px; background: linear-gradient(180deg, #6f452b, #4a2a1a); border: 1px solid #9a6239; border-radius: 8px; padding: 18px; box-shadow: 0 18px 42px rgba(39, 20, 13, .22); color: #fff8ed; }
        .profile-card h2 { color: #fff8ed; }
        .profile-card .muted { color: rgba(255, 248, 237, .78); }
        .section { display: grid; gap: 14px; }
        .section-head { display: flex; justify-content: space-between; gap: 18px; align-items: flex-start; }
        .field-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 12px; }
        label { display: grid; gap: 7px; color: #fff8ed; font-size: 13px; font-weight: 900; }
        input { width: 100%; box-sizing: border-box; border: 1px solid #d8b893; border-radius: 7px; padding: 11px 12px; background: #fffaf2; color: #352016; font: inherit; font-weight: 700; }
        input:focus { outline: 0; border-color: #6f452b; box-shadow: 0 0 0 3px rgba(183, 122, 67, .18); }
        .readonly-value { border: 1px solid rgba(255, 246, 232, .24); border-radius: 7px; padding: 11px 12px; background: rgba(255, 246, 232, .1); color: #fff8ed; font-weight: 800; }
        .actions { display: flex; flex-wrap: wrap; gap: 10px; }
        .btn { border: 0; border-radius: 7px; background: linear-gradient(135deg, #6f452b, #352016); color: #fff8ed; padding: 11px 14px; font: inherit; font-weight: 900; cursor: pointer; text-decoration: none; }
        .btn.secondary { background: rgba(255, 246, 232, .9); color: #352016; border: 1px solid rgba(255, 246, 232, .72); }
        .password-row { display: flex; justify-content: space-between; gap: 18px; align-items: center; border-top: 1px solid rgba(255, 246, 232, .2); padding-top: 16px; }
        .alert { padding: 12px 13px; border-radius: 7px; font-weight: 800; cursor: pointer; transition: opacity .18s ease, transform .18s ease; }
        .alert.is-hiding { opacity: 0; transform: translateY(-4px); }
        .alert.success { background: #edf5e8; color: #355b28; border: 1px solid #c5ddb7; }
        .alert.error { background: #fff0e8; color: #8a341b; border: 1px solid #e6b292; }
        .modal-backdrop { position: fixed; inset: 0; z-index: 2000; display: none; place-items: center; padding: 20px; background: rgba(39, 20, 13, .56); }
        .modal-backdrop.open { display: grid; }
        .password-modal { position: relative; width: min(520px, 100%); display: grid; gap: 16px; background: #fff6e8; border: 1px solid #e1ad73; border-radius: 8px; padding: 22px; box-shadow: 0 24px 70px rgba(24, 13, 7, .34); }
        .modal-close { position: absolute; top: 12px; right: 12px; width: 34px; height: 34px; display: grid; place-items: center; border: 1px solid #d8b893; border-radius: 7px; background: #fffaf2; color: #352016; cursor: pointer; font-size: 20px; line-height: 1; }
        @media (max-width: 760px) {
            main { padding: 24px 16px 44px; }
            .section-head, .password-row { align-items: stretch; flex-direction: column; }
            .field-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                <div class="topbar">
                    <p class="muted">Pengaturan akun</p>
                    <h1>Profil</h1>
                    <p class="muted">Kelola nama profil dan password akun SwiftBite.</p>
                </div>

                @if (session('success'))
                    <div class="alert success" role="button" tabindex="0" aria-label="Tutup notifikasi">{{ session('success') }}</div>
                @endif

                @if ($errors->any() && ! session('open_password_modal'))
                    <div class="alert error" role="button" tabindex="0" aria-label="Tutup notifikasi">{{ $errors->first() }}</div>
                @endif

                <section class="profile-card">
                    <div class="section">
                        <div class="section-head">
                            <div>
                                <h2>Informasi Profil</h2>
                                <p class="muted">Nama ini tampil di sidebar dan digunakan sebagai username login.</p>
                            </div>
                        </div>

                        <form method="post" action="{{ route('profile.name.update') }}">
                            @csrf
                            @method('patch')
                            <div class="field-grid">
                                <label>
                                    Nama
                                    <input name="name" value="{{ old('name', $user->name) }}" autocomplete="name" required>
                                </label>
                                <label>
                                    Email
                                    <div class="readonly-value">{{ $user->email ?: 'Belum diisi' }}</div>
                                </label>
                            </div>
                            <div class="actions" style="margin-top: 12px">
                                <button class="btn" type="submit">Simpan Nama</button>
                            </div>
                        </form>
                    </div>

                    <div class="password-row">
                        <div>
                            <h2>Password</h2>
                            <p class="muted">Ubah password akun dengan memasukkan password lama terlebih dahulu.</p>
                        </div>
                        <div class="actions">
                            <button class="btn secondary" type="button" id="openPasswordModal">Ubah Password</button>
                        </div>
                    </div>
                </section>
            </main>
        </div>

        <div class="modal-backdrop" id="passwordModal" aria-hidden="true">
            <section class="password-modal" role="dialog" aria-modal="true" aria-labelledby="passwordModalTitle">
                <button class="modal-close" type="button" id="closePasswordModal" aria-label="Tutup popup">&times;</button>

                <div>
                    <h2 id="passwordModalTitle">Ubah Password</h2>
                    <p class="muted">Password baru minimal 6 karakter.</p>
                </div>

                @if ($errors->any() && session('open_password_modal'))
                    <div class="alert error" role="button" tabindex="0" aria-label="Tutup notifikasi">{{ $errors->first() }}</div>
                @endif

                <form method="post" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('patch')
                    <div class="field-grid">
                        <label>
                            Password Lama
                            <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
                        </label>
                        <label>
                            Password Baru
                            <input name="password" type="password" autocomplete="new-password" required>
                        </label>
                        <label>
                            Konfirmasi Password Baru
                            <input name="password_confirmation" type="password" autocomplete="new-password" required>
                        </label>
                    </div>
                    <div class="actions" style="margin-top: 14px">
                        <button class="btn" type="submit">Simpan Password</button>
                    </div>
                </form>
            </section>
        </div>
    </div>

    <script>
        const modal = document.getElementById('passwordModal');
        const openButton = document.getElementById('openPasswordModal');
        const closeButton = document.getElementById('closePasswordModal');
        const firstInput = document.getElementById('current_password');
        const shouldOpen = @json((bool) session('open_password_modal'));

        function openModal() {
            modal?.classList.add('open');
            modal?.setAttribute('aria-hidden', 'false');
            setTimeout(() => firstInput?.focus(), 50);
        }

        function closeModal() {
            modal?.classList.remove('open');
            modal?.setAttribute('aria-hidden', 'true');
        }

        openButton?.addEventListener('click', openModal);
        closeButton?.addEventListener('click', closeModal);
        modal?.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        document.querySelectorAll('.alert').forEach((alert) => {
            const dismiss = () => {
                alert.classList.add('is-hiding');
                setTimeout(() => alert.remove(), 180);
            };

            alert.addEventListener('click', dismiss);
            alert.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    dismiss();
                }
            });
        });

        if (shouldOpen) {
            openModal();
        }
    </script>
</body>
</html>
