<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Export PDF Laporan Produk</title>
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
        .card strong { display: block; margin-top: 8px; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #d6b9a5; padding: 8px; text-align: left; font-size: 12px; }
        th { background: #f4e3cd; }
        td:last-child, th:last-child { text-align: right; }
        @media print { html, body { margin: 0 !important; } body { padding: 12mm; } }
    </style>
</head>
<body>
    <h1>Laporan Produk SwiftBite</h1>
    <p class="muted">Periode {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>

    <section class="stats">
        <div class="card"><span>Produk Terjual</span><strong>{{ number_format($summary['total_products_sold']) }}</strong></div>
        <div class="card"><span>Menu Aktif</span><strong>{{ number_format($summary['total_active_menu']) }}</strong></div>
        <div class="card"><span>Produk Terlaris</span><strong>{{ $summary['best_product'] }}</strong></div>
        <div class="card"><span>Produk Terendah</span><strong>{{ $summary['lowest_product'] }}</strong></div>
    </section>

    <h2>Top 5 Produk Terlaris</h2>
    <table>
        <thead><tr><th>Nama Produk</th><th>Terjual</th></tr></thead>
        <tbody>
            @forelse ($topProducts as $product)
                <tr><td>{{ $product->nama_menu }}</td><td>{{ number_format($product->total_sold) }}</td></tr>
            @empty
                <tr><td colspan="2">Belum ada produk terjual pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Top 5 Produk Kurang Diminati</h2>
    <table>
        <thead><tr><th>Nama Produk</th><th>Terjual</th></tr></thead>
        <tbody>
            @forelse ($lowProducts as $product)
                <tr><td>{{ $product->nama_menu }}</td><td>{{ number_format($product->total_sold) }}</td></tr>
            @empty
                <tr><td colspan="2">Belum ada data produk.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Pendapatan per Produk</h2>
    <table>
        <thead><tr><th>Nama Produk</th><th>Pendapatan</th></tr></thead>
        <tbody>
            @forelse ($revenueByProduct as $product)
                <tr><td>{{ $product->nama_menu }}</td><td>Rp{{ number_format($product->total_revenue, 0, ',', '.') }}</td></tr>
            @empty
                <tr><td colspan="2">Belum ada pendapatan produk pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Detail Produk</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Terjual</th>
                <th>Pendapatan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productRows as $product)
                <tr>
                    <td>{{ $product->nama_menu }}</td>
                    <td>{{ $product->nama_kategori }}</td>
                    <td>Rp{{ number_format($product->harga, 0, ',', '.') }}</td>
                    <td>{{ number_format($product->total_sold) }}</td>
                    <td>Rp{{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                    <td>{{ $product->status === 'tersedia' ? 'Aktif' : 'Habis' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
