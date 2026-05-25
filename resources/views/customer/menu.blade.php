<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menu {{ $table->name }}</title>
    <style>
        :root { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        body { margin: 0; background: #f8f6f0; color: #211f1b; }
        main { max-width: 760px; margin: 0 auto; padding: 24px 16px 108px; }
        h1, h2, h3, p { margin: 0; }
        h1 { font-size: 34px; }
        h2 { margin: 28px 0 12px; font-size: 20px; }
        h3 { font-size: 17px; }
        .muted { color: #766f64; }
        .top { display: grid; gap: 8px; margin-bottom: 18px; }
        .item { display: grid; grid-template-columns: 1fr 86px; gap: 14px; align-items: center; background: #fffdfa; border: 1px solid #e5dccc; border-radius: 8px; padding: 14px; margin-bottom: 10px; }
        .price { font-weight: 900; margin-top: 8px; }
        input, textarea, select { box-sizing: border-box; width: 100%; border: 1px solid #d7cab8; border-radius: 7px; padding: 11px; font: inherit; background: white; }
        input[type="number"] { text-align: center; font-weight: 900; }
        label { display: grid; gap: 6px; font-weight: 800; font-size: 13px; }
        .checkout { position: fixed; left: 0; right: 0; bottom: 0; background: rgba(255, 253, 250, .96); border-top: 1px solid #dfd2c1; backdrop-filter: blur(10px); }
        .checkout-inner { max-width: 760px; margin: 0 auto; padding: 12px 16px; display: grid; grid-template-columns: 1fr 1fr auto; gap: 10px; align-items: end; }
        button { border: 0; border-radius: 7px; background: #2f6f61; color: white; padding: 12px 14px; font-weight: 900; cursor: pointer; }
        .notice { padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; background: #fff0ed; color: #9c2b1e; border: 1px solid #f2beb5; }
        @media (max-width: 680px) { .checkout-inner { grid-template-columns: 1fr; } .item { grid-template-columns: 1fr 74px; } }
    </style>
</head>
<body>
    <main>
        <div class="top">
            <p class="muted">Pesanan meja digital</p>
            <h1>{{ $table->name }}</h1>
            <p class="muted">Pilih jumlah menu, lalu kirim pesanan. Pembayaran bisa cash atau QRIS dummy.</p>
        </div>

        @if ($errors->any())
            <div class="notice">{{ $errors->first() }}</div>
        @endif

        <form method="post" action="{{ route('customer.orders.store', $table->qr_token) }}">
            @csrf

            @forelse ($menuItems as $category => $items)
                <h2>{{ $category }}</h2>
                @foreach ($items as $item)
                    <article class="item">
                        <div>
                            <h3>{{ $item->name }}</h3>
                            @if ($item->description)
                                <p class="muted">{{ $item->description }}</p>
                            @endif
                            <p class="price">Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <label>
                            Jumlah
                            <input type="number" name="quantities[{{ $item->id }}]" value="{{ old('quantities.' . $item->id, 0) }}" min="0" max="20">
                        </label>
                    </article>
                @endforeach
            @empty
                <p class="muted">Menu belum tersedia.</p>
            @endforelse

            <div class="checkout">
                <div class="checkout-inner">
                    <label>
                        Nama
                        <input name="customer_name" value="{{ old('customer_name') }}" placeholder="Opsional">
                    </label>
                    <label>
                        Bayar
                        <select name="payment_method" required>
                            <option value="cash" @selected(old('payment_method') === 'cash')>Cash</option>
                            <option value="qris" @selected(old('payment_method') === 'qris')>QRIS Dummy</option>
                        </select>
                    </label>
                    <button type="submit">Kirim Pesanan</button>
                </div>
            </div>
        </form>
    </main>
</body>
</html>
