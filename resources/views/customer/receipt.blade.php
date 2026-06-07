<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Pesanan #{{ $order->id }}</title>
    <style>
        :root {
            --brown-dark: #2b1a12;
            --brown: #6f452b;
            --cream: #fff6e8;
            --cream-soft: #fffaf2;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background:
                linear-gradient(145deg, rgba(43, 26, 18, .94), rgba(111, 69, 43, .96)),
                repeating-linear-gradient(45deg, rgba(255, 246, 232, .08) 0 1px, transparent 1px 14px),
                var(--brown);
            color: var(--brown-dark);
            overflow-x: hidden;
        }
        h1, h2, p { margin: 0; }
        main {
            width: min(100%, 520px);
            margin: 0 auto;
            padding: 18px 14px calc(22px + env(safe-area-inset-bottom));
        }
        .panel {
            display: grid;
            gap: 14px;
            padding: 14px;
            border-radius: 8px;
            border: 1px solid #e1ad73;
            background: var(--cream);
            box-shadow: 0 16px 30px rgba(24, 13, 7, .18);
        }
        .notice {
            width: fit-content;
            padding: 6px 10px;
            border-radius: 999px;
            background: #eaf7dd;
            color: #2f6d1f;
            font-size: 12px;
            font-weight: 900;
        }
        .eyebrow {
            color: #7a5a46;
            font-size: 13px;
            font-weight: 800;
        }
        h1 {
            font-size: 28px;
            line-height: 1.1;
        }
        .badge-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            min-height: 30px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
        }
        .badge-status {
            background: #fff4cc;
            color: #8a5a00;
            border: 1px solid #ecd27a;
        }
        .badge-payment {
            background: #fff1df;
            color: var(--brown);
            border: 1px solid #d8b893;
        }
        .card {
            display: grid;
            gap: 10px;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ead4ba;
            background: #fffdfa;
        }
        .order-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding-top: 9px;
            border-top: 1px solid #ead4ba;
            color: #5b3825;
            font-size: 14px;
        }
        .order-row:first-child {
            padding-top: 0;
            border-top: 0;
        }
        .order-row strong {
            flex: 0 0 auto;
            color: var(--brown-dark);
        }
        .total-card {
            display: grid;
            gap: 4px;
            padding: 14px;
            border-radius: 8px;
            background: #fff1df;
            border: 1px solid #d8b893;
            text-align: center;
        }
        .total-card span {
            color: #7a5a46;
            font-size: 13px;
            font-weight: 900;
        }
        .total-card strong {
            color: var(--brown-dark);
            font-size: 28px;
            line-height: 1.1;
        }
        .muted {
            color: #7a5a46;
            font-size: 13px;
            line-height: 1.4;
            font-weight: 800;
        }
        .detail-card {
            display: grid;
            gap: 8px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            color: #5b3825;
            font-size: 14px;
            font-weight: 800;
        }
        .detail-row strong {
            color: var(--brown-dark);
            text-align: right;
        }
        .order-note {
            padding-top: 8px;
            border-top: 1px solid #ead4ba;
            color: #7a5a46;
            font-size: 13px;
            line-height: 1.35;
            font-weight: 800;
        }
        .add-order {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--brown), var(--brown-dark));
            color: #fff8ed;
            text-decoration: none;
            font-weight: 900;
        }
    </style>
</head>
<body>
    <main>
        <section class="panel">
            @if (session('success'))
                <span class="notice">{{ session('success') }}</span>
            @endif

            @php
                $paymentLabel = match ($order->payment_method) {
                    'cash' => 'Tunai',
                    'qris' => 'QRIS',
                    'gopay' => 'GoPay',
                    'ovo' => 'OVO',
                    'dana' => 'DANA',
                    'shopeepay' => 'ShopeePay',
                    default => strtoupper((string) $order->payment_method),
                };
                $statusLabel = $order->status_label;
            @endphp

            <div>
                <h1>Pesanan #{{ $order->id }}</h1>
                <div class="badge-row">
                    <span class="badge badge-status">{{ ucfirst($statusLabel) }}</span>
                    <span class="badge badge-payment">{{ $paymentLabel }}</span>
                </div>
            </div>

            <section class="card" aria-label="Daftar pesanan">
                @foreach ($order->items as $item)
                    <div class="order-row">
                        <span>{{ $item->quantity }}x {{ $item->menu_name }}</span>
                        <strong>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                    </div>
                @endforeach
            </section>

            <section class="total-card" aria-label="Total pembayaran">
                <span>Total Pembayaran</span>
                <strong>Rp{{ number_format($order->total_price, 0, ',', '.') }}</strong>
            </section>

            <section class="card detail-card" aria-label="Detail pesanan">
                <div class="detail-row">
                    <span>Meja</span>
                    <strong>{{ $table->name }}</strong>
                </div>
                <div class="detail-row">
                    <span>Waktu</span>
                    <strong>{{ $order->created_at?->timezone('Asia/Jakarta')->format('d M Y - H:i') }}</strong>
                </div>
                <p class="order-note">
                    @if ($order->payment_method === 'cash')
                        Silakan lakukan pembayaran di kasir. Pesanan Anda sudah masuk ke sistem.
                    @else
                        Pembayaran digital Anda sedang diproses. Pesanan akan diperbarui otomatis setelah pembayaran terdeteksi.
                    @endif
                </p>
            </section>

            <a class="add-order" href="{{ route('customer.menu', $table->qr_token) }}">+ Tambah Pesanan Lagi</a>
        </section>
    </main>
</body>
</html>
