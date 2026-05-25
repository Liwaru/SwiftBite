<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Kasir</title>
    <style>
        :root { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        body { margin: 0; background: #ffffff; color: #151515; }
        main { width: 100%; max-width: none; box-sizing: border-box; padding: 34px 30px 56px; }
        h1, h2, h3, p { margin: 0; }
        h1 { font-size: clamp(30px, 4vw, 44px); }
        h2 { font-size: 22px; margin-bottom: 14px; }
        h3 { font-size: 17px; margin-bottom: 6px; }
        .muted { color: #6c6c6c; }
        .topbar { display: flex; justify-content: space-between; gap: 16px; align-items: end; margin-bottom: 24px; }
        .panel { background: #ffffff; border: 1px solid #f0d4d7; border-radius: 8px; padding: 18px; box-shadow: 0 14px 34px rgba(169, 0, 16, .08); }
        .notice { padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; background: #fff0f1; color: #a90010; border: 1px solid #ffc5ca; font-weight: 800; }
        .badge { display: inline-flex; width: fit-content; padding: 4px 8px; border-radius: 999px; background: #fff0f1; color: #a90010; font-size: 12px; font-weight: 900; }
        .order-list { display: grid; gap: 14px; }
        .order { display: grid; gap: 12px; border-top: 1px solid #f3dde0; padding-top: 14px; }
        .order:first-child { border-top: 0; padding-top: 0; }
        .row { display: flex; justify-content: space-between; gap: 12px; align-items: center; }
        .price { font-weight: 900; white-space: nowrap; }
        form.status { display: grid; grid-template-columns: 1fr auto; gap: 10px; }
        select { width: 100%; box-sizing: border-box; border: 1px solid #f0a7ad; border-radius: 7px; padding: 10px 11px; font: inherit; background: #fff; color: #8f0010; }
        button { border: 0; border-radius: 7px; background: linear-gradient(135deg, #d90416, #a90010); color: white; padding: 10px 13px; font-weight: 900; cursor: pointer; }
        @media (max-width: 760px) { main { padding: 24px 16px 44px; } .topbar { align-items: flex-start; flex-direction: column; } form.status { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                <div class="topbar">
                    <div>
                        <h1>Dashboard Kasir</h1>
                    </div>
                </div>

                @if (session('success'))
                    <div class="notice">{{ session('success') }}</div>
                @endif

                <section class="panel">
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

                                <form class="status" method="post" action="{{ route('cashier.orders.status', $order) }}">
                                    @csrf
                                    @method('patch')
                                    <select name="status">
                                        @foreach (['new' => 'Baru', 'preparing' => 'Diproses', 'ready' => 'Siap', 'paid' => 'Sudah Dibayar', 'cancelled' => 'Batal'] as $value => $label)
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
                </section>
            </main>
        </div>
    </div>
</body>
</html>
