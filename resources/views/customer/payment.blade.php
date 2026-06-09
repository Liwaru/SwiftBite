<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Pembayaran Pesanan #{{ $order->id }}</title>
    <style>
.voice-note-wrap {
    position: relative;
}

.voice-note-wrap textarea {
    width: 100%;
    padding-right: 42px !important;
    padding-bottom: 10px !important;

}

.voice-note-btn {
    position: absolute;
    right: 10px;
    bottom: 10px;
    z-index: 5;
    width: 30px;
    height: 30px;
    border: 0;
    border-radius: 999px;
    background: #fff1df;
    color: var(--brown);
    display: grid;
    place-items: center;
    cursor: pointer;
}

.voice-note-btn.is-listening {
    background: #ffe2dc;
    color: #dc2626;
    transform: scale(1.1);
}
        :root {
            --brown-dark: #2b1a12;
            --brown: #6f452b;
            --cream: #fff6e8;
            --cream-soft: #fffaf2;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background:
                linear-gradient(145deg, rgba(43, 26, 18, .94), rgba(111, 69, 43, .96)),
                repeating-linear-gradient(45deg, rgba(255, 246, 232, .08) 0 1px, transparent 1px 14px),
                var(--brown);
            color: var(--brown-dark);
            overflow-x: hidden;
        }
        h1, h2, p { margin: 0; }
        button { font: inherit; }
        main {
            width: min(100%, 540px);
            margin: 0 auto;
            padding: 18px 12px calc(22px + env(safe-area-inset-bottom));
            overflow-x: hidden;
        }
        .brand-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 4px 2px 18px;
            color: #fff8ed;
        }
        .brand-left { display: flex; align-items: center; gap: 10px; }
        .brand-logo {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: #fff8ed;
            object-fit: contain;
            padding: 4px;
            box-shadow: 0 10px 22px rgba(24, 13, 7, .18);
        }
        .brand-name { font-size: 16px; font-weight: 900; }
        .table-chip {
            border: 1px solid rgba(255, 246, 232, .24);
            border-radius: 999px;
            padding: 6px 10px;
            background: rgba(255, 246, 232, .16);
            color: #fff8ed;
            font-size: 12px;
            font-weight: 900;
        }
        .panel {
            display: grid;
            gap: 14px;
            width: 100%;
            min-width: 0;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #e1ad73;
            background: var(--cream);
            box-shadow: 0 16px 30px rgba(24, 13, 7, .18);
        }
        .notice,
        .error {
            width: fit-content;
            border-radius: 999px;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 900;
        }
        .notice { background: #eaf7dd; color: #2f6d1f; }
        .error { background: #ffe2dc; color: #7b2418; }
        .eyebrow {
            color: #7a5a46;
            font-size: 13px;
            font-weight: 800;
        }
        h1 { font-size: 26px; line-height: 1.1; }
        .summary-card,
        .method-section {
            display: grid;
            gap: 10px;
            min-width: 0;
            padding: 11px;
            border-radius: 8px;
            border: 1px solid #ead4ba;
            background: #fffdfa;
        }
        .summary-card h2,
        .method-section h2 {
            font-size: 15px;
            line-height: 1.2;
        }
        .summary {
            display: grid;
            gap: 8px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding-top: 9px;
            border-top: 1px solid #ead4ba;
            color: #5b3825;
            font-size: 13px;
            font-weight: 800;
        }
        .summary-row strong {
            flex: 0 0 auto;
            color: var(--brown-dark);
        }
        .total {
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 10px;
            padding: 12px;
            border-radius: 8px;
            background: #fff6e8;
            border: 1px solid #ead4ba;
            font-size: 17px;
            font-weight: 900;
        }
        .total span:last-child {
            justify-self: center;
            text-align: center;
        }
        .method-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
            min-width: 0;
        }
        .method-grid.ewallet-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 6px;
        }
        .method-item {
            display: grid;
            justify-items: center;
            align-content: center;
            gap: 6px;
            min-width: 0;
            min-height: 82px;
            padding: 8px 5px;
            border-radius: 8px;
            border: 1px solid #d8b893;
            background: var(--cream-soft);
            color: var(--brown-dark);
            text-align: center;
            cursor: pointer;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease, transform .2s ease;
        }
        .method-item.is-active {
            border: 2px solid var(--brown);
            background: #fff9ee;
            box-shadow: 0 8px 18px rgba(39, 20, 13, .1);
            transform: translateY(-1px);
        }
        .method-item img {
            width: 32px;
            height: 32px;
            object-fit: contain;
            border-radius: 8px;
        }
        .method-icon {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: #fff1df;
            color: var(--brown);
            font-size: 22px;
            font-weight: 900;
        }
        .method-item strong {
            width: 100%;
            font-size: 12px;
            line-height: 1.2;
            overflow-wrap: anywhere;
        }
        .method-item span {
            color: #7a5a46;
            font-size: 10px;
            line-height: 1.25;
            font-weight: 800;
        }
        .ewallet-grid .method-item {
            min-height: 76px;
            gap: 5px;
            padding: 7px 4px;
        }
        .ewallet-grid .method-item img,
        .ewallet-grid .method-icon {
            width: 30px;
            height: 30px;
            font-size: 10px;
        }
        .ewallet-grid .method-item strong {
            font-size: 9px;
            line-height: 1.1;
            white-space: nowrap;
            overflow-wrap: normal;
            text-align: center;
        }
        .ewallet-grid .method-item span:not(.method-icon) {
            display: none;
        }
        .ewallet-number {
            display: grid;
            gap: 6px;
            margin-top: 2px;
        }
        .ewallet-number label {
            color: var(--brown-dark);
            font-size: 13px;
            font-weight: 900;
        }
        .ewallet-number input {
            width: 100%;
            min-width: 0;
            min-height: 44px;
            border: 1px solid #d8b893;
            border-radius: 8px;
            background: var(--cream-soft);
            color: var(--brown-dark);
            padding: 10px 11px;
            font: inherit;
            font-weight: 800;
        }
        .ewallet-number[hidden] { display: none; }
        .small-note {
            color: #7a5a46;
            font-size: 11px;
            line-height: 1.4;
            text-align: center;
            font-weight: 800;
            overflow-wrap: anywhere;
        }
        .submit-payment {
            width: 100%;
            min-width: 0;
            min-height: 50px;
            border: 0;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--brown), var(--brown-dark));
            color: #fff8ed;
            font-weight: 900;
            cursor: pointer;
            overflow-wrap: anywhere;
        }
        .submit-payment:disabled {
            opacity: .72;
            cursor: wait;
        }

        .back-link {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    width: fit-content;
    margin: 0 0 12px;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(255, 250, 242, .12);
    border: 1px solid rgba(255, 250, 242, .26);
    color: #fff8ed;
    font-size: 13px;
    font-weight: 900;
    text-decoration: none;
    box-shadow: 0 8px 18px rgba(24, 13, 7, .18);
}

