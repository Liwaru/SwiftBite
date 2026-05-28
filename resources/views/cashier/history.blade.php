<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Pesanan</title>
    <style>
        :root { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        body { margin: 0; background: #ffffff; color: #2b1c15; }
        main { width: 100%; max-width: none; box-sizing: border-box; padding: 34px 30px 56px; }
        h1, h2, h3, p { margin: 0; }
        h1 { font-size: clamp(30px, 4vw, 44px); }
        h2 { font-size: 22px; margin-bottom: 14px; }
        h3 { font-size: 17px; margin-bottom: 6px; }
        .muted { color: #7a5a46; }
        .topbar { margin-bottom: 24px; }
        .panel { background: #fff6e8; border: 1px solid #e1ad73; border-radius: 8px; padding: 18px; box-shadow: 0 12px 30px rgba(39, 20, 13, .12); color: #2b1c15; }
        .panel .muted { color: #7a5a46; }
        .badge { display: inline-flex; width: fit-content; padding: 4px 8px; border-radius: 999px; background: #f4e3cd; color: #5d3820; font-size: 12px; font-weight: 900; }
        .order-list { display: grid; gap: 14px; }
        .order { display: grid; gap: 12px; border-top: 1px solid #ead4ba; padding-top: 14px; }
        .order:first-child { border-top: 0; padding-top: 0; }
        .row { display: flex; justify-content: space-between; gap: 12px; align-items: center; }
        .price { font-weight: 900; white-space: nowrap; }
        @media (max-width: 760px) { main { padding: 24px 16px 44px; } .row { align-items: flex-start; flex-direction: column; } }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                <div class="topbar">
                    <h1>Riwayat Pesanan</h1>
                </div>

                <section class="panel">
                    <h2>Riwayat</h2>
                    <div class="order-list">
                        @forelse ($orders as $order)
                            <article class="order">
                                <div class="row">
                                    <div>
                                        <span class="badge">{{ $order->status }}</span>
                                        <h3>#{{ $order->id }} - {{ $order->diningTable->name }}</h3>
                                        <p class="muted">{{ $order->customer_name ?: 'Tanpa nama' }} - {{ strtoupper($order->payment_method) }} - {{ $order->updated_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <span class="price">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>

                                <div>
                                    @foreach ($order->items as $item)
                                        <p>{{ $item->quantity }}x {{ $item->menu_name }} <span class="muted">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span></p>
                                    @endforeach
                                </div>
                            </article>
                        @empty
                            <p class="muted">Belum ada riwayat pesanan.</p>
                        @endforelse
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
