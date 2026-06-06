<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Menu {{ $table->name }}</title>
    <style>
        :root {
            --brown-dark: #2b1a12;
            --brown: #6f452b;
            --brown-soft: #9a6239;
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
        h1, h2, h3, p { margin: 0; }
        button, input, select { font: inherit; }
        main {
            width: min(100%, 680px);
            margin: 0 auto;
            padding: 18px 14px calc(30px + env(safe-area-inset-bottom));
            overflow-x: hidden;
        }
        body.has-checkout main {
            padding-bottom: calc(118px + env(safe-area-inset-bottom));
        }
        .brand-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 4px 2px 18px;
            color: #fff8ed;
        }
        .brand-left {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }
        .brand-logo {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #fff8ed;
            object-fit: contain;
            padding: 4px;
            box-shadow: 0 10px 22px rgba(24, 13, 7, .18);
        }
        .brand-name {
            font-size: 17px;
            font-weight: 900;
            line-height: 1;
        }
        .table-chip {
            flex: 0 0 auto;
            border: 1px solid rgba(255, 246, 232, .24);
            border-radius: 999px;
            padding: 6px 10px;
            background: rgba(255, 246, 232, .16);
            color: #fff8ed;
            font-size: 12px;
            font-weight: 900;
        }
        .notice {
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 14px;
            background: #fff0e8;
            color: #8a341b;
            border: 1px solid #e6b292;
            font-weight: 800;
        }
        .section-title {
            margin: 8px 0 10px;
            color: #fff8ed;
            font-size: 22px;
            line-height: 1.15;
        }
        .section-subtitle {
            margin-top: -6px;
            margin-bottom: 10px;
            color: rgba(255, 248, 237, .72);
            font-size: 13px;
            line-height: 1.4;
        }
        .deal-rail {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: minmax(260px, 86%);
            justify-content: center;
            gap: 12px;
            overflow-x: auto;
            padding: 2px 8px 8px;
            margin: 0 -2px 8px;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
        }
        .deal-rail::-webkit-scrollbar { display: none; }
        .promo-card {
            scroll-snap-align: start;
            display: grid;
            grid-template-columns: 88px minmax(0, 1fr);
            gap: 12px;
            min-height: 150px;
            border-radius: 8px;
            border: 1px solid #e1ad73;
            background:
                linear-gradient(135deg, rgba(255, 246, 232, .98), rgba(245, 217, 180, .98));
            box-shadow: 0 12px 28px rgba(39, 20, 13, .16);
            padding: 12px;
        }
        .promo-card .thumb {
            width: 88px;
        }
        .promo-copy {
            min-width: 0;
            display: grid;
            align-content: start;
            gap: 7px;
        }
        .promo-copy h3 {
            color: var(--brown-dark);
            font-size: 17px;
            line-height: 1.2;
            overflow-wrap: anywhere;
        }
        .promo-copy .price {
            margin-top: 0;
        }
        .promo-copy .item-bottom {
            margin-top: 2px;
        }
        .promo-tag {
            width: fit-content;
            border-radius: 999px;
            background: #ffe8dd;
            color: #8a341b;
            padding: 5px 9px;
            font-size: 11px;
            font-weight: 900;
        }
        .promo-card.promo-empty {
            grid-template-columns: 88px minmax(0, 1fr);
            align-items: center;
            min-height: 132px;
            padding: 14px;
            text-align: left;
        }
        .promo-card.promo-empty .thumb {
            width: 88px;
        }
        .promo-card.promo-empty .promo-copy {
            justify-items: start;
            align-content: center;
            gap: 8px;
        }
        .promo-card.promo-empty .promo-tag {
            background: #fff1df;
            color: #7a321f;
        }
        .package-stack {
            display: grid;
            gap: 10px;
        }
        .item {
            display: grid;
            grid-template-columns: 82px minmax(0, 1fr);
            gap: 12px;
            align-items: start;
            width: 100%;
            min-width: 0;
            margin-bottom: 10px;
            padding: 11px;
            border-radius: 8px;
            border: 1px solid #e1ad73;
            background: var(--cream);
            box-shadow: 0 10px 24px rgba(39, 20, 13, .12);
        }
        .thumb {
            width: 82px;
            aspect-ratio: 1;
            border-radius: 8px;
            border: 1px solid #ead4ba;
            background: var(--cream-soft);
            display: grid;
            place-items: center;
            overflow: hidden;
            color: var(--brown);
            font-size: 24px;
            font-weight: 900;
        }
        .thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .item-body {
            min-width: 0;
            display: grid;
            gap: 8px;
        }
        .item h3 {
            color: var(--brown-dark);
            font-size: 16px;
            line-height: 1.2;
            overflow-wrap: anywhere;
        }
        .muted {
            color: #7a5a46;
            line-height: 1.35;
            font-size: 13px;
        }
        .price {
            color: var(--brown-dark);
            font-weight: 900;
        }
        .item-bottom {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 10px;
        }
        .qty-control {
            display: grid;
            grid-template-columns: 38px 44px 38px;
            align-items: center;
            border: 1px solid #d8b893;
            border-radius: 8px;
            overflow: hidden;
            background: #fffdfa;
        }
        .qty-btn {
            min-height: 38px;
            border: 0;
            background: transparent;
            color: var(--brown-dark);
            font-size: 20px;
            font-weight: 900;
            cursor: pointer;
        }
        .qty-input {
            width: 100%;
            min-height: 38px;
            border: 0;
            border-inline: 1px solid #ead4ba;
            background: #fffdfa;
            color: var(--brown-dark);
            text-align: center;
            font-weight: 900;
            -moz-appearance: textfield;
        }
        .qty-input::-webkit-outer-spin-button,
        .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .package-item {
            grid-template-columns: 88px minmax(0, 1fr);
        }
        .package-card {
            margin-bottom: 0;
        }
        .package-empty {
            align-items: center;
            min-height: 122px;
            background:
                linear-gradient(135deg, rgba(255, 250, 241, .98), rgba(248, 227, 200, .98));
        }
        .package-empty .thumb {
            background: #fff4e4;
        }
        .package-empty .package-badge {
            width: fit-content;
            border-radius: 999px;
            background: #eaf7dd;
            color: #2f6d1f;
            padding: 5px 9px;
            font-size: 11px;
            font-weight: 900;
        }
        .package-lines {
            display: grid;
            gap: 4px;
            color: #7a5a46;
            font-size: 12px;
            font-weight: 800;
        }
        .package-choices {
            grid-column: 1 / -1;
            display: grid;
            gap: 10px;
            padding-top: 10px;
            border-top: 1px solid #ead4ba;
        }
        .choice-row { display: grid; gap: 7px; }
        .choice-row label {
            color: var(--brown-dark);
            font-size: 13px;
            font-weight: 900;
        }
        .choice-selects {
            display: grid;
            gap: 8px;
        }
        select, .checkout input {
            width: 100%;
            min-height: 44px;
            border: 1px solid #d8b893;
            border-radius: 8px;
            background: var(--cream-soft);
            color: var(--brown-dark);
            padding: 10px 11px;
            font: inherit;
            font-weight: 800;
        }
        .empty {
            color: rgba(255, 248, 237, .78);
            font-weight: 800;
            padding: 18px 2px;
        }
        .menu-end-card {
            display: grid;
            justify-items: center;
            margin: 8px auto 8px;
            padding: 8px 14px;
            max-width: 300px;
            text-align: center;
            color: rgba(255, 248, 237, .86);
        }
        .menu-end-card p {
            font-size: 12px;
            line-height: 1.45;
            color: rgba(255, 248, 237, .82);
        }
        .checkout {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 20;
            padding: 0 10px env(safe-area-inset-bottom);
            background: transparent;
        }
        .checkout-inner {
            width: min(100%, 680px);
            margin: 0 auto;
            padding: 15px 14px 12px;
            display: grid;
            gap: 10px;
            border-radius: 20px 20px 0 0;
            border: 1px solid #d8b893;
            border-bottom: 0;
            background: rgba(255, 246, 232, .98);
            box-shadow: 0 -8px 24px rgba(39, 20, 13, .18);
        }
        .checkout[hidden] { display: none; }
        .cart-summary {
            display: grid;
            gap: 8px;
        }
        .cart-summary-title {
            color: var(--brown-dark);
            font-size: 13px;
            font-weight: 900;
        }
        .cart-summary-list {
            display: grid;
            gap: 6px;
            max-height: 112px;
            overflow-y: auto;
        }
        .cart-summary-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            color: #5b3825;
            font-size: 13px;
            font-weight: 800;
        }
        .cart-summary-row strong {
            flex: 0 0 auto;
            color: var(--brown-dark);
        }
        .submit-order {
            width: 100%;
            min-height: 48px;
            border: 0;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--brown), var(--brown-dark));
            color: #fff8ed;
            font-weight: 900;
            cursor: pointer;
        }
        @media (min-width: 320px) and (max-width: 480px) {
            main {
                padding-inline: 12px;
                padding-bottom: calc(26px + env(safe-area-inset-bottom));
            }
            .brand-logo { width: 30px; height: 30px; }
            .brand-name { font-size: 16px; }
            .deal-rail { grid-auto-columns: minmax(248px, 88%); }
            .item,
            .package-item {
                grid-template-columns: 76px minmax(0, 1fr);
                gap: 10px;
                padding: 10px;
            }
            .thumb { width: 76px; }
            .item h3 { font-size: 15px; }
            .muted {
                font-size: 12px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .item-bottom {
                grid-template-columns: 1fr;
                align-items: stretch;
                gap: 8px;
            }
            .price { margin-top: 0; }
            .qty-control {
                width: 128px;
                grid-template-columns: 38px 52px 38px;
                justify-self: end;
            }
        }
        @media (min-width: 320px) and (max-width: 359px) {
            main {
                padding-inline: 10px;
                padding-bottom: calc(24px + env(safe-area-inset-bottom));
            }
            .deal-rail { grid-auto-columns: minmax(238px, 92%); }
            .promo-card { grid-template-columns: 72px minmax(0, 1fr); }
            .promo-card .thumb { width: 72px; }
            .item,
            .package-item {
                grid-template-columns: 70px minmax(0, 1fr);
                gap: 9px;
            }
            .thumb { width: 70px; }
            .qty-control {
                width: 122px;
                grid-template-columns: 36px 50px 36px;
            }
        }
        @media (min-width: 360px) and (max-width: 389px) {
            .deal-rail { grid-auto-columns: minmax(260px, 88%); }
            .item,
            .package-item {
                grid-template-columns: 74px minmax(0, 1fr);
            }
            .thumb { width: 74px; }
        }
        @media (min-width: 390px) and (max-width: 430px) {
            main { padding-inline: 14px; }
            .deal-rail { grid-auto-columns: minmax(292px, 86%); }
            .item,
            .package-item {
                grid-template-columns: 84px minmax(0, 1fr);
            }
            .thumb { width: 84px; }
            .item-bottom {
                grid-template-columns: minmax(0, 1fr) auto;
                align-items: center;
            }
        }
        @media (min-width: 431px) and (max-width: 480px) {
            main { padding-inline: 16px; }
            .deal-rail { grid-auto-columns: minmax(320px, 84%); }
            .item,
            .package-item {
                grid-template-columns: 90px minmax(0, 1fr);
                padding: 12px;
            }
            .thumb { width: 90px; }
            .item-bottom {
                grid-template-columns: minmax(0, 1fr) auto;
                align-items: center;
            }
        }
        @media (min-width: 481px) and (max-width: 619px) {
            main { padding: 24px 16px calc(30px + env(safe-area-inset-bottom)); }
            .item { grid-template-columns: 92px minmax(0, 1fr); padding: 12px; }
            .package-item { grid-template-columns: 92px minmax(0, 1fr); }
            .thumb { width: 92px; }
            .checkout-inner { grid-template-columns: 1fr auto; align-items: end; }
            .submit-order { min-width: 148px; }
        }
        @media (min-width: 620px) {
            main { padding: 28px 18px calc(34px + env(safe-area-inset-bottom)); }
            .item { grid-template-columns: 96px minmax(0, 1fr); padding: 13px; }
            .thumb { width: 96px; }
            .checkout-inner { grid-template-columns: 1fr auto; align-items: end; }
            .submit-order { min-width: 150px; }
        }
    </style>
</head>
<body>
    <main>
        <header class="brand-header">
            <div class="brand-left">
                <img class="brand-logo" src="{{ asset('images/Swiftbite-icon.png') }}" alt="SwiftBite">
                <div class="brand-name">SwiftBite</div>
            </div>
            <div class="table-chip">{{ $table->name }}</div>
        </header>

        @if ($errors->any())
            <div class="notice">{{ $errors->first() }}</div>
        @endif

        <form method="post" action="{{ route('customer.orders.store', $table->qr_token) }}">
            @csrf

            <h2 class="section-title">Promo</h2>
            <div class="deal-rail">
                @if ($promoPackages->isNotEmpty())
                    @foreach ($promoPackages as $package)
                        <article class="promo-card">
                            <div class="thumb">
                                @if ($package->foto)
                                    <img src="{{ asset($package->foto) }}" alt="{{ $package->nama_paket }}">
                                @else
                                    {{ strtoupper(substr($package->nama_paket, 0, 1)) }}
                                @endif
                            </div>
                            <div class="promo-copy">
                                <span class="promo-tag">Promo</span>
                                <h3>{{ $package->nama_paket }}</h3>
                                @if ($package->deskripsi)
                                    <p class="muted">{{ $package->deskripsi }}</p>
                                @endif
                                <div class="package-lines">
                                    @foreach ($package->items as $packageItem)
                                        <span>{{ $packageItem->qty }}x {{ $packageItem->menuItem?->nama_menu ?? 'Menu' }} tetap</span>
                                    @endforeach
                                    @foreach ($package->choices as $choice)
                                        <span>{{ $choice->qty }} {{ strtolower($choice->category) }} bebas dipilih</span>
                                    @endforeach
                                </div>
                                <div class="item-bottom">
                                    <p class="price">Rp{{ number_format($package->harga, 0, ',', '.') }}</p>
                                    <div class="qty-control">
                                        <button type="button" class="qty-btn" data-qty-step="-1">-</button>
                                        <input class="qty-input" type="number" name="package_quantities[{{ $package->id_paket }}]" value="{{ old('package_quantities.' . $package->id_paket, 0) }}" min="0" max="1">
                                        <button type="button" class="qty-btn" data-qty-step="1">+</button>
                                    </div>
                                </div>
                            </div>

                            @if ($package->choices->isNotEmpty())
                                <div class="package-choices">
                                    @foreach ($package->choices as $choice)
                                        @php
                                            $options = $choiceMenuOptions->get($choice->category, collect());
                                        @endphp
                                        <div class="choice-row">
                                            <label>{{ $choice->qty }} {{ strtolower($choice->category) }} bebas</label>
                                            <div class="choice-selects">
                                                @for ($i = 0; $i < $choice->qty; $i++)
                                                    <select name="package_choices[{{ $package->id_paket }}][{{ $choice->category }}][]" aria-label="Pilihan {{ strtolower($choice->category) }} {{ $i + 1 }} untuk {{ $package->nama_paket }}">
                                                        <option value="">Pilih {{ strtolower($choice->category) }}</option>
                                                        @foreach ($options as $option)
                                                            <option value="{{ $option->id_menu }}" @selected(old('package_choices.' . $package->id_paket . '.' . $choice->category . '.' . $i) == $option->id_menu)>
                                                                {{ $option->nama_menu }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endfor
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </article>
                    @endforeach
                @else
                    <article class="promo-card promo-empty">
                        <div class="thumb">
                            <img src="{{ asset('images/Promo Emoji.png') }}" alt="Promo SwiftBite">
                        </div>
                        <div class="promo-copy">
                            <span class="promo-tag">Segera Hadir</span>
                            <h3>Promo segera hadir</h3>
                            <p class="muted">Cek paket dan menu favorit SwiftBite hari ini.</p>
                        </div>
                    </article>
                @endif
            </div>

            <h2 class="section-title">Paket</h2>
            <div class="package-stack">
                @if ($regularPackages->isNotEmpty())
                    @foreach ($regularPackages as $package)
                        <article class="item package-item package-card">
                            <div class="thumb">
                                @if ($package->foto)
                                    <img src="{{ asset($package->foto) }}" alt="{{ $package->nama_paket }}">
                                @else
                                    {{ strtoupper(substr($package->nama_paket, 0, 1)) }}
                                @endif
                            </div>
                            <div class="item-body">
                                <h3>{{ $package->nama_paket }}</h3>
                                @if ($package->deskripsi)
                                    <p class="muted">{{ $package->deskripsi }}</p>
                                @endif
                                <div class="package-lines">
                                    @foreach ($package->items as $packageItem)
                                        <span>{{ $packageItem->qty }}x {{ $packageItem->menuItem?->nama_menu ?? 'Menu' }} tetap</span>
                                    @endforeach
                                    @foreach ($package->choices as $choice)
                                        <span>{{ $choice->qty }} {{ strtolower($choice->category) }} bebas dipilih</span>
                                    @endforeach
                                </div>
                                <div class="item-bottom">
                                    <p class="price">Rp{{ number_format($package->harga, 0, ',', '.') }}</p>
                                    <div class="qty-control">
                                        <button type="button" class="qty-btn" data-qty-step="-1">-</button>
                                        <input class="qty-input" type="number" name="package_quantities[{{ $package->id_paket }}]" value="{{ old('package_quantities.' . $package->id_paket, 0) }}" min="0" max="1">
                                        <button type="button" class="qty-btn" data-qty-step="1">+</button>
                                    </div>
                                </div>
                            </div>

                            @if ($package->choices->isNotEmpty())
                                <div class="package-choices">
                                    @foreach ($package->choices as $choice)
                                        @php
                                            $options = $choiceMenuOptions->get($choice->category, collect());
                                        @endphp
                                        <div class="choice-row">
                                            <label>{{ $choice->qty }} {{ strtolower($choice->category) }} bebas</label>
                                            <div class="choice-selects">
                                                @for ($i = 0; $i < $choice->qty; $i++)
                                                    <select name="package_choices[{{ $package->id_paket }}][{{ $choice->category }}][]" aria-label="Pilihan {{ strtolower($choice->category) }} {{ $i + 1 }} untuk {{ $package->nama_paket }}">
                                                        <option value="">Pilih {{ strtolower($choice->category) }}</option>
                                                        @foreach ($options as $option)
                                                            <option value="{{ $option->id_menu }}" @selected(old('package_choices.' . $package->id_paket . '.' . $choice->category . '.' . $i) == $option->id_menu)>
                                                                {{ $option->nama_menu }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endfor
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </article>
                    @endforeach
                @else
                    <article class="item package-item package-card package-empty">
                        <div class="thumb">
                            <img src="{{ asset('images/Promo Emoji.png') }}" alt="Paket SwiftBite">
                        </div>
                        <div class="item-body">
                            <span class="package-badge">Paket Hemat</span>
                            <h3>Paket segera hadir</h3>
                            <p class="muted">Kombinasi roti dan minuman favorit akan tersedia di sini.</p>
                        </div>
                    </article>
                @endif
            </div>

            @forelse ($menuItems as $category => $items)
                <h2 class="section-title">{{ $category }}</h2>
                @foreach ($items as $item)
                    @php
                        $initial = strtoupper(substr($item->name, 0, 1));
                    @endphp
                    <article class="item">
                        <div class="thumb">
                            @if ($item->foto)
                                <img src="{{ asset($item->foto) }}" alt="{{ $item->name }}">
                            @else
                                {{ $initial }}
                            @endif
                        </div>
                        <div class="item-body">
                            <h3>{{ $item->name }}</h3>
                            @if ($item->description)
                                <p class="muted">{{ $item->description }}</p>
                            @endif
                            <div class="item-bottom">
                                <p class="price">Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                                <div class="qty-control">
                                    <button type="button" class="qty-btn" data-qty-step="-1">-</button>
                                    <input class="qty-input" type="number" name="quantities[{{ $item->id }}]" value="{{ old('quantities.' . $item->id, 0) }}" min="0" max="20">
                                    <button type="button" class="qty-btn" data-qty-step="1">+</button>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            @empty
                <p class="empty">Menu belum tersedia.</p>
            @endforelse

            <section class="menu-end-card" aria-label="Akhir daftar menu">
                <p>Terima kasih telah memilih SwiftBite.</p>
            </section>

            <div class="checkout" id="checkoutBar" hidden>
                <div class="checkout-inner">
                    <input type="hidden" name="payment_method" value="cash">
                    <div class="cart-summary">
                        <div class="cart-summary-title">Pesanan dipilih</div>
                        <div class="cart-summary-list" id="cartSummaryList"></div>
                    </div>
                    <button class="submit-order" type="submit">Konfirmasi Pesanan</button>
                </div>
            </div>
        </form>
    </main>

    <script>
        const checkoutBar = document.getElementById('checkoutBar');
        const cartSummaryList = document.getElementById('cartSummaryList');
        const quantityInputs = document.querySelectorAll('.qty-input');

        function escapeHtml(value) {
            return String(value || '').replace(/[&<>"']/g, (char) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char]));
        }

        function renderCartSummary() {
            if (!checkoutBar || !cartSummaryList) {
                return;
            }

            const rows = [];

            quantityInputs.forEach((input) => {
                const qty = Number(input.value || 0);

                if (qty <= 0) {
                    return;
                }

                const item = input.closest('.item, .promo-card');
                const name = item?.querySelector('h3')?.textContent?.trim() || 'Menu';

                rows.push({ name, qty });
            });

            checkoutBar.hidden = rows.length === 0;
            document.body.classList.toggle('has-checkout', rows.length > 0);
            cartSummaryList.innerHTML = rows.map((row) => (
                '<div class="cart-summary-row"><span>' + escapeHtml(row.name) + '</span><strong>' + row.qty + 'x</strong></div>'
            )).join('');
        }

        document.querySelectorAll('[data-qty-step]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = button.parentElement.querySelector('.qty-input');
                const step = Number(button.dataset.qtyStep || 0);
                const min = Number(input.min || 0);
                const max = Number(input.max || 99);
                const value = Number(input.value || 0);

                input.value = Math.max(min, Math.min(max, value + step));
                renderCartSummary();
            });
        });

        quantityInputs.forEach((input) => {
            input.addEventListener('input', renderCartSummary);
        });

        renderCartSummary();
    </script>
</body>
</html>
