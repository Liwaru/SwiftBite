<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <title>Laporan Keuangan SwiftBite</title>
    <style>
        @page { size: auto; margin: 0; }
        body { margin: 0; padding: 28px; font-family: Arial, sans-serif; color: #26150d; }
        h1, h2, p { margin: 0; }
        h1 { font-size: 28px; }
        h2 { margin-top: 24px; margin-bottom: 10px; font-size: 18px; }
        .muted { margin-top: 6px; color: #6d5547; }
        .stats { display: table; width: 100%; margin-top: 18px; table-layout: fixed; border-spacing: 8px; }
        .card { display: table-cell; border: 1px solid #b98a6a; border-radius: 8px; padding: 12px; }
        .card span { display: block; color: #6d5547; font-size: 12px; font-weight: 700; }
        .card strong { display: block; margin-top: 8px; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #d6b9a5; padding: 8px; text-align: left; font-size: 12px; }
        th { background: #f4e3cd; }
        td:last-child, th:last-child { text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Keuangan SwiftBite</h1>
    <p class="muted">Periode {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>

    <section class="stats">
        <div class="card"><span>Total Pemasukan</span><strong>Rp{{ number_format($summary['total_income'], 0, ',', '.') }}</strong></div>
        <div class="card"><span>Total Pengeluaran</span><strong>Rp{{ number_format($summary['total_expense'], 0, ',', '.') }}</strong></div>
        <div class="card"><span>Laba Bersih</span><strong>Rp{{ number_format($summary['net_profit'], 0, ',', '.') }}</strong></div>
        <div class="card"><span>Transaksi</span><strong>{{ number_format($summary['transactions']) }}</strong></div>
    </section>

    <h2>Pemasukan vs Pengeluaran</h2>
    <table>
        <thead><tr><th>Keterangan</th><th>Nominal</th></tr></thead>
        <tbody>
            @foreach ($comparison as $label => $amount)
                <tr><td>{{ $label }}</td><td>Rp{{ number_format($amount, 0, ',', '.') }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <h2>Pengeluaran Terbesar</h2>
    <table>
        <thead><tr><th>Bahan</th><th>Nominal</th></tr></thead>
        <tbody>
            @forelse ($topExpenses as $expense)
                <tr><td>{{ $expense->nama_bahan }}</td><td>Rp{{ number_format($expense->total_expense, 0, ',', '.') }}</td></tr>
            @empty
                <tr><td colspan="2">Belum ada pengeluaran bahan pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Detail Keuangan</h2>
    <table>
        <thead><tr><th>Tanggal</th><th>Jenis</th><th>Keterangan</th><th>Nominal</th></tr></thead>
        <tbody>
            @forelse ($financialRows as $row)
                <tr>
                    <td>{{ $row['date']?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $row['type'] }}</td>
                    <td>{{ $row['description'] }}</td>
                    <td>Rp{{ number_format($row['amount'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Belum ada data keuangan pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
