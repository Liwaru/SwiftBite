<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <title>Export PDF Laporan Bahan</title>
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
    <h1>Laporan Bahan SwiftBite</h1>
    <p class="muted">Periode {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>

    <section class="stats">
        <div class="card"><span>Total Bahan</span><strong>{{ number_format($summary['total']) }}</strong></div>
        <div class="card"><span>Bahan Aman</span><strong>{{ number_format($summary['aman']) }}</strong></div>
        <div class="card"><span>Bahan Menipis</span><strong>{{ number_format($summary['menipis']) }}</strong></div>
        <div class="card"><span>Bahan Habis</span><strong>{{ number_format($summary['habis']) }}</strong></div>
    </section>

    <h2>Top Bahan Paling Banyak Digunakan</h2>
    <table>
        <thead><tr><th>Nama Bahan</th><th>Digunakan</th></tr></thead>
        <tbody>
            @forelse ($topUsedIngredients as $ingredient)
                <tr><td>{{ $ingredient['nama_bahan'] }}</td><td>{{ number_format($ingredient['digunakan'], 2, ',', '.') }} {{ $ingredient['satuan'] }}</td></tr>
            @empty
                <tr><td colspan="2">Belum ada bahan yang digunakan pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Ringkasan Penggunaan Bahan</h2>
    <table>
        <thead>
            <tr>
                <th>Bahan</th>
                <th>Stok Awal</th>
                <th>Masuk</th>
                <th>Digunakan</th>
                <th>Sisa</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usageSummary as $row)
                <tr>
                    <td>{{ $row['nama_bahan'] }}</td>
                    <td>{{ number_format($row['stok_awal'], 2, ',', '.') }} {{ $row['satuan'] }}</td>
                    <td>{{ number_format($row['masuk'], 2, ',', '.') }} {{ $row['satuan'] }}</td>
                    <td>{{ number_format($row['digunakan'], 2, ',', '.') }} {{ $row['satuan'] }}</td>
                    <td>{{ number_format($row['sisa'], 2, ',', '.') }} {{ $row['satuan'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Riwayat Pergerakan Bahan</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Bahan</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($movementRows as $row)
                <tr>
                    <td>{{ $row['date']?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $row['ingredient'] }}</td>
                    <td>{{ $row['type'] }}</td>
                    <td>{{ number_format($row['qty'], 2, ',', '.') }} {{ $row['unit'] }}</td>
                    <td>{{ $row['note'] }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada pergerakan bahan pada periode ini.</td></tr>
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
