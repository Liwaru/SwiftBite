<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard QR Resto</title>
    <style>
        :root { color-scheme: light; font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        body { margin: 0; background: #ffffff; color: #211f1b; }
        main { max-width: 1180px; padding: 34px 30px 56px; }
        header { display: flex; justify-content: space-between; gap: 16px; align-items: flex-end; margin-bottom: 24px; }
        h1, h2, h3, p { margin: 0; }
        h1 { font-size: clamp(28px, 4vw, 44px); }
        h2 { font-size: 22px; margin-bottom: 14px; }
        h3 { font-size: 17px; margin-bottom: 6px; }
        .muted { color: #746d61; }
        .grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 16px; align-items: start; }
        .panel { background: #ffffff; border: 1px solid #f0d4d7; border-radius: 8px; padding: 18px; box-shadow: 0 14px 34px rgba(169, 0, 16, .08); }
        .span-4 { grid-column: span 4; }
        .span-8 { grid-column: span 8; }
        .span-12 { grid-column: span 12; }
        form { display: grid; gap: 10px; }
        label { display: grid; gap: 6px; font-weight: 700; font-size: 13px; }
        input, textarea, select { width: 100%; box-sizing: border-box; border: 1px solid #f0a7ad; border-radius: 7px; padding: 10px 11px; font: inherit; background: #fff; }
        textarea { resize: vertical; min-height: 74px; }
        button, .button { border: 0; border-radius: 7px; background: linear-gradient(135deg, #d90416, #a90010); color: white; padding: 10px 13px; font-weight: 800; cursor: pointer; text-decoration: none; text-align: center; }
        .notice { padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; background: #fff0f1; color: #a90010; border: 1px solid #ffc5ca; font-weight: 800; }
        .error { background: #fff0ed; color: #9c2b1e; border-color: #f2beb5; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 14px; }
        .table-card { display: grid; gap: 10px; }
        .qr { display: grid; place-items: center; background: white; padding: 14px; border: 1px dashed #f0a7ad; border-radius: 8px; }
        .menu-list, .order-list { display: grid; gap: 10px; }
        .row { display: flex; justify-content: space-between; gap: 12px; align-items: center; border-top: 1px solid #f3dde0; padding-top: 10px; }
        .badge { display: inline-flex; width: fit-content; padding: 4px 8px; border-radius: 999px; background: #fff0f1; color: #a90010; font-size: 12px; font-weight: 900; }
        .price { font-weight: 900; white-space: nowrap; }
        .order { display: grid; gap: 12px; border-top: 1px solid #f3dde0; padding-top: 14px; }
        @media (max-width: 860px) { header { align-items: flex-start; flex-direction: column; } .span-4, .span-8 { grid-column: span 12; } main { padding: 24px 16px 44px; } }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                <header>
                    <div>
                        <p class="muted">QR table ordering</p>
                        <h1>Dashboard Admin</h1>
                    </div>
                </header>

        @if (session('success'))
            <div class="notice">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="notice error">{{ $errors->first() }}</div>
        @endif

        <section class="grid">
            <div class="panel span-4">
                <h2>Tambah Meja</h2>
                <form method="post" action="{{ route('admin.tables.store') }}">
                    @csrf
                    <label>
                        Nama meja
                        <input name="name" placeholder="Meja 1" required>
                    </label>
                    <button type="submit">Buat QR Meja</button>
                </form>
            </div>

            <div class="panel span-8">
                <h2>Tambah Menu</h2>
                <form method="post" action="{{ route('admin.menu-items.store') }}">
                    @csrf
                    <label>
                        Nama menu
                        <input name="name" placeholder="Nasi goreng kampung" required>
                    </label>
                    <label>
                        Kategori
                        <input name="category" placeholder="Makanan / Minuman / Roti" required>
                    </label>
                    <label>
                        Harga
                        <input name="price" type="number" min="0" placeholder="25000" required>
                    </label>
                    <label>
                        Deskripsi
                        <textarea name="description" placeholder="Opsional"></textarea>
                    </label>
                    <button type="submit">Simpan Menu</button>
                </form>
            </div>

            <div class="panel span-12">
                <h2>QR Meja</h2>
                <div class="cards">
                    @forelse ($tables as $table)
                        <article class="table-card">
                            <h3>{{ $table->name }}</h3>
                            <div class="qr">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->margin(1)->generate(route('customer.menu', $table->qr_token)) !!}
                            </div>
                            <a class="button" href="{{ route('customer.menu', $table->qr_token) }}" target="_blank">Buka Menu</a>
                            <p class="muted">{{ route('customer.menu', $table->qr_token) }}</p>
                        </article>
                    @empty
                        <p class="muted">Belum ada meja. Buat meja dulu untuk menampilkan QR.</p>
                    @endforelse
                </div>
            </div>

            <div class="panel span-4">
                <h2>Daftar Menu</h2>
                <div class="menu-list">
                    @forelse ($menuItems as $item)
                        <div class="row">
                            <div>
                                <span class="badge">{{ $item->category }}</span>
                                <h3>{{ $item->name }}</h3>
                                <p class="muted">{{ $item->description }}</p>
                            </div>
                            <span class="price">Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="muted">Belum ada menu.</p>
                    @endforelse
                </div>
            </div>

            <div class="panel span-8">
                <h2>Pesanan Masuk</h2>
                <div class="order-list">
                    @forelse ($orders as $order)
                        <article class="order">
                            <div class="row">
                                <div>
                                    <span class="badge">{{ $order->status }}</span>
                                    <h3>#{{ $order->id }} - {{ $order->diningTable->name }}</h3>
                                    <p class="muted">{{ $order->customer_name ?: 'Tanpa nama' }} - {{ strtoupper($order->payment_method) }}</p>
                                </div>
                                <span class="price">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                @foreach ($order->items as $item)
                                    <p>{{ $item->quantity }}x {{ $item->menu_name }} <span class="muted">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span></p>
                                @endforeach
                            </div>
                            @if ($order->notes)
                                <p class="muted">Catatan: {{ $order->notes }}</p>
                            @endif
                            <form method="post" action="{{ route('admin.orders.status', $order) }}">
                                @csrf
                                @method('patch')
                                <select name="status">
                                    @foreach (['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $value => $label)
                                        <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button type="submit">Update Status</button>
                            </form>
                        </article>
                    @empty
                        <p class="muted">Belum ada pesanan masuk.</p>
                    @endforelse
                </div>
            </div>
        </section>
            </main>
        </div>
    </div>
</body>
</html>
