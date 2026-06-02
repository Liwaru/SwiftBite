<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Chef</title>
    @include('chef.partials.styles')
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                <section class="hero-card">
                    <div class="eyebrow">Dapur SwiftBite</div>
                    <h1 class="hero-title">Dashboard Chef</h1>
                    <p class="hero-subtitle">Pantau pesanan yang sedang dibuat dan kondisi bahan baku dapur.</p>
                </section>

                <section class="stats">
                    <article class="stat-card"><span>Pesanan Diproses</span><strong>{{ $stats['pesanan_diproses'] }}</strong></article>
                    <article class="stat-card"><span>Total Bahan</span><strong>{{ $stats['total_bahan'] }}</strong></article>
                    <article class="stat-card"><span>Bahan Menipis</span><strong>{{ $stats['bahan_menipis'] }}</strong></article>
                    <article class="stat-card"><span>Bahan Habis</span><strong>{{ $stats['bahan_habis'] }}</strong></article>
                </section>

                <section class="grid">
                    <div class="panel">
                        <h2>Pesanan Diproses</h2>
                        @if ($processingOrders->isEmpty())
                            <p class="empty-state">Belum ada pesanan yang sedang diproses.</p>
                        @else
                            <div class="list-stack">
                                @foreach ($processingOrders as $order)
                                    <div class="order-card">
                                        <div>{{ $order->kode_pesanan }} - {{ $order->diningTable?->nama_meja ?? 'Tanpa meja' }}</div>
                                        <div class="order-meta">
                                            @foreach ($order->items as $item)
                                                {{ $item->qty }}x {{ $item->menuItem?->nama_menu ?? 'Menu' }}@if (! $loop->last), @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="panel">
                        <h2>Stok Bahan</h2>
                        @if ($ingredients->isEmpty())
                            <p class="empty-state">Belum ada data bahan.</p>
                        @else
                            <div class="list-stack">
                                @foreach ($ingredients->take(6) as $ingredient)
                                    <div class="ingredient-row">
                                        <div>{{ $ingredient->nama_bahan }}</div>
                                        <div class="small">{{ number_format($ingredient->stok, 2, ',', '.') }} {{ $ingredient->satuan }}</div>
                                        <div class="small"><span class="status {{ $ingredient->status_type }}">{{ $ingredient->status_label }}</span></div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
