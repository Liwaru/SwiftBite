<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan #{{ $order->id }}</title>
    <style>
        :root { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        html, body, * { scrollbar-width: none; -ms-overflow-style: none; }
        *::-webkit-scrollbar { display: none; width: 0; height: 0; }
        body {
            margin: 0;
            background:
                linear-gradient(135deg, rgba(53, 32, 22, .82), rgba(111, 69, 43, .9)),
                repeating-linear-gradient(45deg, rgba(255, 246, 232, .08) 0 1px, transparent 1px 14px),
                #6f452b;
            color: #2b1c15;
        }
        main { max-width: 620px; margin: 0 auto; padding: 28px 16px; }
        .panel { background: #fff6e8; border: 1px solid #e1ad73; border-radius: 8px; padding: 18px; display: grid; gap: 14px; box-shadow: 0 12px 30px rgba(39, 20, 13, .12); }
        h1, h2, p { margin: 0; }
        h1 { font-size: 30px; }
        .muted { color: #7a5a46; }
        .row { display: flex; justify-content: space-between; gap: 12px; border-top: 1px solid #ead4ba; padding-top: 10px; }
        .total { font-size: 22px; font-weight: 900; }
        .badge { width: fit-content; padding: 5px 9px; border-radius: 999px; background: #edf5e8; color: #355b28; font-weight: 900; font-size: 13px; }
        a { color: #6f452b; font-weight: 900; }
    </style>
</head>
<body>
    <main>
        <section class="panel">
            @if (session('success'))
                <span class="badge">{{ session('success') }}</span>
            @endif

            <div>
                <p class="muted">{{ $table->name }}</p>
                <h1>Pesanan #{{ $order->id }}</h1>
                <p class="muted">Status: {{ $order->status }} - Bayar: {{ $order->payment_method === 'cash' ? 'Tunai' : strtoupper($order->payment_method) }}</p>
            </div>

            @foreach ($order->items as $item)
                <div class="row">
                    <p>{{ $item->quantity }}x {{ $item->menu_name }}</p>
                    <strong>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                </div>
            @endforeach

            <div class="row total">
                <p>Total</p>
                <p>Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
            </div>

            @if ($order->payment_method === 'qris')
                <div>
                    <h2>QRIS Dummy</h2>
                    <p class="muted">Untuk demo sekolah, anggap pembayaran QRIS berhasil setelah kasir mengubah status menjadi sudah dibayar.</p>
                    <div style="margin-top: 12px">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->margin(1)->generate('QRIS-DUMMY-ORDER-' . $order->id . '-TOTAL-' . $order->total_price) !!}
                    </div>
                </div>
            @endif

            <a href="{{ route('customer.menu', $table->qr_token) }}">Tambah pesanan lagi</a>
        </section>
    </main>
</body>
</html>
