<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Antar</title>
    <style>
        :root {
            --brown: #5a321f;
            --brown-dark: #27140d;
            --brown-light: #9a6239;
            --cream: #fff6e8;
            --cream-soft: #fffaf2;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: #ffffff; color: #2b1c15; }
        .waiter-shell { min-height: 100vh; background: #ffffff; }
        .topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            padding: 14px 16px;
            background: linear-gradient(135deg, var(--brown-light), var(--brown) 48%, var(--brown-dark));
            color: var(--cream);
            box-shadow: 0 8px 26px rgba(39, 20, 13, .18);
        }
        .brand { display: flex; align-items: center; gap: 10px; min-width: 0; }
        .logo { width: 38px; height: 38px; display: grid; place-content: center; border-radius: 8px; background: rgba(255, 246, 232, .18); font-size: 13px; font-weight: 900; line-height: 0; text-align: center; }
        .brand strong, .brand span { display: block; white-space: nowrap; }
        .brand span { color: rgba(255, 246, 232, .75); font-size: 12px; font-weight: 800; }
        .account { display: flex; align-items: center; gap: 8px; }
        .account-name { max-width: 96px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 13px; font-weight: 900; }
        .logout { margin: 0; }
        .logout button { border: 1px solid rgba(255, 246, 232, .32); border-radius: 7px; background: rgba(255, 246, 232, .12); color: var(--cream); padding: 8px 10px; font: inherit; font-size: 13px; font-weight: 900; cursor: pointer; }
        main { width: min(100%, 760px); margin: 0 auto; padding: 24px 16px 44px; }
        h1, h2, h3, p { margin: 0; }
        h1 { font-size: clamp(28px, 8vw, 40px); margin-bottom: 16px; }
        h2 { font-size: 20px; margin-bottom: 14px; }
        h3 { font-size: 18px; margin-bottom: 6px; }
        .notice { padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; background: #edf5e8; color: #355b28; border: 1px solid #c5ddb7; font-weight: 800; }
        .panel {
            background: linear-gradient(135deg, rgba(154, 98, 57, .94), rgba(90, 50, 31, .98) 52%, rgba(39, 20, 13, .98));
            border: 1px solid rgba(255, 246, 232, .22);
            border-radius: 8px;
            box-shadow: 0 12px 30px rgba(39, 20, 13, .18);
            color: var(--cream);
            padding: 16px;
        }
        .tabs { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 16px; }
        .tab { border: 1px solid rgba(255, 246, 232, .24); border-radius: 7px; background: rgba(255, 246, 232, .1); color: var(--cream); padding: 10px 12px; font-weight: 900; text-align: center; text-decoration: none; }
        .tab.active { background: var(--cream); border-color: var(--cream); color: var(--brown-dark); }
        .order-list { display: grid; gap: 12px; }
        .order-card { display: grid; gap: 15px; border: 1px solid rgba(255, 246, 232, .22); border-radius: 8px; background: rgba(255, 246, 232, .08); padding: 15px; }
        .card-head { display: flex; justify-content: space-between; gap: 12px; align-items: flex-start; }
        .badge-row { display: flex; flex-wrap: wrap; gap: 7px; margin-bottom: 12px; }
        .badge { display: inline-flex; width: fit-content; padding: 4px 8px; border-radius: 999px; background: rgba(255, 246, 232, .16); color: var(--cream); font-size: 12px; font-weight: 900; }
        .badge.payment { background: #edf5e8; color: #355b28; }
        .price { font-size: 17px; font-weight: 900; white-space: nowrap; }
        .muted { color: rgba(255, 246, 232, .76); line-height: 1.5; }
        .items { display: grid; gap: 7px; padding-top: 2px; }
        .note { display: grid; gap: 3px; border-top: 1px solid rgba(255, 246, 232, .16); padding-top: 12px; }
        .note span { color: rgba(255, 246, 232, .68); font-size: 12px; font-weight: 800; }
        .action button { width: 100%; border: 0; border-radius: 7px; background: var(--cream); color: var(--brown-dark); padding: 12px 13px; font: inherit; font-weight: 900; cursor: pointer; }
        .status-done { display: inline-flex; justify-content: center; width: 100%; border-radius: 7px; background: #edf5e8; color: #355b28; padding: 11px 13px; font-weight: 900; }
        .empty { padding: 10px 0 2px; }
        .pagination { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 10px; margin-top: 14px; }
        .pagination-info { color: rgba(255, 246, 232, .76); font-size: 13px; font-weight: 800; }
        .pagination-links { display: flex; flex-wrap: wrap; gap: 7px; }
        .page-link, .page-current, .page-disabled { min-width: 34px; box-sizing: border-box; border-radius: 7px; padding: 8px 10px; text-align: center; font-size: 13px; font-weight: 900; }
        .page-link { border: 1px solid rgba(255, 246, 232, .26); color: var(--cream); text-decoration: none; background: rgba(255, 246, 232, .1); }
        .page-current { background: var(--cream); color: var(--brown-dark); }
        .page-disabled { border: 1px solid rgba(255, 246, 232, .12); color: rgba(255, 246, 232, .45); }
        @media (max-width: 480px) {
            .topbar { padding: 12px; }
            main { padding: 20px 12px 36px; }
            .account-name { display: none; }
            .card-head { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="waiter-shell">
        <header class="topbar">
            <div class="brand">
                <span class="logo">SB</span>
                <div>
                    <strong>SwiftBite</strong>
                    <span>Waiter</span>
                </div>
            </div>
            <div class="account">
                <form class="logout" method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </header>

        <main>
            <h1>Pesanan Antar</h1>

            @if (session('success'))
                <div class="notice">{{ session('success') }}</div>
            @endif

            <section class="panel">
                <div class="tabs">
                    <a class="tab {{ $status === 'aktif' ? 'active' : '' }}" href="{{ route('waiter.dashboard', ['per_page' => $perPage]) }}">Aktif</a>
                    <a class="tab {{ $status === 'selesai' ? 'active' : '' }}" href="{{ route('waiter.dashboard', ['status' => 'selesai', 'per_page' => $perPage]) }}">Selesai</a>
                </div>

                <div class="order-list">
                    @forelse ($orders as $order)
                        @php
                            $paymentMethod = strtoupper($order->payment_method);
                            $paymentLabel = $paymentMethod === 'CASH' ? 'TUNAI' : $paymentMethod;
                            $actionLabel = $order->payment_method === 'cash' ? 'Konfirmasi Bayar & Selesai' : 'Tandai Selesai';
                            $waitingMinutes = $order->created_at ? (int) $order->created_at->diffInMinutes(now()) : 0;
                            $waitingText = $waitingMinutes < 60
                                ? max(1, $waitingMinutes) . ' menit lalu'
                                : floor($waitingMinutes / 60) . ' jam ' . ($waitingMinutes % 60) . ' menit lalu';
                        @endphp
                        <article class="order-card">
                            <div class="card-head">
                                <div>
                                    <div class="badge-row">
                                        <span class="badge">{{ ucfirst($order->status) }}</span>
                                        <span class="badge payment">{{ $paymentLabel }}</span>
                                    </div>
                                    <h3>{{ $order->diningTable?->name ?? 'Meja' }} &middot; #{{ $order->kode_pesanan }}</h3>
                                    <p class="muted">{{ $waitingText }}</p>
                                </div>
                                <span class="price">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>

                            <div class="items">
                                @foreach ($order->items as $item)
                                    <p>{{ $item->quantity }}x {{ $item->menu_name }} <span class="muted">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span></p>
                                @endforeach
                            </div>

                            <div class="note">
                                <span>Catatan pelanggan</span>
                                <p>{{ $order->notes ?: '-' }}</p>
                            </div>

                            @if ($order->status === 'diproses')
                                <form class="action" method="post" action="{{ route('waiter.orders.complete', $order) }}">
                                    @csrf
                                    @method('patch')
                                    <button type="submit">{{ $actionLabel }}</button>
                                </form>
                            @else
                                <span class="status-done">Pesanan Selesai</span>
                            @endif
                        </article>
                    @empty
                        @if ($status === 'aktif')
                            <article class="order-card">
                                <div class="card-head">
                                    <div>
                                        <div class="badge-row">
                                            <span class="badge">Diproses</span>
                                            <span class="badge payment">TUNAI</span>
                                        </div>
                                        <h3>Meja 06 &middot; #SB-1025</h3>
                                        <p class="muted">8 menit lalu</p>
                                    </div>
                                    <span class="price">Rp28.000</span>
                                </div>

                                <div class="items">
                                    <p>1x Roti Croissant <span class="muted">Rp18.000</span></p>
                                    <p>1x Air Putih <span class="muted">Rp10.000</span></p>
                                </div>

                                <div class="note">
                                    <span>Catatan pelanggan</span>
                                    <p>-</p>
                                </div>

                                <form class="action">
                                    <button type="button" disabled>Konfirmasi Bayar & Selesai</button>
                                </form>
                            </article>
                        @else
                            <p class="muted empty">Belum ada pesanan selesai.</p>
                        @endif
                    @endforelse
                </div>

                @if ($orders->hasPages())
                    <nav class="pagination" aria-label="Pagination pesanan waiter">
                        <span class="pagination-info">
                            Menampilkan {{ $orders->firstItem() }}-{{ $orders->lastItem() }} dari {{ $orders->total() }} pesanan
                        </span>
                        <div class="pagination-links">
                            @if ($orders->onFirstPage())
                                <span class="page-disabled">Prev</span>
                            @else
                                <a class="page-link" href="{{ $orders->previousPageUrl() }}">Prev</a>
                            @endif

                            @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                                @if ($page === $orders->currentPage())
                                    <span class="page-current">{{ $page }}</span>
                                @else
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($orders->hasMorePages())
                                <a class="page-link" href="{{ $orders->nextPageUrl() }}">Next</a>
                            @else
                                <span class="page-disabled">Next</span>
                            @endif
                        </div>
                    </nav>
                @endif
            </section>
        </main>
    </div>
    <script>
        (function () {
            const targetPerPage = window.innerWidth <= 760 ? '3' : '5';
            const url = new URL(window.location.href);

            if (url.searchParams.get('per_page') !== targetPerPage) {
                url.searchParams.set('per_page', targetPerPage);
                url.searchParams.delete('page');
                window.location.replace(url.toString());
            }
        })();
    </script>
</body>
</html>
