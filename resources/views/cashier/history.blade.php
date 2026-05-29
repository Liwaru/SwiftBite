<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Transaksi</title>
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
        main { width: 100%; max-width: none; box-sizing: border-box; padding: 34px 30px 56px; }
        h1, h2, h3, p { margin: 0; }
        h1 { font-size: clamp(30px, 4vw, 44px); }
        h2 { font-size: 22px; margin-bottom: 14px; }
        h3 { font-size: 17px; margin-bottom: 6px; }
        .muted { color: #7a5a46; line-height: 1.5; }
        .topbar { display: flex; justify-content: space-between; gap: 16px; align-items: end; margin-bottom: 22px; }
        .panel {
            background:
                linear-gradient(135deg, rgba(154, 98, 57, .94), rgba(90, 50, 31, .98) 52%, rgba(39, 20, 13, .98));
            border: 1px solid rgba(255, 246, 232, .22);
            border-radius: 8px;
            box-shadow: 0 12px 30px rgba(39, 20, 13, .18);
            color: var(--cream);
            padding: 18px;
        }
        .panel .muted { color: rgba(255, 246, 232, .76); }
        .section-head { display: flex; justify-content: space-between; gap: 14px; align-items: center; margin-bottom: 16px; }
        .section-head h2 { margin-bottom: 0; }
        .filter-form { display: grid; grid-template-columns: minmax(260px, 1.4fr) minmax(150px, .7fr) minmax(170px, .8fr) minmax(150px, .7fr) auto; gap: 10px; align-items: center; margin-bottom: 16px; }
        .search-input, .filter-select, .date-input {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid rgba(255, 246, 232, .34);
            border-radius: 7px;
            padding: 10px 11px;
            font: inherit;
            background: var(--cream-soft);
            color: #352016;
        }
        .custom-range { display: none; grid-column: 1 / -1; grid-template-columns: 1fr 1fr; gap: 10px; }
        .custom-range.show { display: grid; }
        .filter-actions { display: flex; gap: 8px; align-items: center; }
        button, .button { border: 0; border-radius: 7px; background: var(--cream); color: var(--sidebar-brown-dark); padding: 10px 13px; font-weight: 900; cursor: pointer; text-decoration: none; text-align: center; white-space: nowrap; }
        .button.secondary { background: rgba(255, 246, 232, .14); color: var(--cream); border: 1px solid rgba(255, 246, 232, .26); }
        .badge { display: inline-flex; width: fit-content; padding: 4px 8px; border-radius: 999px; background: rgba(255, 246, 232, .16); color: var(--cream); font-size: 12px; font-weight: 900; }
        .badge.payment { background: #edf5e8; color: #355b28; }
        .badge.demo { background: var(--cream); color: var(--sidebar-brown-dark); }
        .history-table-wrap { overflow-x: auto; border: 1px solid rgba(255, 246, 232, .2); border-radius: 8px; background: rgba(255, 246, 232, .08); }
        .history-table { width: 100%; min-width: 820px; border-collapse: collapse; }
        .history-table th, .history-table td { padding: 13px 14px; text-align: left; vertical-align: top; border-bottom: 1px solid rgba(255, 246, 232, .16); }
        .history-table th { background: rgba(255, 246, 232, .14); color: rgba(255, 246, 232, .82); font-size: 12px; letter-spacing: 0; text-transform: uppercase; }
        .history-table tr:last-child td { border-bottom: 0; }
        .history-table tbody tr { background: rgba(255, 246, 232, .04); }
        .history-table tbody tr:nth-child(even) { background: rgba(255, 246, 232, .08); }
        .history-table strong { color: var(--cream); }
        .items { display: grid; gap: 4px; min-width: 220px; }
        .row { display: flex; justify-content: space-between; gap: 12px; align-items: center; }
        .price { font-weight: 900; white-space: nowrap; }
        .empty-row { padding: 18px; text-align: center; }
        .pagination { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 10px; margin-top: 14px; }
        .pagination-info { color: rgba(255, 246, 232, .76); font-size: 13px; font-weight: 800; }
        .pagination-links { display: flex; flex-wrap: wrap; gap: 7px; }
        .page-link, .page-current, .page-disabled { min-width: 34px; box-sizing: border-box; border-radius: 7px; padding: 8px 10px; text-align: center; font-size: 13px; font-weight: 900; }
        .page-link { border: 1px solid rgba(255, 246, 232, .26); color: var(--cream); text-decoration: none; background: rgba(255, 246, 232, .1); }
        .page-current { background: var(--cream); color: var(--sidebar-brown-dark); }
        .page-disabled { border: 1px solid rgba(255, 246, 232, .12); color: rgba(255, 246, 232, .45); }
        @media (max-width: 1180px) { .filter-form { grid-template-columns: minmax(240px, 1fr) repeat(3, minmax(140px, .6fr)); } .filter-actions { grid-column: 1 / -1; justify-content: flex-end; } }
        @media (max-width: 760px) { main { padding: 24px 16px 44px; } .topbar, .section-head { align-items: flex-start; flex-direction: column; } .row { align-items: flex-start; flex-direction: column; } .filter-form, .custom-range { grid-template-columns: 1fr; } .filter-actions { justify-content: stretch; } .filter-actions button, .filter-actions .button { flex: 1; } .pagination { align-items: flex-start; flex-direction: column; } }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                <div class="topbar">
                    <h1>Riwayat Transaksi</h1>
                </div>

                <section class="panel">
                    <div class="section-head">
                        <div>
                            <h2>Riwayat Transaksi</h2>
                            <p class="muted">Daftar pesanan yang sudah selesai atau dibatalkan.</p>
                        </div>
                    </div>

                    <form class="filter-form" method="get" action="{{ route('cashier.history') }}">
                        <input class="search-input" type="search" name="search" value="{{ $filters['search'] }}" placeholder="Cari invoice, customer, atau meja..." aria-label="Cari transaksi">

                        <select class="filter-select" name="status" aria-label="Filter status transaksi">
                            @foreach (['semua' => 'Semua Status', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $value => $label)
                                <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                            @endforeach
                        </select>

                        <select class="filter-select" name="payment" aria-label="Filter metode pembayaran">
                            @foreach (['semua' => 'Semua Pembayaran', 'cash' => 'Tunai', 'qris' => 'QRIS', 'ewallet' => 'E-Wallet'] as $value => $label)
                                <option value="{{ $value }}" @selected($filters['payment'] === $value)>{{ $label }}</option>
                            @endforeach
                        </select>

                        <select class="filter-select" id="historyDateFilter" name="date" aria-label="Filter tanggal">
                            @foreach (['today' => 'Hari Ini', 'yesterday' => 'Kemarin', 'week' => 'Minggu Ini', 'month' => 'Bulan Ini', 'custom' => 'Custom Range'] as $value => $label)
                                <option value="{{ $value }}" @selected($filters['date'] === $value)>{{ $label }}</option>
                            @endforeach
                        </select>

                        <div class="filter-actions">
                            <button type="submit">Terapkan</button>
                            <a class="button secondary" href="{{ route('cashier.history') }}">Reset</a>
                        </div>

                        <div class="custom-range {{ $filters['date'] === 'custom' ? 'show' : '' }}" id="customDateRange">
                            <input class="date-input" type="date" name="date_from" value="{{ $filters['date_from'] }}" aria-label="Tanggal mulai">
                            <input class="date-input" type="date" name="date_to" value="{{ $filters['date_to'] }}" aria-label="Tanggal akhir">
                        </div>
                    </form>

                    <div class="history-table-wrap">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Pesanan</th>
                                    <th>Customer</th>
                                    <th>Item</th>
                                    <th>Pembayaran</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                        @forelse ($orders as $order)
                            @php
                                $paymentLabel = $order->payment_method === 'cash' ? 'TUNAI' : strtoupper($order->payment_method);
                            @endphp
                                <tr>
                                    <td>
                                        <strong>#{{ $order->kode_pesanan }}</strong>
                                        <p class="muted">{{ $order->diningTable?->name ?? 'Meja' }}</p>
                                    </td>
                                    <td>{{ $order->customer_name ?: 'Tanpa nama' }}</td>
                                    <td>
                                        <div class="items">
                                    @foreach ($order->items as $item)
                                        <p>{{ $item->quantity }}x {{ $item->menu_name }} <span class="muted">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span></p>
                                    @endforeach
                                        </div>
                                    </td>
                                    <td><span class="badge payment">{{ $paymentLabel }}</span></td>
                                    <td><span class="badge">{{ $order->status }}</span></td>
                                    <td class="price">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td>{{ $order->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                        @empty
                            @php
                                $demoOrders = collect([
                                    [
                                        'code' => 'SB-1024',
                                        'table' => 'Meja 04',
                                        'customer' => 'Dewi',
                                        'items' => ['2x Croissant Butter', '1x Iced Latte'],
                                        'payment' => 'QRIS',
                                        'status' => 'selesai',
                                        'total' => 78000,
                                        'time' => '29 Mei 2026 18:20',
                                    ],
                                    [
                                        'code' => 'SB-1023',
                                        'table' => 'Meja 02',
                                        'customer' => 'Raka',
                                        'items' => ['1x Cinnamon Roll', '2x Americano'],
                                        'payment' => 'TUNAI',
                                        'status' => 'selesai',
                                        'total' => 64000,
                                        'time' => '29 Mei 2026 17:45',
                                    ],
                                    [
                                        'code' => 'SB-1022',
                                        'table' => 'Meja 07',
                                        'customer' => 'Tanpa nama',
                                        'items' => ['3x Pain au Chocolat'],
                                        'payment' => 'SHOPEEPAY',
                                        'status' => 'dibatalkan',
                                        'total' => 0,
                                        'time' => '29 Mei 2026 16:10',
                                    ],
                                ])
                                    ->when($filters['search'] !== '', function ($orders) use ($filters) {
                                        $search = strtolower($filters['search']);

                                        return $orders->filter(function ($order) use ($search) {
                                            return str_contains(strtolower($order['code']), $search)
                                                || str_contains(strtolower($order['customer']), $search)
                                                || str_contains(strtolower($order['table']), $search);
                                        });
                                    })
                                    ->when($filters['status'] !== 'semua', fn ($orders) => $orders->where('status', $filters['status']))
                                    ->when($filters['payment'] !== 'semua', function ($orders) use ($filters) {
                                        if ($filters['payment'] === 'ewallet') {
                                            return $orders->whereIn('payment', ['GOPAY', 'OVO', 'DANA', 'SHOPEEPAY']);
                                        }

                                        return $orders->where('payment', $filters['payment'] === 'cash' ? 'TUNAI' : strtoupper($filters['payment']));
                                    });
                            @endphp

                            @forelse ($demoOrders as $demoOrder)
                                <tr>
                                    <td>
                                        <strong>#{{ $demoOrder['code'] }}</strong>
                                        <p class="muted">{{ $demoOrder['table'] }}</p>
                                    </td>
                                    <td>{{ $demoOrder['customer'] }}</td>
                                    <td>
                                        <div class="items">
                                            @foreach ($demoOrder['items'] as $item)
                                                <p>{{ $item }}</p>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td><span class="badge payment">{{ $demoOrder['payment'] }}</span></td>
                                    <td><span class="badge">{{ $demoOrder['status'] }}</span></td>
                                    <td class="price">Rp{{ number_format($demoOrder['total'], 0, ',', '.') }}</td>
                                    <td>{{ $demoOrder['time'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <p class="empty-row muted">Tidak ada transaksi yang cocok dengan filter.</p>
                                    </td>
                                </tr>
                            @endforelse
                        @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($orders->hasPages())
                        <nav class="pagination" aria-label="Pagination riwayat transaksi">
                            <span class="pagination-info">
                                Menampilkan {{ $orders->firstItem() }}-{{ $orders->lastItem() }} dari {{ $orders->total() }} transaksi
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
    </div>
    <script>
        (function () {
            const dateFilter = document.getElementById('historyDateFilter');
            const customRange = document.getElementById('customDateRange');

            function syncCustomRange() {
                customRange?.classList.toggle('show', dateFilter?.value === 'custom');
            }

            dateFilter?.addEventListener('change', syncCustomRange);
            syncCustomRange();
        })();
    </script>
</body>
</html>