.back-link:active {
    transform: translateY(1px);
}

.customer-note-card {
    display: grid;
    gap: 7px;
    padding: 11px;
    border-radius: 8px;
    border: 1px solid #ead4ba;
    background: #fff9ee;
}

.customer-note-card label {
    color: var(--brown-dark);
    font-size: 15px;
    line-height: 1.2;
    font-weight: 900;
}

.customer-note-card textarea {
    width: 100%;
    min-height: 52pxs;
    resize: vertical;
    border: 1px solid #d8b893;
    border-radius: 8px;
    background: var(--cream-soft);
    color: var(--brown-dark);
    padding: 10px 11px;
    font: inherit;
    font-size: 13px;
    font-weight: 800;
    line-height: 1.4;
    outline: none;
}

.customer-note-card textarea:focus {
    border-color: var(--brown);
    box-shadow: 0 0 0 3px rgba(111, 69, 43, .12);
}
    </style>
</head>
<body>
    <main>
    <a href="{{ route('customer.menu', $table->qr_token) }}" class="back-link">← Kembali ke Menu</a>
        
        @php
            $currentPayment = old('payment_method', $order->payment_method);
            $currentMethod = in_array($currentPayment, ['gopay', 'ovo', 'dana', 'shopeepay'], true) ? $currentPayment : ($currentPayment ?: 'cash');
        @endphp

        <form
            method="post"
            action="{{ route('customer.orders.payment.confirm', [$table->qr_token, $order]) }}"
            class="panel"
            id="paymentForm"
            data-ewallet-url="{{ route('customer.orders.ewallet.create', [$table->qr_token, $order]) }}"
            data-sync-url="{{ route('customer.orders.midtrans.sync', [$table->qr_token, $order]) }}"
            data-receipt-url="{{ route('customer.orders.show', [$table->qr_token, $order]) }}"
        >
            @csrf
            @method('patch')
            <input type="hidden" name="payment_method" id="paymentMethodInput" value="{{ $currentMethod }}">

            @if (session('success'))
                <span class="notice">{{ session('success') }}</span>
            @endif
            @if ($errors->any())
                <span class="error">{{ $errors->first() }}</span>
            @endif

            <div>
                <p class="eyebrow">Pembayaran pesanan</p>
                <h1>{{ $table->name }}</h1>
            </div>

            <section class="summary-card" aria-label="Ringkasan pesanan">
                <h2>Ringkasan Pesanan</h2>
                <div class="summary">
                    @foreach ($order->items as $item)
                        <div class="summary-row">
                            <span>{{ $item->quantity }}x {{ $item->menu_name }}</span>
                            <strong>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                        </div>
                    @endforeach
                </div>
                <div class="total">
                    <span>Total Pembayaran</span>
                    <span>Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>

                <section class="customer-note-card" aria-label="Catatan customer">
    <label for="customerNote">Catatan Pesanan</label>
   <div class="voice-note-wrap">
    <textarea
        id="customerNote"
        name="customer_note"
        rows="3"
        placeholder="Contoh: kurangi gula, banyakin es batu..."></textarea>

