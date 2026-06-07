<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Baker</title>
    @include('chef.partials.styles')
</head>
<body>
    <div class="chef-shell">
        @include('chef.partials.topbar')

        <main>
            <section class="hero-card">
                <div class="eyebrow">Baker SwiftBite</div>
                <h1 class="hero-title">Pesanan Baker</h1>
                <p class="hero-subtitle">Daftar pesanan yang sedang dibuat oleh baker.</p>
            </section>

            <section class="panel">
                <h2>Daftar Pesanan Untuk Dibuat</h2>
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
                                    <th>Alur</th>
                                    <th>Catatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    @php
                                        $flowStep = match ($order->status) {
                                            'diproses' => 2,
                                            'siap_diantar' => 3,
                                            'menunggu_pembayaran', 'selesai' => 4,
                                            default => 1,
                                        };
                                        $flowSteps = [1 => 'Cashier', 2 => 'Baker', 3 => 'Waiter', 4 => 'Selesai'];
                                    @endphp
                                    <tr>
                                        <td>{{ $order->kode_pesanan }}</td>
                                        <td>{{ $order->diningTable?->nama_meja ?? '-' }}</td>
                                        <td>
                                            @foreach ($order->items as $item)
                                                <div>{{ $item->qty }}x {{ $item->menuItem?->nama_menu ?? 'Menu' }}</div>
                                            @endforeach
                                        </td>
                                        <td>
                                            <div class="flow-track table-flow" aria-label="Alur pesanan">
                                                @foreach ($flowSteps as $step => $label)
                                                    <span class="flow-step {{ $flowStep > $step ? 'done' : '' }} {{ $flowStep === $step ? 'current' : '' }}">{{ $label }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>{{ $order->notes ?? '-' }}</td>
                                        <td>
                                            <form method="post" action="{{ route('baker.orders.ready', $order) }}">
                                                @csrf
                                                @method('patch')
                                                <button type="submit">Siap Diantar</button>
                                            </form>
                                        </td>
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
