<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konfirmasi Hapus Menu</title>
    <style>
        :root {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            --brown-dark: #27140d;
            --brown: #5a321f;
            --brown-light: #9a6239;
            --cream: #fff6e8;
        }
        body { margin: 0; background: #ffffff; color: #2b1c15; }
        .app-shell .content-with-sidebar { background: #ffffff !important; }
        main { width: 100%; box-sizing: border-box; padding: 34px 30px 56px; }
        .page-shell { max-width: 860px; }
        .hero-card, .panel {
            border-radius: 8px;
            box-shadow: 0 16px 38px rgba(39, 20, 13, .13);
        }
        .hero-card {
            margin-bottom: 16px;
            padding: 22px;
            background: linear-gradient(135deg, var(--brown-light), var(--brown-dark));
            color: #fff8ed;
        }
        .eyebrow { margin-bottom: 8px; color: rgba(255, 248, 237, .76); font-size: 12px; font-weight: 900; letter-spacing: .04em; text-transform: uppercase; }
        h1 { margin: 0; font-size: clamp(32px, 4vw, 46px); line-height: 1.05; }
        p { margin: 10px 0 0; color: rgba(255, 248, 237, .82); line-height: 1.55; }
        .panel {
            padding: 18px;
            background: linear-gradient(135deg, rgba(154, 98, 57, .94), rgba(90, 50, 31, .98) 52%, rgba(39, 20, 13, .98));
            border: 1px solid rgba(255, 246, 232, .22);
            color: #fff8ed;
        }
        .summary {
            margin-bottom: 14px;
            padding: 12px 14px;
            border-radius: 8px;
            background: rgba(255, 246, 232, .1);
            font-weight: 900;
        }
        table { width: 100%; border-collapse: collapse; overflow: hidden; border-radius: 8px; }
        th, td { padding: 13px 14px; border-top: 1px solid rgba(255, 246, 232, .16); text-align: left; }
        th { background: rgba(255, 246, 232, .1); color: rgba(255, 246, 232, .78); font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .actions { display: flex; justify-content: flex-end; gap: 9px; margin-top: 16px; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            padding: 12px 14px;
            border: 1px solid #d9b48b;
            font: inherit;
            font-weight: 900;
            text-decoration: none;
            cursor: pointer;
        }
        .btn.cancel { background: #fffdfa; color: var(--brown-dark); }
        .btn.delete { background: #ffe2dc; color: #7b2418; }
        @media (max-width: 760px) {
            main { padding: 24px 16px 44px; }
            .actions { flex-direction: column; }
            .btn { width: 100%; box-sizing: border-box; }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                <div class="page-shell">
                    <section class="hero-card">
                        <div class="eyebrow">Konfirmasi Laravel</div>
                        <h1>Hapus Menu?</h1>
                        <p>Pastikan menu yang dipilih memang ingin dihapus dari Data Menu SwiftBite.</p>
                    </section>

                    <section class="panel">
                        <div class="summary">{{ $menus->count() }} menu akan dihapus.</div>

                        <table>
                            <thead>
                                <tr>
                                    <th>Nama Menu</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($menus as $menu)
                                    <tr>
                                        <td>{{ $menu->nama_menu }}</td>
                                        <td>{{ $menu->category }}</td>
                                        <td>Rp{{ number_format($menu->harga, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="actions">
                            <a class="btn cancel" href="{{ route('manager.page', 'menus') }}">Batal</a>
                            <form method="POST" action="{{ route('manager.menus.destroy') }}">
                                @csrf
                                @method('delete')
                                @foreach ($menus as $menu)
                                    <input type="hidden" name="menu_ids[]" value="{{ $menu->getKey() }}">
                                @endforeach
                                <button class="btn delete" type="submit">Hapus</button>
                            </form>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