<button type="button" class="voice-note-btn" id="voiceNoteBtn" aria-label="Voice to text">
    <svg viewBox="0 0 16 16" width="18" height="18" fill="currentColor">
        <path d="M8 11a3 3 0 0 0 3-3V3a3 3 0 0 0-6 0v5a3 3 0 0 0 3 3z"/>
        <path d="M13.5 8a.5.5 0 0 0-1 0 4.5 4.5 0 0 1-9 0 .5.5 0 0 0-1 0 5.5 5.5 0 0 0 5 5.478V15H5a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H8.5v-1.522A5.5 5.5 0 0 0 13.5 8z"/>
    </svg>
</button></div>
</section>
            </section>

            <section class="method-section" aria-label="Metode tunai dan QRIS">
                <h2>Metode Pembayaran</h2>
                <div class="method-grid">
                    <button type="button" class="method-item js-method" data-method="cash" data-type="cash">
                        <span class="method-icon">Rp</span>
                        <strong>Tunai</strong>
                        <span>Bayar ke kasir</span>
                    </button>
                    <button type="button" class="method-item js-method" data-method="qris" data-type="qris">
                        <img src="{{ asset('images/qris.jpg') }}" alt="QRIS">
                        <strong>QRIS</strong>
                        <span>Scan QR Midtrans</span>
                    </button>
                </div>
            </section>

            <section class="method-section" aria-label="E-Wallet">
                <h2>E-Wallet</h2>
                <div class="method-grid ewallet-grid">
                    <button type="button" class="method-item js-method" data-method="gopay" data-type="ewallet">
                        <img src="{{ asset('images/gopay.png') }}" alt="GoPay">
                        <strong>GoPay</strong>
                        <span>E-Wallet</span>
                    </button>
                    <button type="button" class="method-item js-method" data-method="dana" data-type="ewallet">
                        <img src="{{ asset('images/dana.png') }}" alt="DANA">
                        <strong>DANA</strong>
                        <span>E-Wallet</span>
                    </button>
                    <button type="button" class="method-item js-method" data-method="ovo" data-type="ewallet">
                        <span class="method-icon">OVO</span>
                        <strong>OVO</strong>
                        <span>E-Wallet</span>
                    </button>
                    <button type="button" class="method-item js-method" data-method="shopeepay" data-type="ewallet">
                        <img src="{{ asset('images/shopeepay.webp') }}" alt="ShopeePay">
                        <strong>ShopeePay</strong>
                        <span>E-Wallet</span>
                    </button>
                </div>
                <div class="ewallet-number" id="ewalletNumberBox" hidden>
                    <label for="ewalletNumber">Nomor Ponsel</label>
                    <input id="ewalletNumber" type="tel" inputmode="numeric" autocomplete="tel" placeholder="Contoh: 081234567890">
                </div>
            </section>

            <button class="submit-payment" type="submit" id="payButton">Lanjutkan Pembayaran</button>

            <p class="small-note">Pembayaran digital diproses melalui Midtrans.</p>
        </form>
    </main>

    <script
        src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('midtrans.client_key') }}"
    ></script>
    <script>
        const paymentForm = document.getElementById('paymentForm');
        const payButton = document.getElementById('payButton');
        const paymentMethodInput = document.getElementById('paymentMethodInput');
        const methodButtons = document.querySelectorAll('.js-method');
        const ewalletNumberBox = document.getElementById('ewalletNumberBox');
        const ewalletNumber = document.getElementById('ewalletNumber');
        let selectedMethod = paymentMethodInput?.value || 'cash';
        let selectedType = selectedMethod === 'qris' ? 'qris' : (selectedMethod === 'cash' ? 'cash' : 'ewallet');

        function setActiveMethod(method, type) {
            selectedMethod = method;
            selectedType = type;
            paymentMethodInput.value = method;
            if (ewalletNumberBox) {
                ewalletNumberBox.hidden = type !== 'ewallet';
            }
            methodButtons.forEach((button) => {
                button.classList.toggle('is-active', button.dataset.method === method);
            });
        }

        methodButtons.forEach((button) => {
            button.addEventListener('click', () => {
                setActiveMethod(button.dataset.method, button.dataset.type);
            });
        });

        setActiveMethod(selectedMethod, selectedType);
        const voiceNoteBtn = document.getElementById('voiceNoteBtn');
