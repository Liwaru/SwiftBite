@forelse ($orders as $order)
    @php
        $waitingMinutes = $order->created_at ? (int) $order->created_at->diffInMinutes(now()) : 0;
        $waitingText = $waitingMinutes < 60
            ? 'Menunggu ' . max(1, $waitingMinutes) . ' menit'
            : 'Menunggu ' . floor($waitingMinutes / 60) . ' jam ' . ($waitingMinutes % 60) . ' menit';
        $isPaid = $order->status === 'selesai';
        $paidAt = $isPaid ? $order->updated_at?->format('d M Y H:i') : '-';
        $canCashierAccept = $order->status === 'menunggu';
        $canConfirmPayment = $order->status === 'menunggu_pembayaran';
        $flowSteps = [
            1 => 'Kasir',
            2 => 'Baker',
            3 => 'Waiter',
            4 => 'Bayar',
            5 => 'Selesai',
        ];
    @endphp
    <article class="order-card" data-order-id="{{ $order->id_order }}">
        <div class="row">
            <div>
                <div class="badge-row">
                    <span class="badge">{{ $order->status_label }}</span>
                    <span class="badge payment {{ $isPaid ? '' : 'pending' }}">{{ strtoupper($order->payment_label) }}</span>
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

        <div class="flow-track" aria-label="Alur pesanan">
            @foreach ($flowSteps as $step => $label)
                <span class="flow-step {{ $order->flow_step > $step ? 'done' : '' }} {{ $order->flow_step === $step ? 'current' : '' }}">{{ $label }}</span>
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

@if ($order->status === 'menunggu')
    <form class="status" method="post" action="{{ route('cashier.orders.status', $order) }}">
        @csrf
        @method('patch')
        <input type="hidden" name="status" value="diproses">
        <button type="submit">Terima Pesanan & Kirim ke Baker</button>
    </form>
@elseif ($order->status === 'menunggu_pembayaran')
    <form class="status" method="post" action="{{ route('cashier.orders.status', $order) }}">
        @csrf
        @method('patch')
        <input type="hidden" name="status" value="selesai">
        <button type="submit">Konfirmasi Pembayaran & Selesai</button>
    </form>
@elseif ($order->status === 'diproses')
    <span class="status-done">Sedang dibuat oleh Baker</span>
@elseif ($order->status === 'siap_diantar')
    <span class="status-done">Menunggu Waiter mengantar</span>
@elseif ($order->status === 'selesai')
    <span class="status-done">Pesanan Selesai</span>
@else
    <span class="status-done">Menunggu pembayaran</span>
@endif
    </article>
@empty
    <p class="muted empty">
        Belum ada pesanan pada tahap ini.
    </p>
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
