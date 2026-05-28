@forelse ($orders as $order)
    <article class="order-card" data-order-id="{{ $order->id_order }}">
        <div class="row">
            <div>
                <div class="badge-row">
                    <span class="badge">{{ $order->status }}</span>
                    <span class="badge payment">{{ strtoupper($order->payment_method) }}</span>
                </div>
                <h3>{{ $order->diningTable?->name ?? 'Meja' }} &middot; #{{ $order->kode_pesanan }}</h3>
                <p class="muted">{{ $order->created_at?->format('d M Y H:i') }}</p>
            </div>
            <span class="price">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>

        <div class="items">
            @foreach ($order->items as $item)
                <p>{{ $item->quantity }}x {{ $item->menu_name }} <span class="muted">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span></p>
            @endforeach
        </div>

        <form class="status" method="post" action="{{ route('cashier.orders.status', $order) }}">
            @csrf
            @method('patch')
            <select name="status">
                @foreach (['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $value => $label)
                    <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit">Update</button>
        </form>
    </article>
@empty
    <p class="muted">Belum ada pesanan QR masuk.</p>
@endforelse
