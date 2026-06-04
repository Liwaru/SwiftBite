<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Diproses</title>
    @include('chef.partials.styles')
</head>
<body>
    <div class="chef-shell">
        @include('chef.partials.topbar')

        <main>
            <section class="hero-card">
                <div class="eyebrow">Dapur SwiftBite</div>
                <h1 class="hero-title">Pesanan Diproses</h1>
                <p class="hero-subtitle">Daftar pesanan yang sedang dibuat oleh dapur.</p>
            </section>

            <section class="panel">
                <h2>Daftar Pesanan</h2>
                @if ($orders->isEmpty())
                    <p class="empty-state">Belum ada pesanan yang sedang diproses.</p>
                @else
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Meja</th>
                                    <th>Isi Pesanan</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->kode_pesanan }}</td>
                                        <td>{{ $order->diningTable?->nama_meja ?? '-' }}</td>
                                        <td>
                                            @foreach ($order->items as $item)
                                                <div>{{ $item->qty }}x {{ $item->menuItem?->nama_menu ?? 'Menu' }}</div>
                                            @endforeach
                                        </td>
                                        <td>{{ $order->notes ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </main>
    </div>
</body>
</html>
