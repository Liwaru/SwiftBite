@if ($processingOrders->isEmpty())
    <p class="empty-state">Belum ada pesanan yang sedang diproses.</p>
@else
    <div class="list-stack">
        @foreach ($processingOrders as $order)
            @php
                $flowStep = match ($order->status) {
                    'diproses' => 2,
                    'siap_diantar' => 3,
                    'menunggu_pembayaran', 'selesai' => 4,
                    default => 1,
                };

                $flowSteps = [
                    1 => 'Cashier',
                    2 => 'Baker',
                    3 => 'Waiter',
                    4 => 'Selesai',
                ];
            @endphp

            <div class="order-card" data-order-id="{{ $order->id_order }}">
                <div>
                    {{ $order->kode_pesanan }} -
                    {{ $order->diningTable?->nama_meja ?? 'Kasir Langsung' }}
                </div>

                <div class="order-meta">
                    @foreach ($order->items as $item)
                        {{ $item->qty }}x {{ $item->menuItem?->nama_menu ?? 'Menu' }}@if (! $loop->last), @endif
                    @endforeach
                </div>

                <div class="flow-track" aria-label="Alur pesanan">
                    @foreach ($flowSteps as $step => $label)
                        <span class="flow-step {{ $flowStep > $step ? 'done' : '' }} {{ $flowStep === $step ? 'current' : '' }}">
                            {{ $label }}
                        </span>
                    @endforeach
                </div>

                <form class="ready-form" method="post" action="{{ route('baker.orders.finish-cooking', $order) }}">
    @csrf
    @method('patch')
    <button type="submit">Selesai Masak</button>
</form>
            </div>
        @endforeach
    </div>
@endif