<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Kasir</title>
    <style>
        :root {
            --sidebar-brown: #5a321f;
            --sidebar-brown-dark: #27140d;
            --sidebar-brown-light: #9a6239;
            --cream: #fff6e8;
            --cream-soft: #fffaf2;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        body { margin: 0; background: #ffffff; color: #2b1c15; }
        .app-shell .content-with-sidebar { background: #ffffff !important; }
        main { width: 100%; max-width: none; min-width: 0; box-sizing: border-box; padding: clamp(22px, 2.4vw, 34px) clamp(16px, 2.3vw, 30px) 56px; }
        h1, h2, h3, p { margin: 0; }
        h1 { font-size: clamp(30px, 4vw, 44px); }
        h2 { font-size: 22px; margin-bottom: 14px; }
        h3 { font-size: 17px; margin-bottom: 6px; }
        .muted { color: #7a5a46; line-height: 1.5; }
        .hero-card {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 16px;
            padding: 22px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--sidebar-brown-light), var(--sidebar-brown-dark));
            color: #fff8ed;
            box-shadow: 0 16px 38px rgba(39, 20, 13, .13);
        }
        .eyebrow { margin-bottom: 8px; color: rgba(255, 248, 237, .76); font-size: 12px; font-weight: 900; letter-spacing: .04em; text-transform: uppercase; }
        .hero-title { margin: 0; font-size: clamp(32px, 4vw, 46px); line-height: 1.05; }
        .hero-subtitle { max-width: 720px; margin-top: 10px; color: rgba(255, 248, 237, .82); line-height: 1.55; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 14px; margin-bottom: 18px; }
        .stat-card, .panel {
            background:
                linear-gradient(135deg, rgba(154, 98, 57, .94), rgba(90, 50, 31, .98) 52%, rgba(39, 20, 13, .98));
            border: 1px solid rgba(255, 246, 232, .22);
            border-radius: 8px;
            box-shadow: 0 12px 30px rgba(39, 20, 13, .18);
            color: var(--cream);
        }
        .stat-card { padding: 15px; display: grid; gap: 6px; }
        .stat-card span { color: rgba(255, 246, 232, .76); font-size: 13px; font-weight: 800; }
        .stat-card strong { font-size: 24px; line-height: 1.1; overflow-wrap: anywhere; }
        .dashboard-grid { display: grid; grid-template-columns: minmax(0, 1.35fr) minmax(min(320px, 100%), .8fr); gap: 16px; align-items: start; }
        .panel { padding: 18px; }
        .summary-table { width: 100%; min-width: min(520px, 100vw); border-collapse: collapse; overflow: hidden; border-radius: 8px; }
        .summary-table th, .summary-table td { padding: 13px 14px; border-top: 1px solid rgba(255, 246, 232, .16); text-align: left; }
        .summary-table th { background: rgba(255, 246, 232, .1); color: rgba(255, 246, 232, .78); font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .summary-table td:last-child { text-align: right; font-weight: 900; }
        .summary-panel { margin-bottom: 16px; overflow-x: auto; scrollbar-width: thin; -ms-overflow-style: auto; }
        .summary-panel::-webkit-scrollbar { display: block; width: 8px; height: 8px; }
        .summary-panel::-webkit-scrollbar-thumb { background: rgba(255, 246, 232, .34); border-radius: 999px; }
        .notice { padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; background: #edf5e8; color: #355b28; border: 1px solid #c5ddb7; font-weight: 800; }
        .tabs { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; }
        .panel .muted { color: rgba(255, 246, 232, .76); }
        .tab { border: 1px solid rgba(255, 246, 232, .24); border-radius: 999px; background: rgba(255, 246, 232, .1); color: var(--cream); padding: 8px 12px; font-weight: 900; text-decoration: none; font-size: 13px; }
        .tab.active { background: var(--cream); border-color: var(--cream); color: var(--sidebar-brown-dark); }
        .badge { display: inline-flex; width: fit-content; padding: 4px 8px; border-radius: 999px; background: rgba(255, 246, 232, .16); color: var(--cream); font-size: 12px; font-weight: 900; }
        .badge.payment { background: #edf5e8; color: #355b28; }
        .badge.pending { background: #fff1cf; color: #7a4b12; }
        .badge.cancelled { background: #ffe2dc; color: #7b2418; }
        .badge-row { display: flex; flex-wrap: wrap; gap: 7px; margin-bottom: 14px; }
        .order-meta { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; margin-top: 8px; color: rgba(255, 246, 232, .76); line-height: 1.5; }
        .wait-time { display: inline-flex; width: fit-content; color: #fff4df; font-size: 13px; font-weight: 900; }
        .live-status { display: inline-flex; align-items: center; gap: 8px; color: rgba(255, 246, 232, .78); font-size: 13px; font-weight: 800; }
        .live-dot { width: 9px; height: 9px; border-radius: 999px; background: #5f8f3d; box-shadow: 0 0 0 4px rgba(95, 143, 61, .14); }
        .live-dot.syncing { background: #b8844d; box-shadow: 0 0 0 4px rgba(184, 132, 77, .16); }
        .order-list { display: grid; gap: 12px; }
        .order-card { display: grid; gap: 18px; border: 1px solid rgba(255, 246, 232, .22); border-radius: 8px; background: rgba(255, 246, 232, .08); padding: 18px 14px 20px; }
        .row { display: flex; justify-content: space-between; gap: 12px; align-items: flex-start; }
        .price { font-size: 18px; font-weight: 900; white-space: nowrap; }
        .items { display: grid; gap: 7px; margin-top: 2px; padding-bottom: 4px; }
        .detail-toggle { width: fit-content; color: var(--cream); font-weight: 900; cursor: pointer; list-style: none; }
        .detail-toggle::-webkit-details-marker { display: none; }
        .detail-toggle::before { content: "▼ "; font-size: 12px; }
        .order-detail { display: grid; gap: 12px; border-top: 1px solid rgba(255, 246, 232, .16); padding-top: 16px; }
        .detail-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px 14px; }
        .detail-grid span { display: block; color: rgba(255, 246, 232, .68); font-size: 12px; font-weight: 800; }
        .detail-grid strong { display: block; margin-top: 2px; }
        form.status { display: grid; grid-template-columns: 1fr; gap: 10px; }
        .status-done { display: inline-flex; width: fit-content; border-radius: 7px; background: #edf5e8; color: #355b28; padding: 10px 13px; font-weight: 900; }
        select, input { width: 100%; box-sizing: border-box; border: 1px solid rgba(255, 246, 232, .34); border-radius: 7px; padding: 10px 11px; font: inherit; background: var(--cream-soft); color: #352016; }
        button, .button { border: 0; border-radius: 7px; background: var(--cream); color: var(--sidebar-brown-dark); padding: 10px 13px; font-weight: 900; cursor: pointer; text-decoration: none; text-align: center; }
        button:disabled { cursor: default; opacity: .86; }
        .button.secondary, button.secondary { background: rgba(255, 246, 232, .16); color: var(--cream); border: 1px solid rgba(255, 246, 232, .26); }
        .pagination { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 10px; margin-top: 14px; }
        .pagination-info { color: rgba(255, 246, 232, .76); font-size: 13px; font-weight: 800; }
        .pagination-links { display: flex; flex-wrap: wrap; gap: 7px; }
        .page-link, .page-current, .page-disabled { min-width: 34px; box-sizing: border-box; border-radius: 7px; padding: 8px 10px; text-align: center; font-size: 13px; font-weight: 900; }
        .page-link { border: 1px solid rgba(255, 246, 232, .26); color: var(--cream); text-decoration: none; background: rgba(255, 246, 232, .1); }
        .page-current { background: var(--cream); color: var(--sidebar-brown-dark); }
        .page-disabled { border: 1px solid rgba(255, 246, 232, .12); color: rgba(255, 246, 232, .45); }
        .pos-panel { display: grid; gap: 14px; }
        .menu-preview { display: grid; gap: 8px; }
        .menu-row { display: flex; justify-content: space-between; gap: 10px; align-items: center; border-top: 1px solid rgba(255, 246, 232, .18); padding-top: 8px; }
        .menu-row:first-child { border-top: 0; padding-top: 0; }
        .quick-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .toast { position: fixed; right: 24px; bottom: 24px; z-index: 40; transform: translateY(18px); opacity: 0; pointer-events: none; transition: opacity .2s ease, transform .2s ease; background: #352016; color: #fff8ed; border: 1px solid #8b6040; border-radius: 8px; padding: 12px 14px; font-weight: 900; box-shadow: 0 16px 38px rgba(39, 20, 13, .24); }
        .toast.show { transform: translateY(0); opacity: 1; }
        @media (max-width: 1050px) { .dashboard-grid { grid-template-columns: 1fr; } }
        @media (max-width: 760px) { main { padding: 24px 16px 44px; } .hero-card { align-items: flex-start; flex-direction: column; } .stats { grid-template-columns: 1fr; } form.status, .quick-actions, .detail-grid { grid-template-columns: 1fr; } .row { flex-direction: column; } .pagination { align-items: flex-start; flex-direction: column; } }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                @if (session('success'))
                    <div class="notice">{{ session('success') }}</div>
                @endif

                @if (($mode ?? 'dashboard') === 'dashboard')
                    <section class="hero-card">
                        <div>
                            <div class="eyebrow">Cashier Operasional</div>
                            <h1 class="hero-title">Dashboard Kasir</h1>
                            <p class="hero-subtitle">Pantau ringkasan pesanan, pembayaran, dan pendapatan kasir hari ini.</p>
                        </div>
                    </section>

                    <section class="stats" aria-label="Statistik kasir">
                        <article class="stat-card">
                            <span>Pesanan Hari Ini</span>
                            <strong id="statTodayOrders">{{ $stats['today_orders'] }}</strong>
                        </article>
                        <article class="stat-card">
                            <span>Pending Payment</span>
                            <strong id="statPendingPayment">{{ $stats['pending_payment'] }}</strong>
                        </article>
                        <article class="stat-card">
                            <span>Diproses</span>
                            <strong id="statProcessingOrders">{{ $stats['processing_orders'] }}</strong>
                        </article>
                        <article class="stat-card">
                            <span>Pendapatan Hari Ini</span>
                            <strong id="statTodayRevenue">Rp{{ number_format($stats['today_revenue'], 0, ',', '.') }}</strong>
                        </article>
                    </section>

                    <section class="panel summary-panel">
                        <h2>Ringkasan Kasir Hari Ini</h2>
                        <table class="summary-table">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Pesanan Hari Ini</td><td>{{ $stats['today_orders'] }}</td></tr>
                                <tr><td>Pending Payment</td><td>{{ $stats['pending_payment'] }}</td></tr>
                                <tr><td>Diproses</td><td>{{ $stats['processing_orders'] }}</td></tr>
                                <tr><td>Pendapatan Hari Ini</td><td>Rp{{ number_format($stats['today_revenue'], 0, ',', '.') }}</td></tr>
                            </tbody>
                        </table>
                    </section>
                @endif

                @if (($mode ?? 'dashboard') === 'orders')
                <section class="dashboard-grid">
                    <div class="panel">
                        <div class="row" style="margin-bottom: 14px">
                            <h2 style="margin-bottom: 0">Pesanan QR Masuk</h2>
                            <span class="live-status">
                                <span class="live-dot" id="liveDot"></span>
                                <span id="liveText">Realtime aktif</span>
                            </span>
                        </div>

                        <div class="tabs">
                            @foreach (['aktif' => 'Aktif', 'menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai'] as $value => $label)
                                <a class="tab {{ $status === $value ? 'active' : '' }}" href="{{ route('cashier.orders', ['status' => $value]) }}">{{ $label }}</a>
                            @endforeach
                        </div>

                        <div class="order-list" id="cashierOrderList">
                            @include('cashier.partials.order-list', ['orders' => $orders])
                        </div>
                    </div>

                    <aside class="panel pos-panel">
                        <div>
                            <h2>Pesanan Kasir Langsung</h2>
                            <p class="muted">Untuk customer walk-in yang beli roti langsung di kasir.</p>
                        </div>

                        <input type="search" placeholder="Cari menu bakery" aria-label="Cari menu bakery">

                        <div class="menu-preview">
                            @forelse ($menuItems as $item)
                                <div class="menu-row">
                                    <div>
                                        <strong>{{ $item->name }}</strong>
                                        <p class="muted">{{ $item->category }}</p>
                                    </div>
                                    <span class="price">Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                                </div>
                            @empty
                                <p class="muted">Menu belum tersedia. Tambahkan menu dari dashboard management.</p>
                            @endforelse
                        </div>

                        <div class="quick-actions">
                            <button class="secondary" type="button">Tunai</button>
                            <button class="secondary" type="button">QRIS</button>
                        </div>
                        <button type="button">Buat Pesanan Manual</button>
                    </aside>
                </section>
                @endif
            </main>
        </div>
    </div>

    @if (($mode ?? 'dashboard') === 'orders')
        <div class="toast" id="liveToast">Pesanan baru masuk.</div>

        @php
            $liveOrdersUrl = route('cashier.orders.live', ['status' => $status, 'page' => request('page', 1)]);
        @endphp

        <script>
            (function () {
                const orderList = document.getElementById('cashierOrderList');
                const liveDot = document.getElementById('liveDot');
                const liveText = document.getElementById('liveText');
                const toast = document.getElementById('liveToast');
                const formatter = new Intl.NumberFormat('id-ID');
                const liveUrl = @json($liveOrdersUrl);
                let latestOrderId = getLatestOrderId();
                let firstSync = true;
                let toastTimer = null;

            function getLatestOrderId() {
                const ids = Array.from(document.querySelectorAll('[data-order-id]')).map(function (element) {
                    return Number(element.dataset.orderId || 0);
                });

                return ids.length ? Math.max.apply(null, ids) : 0;
            }

            function setSyncing(syncing) {
                liveDot?.classList.toggle('syncing', syncing);
                if (liveText) {
                    liveText.textContent = syncing ? 'Sinkronisasi...' : 'Realtime aktif';
                }
            }

            function showToast(message) {
                if (!toast) {
                    return;
                }

                toast.textContent = message;
                toast.classList.add('show');
                clearTimeout(toastTimer);
                toastTimer = setTimeout(function () {
                    toast.classList.remove('show');
                }, 2600);
            }

            function updateStats(stats) {
                const todayOrders = document.getElementById('statTodayOrders');
                const pendingPayment = document.getElementById('statPendingPayment');
                const processingOrders = document.getElementById('statProcessingOrders');
                const todayRevenue = document.getElementById('statTodayRevenue');

                if (todayOrders) {
                    todayOrders.textContent = stats.today_orders;
                }

                if (pendingPayment) {
                    pendingPayment.textContent = stats.pending_payment;
                }

                if (processingOrders) {
                    processingOrders.textContent = stats.processing_orders;
                }

                if (todayRevenue) {
                    todayRevenue.textContent = 'Rp' + formatter.format(stats.today_revenue || 0);
                }
            }

            function currentClockText() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');

                return hours + ':' + minutes + ':' + seconds;
            }

            async function syncOrders() {
                if (!orderList || orderList.contains(document.activeElement)) {
                    return;
                }

                try {
                    setSyncing(true);
                    const response = await fetch(liveUrl, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Gagal mengambil data pesanan.');
                    }

                    const data = await response.json();
                    updateStats(data.stats);
                    orderList.innerHTML = data.orders_html;

                    if (!firstSync && data.latest_order_id > latestOrderId) {
                        showToast('Pesanan baru masuk.');
                    }

                    latestOrderId = data.latest_order_id;
                    firstSync = false;
                    if (liveText) {
                        liveText.textContent = 'Update ' + currentClockText();
                    }
                } catch (error) {
                    if (liveText) {
                        liveText.textContent = 'Realtime terputus';
                    }
                } finally {
                    setTimeout(function () {
                        liveDot?.classList.remove('syncing');
                    }, 250);
                }
            }

                setInterval(syncOrders, 5000);
            })();
        </script>
    @endif
</body>
</html>
