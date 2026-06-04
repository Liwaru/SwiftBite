<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <title>Export PDF Laporan Penjualan</title>
    <style>
        @page { size: auto; margin: 0; }
        body { margin: 0; padding: 28px; font-family: Arial, sans-serif; color: #26150d; }
        h1, h2, p { margin: 0; }
        h1 { font-size: 28px; }
        h2 { margin-top: 24px; margin-bottom: 10px; font-size: 18px; }
        .muted { margin-top: 6px; color: #6d5547; }
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-top: 18px; }
        .card { border: 1px solid #b98a6a; border-radius: 8px; padding: 12px; }
        .card span { display: block; color: #6d5547; font-size: 12px; font-weight: 700; }
        .card strong { display: block; margin-top: 8px; font-size: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #d6b9a5; padding: 8px; text-align: left; font-size: 12px; }
        th { background: #f4e3cd; }
        td:last-child, th:last-child { text-align: right; }
        @media print { html, body { margin: 0 !important; } body { padding: 12mm; } }
    </style>
</head>
<body>
    <h1>Laporan Penjualan SwiftBite</h1>
    <p class="muted">Periode {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>

    <section class="stats">
        <div class="card"><span>Total Pesanan</span><strong>{{ number_format($summary['total_orders']) }}</strong></div>
        <div class="card"><span>Produk Terjual</span><strong>{{ number_format($summary['total_products_sold']) }}</strong></div>
        <div class="card"><span>Pendapatan</span><strong>Rp{{ number_format($summary['total_revenue'], 0, ',', '.') }}</strong></div>
        <div class="card"><span>Rata-rata Transaksi</span><strong>Rp{{ number_format($summary['average_transaction'], 0, ',', '.') }}</strong></div>
    </section>

    <h2>Ringkasan Status Pesanan</h2>
    <table>
        <thead><tr><th>Status</th><th>Jumlah</th></tr></thead>
        <tbody>
            @foreach ($statusSummary as $status => $total)
                <tr><td>{{ $status }}</td><td>{{ number_format($total) }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <h2>Top 5 Menu Terlaris</h2>
    <table>
        <thead><tr><th>Nama Menu</th><th>Terjual</th></tr></thead>
        <tbody>
            @forelse ($topMenus as $menu)
                <tr><td>{{ $menu->nama_menu }}</td><td>{{ number_format($menu->total_qty) }}</td></tr>
            @empty
                <tr><td colspan="2">Belum ada menu terjual pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Metode Pembayaran</h2>
    <table>
        <thead><tr><th>Metode</th><th>Jumlah</th></tr></thead>
        <tbody>
            @foreach ($paymentSummary as $method => $total)
                <tr><td>{{ $method }}</td><td>{{ number_format($total) }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <h2>Detail Transaksi</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Invoice</th>
                <th>Meja</th>
                <th>Item</th>
                <th>Bayar</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactionRows as $transaction)
                <tr>
                    <td>{{ $transaction->created_at?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $transaction->kode_pesanan }}</td>
                    <td>{{ $transaction->diningTable?->nama_meja ?? '-' }}</td>
                    <td>{{ number_format($transaction->total_items ?? 0) }}</td>
                    <td>{{ match ($transaction->metode_pembayaran) {
                        'cash' => 'Tunai',
                        'qris' => 'QRIS',
                        'dana' => 'Dana',
                        'ovo' => 'OVO',
                        'gopay' => 'GoPay',
                        'ewallet' => 'E-Wallet',
                        default => $transaction->metode_pembayaran ? ucfirst($transaction->metode_pembayaran) : '-',
                    } }}</td>
                    <td>Rp{{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($transaction->status) }}</td>
                </tr>
            @empty
                <tr><td colspan="7">Belum ada transaksi pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
