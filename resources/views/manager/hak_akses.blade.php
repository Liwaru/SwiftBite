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
                    <section class="hero-card">
                        <div class="eyebrow">MANAGER OPERASIONAL</div>
                        <h1>Hak Akses</h1>
                        <p class="hero-subtitle">Kelola hak akses berdasarkan role pengguna SwiftBite Morning Bakery.</p>
                    </section>

                    <section class="panel">
                        <p class="muted">Halaman ini sudah disiapkan untuk pengaturan hak akses manager.</p>
                    </section>
            </main>
        </div>
    </div>
    @include('manager.partials.page_scripts')
</body>
</html>