const customerNote = document.getElementById('customerNote');

const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

if (!SpeechRecognition && voiceNoteBtn) {
    voiceNoteBtn.disabled = true;
    voiceNoteBtn.title = 'Voice to text tidak didukung di browser ini.';
} else if (voiceNoteBtn && customerNote) {
    let recognition = new SpeechRecognition();
    let isListening = false;
    let stopTimer = null;

    recognition.lang = 'id-ID';
    recognition.continuous = false;
    recognition.interimResults = false;
    
recognition.onend = () => {
    isListening = false;
    voiceNoteBtn.classList.remove('is-listening');
    clearTimeout(stopTimer);
};
    voiceNoteBtn.addEventListener('click', () => {
          if (isListening) return;
        try {
            recognition.start();

clearTimeout(stopTimer);

stopTimer = setTimeout(() => {
    try {
        recognition.abort(); // lebih paksa daripada stop()
    } catch (e) {}
}, 5000);

        } catch (e) {
            alert('Voice to text sedang aktif. Tunggu sebentar.');
        }
    });

    recognition.onresult = (event) => {
        const text = event.results[0][0].transcript;
        const oldText = customerNote.value.trim();

        customerNote.value = oldText ? oldText + ' ' + text : text;

        customerNote.dispatchEvent(new Event('input', { bubbles: true }));
        customerNote.dispatchEvent(new Event('change', { bubbles: true }));
    };

    recognition.onerror = () => {
        alert('Gagal menangkap suara. Coba klik mic lagi.');
    };

    recognition.onend = () => {
        voiceNoteBtn.classList.remove('is-listening');
        clearTimeout(stopTimer);
    };
}
        async function syncMidtransResult(orderId) {
            await fetch(paymentForm.dataset.syncUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ order_id: orderId })
            });
        }

        paymentForm.addEventListener('submit', async (event) => {
            if (selectedType !== 'ewallet') {
                return;
            }

            event.preventDefault();

            if (!ewalletNumber?.value.trim()) {
                alert('Masukkan nomor ponsel terlebih dahulu.');
                ewalletNumber?.focus();
                return;
            }

            payButton.disabled = true;
            payButton.textContent = 'Memproses...';

            try {
                const formData = new FormData();
                formData.append('_token', @json(csrf_token()));
                formData.append('payment_method', selectedMethod);
                formData.append('account_number', ewalletNumber.value.trim());

                const response = await fetch(paymentForm.dataset.ewalletUrl, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Gagal membuat transaksi E-Wallet.');
                }

                if (!window.snap || !result.snap_token) {
                    throw new Error('Snap Midtrans belum siap atau token tidak tersedia.');
                }

                window.snap.pay(result.snap_token, {
                    onSuccess: async (response) => {
                        await syncMidtransResult(response.order_id || result.order_id);
                        window.location.href = result.redirect_url || paymentForm.dataset.receiptUrl;
                    },
                    onPending: async (response) => {
                        await syncMidtransResult(response.order_id || result.order_id);
                        window.location.href = result.redirect_url || paymentForm.dataset.receiptUrl;
                    },
                    onError: () => {
                        alert('Pembayaran gagal diproses oleh Midtrans.');
                    },
                    onClose: () => {
                        alert('Popup pembayaran ditutup sebelum transaksi selesai.');
                    }
                });
            } catch (error) {
                alert(error.message || 'Terjadi kesalahan saat memproses pembayaran.');
            } finally {
                payButton.disabled = false;
                payButton.textContent = 'Lanjutkan Pembayaran';
            }
        });
    </script>
</body>
</html>
