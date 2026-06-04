<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menu {{ $table->name }}</title>
    <style>
        :root { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        html, body, * { scrollbar-width: none; -ms-overflow-style: none; }
        *::-webkit-scrollbar { display: none; width: 0; height: 0; }
        body {
            margin: 0;
            background:
                linear-gradient(135deg, rgba(53, 32, 22, .82), rgba(111, 69, 43, .9)),
                repeating-linear-gradient(45deg, rgba(255, 246, 232, .08) 0 1px, transparent 1px 14px),
                #6f452b;
            color: #2b1c15;
        }
        main { max-width: 760px; margin: 0 auto; padding: 24px 16px 108px; }
        h1, h2, h3, p { margin: 0; }
        h1 { font-size: 34px; }
        h2 { margin: 28px 0 12px; font-size: 20px; }
        h3 { font-size: 17px; }
        .muted { color: #7a5a46; }
        .top { display: grid; gap: 8px; margin-bottom: 18px; }
        .top, h2 { color: #fff8ed; }
        .top .muted { color: #ead4ba; }
        .item { display: grid; grid-template-columns: 1fr 86px; gap: 14px; align-items: center; background: #fff6e8; border: 1px solid #e1ad73; border-radius: 8px; padding: 14px; margin-bottom: 10px; box-shadow: 0 12px 30px rgba(39, 20, 13, .12); }
        .package-item { grid-template-columns: 108px 1fr 86px; align-items: start; }
        .package-thumb { width: 108px; aspect-ratio: 1; display: grid; place-items: center; border-radius: 8px; background: #fffdfa; border: 1px solid #ead4ba; overflow: hidden; color: #6f452b; font-size: 14px; font-weight: 900; line-height: 1.2; text-align: center; overflow-wrap: anywhere; padding: 8px; }
        .package-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .package-lines { display: grid; gap: 4px; margin-top: 9px; color: #7a5a46; font-size: 13px; font-weight: 800; }
        .package-choices { grid-column: 1 / -1; display: grid; gap: 9px; padding-top: 10px; border-top: 1px solid #ead4ba; }
        .choice-row { display: grid; gap: 8px; }
        .choice-selects { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 8px; }
        .item h3, .item .price { color: #2b1c15; }
        .item .muted { color: #7a5a46; }
        .price { font-weight: 900; margin-top: 8px; }
        input, textarea, select { box-sizing: border-box; width: 100%; border: 1px solid #d8b893; border-radius: 7px; padding: 11px; font: inherit; background: #fffaf2; color: #352016; }
        input[type="number"] { text-align: center; font-weight: 900; }
        label { display: grid; gap: 6px; font-weight: 800; font-size: 13px; }
        .checkout { position: fixed; left: 0; right: 0; bottom: 0; background: rgba(255, 246, 232, .96); border-top: 1px solid #d8b893; backdrop-filter: blur(10px); }
        .checkout-inner { max-width: 760px; margin: 0 auto; padding: 12px 16px; display: grid; grid-template-columns: 1fr 1fr auto; gap: 10px; align-items: end; }
        button { border: 0; border-radius: 7px; background: linear-gradient(135deg, #6f452b, #352016); color: #fff8ed; padding: 12px 14px; font-weight: 900; cursor: pointer; }
        .notice { padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; background: #fff0e8; color: #8a341b; border: 1px solid #e6b292; }
        @media (max-width: 680px) { .checkout-inner { grid-template-columns: 1fr; } .item { grid-template-columns: 1fr 74px; } .package-item { grid-template-columns: 82px 1fr; } .package-item > label { grid-column: 1 / -1; } .package-thumb { width: 82px; } }
    </style>
</head>
<body>
    <main>
        <div class="top">
            <p class="muted">Pesanan meja digital</p>
            <h1>{{ $table->name }}</h1>
            <p class="muted">Pilih jumlah menu, lalu kirim pesanan. Pembayaran bisa tunai atau QRIS dummy.</p>
        </div>

        @if ($errors->any())
            <div class="notice">{{ $errors->first() }}</div>
        @endif

        <form method="post" action="{{ route('customer.orders.store', $table->qr_token) }}">
            @csrf

            @foreach ([
                'Promo' => $promoPackages,
                'Paket' => $regularPackages,
            ] as $packageTitle => $packages)
                @if ($packages->isNotEmpty())
                    <h2>{{ $packageTitle }}</h2>
                    @foreach ($packages as $package)
                        <article class="item package-item">
                            <div class="package-thumb">
                                @if ($package->foto)
                                    <img src="{{ asset($package->foto) }}" alt="{{ $package->nama_paket }}">
                                @else
                                    {{ $package->nama_paket }}
                                @endif
                            </div>
                            <div>
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
                                <p class="price">Rp{{ number_format($package->harga, 0, ',', '.') }}</p>
                            </div>
                            <label>
                                Jumlah
                                <input type="number" name="package_quantities[{{ $package->id_paket }}]" value="{{ old('package_quantities.' . $package->id_paket, 0) }}" min="0" max="1">
                            </label>

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
                @endif
            @endforeach

            @forelse ($menuItems as $category => $items)
                <h2>{{ $category }}</h2>
                @foreach ($items as $item)
                    <article class="item">
                        <div>
                            <h3>{{ $item->name }}</h3>
                            @if ($item->description)
                                <p class="muted">{{ $item->description }}</p>
                            @endif
                            <p class="price">Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <label>
                            Jumlah
                            <input type="number" name="quantities[{{ $item->id }}]" value="{{ old('quantities.' . $item->id, 0) }}" min="0" max="20">
                        </label>
                    </article>
                @endforeach
            @empty
                <p class="muted">Menu belum tersedia.</p>
            @endforelse

            <div class="checkout">
                <div class="checkout-inner">
                    <label>
                        Nama
                        <input name="customer_name" value="{{ old('customer_name') }}" placeholder="Opsional">
                    </label>
                    <label>
                        Bayar
                        <select name="payment_method" required>
                            <option value="cash" @selected(old('payment_method') === 'cash')>Tunai</option>
                            <option value="qris" @selected(old('payment_method') === 'qris')>QRIS Dummy</option>
                            <option value="gopay" @selected(old('payment_method') === 'gopay')>GoPay</option>
                            <option value="ovo" @selected(old('payment_method') === 'ovo')>OVO</option>
                            <option value="dana" @selected(old('payment_method') === 'dana')>DANA</option>
                            <option value="shopeepay" @selected(old('payment_method') === 'shopeepay')>ShopeePay</option>
                        </select>
                    </label>
                    <button type="submit">Kirim Pesanan</button>
                </div>
            </div>
        </form>
    </main>
</body>
</html>
