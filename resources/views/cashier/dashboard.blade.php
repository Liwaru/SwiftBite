<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Kasir</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
        .barcode-input-wrap { display: inline-block; position: relative; width: 100%; }
        .barcode-input-wrap input { box-sizing: border-box; width: 100%; padding-right: 44px; }
        .qr-open-btn { position: absolute; right: 6px; top: 50%; transform: translateY(-50%); background: transparent; border: 0; padding: 6px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; }
        .qr-open-btn .bi { font-size: 20px; color: #6b3d00; }
        .app-shell .content-with-sidebar { background: #ffffff !important; }
        main { width: 100%; max-width: none; min-width: 0; box-sizing: border-box; padding: clamp(22px, 2.4vw, 34px) clamp(16px, 2.3vw, 30px) 56px; }
        h1, h2, h3, p { margin: 0; }
        h1 { font-size: clamp(30px, 4vw, 44px); }
        h2 { font-size: 22px; margin-bottom: 14px; }
        h3 { font-size: 17px; margin-bottom: 6px; }
        .muted { color: #7a5a46; line-height: 1.5; }
        .hero-card {
            min-width: 0;
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
        .stats { min-width: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 14px; margin-bottom: 18px; }
        .stat-card, .panel {
            min-width: 0;
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
        .dashboard-grid { min-width: 0; display: grid; grid-template-columns: minmax(0, 1fr) minmax(430px, .64fr); gap: 16px; align-items: stretch; }
        .panel { max-width: 100%; padding: 18px; overflow-wrap: anywhere; }
        .summary-table { width: 100%; min-width: min(520px, 100vw); border-collapse: collapse; overflow: hidden; border-radius: 8px; }
        .summary-table th, .summary-table td { padding: 13px 14px; border-top: 1px solid rgba(255, 246, 232, .16); text-align: left; }
        .summary-table th { background: rgba(255, 246, 232, .1); color: rgba(255, 246, 232, .78); font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .summary-table td:last-child { text-align: right; font-weight: 900; }
        .summary-panel { max-width: 100%; margin-bottom: 16px; overflow-x: auto; scrollbar-width: thin; -ms-overflow-style: auto; }
        .summary-panel::-webkit-scrollbar { display: block; width: 8px; height: 8px; }
        .summary-panel::-webkit-scrollbar-thumb { background: rgba(255, 246, 232, .34); border-radius: 999px; }
        .notice { padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; background: #edf5e8; color: #355b28; border: 1px solid #c5ddb7; font-weight: 800; cursor: pointer; transition: opacity .18s ease, transform .18s ease; }
        .notice.is-hiding { opacity: 0; transform: translateY(-4px); }
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
        .flow-track { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 6px; }
        .flow-step { min-width: 0; border: 1px solid rgba(255, 246, 232, .18); border-radius: 7px; padding: 8px 5px; background: rgba(255, 246, 232, .08); color: rgba(255, 246, 232, .62); text-align: center; font-size: 11px; font-weight: 900; }
        .flow-step.done { background: rgba(237, 245, 232, .16); color: #dffbd8; border-color: rgba(197, 221, 183, .42); }
        .flow-step.current { background: var(--cream); color: var(--sidebar-brown-dark); border-color: var(--cream); }
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
        .pos-panel { display: grid; grid-template-rows: auto auto auto auto minmax(82px, 1fr) auto; gap: 9px; align-self: stretch; min-height: 100%; max-height: calc(100vh - 128px); overflow: hidden; }
        .pos-panel > * { min-width: 0; }
        .pos-heading { display: grid; gap: 4px; }
        .pos-heading h2 { margin-bottom: 0; font-size: 22px; }
        .pos-heading .muted { font-size: 13px; line-height: 1.35; }
        .pos-mode-tabs { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .pos-mode-tabs button,
        .category-tabs button { min-height: 36px; }
        .pos-mode-tab { background: rgba(255, 246, 232, .16); color: var(--cream); border: 1px solid rgba(255, 246, 232, .26); }
        .pos-mode-tab.active { background: var(--cream); color: var(--sidebar-brown-dark); border-color: var(--cream); }
        .pos-mode-panel { display: none; }
        .pos-mode-panel.active { display: block; }
        .category-tabs { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 7px; }
        .category-tab { min-height: 36px; padding: 7px 8px; border: 1px solid rgba(255, 246, 232, .24); background: rgba(255, 246, 232, .1); color: var(--cream); }
        .category-tab.active { background: var(--cream); color: var(--sidebar-brown-dark); border-color: var(--cream); }
        .barcode-order-form { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 8px; }
        .scan-feedback { min-height: 20px; color: rgba(255, 246, 232, .76); font-size: 13px; font-weight: 900; }
        .scan-feedback.success { color: #dffbd8; }
        .scan-feedback.error { color: #ffd8cf; }
        .menu-preview { min-height: 0; display: grid; align-content: start; gap: 8px; overflow-y: auto; padding-right: 6px; scrollbar-width: thin; scrollbar-color: rgba(255, 246, 232, .38) transparent; }
        .menu-preview::-webkit-scrollbar { width: 8px; }
        .menu-preview::-webkit-scrollbar-track { background: transparent; }
        .menu-preview::-webkit-scrollbar-thumb { background: rgba(255, 246, 232, .32); border-radius: 999px; }
        .menu-row { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 10px; align-items: center; border: 1px solid rgba(255, 246, 232, .14); border-radius: 7px; background: rgba(255, 246, 232, .06); padding: 8px 9px; }
        .menu-row strong, .menu-row p { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .menu-row-actions { display: grid; align-items: center; min-width: 88px; }
        .menu-row-add { width: 42px; height: 36px; justify-self: end; padding: 0; font-size: 0; }
        .menu-row-add::before { content: "+"; font-size: 18px; line-height: 1; }
        .menu-inline-qty { display: grid; grid-template-columns: 28px 32px 28px; gap: 3px; align-items: center; }
        .menu-inline-qty[hidden] { display: none; }
        .menu-inline-qty button { width: 28px; height: 32px; padding: 0; }
        .menu-inline-qty span { min-width: 32px; text-align: center; color: var(--cream); font-weight: 900; }
        .menu-row:first-child { border-top: 1px solid rgba(255, 246, 232, .14); padding-top: 9px; }
        .menu-row.is-hidden { display: none; }
        .cart-table { display: grid; gap: 6px; max-height: 104px; overflow-y: auto; padding-right: 4px; scrollbar-width: thin; scrollbar-color: rgba(255, 246, 232, .38) transparent; }
        .cart-table h3 { margin: 0; font-size: 16px; line-height: 1.2; }
        .cart-head, .cart-line { display: grid; grid-template-columns: minmax(0, 1fr) 86px 96px; gap: 10px; align-items: center; }
        .cart-head { color: rgba(255, 246, 232, .72); font-size: 11px; font-weight: 900; text-transform: uppercase; }
        .cart-head span:nth-child(2) { text-align: center; }
        .cart-head span:last-child { text-align: right; }
        .cart-lines { display: grid; gap: 8px; }
        .cart-line { color: var(--cream); font-weight: 900; }
        .cart-line > span:first-child { min-width: 0; overflow: hidden; }
        .cart-line > span:first-child > :first-child { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .cart-line > span:last-child { text-align: right; white-space: nowrap; font-size: 14px; }
        .cart-line small { display: block; color: rgba(255, 246, 232, .68); font-size: 11px; font-weight: 800; }
        .cart-qty { display: grid; grid-template-columns: 24px 26px 24px; gap: 4px; align-items: center; justify-content: center; }
        .cart-qty button { width: 24px; height: 28px; padding: 0; line-height: 1; }
        .cart-qty span { text-align: center; font-size: 14px; line-height: 1; }
        .payment-choice { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .payment-choice label { display: flex; align-items: center; justify-content: center; gap: 8px; border: 1px solid rgba(255, 246, 232, .24); border-radius: 7px; padding: 8px 10px; font-weight: 900; }
        .payment-choice input { width: auto; }
        .direct-order-form { display: grid; gap: 8px; margin: 0 -18px -18px; padding: 12px 18px 14px; border-top: 1px solid rgba(255, 246, 232, .18); background: linear-gradient(180deg, rgba(90, 50, 31, .96), rgba(39, 20, 13, .98)); box-shadow: 0 -10px 22px rgba(24, 13, 7, .12); }
        .direct-total { display: flex; align-items: center; justify-content: space-between; color: var(--cream); font-weight: 900; }
        .quick-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .scan-form { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 8px; margin-bottom: 14px; }
        .scan-result { margin-bottom: 16px; }
        .scan-result h3 { margin-bottom: 10px; color: var(--cream); }
        .toast { position: fixed; right: 24px; bottom: 24px; z-index: 40; transform: translateY(18px); opacity: 0; pointer-events: none; transition: opacity .2s ease, transform .2s ease; background: #352016; color: #fff8ed; border: 1px solid #8b6040; border-radius: 8px; padding: 12px 14px; font-weight: 900; box-shadow: 0 16px 38px rgba(39, 20, 13, .24); }
        .toast.show { transform: translateY(0); opacity: 1; }
        @media (max-width: 1180px) { .dashboard-grid { grid-template-columns: minmax(0, 1fr) minmax(380px, .72fr); } }
        @media (max-width: 1050px) { .dashboard-grid { grid-template-columns: 1fr; } .pos-panel { max-height: none; overflow: visible; } }
        @media (max-height: 760px) and (min-width: 1051px) {
            .pos-panel { grid-template-rows: auto auto auto auto minmax(70px, 1fr) auto; gap: 8px; max-height: calc(100vh - 112px); }
            .pos-heading .muted { display: none; }
            .pos-mode-tabs button, .category-tabs button { min-height: 34px; padding-block: 7px; }
            .menu-row { padding-block: 7px; }
            .cart-table { max-height: 82px; }
            .direct-order-form { padding-block: 10px 12px; }
        }
        @media (max-width: 760px) { main { padding: 24px 16px 44px; } .hero-card { align-items: flex-start; flex-direction: column; } .stats { grid-template-columns: 1fr; } form.status, .quick-actions, .detail-grid { grid-template-columns: 1fr; } .row { flex-direction: column; } .pagination { align-items: flex-start; flex-direction: column; } }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                @if (session('success'))
                    <div class="notice" role="button" tabindex="0" aria-label="Tutup notifikasi">{{ session('success') }}</div>
                @endif
                @if ($errors->has('scan_code'))
                    <div class="notice" role="button" tabindex="0" aria-label="Tutup notifikasi">{{ $errors->first('scan_code') }}</div>
                @endif
                @if ($errors->has('direct_order'))
                    <div class="notice" role="button" tabindex="0" aria-label="Tutup notifikasi">{{ $errors->first('direct_order') }}</div>
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
                            <span>Menunggu Pembayaran</span>
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
                                <tr><td>Menunggu Pembayaran</td><td>{{ $stats['pending_payment'] }}</td></tr>
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

                        <form class="scan-form" method="post" action="{{ route('cashier.orders.scan') }}">
                            @csrf
                            <input type="search" name="scan_code" placeholder="Scan barcode / masukkan kode pesanan" autocomplete="off" aria-label="Scan barcode pesanan" autofocus>
                            <button type="submit">Scan</button>
                        </form>

                        @if (! empty($scannedOrder))
                            <div class="scan-result">
                                <h3>Hasil Scan</h3>
                                <div class="order-list">
                                    @include('cashier.partials.order-list', ['orders' => collect([$scannedOrder]), 'status' => $scannedOrder->status])
                                </div>
                            </div>
                        @endif

                        <div class="tabs">
                            @foreach (['aktif' => 'Aktif', 'menunggu' => 'Menunggu', 'diproses' => 'Baker', 'siap_diantar' => 'Waiter', 'menunggu_pembayaran' => 'Pembayaran', 'selesai' => 'Selesai'] as $value => $label)
                                <a class="tab {{ $status === $value ? 'active' : '' }}" href="{{ route('cashier.orders', ['status' => $value]) }}">{{ $label }}</a>
                            @endforeach
                        </div>

                        <div class="order-list" id="cashierOrderList">
                            @include('cashier.partials.order-list', ['orders' => $orders])
                        </div>
                    </div>

                    <aside class="panel pos-panel">
                        <div class="pos-heading">
                            <h2>Pesanan Walk-In</h2>
                            <p class="muted">Untuk customer walk-in yang beli roti langsung di kasir.</p>
                        </div>

                        <div class="pos-mode-tabs" role="tablist" aria-label="Mode tambah menu kasir">
                            <button class="pos-mode-tab active" type="button" data-pos-mode="manual">Manual</button>
                            <button class="pos-mode-tab" type="button" data-pos-mode="scan">Scan Barcode</button>
                        </div>

                        <div class="pos-mode-panel active" data-pos-panel="manual">
                            <input type="search" id="manualMenuSearch" placeholder="Cari menu bakery" aria-label="Cari menu bakery">
                        </div>

                        <div class="category-tabs" role="tablist" aria-label="Filter kategori menu">
                            <button class="category-tab active" type="button" data-category-filter="all">Semua</button>
                            <button class="category-tab" type="button" data-category-filter="makanan">Makanan</button>
                            <button class="category-tab" type="button" data-category-filter="minuman">Minuman</button>
                        </div>

                        <div class="pos-mode-panel" data-pos-panel="scan">
                            <form class="barcode-order-form" id="barcodeOrderForm">
                                <div class="barcode-input-wrap">
                                    <input type="search" id="barcodeOrderInput" placeholder="Scan barcode produk" autocomplete="off" inputmode="numeric" aria-label="Scan barcode produk">
                                    <button type="button" class="qr-open-btn js-open-qr" data-target="barcodeOrderInput" aria-label="Buka scanner QR">
                                        <i class="bi bi-qr-code" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <button type="submit">Scan</button>
                            </form>
                            <p class="scan-feedback" id="barcodeOrderFeedback">Arahkan scanner ke barcode produk.</p>
                        </div>

                        <div class="menu-preview" id="manualMenuList">
                            @forelse ($menuItems as $item)
                                <div class="menu-row" data-menu-id="{{ $item->getKey() }}" data-menu-name="{{ strtolower($item->name) }}" data-menu-category-filter="{{ strtolower($item->category) }}">
                                    <div>
                                        <strong>{{ $item->name }}</strong>
                                        <p class="muted">{{ $item->category }} · Stok {{ $item->stok }}</p>
                                    </div>
                                    <div class="menu-row-actions">
                                        <button
                                            type="button"
                                            class="secondary menu-row-add js-add-direct-item"
                                            data-menu-id="{{ $item->getKey() }}"
                                            data-menu-name="{{ $item->name }}"
                                            data-menu-category="{{ $item->category }}"
                                            data-menu-price="{{ (int) $item->price }}"
                                            data-menu-stock="{{ (int) $item->stok }}"
                                            aria-label="Tambah {{ $item->name }}"
                                        >Tambah</button>
                                        <div class="menu-inline-qty" data-menu-qty-wrap="{{ $item->getKey() }}" hidden>
                                            <button type="button" data-menu-inline-minus="{{ $item->getKey() }}" aria-label="Kurangi {{ $item->name }}">-</button>
                                            <span data-menu-inline-qty="{{ $item->getKey() }}">0</span>
                                            <button
                                                type="button"
                                                data-menu-inline-plus="{{ $item->getKey() }}"
                                                data-menu-id="{{ $item->getKey() }}"
                                                data-menu-name="{{ $item->name }}"
                                                data-menu-category="{{ $item->category }}"
                                                data-menu-price="{{ (int) $item->price }}"
                                                data-menu-stock="{{ (int) $item->stok }}"
                                                aria-label="Tambah {{ $item->name }}"
                                            >+</button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="muted">Menu belum tersedia. Tambahkan menu dari dashboard management.</p>
                            @endforelse
                        </div>

                        <form method="post" action="{{ route('cashier.orders.direct-store') }}" id="directOrderForm" class="direct-order-form">
                            @csrf
                            <div class="cart-table">
                                <h3>Pesanan Dipilih</h3>
                                <div class="cart-head">
                                    <span>Menu</span>
                                    <span>Qty</span>
                                    <span>Harga</span>
                                </div>
                                <div class="cart-lines" id="directCartLines">
                                    <p class="muted cart-empty">Belum ada menu dipilih.</p>
                                </div>
                            </div>
                            <div id="directOrderInputs"></div>
                            <div class="payment-choice">
                                <label>
                                    <input type="radio" name="payment_method" value="cash" checked>
                                    Tunai
                                </label>
                                <label>
                                    <input type="radio" name="payment_method" value="qris">
                                    QRIS
                                </label>
                            </div>
                            <div class="direct-total">
                                <span>Total</span>
                                <strong id="directOrderTotal">Rp0</strong>
                            </div>
                            <button type="submit" id="directOrderSubmit" disabled>Buat Pesanan</button>
                        </form>
                    </aside>
                </section>
                @endif
            </main>
        </div>
    </div>

    <script>
        document.querySelectorAll('.notice').forEach((notice) => {
            const dismiss = () => {
                notice.classList.add('is-hiding');
                setTimeout(() => notice.remove(), 180);
            };

            notice.addEventListener('click', dismiss);
            notice.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    dismiss();
                }
            });
        });
    </script>

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
                const barcodeLookupUrl = @json(route('cashier.orders.menu-barcode'));
                const csrfToken = @json(csrf_token());
                const directCartLines = document.getElementById('directCartLines');
                const directOrderInputs = document.getElementById('directOrderInputs');
                const directOrderTotal = document.getElementById('directOrderTotal');
                const directOrderSubmit = document.getElementById('directOrderSubmit');
                const barcodeForm = document.getElementById('barcodeOrderForm');
                const barcodeInput = document.getElementById('barcodeOrderInput');
                const barcodeFeedback = document.getElementById('barcodeOrderFeedback');
                const manualMenuSearch = document.getElementById('manualMenuSearch');
                const manualMenuList = document.getElementById('manualMenuList');
                const categoryTabs = document.querySelectorAll('.category-tab');
                const cart = new Map();
                let activeCategory = 'all';
                let latestOrderId = getLatestOrderId();
                let firstSync = true;
                let toastTimer = null;

            function money(value) {
                return 'Rp' + formatter.format(value || 0);
            }

            function escapeHtml(value) {
                return String(value || '').replace(/[&<>"']/g, function (char) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    }[char];
                });
            }

            function setScanFeedback(message, type) {
                if (!barcodeFeedback) {
                    return;
                }

                barcodeFeedback.textContent = message;
                barcodeFeedback.classList.remove('success', 'error');

                if (type) {
                    barcodeFeedback.classList.add(type);
                }
            }

            function addDirectItem(menu, source) {
                const id = String(menu.id);
                const existing = cart.get(id);
                const stock = Number(menu.stock || 0);

                if (existing) {
                    if (existing.qty >= existing.stock) {
                        setScanFeedback('Stok ' + existing.name + ' hanya ' + existing.stock + '.', 'error');
                        return;
                    }

                    existing.qty += 1;
                    cart.set(id, existing);
                    showToast('+1 ' + existing.name + ' ditambahkan');

                    if (source === 'scan') {
                        setScanFeedback('Scan berhasil: ' + existing.name + ' | Qty ' + existing.qty, 'success');
                    }
                } else {
                    cart.set(id, {
                        id: id,
                        name: menu.name,
                        category: menu.category || 'Menu',
                        price: Number(menu.price || 0),
                        stock: stock,
                        qty: 1
                    });
                    showToast(menu.name + ' ditambahkan');

                    if (source === 'scan') {
                        setScanFeedback('Scan berhasil: ' + menu.name + ' | Qty 1', 'success');
                    }
                }

                renderDirectCart();
            }

            function menuFromDataset(dataset) {
                return {
                    id: dataset.menuId,
                    name: dataset.menuName,
                    category: dataset.menuCategory,
                    price: dataset.menuPrice,
                    stock: dataset.menuStock
                };
            }

            function changeDirectQty(id, delta) {
                const item = cart.get(String(id));

                if (!item) {
                    return;
                }

                item.qty += delta;

                if (item.qty <= 0) {
                    cart.delete(String(id));
                } else if (item.qty > item.stock) {
                    item.qty = item.stock;
                    setScanFeedback('Stok ' + item.name + ' hanya ' + item.stock + '.', 'error');
                } else {
                    cart.set(String(id), item);
                }

                renderDirectCart();
            }

            function renderDirectCart() {
                if (!directCartLines || !directOrderInputs || !directOrderTotal || !directOrderSubmit) {
                    return;
                }

                directCartLines.innerHTML = '';
                directOrderInputs.innerHTML = '';

                if (cart.size === 0) {
                    directCartLines.innerHTML = '<p class="muted cart-empty">Belum ada menu dipilih.</p>';
                    directOrderTotal.textContent = 'Rp0';
                    directOrderSubmit.disabled = true;
                    updateMenuQtyControls();
                    return;
                }

                let total = 0;

                cart.forEach(function (item) {
                    total += item.price * item.qty;

                    const row = document.createElement('div');
                    row.className = 'cart-line';
                    row.innerHTML =
                        '<span><strong>' + escapeHtml(item.name) + '</strong><small>' + escapeHtml(item.category) + '</small></span>' +
                        '<span class="cart-qty">' +
                            '<button type="button" data-cart-minus="' + item.id + '">-</button>' +
                            '<span>' + item.qty + '</span>' +
                            '<button type="button" data-cart-plus="' + item.id + '">+</button>' +
                        '</span>' +
                        '<span>' + money(item.price * item.qty) + '</span>';
                    directCartLines.appendChild(row);

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'items[' + item.id + ']';
                    input.value = item.qty;
                    directOrderInputs.appendChild(input);
                });

                directOrderTotal.textContent = money(total);
                directOrderSubmit.disabled = false;
                updateMenuQtyControls();
            }

            function updateMenuQtyControls() {
                document.querySelectorAll('#manualMenuList .menu-row').forEach(function (row) {
                    const id = String(row.dataset.menuId || '');
                    const item = cart.get(id);
                    const addButton = row.querySelector('.menu-row-add');
                    const qtyWrap = row.querySelector('[data-menu-qty-wrap]');
                    const qtyText = row.querySelector('[data-menu-inline-qty]');

                    if (item) {
                        if (addButton) {
                            addButton.hidden = true;
                        }
                        if (qtyWrap) {
                            qtyWrap.hidden = false;
                        }
                        if (qtyText) {
                            qtyText.textContent = item.qty;
                        }
                    } else {
                        if (addButton) {
                            addButton.hidden = false;
                        }
                        if (qtyWrap) {
                            qtyWrap.hidden = true;
                        }
                        if (qtyText) {
                            qtyText.textContent = '0';
                        }
                    }
                });
            }

            async function lookupBarcode(code) {
                const response = await fetch(barcodeLookupUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ barcode: code })
                });

                const data = await response.json();

                if (!response.ok || !data.found) {
                    throw new Error(data.message || 'Barcode tidak ditemukan.');
                }

                return data.menu;
            }

            document.querySelectorAll('.pos-mode-tab').forEach(function (button) {
                button.addEventListener('click', function () {
                    const mode = button.dataset.posMode;
                    document.querySelectorAll('.pos-mode-tab').forEach(function (tab) {
                        tab.classList.toggle('active', tab === button);
                    });
                    document.querySelectorAll('.pos-mode-panel').forEach(function (panel) {
                        panel.classList.toggle('active', panel.dataset.posPanel === mode);
                    });

                    if (mode === 'scan') {
                        setTimeout(function () {
                            barcodeInput?.focus();
                        }, 60);
                    }
                });
            });

            document.querySelectorAll('.js-add-direct-item').forEach(function (button) {
                button.addEventListener('click', function () {
                    addDirectItem(menuFromDataset(button.dataset), 'manual');
                });
            });

            manualMenuList?.addEventListener('click', function (event) {
                const minus = event.target.closest('[data-menu-inline-minus]');
                const plus = event.target.closest('[data-menu-inline-plus]');

                if (minus) {
                    changeDirectQty(minus.dataset.menuInlineMinus, -1);
                }

                if (plus) {
                    addDirectItem(menuFromDataset(plus.dataset), 'manual');
                }
            });

            function applyMenuFilters() {
                const keyword = (manualMenuSearch?.value || '').trim().toLowerCase();

                document.querySelectorAll('#manualMenuList .menu-row').forEach(function (row) {
                    const nameMatches = keyword === '' || row.dataset.menuName.includes(keyword);
                    const categoryMatches = activeCategory === 'all' || row.dataset.menuCategoryFilter === activeCategory;
                    row.classList.toggle('is-hidden', !nameMatches || !categoryMatches);
                });
            }

            categoryTabs.forEach(function (button) {
                button.addEventListener('click', function () {
                    activeCategory = button.dataset.categoryFilter || 'all';
                    categoryTabs.forEach(function (tab) {
                        tab.classList.toggle('active', tab === button);
                    });
                    applyMenuFilters();
                });
            });

            directCartLines?.addEventListener('click', function (event) {
                const minus = event.target.closest('[data-cart-minus]');
                const plus = event.target.closest('[data-cart-plus]');

                if (minus) {
                    changeDirectQty(minus.dataset.cartMinus, -1);
                }

                if (plus) {
                    changeDirectQty(plus.dataset.cartPlus, 1);
                }
            });

            manualMenuSearch?.addEventListener('input', function () {
                applyMenuFilters();
            });

            barcodeForm?.addEventListener('submit', async function (event) {
                event.preventDefault();

                const code = barcodeInput.value.trim();

                if (!code) {
                    return;
                }

                try {
                    setScanFeedback('Mencari barcode...', null);
                    const menu = await lookupBarcode(code);
                    addDirectItem(menu, 'scan');
                    barcodeInput.value = '';
                    barcodeInput.focus();
                } catch (error) {
                    setScanFeedback(error.message, 'error');
                    barcodeInput.select();
                }
            });

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
<script src="https://unpkg.com/html5-qrcode@2.3.7/minified/html5-qrcode.min.js"></script>
<script>
    (function () {
        let _html5QrScanner = null;
        let _qrScannerTarget = null;
        let _qrRawStream = null;
        let _qrScanRaf = null;
        let _barcodeDetector = null;
        let _qrEscHandler = null;

        function ensureQrModal() {
            if (document.getElementById('qrScannerModal')) return;

            const html = '\n<div id="qrScannerModal" style="position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,0.6);z-index:11000;">\n  <div style="width:360px;max-width:92%;background:#fff;padding:12px;border-radius:8px;box-sizing:border-box;text-align:center;">\n    <div id="qrReader" style="width:100%;height:260px;margin-bottom:8px;"></div>\n    <div id="qrStatus" style="font-size:13px;color:#333;margin-bottom:8px;">Menunggu...</div>\n    <div style="display:flex;gap:8px;justify-content:center;">\n      <button type="button" id="qrCloseBtn" style="padding:8px 12px;border-radius:6px;">Tutup</button>\n    </div>\n  </div>\n</div>';

            document.body.insertAdjacentHTML('beforeend', html);
            const modalEl = document.getElementById('qrScannerModal');
            document.getElementById('qrCloseBtn')?.addEventListener('click', closeQrScanner);
            modalEl?.addEventListener('click', (ev) => { if (ev.target === modalEl) closeQrScanner(); });
        }

        function openQrScanner(targetId) {
            ensureQrModal();
            const modal = document.getElementById('qrScannerModal');
            if (!modal) return;
            modal.style.display = 'flex';
            // show raw camera immediately to prompt permission and display preview
            try { cleanupQrResources(); showRawCameraStream().catch(()=>{}); } catch(e) {}
            // add escape key handler to close modal and stop camera
            try {
                if (!_qrEscHandler) {
                    _qrEscHandler = function (ev) { if (ev.key === 'Escape') closeQrScanner(); };
                    document.addEventListener('keydown', _qrEscHandler);
                }
            } catch(e) {}
            _qrScannerTarget = document.getElementById(targetId) || null;

            // diagnostic info
            debugCameraInfo().catch(e => console.warn('debugCameraInfo failed', e));

            try {
                // prefer native BarcodeDetector if available
                if ('BarcodeDetector' in window) {
                    try {
                        if (!_barcodeDetector) {
                            try { _barcodeDetector = new BarcodeDetector({ formats: ['qr_code','ean_13','code_128'] }); } catch(e) { _barcodeDetector = null; }
                        }
                        await startCameraPreviewAndScan();
                    } catch (e) {
                        console.error('BarcodeDetector flow failed', e);
                        // fallback to html5-qrcode
                    }
                }

                if (!_html5QrScanner && typeof Html5Qrcode !== 'undefined') {
                    try {
                        cleanupQrResources();
                        _html5QrScanner = new Html5Qrcode('qrReader');
                        const cameras = await Html5Qrcode.getCameras();
                        const cameraId = (cameras && cameras.length) ? cameras[0].id : null;
                        const cfg = { fps: 10, qrbox: { width: 250, height: 200 } };
                        const startArg = cameraId ? { deviceId: { exact: cameraId } } : { facingMode: { ideal: 'environment' } };
                        await _html5QrScanner.start(startArg, cfg, (decodedText) => {
                            if (_qrScannerTarget) {
                                _qrScannerTarget.value = decodedText;
                                _qrScannerTarget.dispatchEvent(new Event('input', { bubbles: true }));
                            }
                            closeQrScanner();
                        }, (err) => {});
                    } catch (err) {
                        console.error('Html5Qrcode start failed', err);
                        showRawCameraStream().catch(()=>{});
                    }
                }
            } catch (e) {
                console.error(e);
            }
            // ensure at least a raw preview is shown if other flows didn't start
            try {
                const hasPreview = !!document.getElementById('qrPreview') || !!_qrRawStream || (!!_html5QrScanner && typeof _html5QrScanner.getState === 'function');
                if (!hasPreview) {
                    showRawCameraStream().catch(()=>{});
                }
            } catch(e) {}
        }

        function closeQrScanner() {
            const modal = document.getElementById('qrScannerModal');
            if (modal) modal.style.display = 'none';
            try { cleanupQrResources(); } catch(e) {}
            _qrScannerTarget = null;
            try { if (_qrEscHandler) { document.removeEventListener('keydown', _qrEscHandler); _qrEscHandler = null; } } catch(e) {}
        }

        function setQrStatus(text) {
            const el = document.getElementById('qrStatus');
            if (el) el.textContent = text;
        }

        async function showRawCameraStream() {
            try {
                setQrStatus('Menampilkan kamera langsung...');
                let stream = _qrRawStream || null;
                if (!stream) {
                    stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                    _qrRawStream = stream;
                }
                const readerEl = document.getElementById('qrReader');
                if (!readerEl) return;
                readerEl.innerHTML = '';
                const v = document.createElement('video');
                v.style.width = '100%';
                v.style.height = '100%';
                v.style.objectFit = 'cover';
                v.autoplay = true;
                v.playsInline = true;
                v.muted = true;
                v.srcObject = stream;
                readerEl.appendChild(v);
                try { await v.play(); } catch (playErr) { console.warn('Video play failed', playErr); }
                console.log('Raw camera stream attached (cashier)');
            } catch (err) {
                console.error('Failed to show raw camera stream (cashier)', err);
                setQrStatus('Gagal menampilkan kamera: ' + (err && err.message ? err.message : 'unknown'));
            }
        }

        async function startCameraPreviewAndScan() {
            try {
                const stream = _qrRawStream || await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                _qrRawStream = _qrRawStream || stream;
                const readerEl = document.getElementById('qrReader');
                if (!readerEl) return;
                readerEl.innerHTML = '';
                const v = document.createElement('video');
                v.id = 'qrPreview';
                v.style.width = '100%';
                v.style.height = '100%';
                v.style.objectFit = 'cover';
                v.autoplay = true;
                v.playsInline = true;
                v.muted = true;
                v.srcObject = stream;
                readerEl.appendChild(v);
                try { await v.play(); } catch (e) { console.warn('video play failed', e); }

                const loop = async () => {
                    if (!v || v.readyState < 2 || !_barcodeDetector) { _qrScanRaf = requestAnimationFrame(loop); return; }
                    try {
                        const detections = await _barcodeDetector.detect(v);
                        if (detections && detections.length) {
                            const text = detections[0].rawValue || null;
                            if (text && _qrScannerTarget) {
                                _qrScannerTarget.value = text;
                                _qrScannerTarget.dispatchEvent(new Event('input', { bubbles: true }));
                                closeQrScanner();
                                return;
                            }
                        }
                    } catch (e) {}
                    _qrScanRaf = requestAnimationFrame(loop);
                };
                _qrScanRaf = requestAnimationFrame(loop);
            } catch (err) {
                console.error('startCameraPreviewAndScan failed', err);
                throw err;
            }
        }

        function stopCameraPreviewAndScan() {
            if (_qrScanRaf) { cancelAnimationFrame(_qrScanRaf); _qrScanRaf = null; }
            if (_qrRawStream) { try { _qrRawStream.getTracks().forEach(t => t.stop()); } catch(e) {} _qrRawStream = null; }
            const v = document.getElementById('qrPreview'); if (v) { try { v.pause(); v.srcObject = null; } catch(e){} if (v.parentNode) v.parentNode.removeChild(v); }
        }

        function cleanupQrResources() {
            try { if (_qrScanRaf) { cancelAnimationFrame(_qrScanRaf); _qrScanRaf = null; } } catch(e) {}
            try { if (_qrRawStream) { _qrRawStream.getTracks().forEach(t => t.stop()); _qrRawStream = null; } } catch(e) {}
            try {
                if (_html5QrScanner) {
                    try { _html5QrScanner.stop().catch(()=>{}); } catch(e) {}
                    try { _html5QrScanner.clear(); } catch(e) {}
                    _html5QrScanner = null;
                }
            } catch(e) {}
            try {
                const videos = document.querySelectorAll('#qrReader video');
                videos.forEach((vv) => {
                    try { if (vv.srcObject && vv.srcObject.getTracks) { vv.srcObject.getTracks().forEach(t=>t.stop()); } } catch(e) {}
                    try { vv.pause(); vv.srcObject = null; } catch(e) {}
                    try { if (vv.parentNode) vv.parentNode.removeChild(vv); } catch(e) {}
                });
                const v = document.getElementById('qrPreview'); if (v) { try { v.pause(); v.srcObject = null; } catch(e){} if (v.parentNode) v.parentNode.removeChild(v); }
            } catch(e) {}
        }

        async function debugCameraInfo() {
            try {
                const statusEl = document.getElementById('qrStatus');
                const parts = [];
                if (navigator.permissions && navigator.permissions.query) {
                    try { const p = await navigator.permissions.query({ name: 'camera' }); parts.push('perm=' + p.state); } catch(e) {}
                }
                if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    const vids = devices.filter(d => d.kind === 'videoinput');
                    parts.push('videoInputs=' + vids.length);
                    vids.forEach((d,i) => parts.push('#' + i + ':' + (d.label || 'hidden')));
                }
                console.log('Camera debug:', parts.join(' | '));
                if (statusEl) statusEl.textContent = parts.join(' | ');
            } catch(err) { console.warn('debugCameraInfo error', err); }
        }

        document.addEventListener('click', (event) => {
            const btn = event.target.closest && event.target.closest('.js-open-qr');
            if (!btn) return;
            const target = btn.dataset.target;
            if (target) openQrScanner(target);
        });
    })();
</script>
</html>
