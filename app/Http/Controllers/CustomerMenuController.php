<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Package;
use App\Support\ActivityRecorder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CustomerMenuController extends Controller
{
    public function show(string $token): View
    {
        $table = DiningTable::where('token', $token)
            ->firstOrFail();

        if (! $this->tableIsActive($table)) {
            return view('customer.table-inactive', compact('table'));
        }

        $menuItems = MenuItem::with('categoryModel')
            ->where('status', 'tersedia')
            ->where('stok', '>', 0)
            ->orderBy('nama_menu')
            ->get()
            ->groupBy('category');
        $menuItems = collect($menuItems->all());

        $categoryOrder = collect(['Promo', 'Paket', 'Makanan', 'Minuman']);
        $menuItems = $categoryOrder
            ->filter(fn (string $category) => $menuItems->has($category))
            ->mapWithKeys(fn (string $category) => [$category => $menuItems->get($category)])
            ->merge($menuItems->except($categoryOrder->all()));

        $packages = Package::with(['items.menuItem.categoryModel', 'choices'])
            ->where('status', 'tersedia')
            ->where(fn ($query) => $query->whereNull('starts_at')->orWhereDate('starts_at', '<=', today()))
            ->where(fn ($query) => $query->whereNull('ends_at')->orWhereDate('ends_at', '>=', today()))
            ->orderBy('nama_paket')
            ->get();

        $promoPackages = $packages
            ->filter(fn (Package $package) => Str::contains(Str::lower($package->nama_paket), 'promo'))
            ->values();

        $regularPackages = $packages
            ->reject(fn (Package $package) => Str::contains(Str::lower($package->nama_paket), 'promo'))
            ->values();

        $choiceMenuOptions = MenuItem::with('categoryModel')
            ->where('status', 'tersedia')
            ->where('stok', '>', 0)
            ->orderBy('nama_menu')
            ->get()
            ->groupBy('category');

        return view('customer.menu', compact('table', 'menuItems', 'promoPackages', 'regularPackages', 'choiceMenuOptions'));
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $table = DiningTable::where('token', $token)
            ->firstOrFail();

        if (! $this->tableIsActive($table)) {
            return redirect()
                ->route('customer.menu', $table->token)
                ->withErrors(['table' => 'Meja ini sedang nonaktif dan belum bisa menerima pesanan.']);
        }

        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:100'],
            'payment_method' => ['required', 'in:cash,qris,gopay,ovo,dana,shopeepay'],
            'notes' => ['nullable', 'string', 'max:500'],
            'quantities' => ['nullable', 'array'],
            'quantities.*' => ['nullable', 'integer', 'min:0', 'max:20'],
            'package_quantities' => ['nullable', 'array'],
            'package_quantities.*' => ['nullable', 'integer', 'min:0', 'max:1'],
            'package_choices' => ['nullable', 'array'],
        ]);

        $quantities = collect($validated['quantities'] ?? [])
            ->map(fn ($quantity) => (int) $quantity)
            ->filter(fn ($quantity) => $quantity > 0);
        $packageQuantities = collect($validated['package_quantities'] ?? [])
            ->map(fn ($quantity) => (int) $quantity)
            ->filter(fn ($quantity) => $quantity > 0);

        if ($quantities->isEmpty() && $packageQuantities->isEmpty()) {
            return back()
                ->withErrors(['quantities' => 'Pilih minimal 1 menu atau paket dulu.'])
                ->withInput();
        }

        $order = DB::transaction(function () use ($table, $validated, $quantities, $packageQuantities) {
            $total = 0;
            $items = MenuItem::whereIn('id_menu', $quantities->keys())
                ->where('status', 'tersedia')
                ->lockForUpdate()
                ->get()
                ->keyBy('id_menu');

            foreach ($quantities as $menuId => $quantity) {
                $item = $items->get((int) $menuId);

                if (! $item) {
                    throw ValidationException::withMessages([
                        'quantities' => 'Beberapa menu sudah tidak tersedia.',
                    ]);
                }

                if ((int) $item->stok < (int) $quantity) {
                    throw ValidationException::withMessages([
                        'quantities' => 'Stok ' . $item->nama_menu . ' tidak cukup. Stok saat ini ' . $item->stok . ' pcs.',
                    ]);
                }
            }

            $orderData = [
                'id_meja' => $table->id_meja,
                'kode_pesanan' => 'ORD-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                'metode_pembayaran' => $validated['payment_method'],
                'status' => 'menunggu',
            ];

            if (Schema::hasColumn('orders', 'customer_name')) {
                $orderData['customer_name'] = $validated['customer_name'] ?? null;
            }

            if (Schema::hasColumn('orders', 'notes')) {
                $orderData['notes'] = $validated['notes'] ?? null;
            }

            $order = Order::create($orderData);

            foreach ($items as $item) {
                $quantity = $quantities->get($item->id_menu, 0);
                $subtotal = $item->harga * $quantity;
                $total += $subtotal;

                $order->items()->create([
                    'id_menu' => $item->id_menu,
                    'harga' => $item->harga,
                    'qty' => $quantity,
                    'subtotal' => $subtotal,
                ]);

                $item->decrement('stok', $quantity);
            }

            $packages = Package::with(['items.menuItem.categoryModel', 'choices'])
                ->whereIn('id_paket', $packageQuantities->keys())
                ->where('status', 'tersedia')
                ->where(fn ($query) => $query->whereNull('starts_at')->orWhereDate('starts_at', '<=', today()))
                ->where(fn ($query) => $query->whereNull('ends_at')->orWhereDate('ends_at', '>=', today()))
                ->lockForUpdate()
                ->get()
                ->keyBy('id_paket');

            foreach ($packageQuantities as $packageId => $quantity) {
                $package = $packages->get((int) $packageId);

                if (! $package) {
                    throw ValidationException::withMessages([
                        'package_quantities' => 'Beberapa paket sudah tidak tersedia.',
                    ]);
                }

                foreach ($package->items as $packageItem) {
                    $menu = $packageItem->menuItem;
                    $needed = (int) $packageItem->qty * $quantity;

                    if (! $menu || $menu->status !== 'tersedia' || (int) $menu->stok < $needed) {
                        throw ValidationException::withMessages([
                            'package_quantities' => 'Stok isi paket ' . $package->nama_paket . ' tidak cukup.',
                        ]);
                    }
                }

                foreach ($package->choices as $choice) {
                    $selectedIds = collect($validated['package_choices'][$packageId][$choice->category] ?? [])
                        ->map(fn ($menuId) => (int) $menuId)
                        ->filter()
                        ->values();
                    $needed = (int) $choice->qty * $quantity;

                    if ($selectedIds->count() !== $needed) {
                        throw ValidationException::withMessages([
                            'package_choices' => 'Pilih ' . $needed . ' ' . strtolower($choice->category) . ' untuk ' . $package->nama_paket . '.',
                        ]);
                    }

                    $selectedMenus = MenuItem::with('categoryModel')
                        ->whereIn('id_menu', $selectedIds)
                        ->where('status', 'tersedia')
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id_menu');

                    foreach ($selectedIds->countBy() as $menuId => $selectedQty) {
                        $menu = $selectedMenus->get((int) $menuId);

                        if (! $menu || $menu->category !== $choice->category || (int) $menu->stok < (int) $selectedQty) {
                            throw ValidationException::withMessages([
                                'package_choices' => 'Pilihan ' . strtolower($choice->category) . ' untuk ' . $package->nama_paket . ' tidak valid atau stok tidak cukup.',
                            ]);
                        }
                    }
                }
            }

            foreach ($packageQuantities as $packageId => $quantity) {
                $package = $packages->get((int) $packageId);
                $packageTotal = (int) $package->harga * $quantity;
                $total += $packageTotal;
                $priceApplied = false;

                foreach ($package->items as $packageItem) {
                    $menu = $packageItem->menuItem;
                    $lineQty = (int) $packageItem->qty * $quantity;
                    $lineSubtotal = $priceApplied ? 0 : $packageTotal;

                    $order->items()->create([
                        'id_menu' => $menu->id_menu,
                        'id_paket' => $package->id_paket,
                        'package_name' => $package->nama_paket,
                        'package_component_type' => 'fixed',
                        'harga' => $priceApplied ? 0 : $package->harga,
                        'qty' => $lineQty,
                        'subtotal' => $lineSubtotal,
                    ]);

                    $menu->decrement('stok', $lineQty);
                    $priceApplied = true;
                }

                foreach ($package->choices as $choice) {
                    $selectedIds = collect($validated['package_choices'][$packageId][$choice->category] ?? [])
                        ->map(fn ($menuId) => (int) $menuId)
                        ->filter()
                        ->values();
                    $selectedMenus = MenuItem::whereIn('id_menu', $selectedIds)->lockForUpdate()->get()->keyBy('id_menu');

                    foreach ($selectedIds->countBy() as $menuId => $selectedQty) {
                        $menu = $selectedMenus->get((int) $menuId);
                        $lineSubtotal = $priceApplied ? 0 : $packageTotal;

                        $order->items()->create([
                            'id_menu' => $menu->id_menu,
                            'id_paket' => $package->id_paket,
                            'package_name' => $package->nama_paket,
                            'package_component_type' => 'choice',
                            'harga' => $priceApplied ? 0 : $package->harga,
                            'qty' => (int) $selectedQty,
                            'subtotal' => $lineSubtotal,
                        ]);

                        $menu->decrement('stok', (int) $selectedQty);
                        $priceApplied = true;
                    }
                }
            }

            $order->update(['total_harga' => $total]);

            return $order;
        });

        ActivityRecorder::activity('Customer', 'Membuat pesanan baru dari ' . ($table->nama_meja ?? 'meja') . ' #' . $order->kode_pesanan, $validated['customer_name'] ?? 'Customer');

        return redirect()
            ->route('customer.orders.payment', [$table->token, $order])
            ->with('success', 'Pesanan berhasil dibuat. Silakan pilih metode pembayaran.');
    }

    public function payment(string $token, Order $order): View
    {
        $table = DiningTable::where('token', $token)->firstOrFail();

        abort_unless((int) $order->id_meja === (int) $table->id_meja, 404);

        $order->load('items.menuItem');

        return view('customer.payment', compact('table', 'order'));
    }

    public function confirmPayment(Request $request, string $token, Order $order): RedirectResponse
    {
        $table = DiningTable::where('token', $token)->firstOrFail();

        abort_unless((int) $order->id_meja === (int) $table->id_meja, 404);

        $validated = $request->validate([
            'payment_method' => ['required', 'in:cash,qris,gopay,ovo,dana,shopeepay'],
        ]);

        if ($validated['payment_method'] === 'cash') {
            $order->update([
                'metode_pembayaran' => 'cash',
                'payment_status' => 'belum_dibayar',
            ]);

            ActivityRecorder::activity('Customer', 'Memilih pembayaran tunai untuk pesanan #' . $order->kode_pesanan);

            return redirect()
                ->route('customer.orders.show', [$table->token, $order])
                ->with('success', 'Metode tunai dipilih. Silakan bayar ke kasir saat pesanan siap.');
        }

        if ($validated['payment_method'] === 'qris') {
            $order->update([
                'metode_pembayaran' => 'qris',
                'payment_status' => 'diproses',
            ]);

            $result = $this->createMidtransQris($order);

            if (! $result['ok']) {
                return back()
                    ->withErrors(['payment_method' => $result['message']])
                    ->withInput();
            }

            ActivityRecorder::activity('Customer', 'Membuka pembayaran QRIS untuk pesanan #' . $order->kode_pesanan);

            return redirect()->route('customer.orders.qris', [$table->token, $order]);
        }

        return redirect()
            ->route('customer.orders.payment', [$table->token, $order])
            ->withErrors(['payment_method' => 'Gunakan tombol pembayaran E-Wallet di halaman ini.'])
            ->withInput();
    }

    public function createEwalletPayment(Request $request, string $token, Order $order): JsonResponse
    {
        $table = DiningTable::where('token', $token)->firstOrFail();

        abort_unless((int) $order->id_meja === (int) $table->id_meja, 404);

        $validated = $request->validate([
            'payment_method' => ['required', 'in:gopay,ovo,dana,shopeepay'],
            'account_number' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s]+$/'],
        ]);

        $result = $this->createMidtransSnap($order, $validated['payment_method'], $validated['account_number']);

        if (! $result['ok']) {
            return response()->json([
                'message' => $result['message'],
            ], 422);
        }

        ActivityRecorder::activity('Customer', 'Membuka pembayaran E-Wallet ' . strtoupper($validated['payment_method']) . ' untuk pesanan #' . $order->kode_pesanan);

        return response()->json([
            'snap_token' => $result['snap_token'],
            'order_id' => $result['order_id'],
            'redirect_url' => route('customer.orders.show', [$table->token, $order]),
        ]);
    }

    public function syncMidtransPayment(Request $request, string $token, Order $order): JsonResponse
    {
        $table = DiningTable::where('token', $token)->firstOrFail();

        abort_unless((int) $order->id_meja === (int) $table->id_meja, 404);

        $this->syncMidtransStatus($order);

        $order->refresh();

        return response()->json([
            'status' => $order->payment_status ?? 'diproses',
            'redirect_url' => route('customer.orders.show', [$table->token, $order]),
        ]);
    }

    public function qris(string $token, Order $order): View
    {
        $table = DiningTable::where('token', $token)->firstOrFail();

        abort_unless((int) $order->id_meja === (int) $table->id_meja, 404);

        $order->load('items.menuItem');

        return view('customer.qris', compact('table', 'order'));
    }

    public function qrisStatus(string $token, Order $order): JsonResponse
    {
        $table = DiningTable::where('token', $token)->firstOrFail();

        abort_unless((int) $order->id_meja === (int) $table->id_meja, 404);

        $this->syncMidtransStatus($order);

        $order->refresh();

        return response()->json([
            'status' => $order->payment_status ?? 'diproses',
            'redirect_url' => route('customer.orders.show', [$table->token, $order]),
        ]);
    }

    public function midtransNotification(Request $request): JsonResponse
    {
        $payload = $request->all();
        $midtransOrderId = (string) ($payload['order_id'] ?? '');

        if ($midtransOrderId === '') {
            return response()->json(['message' => 'order_id tidak ditemukan.'], 422);
        }

        if (! $this->validMidtransSignature($payload)) {
            return response()->json(['message' => 'Signature Midtrans tidak valid.'], 403);
        }

        $order = Order::where('midtrans_order_id', $midtransOrderId)->first();

        if (! $order) {
            return response()->json(['message' => 'Pesanan tidak ditemukan.'], 404);
        }

        $this->applyMidtransPayload($order, $payload);

        ActivityRecorder::activity('Customer', 'Webhook Midtrans memperbarui pembayaran #' . $order->kode_pesanan . ' menjadi ' . $order->payment_status);

        return response()->json([
            'message' => 'Status pembayaran diperbarui.',
            'payment_status' => $order->payment_status,
        ]);
    }

    public function receipt(string $token, Order $order): View
    {
        $table = DiningTable::where('token', $token)->firstOrFail();

        abort_unless((int) $order->id_meja === (int) $table->id_meja, 404);

        $order->load('items.menuItem');

        return view('customer.receipt', compact('table', 'order'));
    }

    private function tableIsActive(DiningTable $table): bool
    {
        return in_array($table->status, ['aktif', 'kosong', 'terisi'], true);
    }

    public function createMidtransQrisForOrder(Order $order): array
    {
        return $this->createMidtransQris($order);
    }

    private function createMidtransQris(Order $order): array
    {
        if (! $this->midtransConfigured()) {
            return ['ok' => false, 'message' => 'Midtrans belum dikonfigurasi. Isi MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY di .env.'];
        }

        $midtransOrderId = $this->midtransOrderId($order, 'QRIS');
        $expiresAt = now()->addMinutes(15);

        try {
            $response = $this->midtransRequest()
                ->post($this->midtransApiBaseUrl() . '/v2/charge', [
                    'payment_type' => 'qris',
                    'transaction_details' => [
                        'order_id' => $midtransOrderId,
                        'gross_amount' => (int) $order->total_harga,
                    ],
                    'item_details' => $this->midtransItemDetails($order),
                    'custom_expiry' => [
                        'expiry_duration' => 15,
                        'unit' => 'minute',
                    ],
                ]);
        } catch (Throwable $exception) {
            return ['ok' => false, 'message' => 'Gagal menghubungi Midtrans: ' . $exception->getMessage()];
        }

        if (! $response->successful()) {
            return ['ok' => false, 'message' => $response->json('status_message') ?? 'Gagal membuat pembayaran QRIS Midtrans.'];
        }

        $payload = $response->json();
        $qrAction = collect($payload['actions'] ?? [])->firstWhere('name', 'generate-qr-code');
        $qrUrl = is_array($qrAction) ? ($qrAction['url'] ?? null) : null;

        $order->update([
            'metode_pembayaran' => 'qris',
            'payment_status' => 'diproses',
            'midtrans_order_id' => $midtransOrderId,
            'qris_url' => $qrUrl,
            'payment_expires_at' => $expiresAt,
            'payment_payload' => $payload,
        ]);

        return ['ok' => true];
    }

    private function createMidtransSnap(Order $order, string $method, ?string $accountNumber = null): array
    {
        if (! $this->midtransConfigured()) {
            return ['ok' => false, 'message' => 'Midtrans belum dikonfigurasi. Isi MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY di .env.'];
        }

        $midtransOrderId = $this->midtransOrderId($order, strtoupper($method));

        try {
            $response = $this->midtransRequest()
                ->post($this->midtransSnapBaseUrl() . '/snap/v1/transactions', [
                    'transaction_details' => [
                        'order_id' => $midtransOrderId,
                        'gross_amount' => (int) $order->total_harga,
                    ],
                    'item_details' => $this->midtransItemDetails($order),
                    'enabled_payments' => [$method],
                    'callbacks' => [
                        'finish' => route('customer.orders.show', [$order->diningTable?->qr_token ?? $order->diningTable?->token, $order]),
                    ],
                ]);
        } catch (Throwable $exception) {
            return ['ok' => false, 'message' => 'Gagal menghubungi Midtrans: ' . $exception->getMessage()];
        }

        if (! $response->successful()) {
            return ['ok' => false, 'message' => $response->json('error_messages.0') ?? $response->json('status_message') ?? 'Gagal membuat transaksi E-Wallet Midtrans.'];
        }

        $payload = $response->json();

        $order->update([
            'metode_pembayaran' => $method,
            'payment_status' => 'diproses',
            'midtrans_order_id' => $midtransOrderId,
            'payment_expires_at' => now()->addMinutes(30),
            'payment_payload' => array_merge($payload, [
                'account_number' => $accountNumber,
            ]),
        ]);

        return [
            'ok' => true,
            'snap_token' => $payload['token'] ?? null,
            'order_id' => $midtransOrderId,
        ];
    }

    private function syncMidtransStatus(Order $order): void
    {
        if (! $order->midtrans_order_id || ! $this->midtransConfigured()) {
            return;
        }

        try {
            $response = $this->midtransRequest()
                ->get($this->midtransApiBaseUrl() . '/v2/' . $order->midtrans_order_id . '/status');
        } catch (Throwable) {
            return;
        }

        if (! $response->successful()) {
            return;
        }

        $this->applyMidtransPayload($order, $response->json());
    }

    private function applyMidtransPayload(Order $order, array $payload): void
    {
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentStatus = match ($transactionStatus) {
            'settlement' => 'berhasil',
            'capture' => $fraudStatus === 'challenge' ? 'diproses' : 'berhasil',
            'pending' => 'diproses',
            'deny', 'cancel', 'expire', 'failure' => 'gagal',
            default => $order->payment_status ?? 'diproses',
        };

        $order->update([
            'payment_status' => $paymentStatus,
            'payment_payload' => $payload,
        ]);
    }

    private function validMidtransSignature(array $payload): bool
    {
        if (! $this->midtransConfigured()) {
            return false;
        }

        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signature = (string) ($payload['signature_key'] ?? '');

        if ($orderId === '' || $statusCode === '' || $grossAmount === '' || $signature === '') {
            return false;
        }

        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . (string) config('midtrans.server_key'));

        return hash_equals($expected, $signature);
    }

    private function midtransItemDetails(Order $order): array
    {
        $order->loadMissing('items.menuItem');

        return $order->items
            ->filter(fn ($item) => (int) $item->subtotal > 0)
            ->map(fn ($item) => [
                'id' => (string) ($item->id_menu ?? $item->getKey()),
                'price' => (int) ($item->subtotal / max((int) $item->qty, 1)),
                'quantity' => (int) $item->qty,
                'name' => Str::limit($item->menu_name, 48, ''),
            ])
            ->values()
            ->all();
    }

    private function midtransRequest()
    {
        return Http::withBasicAuth((string) config('midtrans.server_key'), '')
            ->acceptJson()
            ->asJson()
            ->timeout(20);
    }

    private function midtransConfigured(): bool
    {
        return filled(config('midtrans.server_key')) && filled(config('midtrans.client_key'));
    }

    private function midtransOrderId(Order $order, string $prefix): string
    {
        return 'SWIFTBITE-' . $prefix . '-' . $order->getKey() . '-' . now()->format('YmdHis');
    }

    private function midtransApiBaseUrl(): string
    {
        return config('midtrans.is_production')
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';
    }

    private function midtransSnapBaseUrl(): string
    {
        return config('midtrans.is_production')
            ? 'https://app.midtrans.com'
            : 'https://app.sandbox.midtrans.com';
    }
}
