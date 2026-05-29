@forelse ($orders as $order)
    @php
        $waitingMinutes = $order->created_at ? (int) $order->created_at->diffInMinutes(now()) : 0;
        $waitingText = $waitingMinutes < 60
            ? 'Menunggu ' . max(1, $waitingMinutes) . ' menit'
            : 'Menunggu ' . floor($waitingMinutes / 60) . ' jam ' . ($waitingMinutes % 60) . ' menit';
        $paymentMethod = strtoupper($order->payment_method);
        $paymentLabel = $paymentMethod === 'CASH' ? 'TUNAI' : $paymentMethod;
        $isPaid = $order->status === 'selesai';
        $paidAt = $isPaid ? $order->updated_at?->format('d M Y H:i') : '-';
        $canCashierAccept = $order->status === 'menunggu';
    @endphp
    <article class="order-card" data-order-id="{{ $order->id_order }}">
        <div class="row">
            <div>
                <div class="badge-row">
                    <span class="badge">{{ ucfirst($order->status) }}</span>
                    <span class="badge payment {{ $isPaid ? '' : 'pending' }}">{{ $paymentLabel }}</span>
                </div>
                <h3>{{ $order->diningTable?->name ?? 'Meja' }} &middot; #{{ $order->kode_pesanan }}</h3>
                <p class="order-meta">
                    <span>{{ $order->created_at?->format('H:i') }}</span>
                    <span>&middot;</span>
                    <span class="wait-time">{{ $waitingText }}</span>
                </p>
            </div>
            <span class="price">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>

        <div class="items">
            @foreach ($order->items as $item)
                <p>{{ $item->quantity }}x {{ $item->menu_name }} <span class="muted">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span></p>
            @endforeach
        </div>

        <details class="order-detail">
            <summary class="detail-toggle">Detail Pesanan</summary>
            <div class="detail-grid">
                <p>
                    <span>Catatan pelanggan</span>
                    <strong>{{ $order->notes ?: '-' }}</strong>
                </p>
                <p>
                    <span>Waktu bayar</span>
                    <strong>{{ $paidAt }}</strong>
                </p>
            </div>
        </details>

        @if ($canCashierAccept)
            <form class="status" method="post" action="{{ route('cashier.orders.status', $order) }}">
                @csrf
                @method('patch')
                <input type="hidden" name="status" value="diproses">
                <button type="submit">Terima Pesanan</button>
            </form>
        @elseif ($order->status === 'diproses')
            <span class="status-done">Menunggu konfirmasi waiter</span>
        @else
            <span class="status-done">Pesanan Selesai</span>
        @endif
    </article>
@empty
    <article class="order-card" data-order-id="0">
        <div class="row">
            <div>
                <div class="badge-row">
                    <span class="badge">Menunggu</span>
                    <span class="badge payment pending">Tunai</span>
                </div>
                <h3>Meja 06 &middot; #SB-1025</h3>
                <p class="order-meta">
                    <span>18:35</span>
                    <span>&middot;</span>
                    <span class="wait-time">Menunggu 8 menit</span>
                </p>
            </div>
            <span class="price">Rp28.000</span>
        </div>

        <div class="items">
            <p>1x Roti Croissant <span class="muted">Rp18.000</span></p>
            <p>1x Air Putih <span class="muted">Rp10.000</span></p>
        </div>

        <details class="order-detail">
            <summary class="detail-toggle">Detail Pesanan</summary>
            <div class="detail-grid">
                <p>
                    <span>Catatan pelanggan</span>
                    <strong>-</strong>
                </p>
                <p>
                    <span>Waktu bayar</span>
                    <strong>-</strong>
                </p>
            </div>
        </details>

        <form class="status">
            <button type="button" disabled>Terima Pesanan</button>
        </form>
    </article>
@endforelse

@if (method_exists($orders, 'hasPages') && $orders->hasPages())
    <nav class="pagination" aria-label="Pagination pesanan">
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
