<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Pembayaran QRIS Pesanan #{{ $order->id }}</title>
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
        }
        h1, h2, p { margin: 0; }
        button, a { font: inherit; }
        main {
            width: min(100%, 520px);
            margin: 0 auto;
            padding: 18px 14px calc(22px + env(safe-area-inset-bottom));
        }
        .brand-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 4px 2px 18px;
            color: #fff8ed;
        }
        .brand-left { display: flex; align-items: center; gap: 10px; }
        .brand-logo {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: #fff8ed;
            object-fit: contain;
            padding: 4px;
        }
        .brand-name { font-size: 16px; font-weight: 900; }
        .table-chip {
            border: 1px solid rgba(255, 246, 232, .24);
            border-radius: 999px;
            padding: 6px 10px;
            background: rgba(255, 246, 232, .16);
            color: #fff8ed;
            font-size: 12px;
            font-weight: 900;
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
        .topline {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 8px 12px;
            border: 1px solid #d8b893;
            border-radius: 999px;
            background: var(--cream-soft);
            color: var(--brown);
            font-weight: 900;
            text-decoration: none;
            font-size: 13px;
        }
        .eyebrow {
            color: #7a5a46;
            font-size: 13px;
            font-weight: 800;
        }
        h1 { font-size: 26px; line-height: 1.1; }
        .card {
            display: grid;
            gap: 12px;
            padding: 14px;
            border-radius: 8px;
            border: 1px solid #ead4ba;
            background: #fffdfa;
        }
        .qr-card {
            justify-items: center;
            text-align: center;
        }
        .qris-logo {
            width: 72px;
            height: auto;
            object-fit: contain;
        }
        .qr-box {
            display: grid;
            place-items: center;
            width: min(100%, 280px);
            aspect-ratio: 1 / 1;
            border-radius: 8px;
            border: 1px solid #ead4ba;
            background: #fff;
            padding: 10px;
        }
        .qr-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .timer-card {
            background: #fff1df;
            text-align: center;
        }
        .timer-label {
            color: #7a5a46;
            font-size: 13px;
            font-weight: 800;
        }
        .timer-value {
            color: var(--brown-dark);
            font-size: 28px;
            font-weight: 900;
            letter-spacing: .04em;
        }
        .status-pill {
            justify-self: center;
            border-radius: 999px;
            padding: 7px 12px;
            font-size: 13px;
            font-weight: 900;
            text-transform: capitalize;
        }
        .status-diproses { background: #fff4cc; color: #8a5a00; }
        .status-berhasil { background: #dcfce7; color: #166534; }
        .status-gagal { background: #fee2e2; color: #991b1b; }
        .status-card {
            justify-items: center;
            text-align: center;
        }
        .status-card #statusMessage {
            text-align: center;
            justify-self: center;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            color: #5b3825;
            font-size: 13px;
            font-weight: 800;
        }
        .summary-row strong {
            flex: 0 0 auto;
            color: var(--brown-dark);
        }
        .action-stack {
            display: grid;
            gap: 8px;
        }
        .action-btn {
            width: 100%;
            min-height: 46px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            text-decoration: none;
            font-weight: 900;
            cursor: pointer;
        }
        .action-btn-primary {
            border: 0;
            background: linear-gradient(135deg, var(--brown), var(--brown-dark));
            color: #fff8ed;
        }
        .action-btn-secondary {
            border: 1px solid #d8b893;
            background: var(--cream-soft);
            color: var(--brown-dark);
        }
        .small-note {
            color: #7a5a46;
            font-size: 11px;
            text-align: center;
            font-weight: 800;
        }
    </style>
</head>
<body>
    <main>
        <section class="panel">
            <div class="topline">
                <a class="back-link" href="{{ route('customer.orders.payment', [$table->qr_token, $order]) }}">Kembali</a>
                <span class="table-chip">QRIS</span>
            </div>

            <div>
                <p class="eyebrow">Pembayaran QRIS</p>
                <h1>{{ $table->name }}</h1>
            </div>

            <div class="card qr-card">
                <p class="eyebrow">Scan QR untuk membayar</p>
                <img class="qris-logo" src="{{ asset('images/qris.jpg') }}" alt="QRIS">
                <div class="qr-box">
                    @if ($order->qris_url)
                        <img id="qrImage" src="{{ $order->qris_url }}" alt="QR Code QRIS">
                    @else
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(240)->margin(1)->generate($order->midtrans_order_id ?? $order->kode_pesanan) !!}
                    @endif
                </div>
            </div>

            <div class="card timer-card">
                <p class="timer-label">Selesaikan pembayaran dalam</p>
                <p class="timer-value" id="countdown">00:00:00</p>
            </div>

            <div class="card status-card">
                <span class="status-pill status-{{ $order->payment_status ?? 'diproses' }}" id="statusBadge">{{ str_replace('_', ' ', $order->payment_status ?? 'diproses') }}</span>
                <p class="eyebrow" id="statusMessage">Midtrans akan memperbarui status pembayaran otomatis setelah QRIS dibayar.</p>
            </div>

            <div class="card">
                @foreach ($order->items as $item)
                    <div class="summary-row">
                        <span>{{ $item->quantity }}x {{ $item->menu_name }}</span>
                        <strong>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                    </div>
                @endforeach
                <div class="summary-row">
                    <span>Total Bayar</span>
                    <strong>Rp{{ number_format($order->total_price, 0, ',', '.') }}</strong>
                </div>
            </div>

            <div class="action-stack">
                @if ($order->qris_url)
                    <a href="{{ $order->qris_url }}" download="qris-{{ $order->midtrans_order_id }}.png" class="action-btn action-btn-primary">Unduh QR Code</a>
                    <button type="button" class="action-btn action-btn-secondary" id="shareButton">Bagikan QR Code</button>
                @endif
            </div>

            <p class="small-note">Pembayaran terpercaya, diproses melalui Midtrans.</p>
        </section>
    </main>

    <script>
        const statusUrl = @json(route('customer.orders.qris.status', [$table->qr_token, $order]));
        const receiptUrl = @json(route('customer.orders.show', [$table->qr_token, $order]));
        const expiresAt = new Date(@json(optional($order->payment_expires_at)->toIso8601String() ?? now()->addMinutes(15)->toIso8601String()));
        const countdownElement = document.getElementById('countdown');
        const statusBadge = document.getElementById('statusBadge');
        const statusMessage = document.getElementById('statusMessage');
        const qrImage = document.getElementById('qrImage');
        const shareButton = document.getElementById('shareButton');

        function setStatus(status, message) {
            statusBadge.className = 'status-pill status-' + status;
            statusBadge.textContent = status.replace('_', ' ');
            if (message) {
                statusMessage.textContent = message;
            }
        }

        function updateCountdown() {
            const diff = expiresAt.getTime() - Date.now();
            if (diff <= 0) {
                countdownElement.textContent = '00:00:00';
                setStatus('gagal', 'Waktu pembayaran QRIS telah habis.');
                return false;
            }

            const hours = String(Math.floor(diff / 3600000)).padStart(2, '0');
            const minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
            const seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
            countdownElement.textContent = `${hours}:${minutes}:${seconds}`;
            return true;
        }

        async function checkStatus() {
            try {
                const response = await fetch(statusUrl, { headers: { 'Accept': 'application/json' } });
                if (!response.ok) return;
                const result = await response.json();

                if (result.status === 'berhasil') {
                    setStatus('berhasil', 'Pembayaran berhasil terdeteksi. Anda akan dialihkan.');
                    setTimeout(() => {
                        window.location.href = result.redirect_url || receiptUrl;
                    }, 1600);
                    return;
                }

                if (result.status === 'gagal') {
                    setStatus('gagal', 'Pembayaran QRIS gagal atau kedaluwarsa.');
                    return;
                }

                setStatus('diproses', 'Menunggu pembayaran QRIS.');
            } catch (error) {
                console.error(error);
            }
        }

        shareButton?.addEventListener('click', async () => {
            try {
                if (navigator.share && qrImage) {
                    await navigator.share({
                        title: 'QRIS SwiftBite',
                        text: 'Scan QRIS ini untuk membayar pesanan SwiftBite.',
                        url: qrImage.src
                    });
                    return;
                }

                if (qrImage) {
                    await navigator.clipboard.writeText(qrImage.src);
                    alert('Link QRIS berhasil disalin.');
                }
            } catch (error) {
                alert('Bagikan QR tidak tersedia di perangkat ini.');
            }
        });

        updateCountdown();
        const countdownTimer = setInterval(() => {
            if (!updateCountdown()) clearInterval(countdownTimer);
        }, 1000);
        checkStatus();
        setInterval(checkStatus, 5000);
    </script>
</body>
</html>
