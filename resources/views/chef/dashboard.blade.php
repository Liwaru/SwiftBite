<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Baker</title>
    @include('chef.partials.styles')
</head>
<body>
    <div class="chef-shell">
        @include('chef.partials.topbar')

        <main>
            <section class="hero-card">
                <div class="eyebrow">Baker SwiftBite</div>
                <h1 class="hero-title">Dashboard Baker</h1>
                <button type="button" id="enableBakerVoice" style="margin-top:12px;">
    Aktifkan Suara Pesanan
</button>
            </section>

            <section class="stats">
                <article class="stat-card"><span>Pesanan Diproses</span><strong>{{ $stats['pesanan_diproses'] }}</strong></article>
                <article class="stat-card"><span>Total Bahan</span><strong>{{ $stats['total_bahan'] }}</strong></article>
                <article class="stat-card"><span>Bahan Menipis</span><strong>{{ $stats['bahan_menipis'] }}</strong></article>
                <article class="stat-card"><span>Bahan Habis</span><strong>{{ $stats['bahan_habis'] }}</strong></article>
            </section>

            <section class="grid">
                <div class="panel">
    <h2>Pesanan Untuk Dibuat</h2>

    <div id="bakerLiveOrders">
        @include('chef.partials.live-orders', ['processingOrders' => $processingOrders])
    </div>
</div>
                        
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
<script>
    const bakerLiveOrdersUrl = @json(route('baker.orders.live'));
    const bakerLiveOrders = document.getElementById('bakerLiveOrders');
    const bakerProcessedStat = document.querySelector('.stat-card strong');
    const enableBakerVoice = document.getElementById('enableBakerVoice');

    let bakerVoiceEnabled = localStorage.getItem('baker_voice_enabled') === '1';
    let latestBakerOrderId = Math.max(
        0,
        ...Array.from(document.querySelectorAll('[data-order-id]'))
            .map(card => Number(card.dataset.orderId || 0))
    );

    function setVoiceButtonState() {
        if (!enableBakerVoice) return;

        if (bakerVoiceEnabled) {
            enableBakerVoice.textContent = 'Suara Pesanan Aktif';
        } else {
            enableBakerVoice.textContent = 'Aktifkan Suara Pesanan';
        }
    }

    function speakText(text) {
        if (!bakerVoiceEnabled) return;
        if (!('speechSynthesis' in window)) return;

        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'id-ID';
        utterance.rate = 1;
        utterance.pitch = 1;

        window.speechSynthesis.cancel();
        window.speechSynthesis.speak(utterance);
    }

    function speakBakerOrder(order) {
        const tableName = order.table || 'Kasir Langsung';
        const itemNames = (order.items || []).slice(0, 3).join(', ');
        speakText('Pesanan baru dari ' + tableName + '. ' + itemNames + '.');
    }

    enableBakerVoice?.addEventListener('click', () => {
        bakerVoiceEnabled = true;
        localStorage.setItem('baker_voice_enabled', '1');
        setVoiceButtonState();

        const utterance = new SpeechSynthesisUtterance('Suara pesanan Baker aktif.');
        utterance.lang = 'id-ID';
        utterance.rate = 1;

        window.speechSynthesis.cancel();
        window.speechSynthesis.speak(utterance);
    });

    async function refreshBakerOrders() {
        try {
            const response = await fetch(bakerLiveOrdersUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) return;

            const data = await response.json();
            console.log('LIVE BAKER:', data.latest_order_id, latestBakerOrderId, data.orders);

            if (Number(data.latest_order_id) > latestBakerOrderId) {
                const newOrder = (data.orders || []).find(order => Number(order.id) === Number(data.latest_order_id));
                if (newOrder) speakBakerOrder(newOrder);
                latestBakerOrderId = Number(data.latest_order_id);
            }

            if (bakerLiveOrders && data.html) {
                bakerLiveOrders.innerHTML = data.html;
            }

            if (bakerProcessedStat) {
                bakerProcessedStat.textContent = data.count;
            }
        } catch (error) {
            console.log('Gagal refresh pesanan baker', error);
        }
    }

    setVoiceButtonState();
    setInterval(refreshBakerOrders, 5000);
</script>
</body>
</html>
