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
                                <div class="eyebrow">Manager Sistem</div>
                                <h1 class="hero-title">Database</h1>
                                <p class="hero-subtitle">Kelola backup, import, dan reset data operasional SwiftBite Morning Bakery.</p>
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

                        <section class="database-grid">
                            <article class="database-card">
                                <div>
                                    <h2>Backup Database</h2>
                                    <p>Unduh file SQL berisi data utama aplikasi: user, meja, menu, pesanan, dan detail pesanan.</p>
                                </div>
                                <div class="database-note">Gunakan backup sebelum import atau reset data.</div>
                                <form method="POST" action="{{ route('manager.database.backup') }}">
                                    @csrf
                                    <button type="submit" class="submit-btn">Backup Sekarang</button>
                                </form>
                            </article>

                            <article class="database-card">
                                <div>
                                    <h2>Import Database</h2>
                                    <p>Upload file SQL hasil backup SwiftBite untuk mengembalikan data.</p>
                                </div>
                                <form method="POST" action="{{ route('manager.database.import') }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="database_file" accept=".sql,.txt" required>
                                    <button type="submit" class="submit-btn">Import Database</button>
                                </form>
                            </article>

                            <article class="database-card">
                                <div>
                                    <h2>Reset Database</h2>
                                    <p>Mengosongkan data operasional: meja, menu, kategori, pesanan, dan detail pesanan. Akun user tetap disimpan.</p>
                                </div>
                                <div class="database-note">Ketik <strong>RESET DATABASE</strong> untuk konfirmasi.</div>
                                <form method="POST" action="{{ route('manager.database.reset') }}">
                                    @csrf
                                    @method('delete')
                                    <input type="text" name="confirmation" placeholder="RESET DATABASE" required>
                                    <button type="submit" class="submit-btn">Reset Database</button>
                                </form>
                            </article>
                        </section>
                    </div>
            </main>
        </div>
    </div>
    @include('manager.partials.page_scripts')
</body>
</html>
