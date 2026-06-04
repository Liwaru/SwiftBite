<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Package;
use App\Support\ActivityRecorder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
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

        $categoryOrder = collect(['Promo', 'Paket', 'Makanan', 'Minuman']);
        $menuItems = $categoryOrder
            ->filter(fn (string $category) => $menuItems->has($category))
            ->mapWithKeys(fn (string $category) => [$category => $menuItems->get($category)])
            ->merge($menuItems->except($categoryOrder->all()));

        $packages = Package::with(['items.menuItem.categoryModel', 'choices'])
            ->where('status', 'tersedia')
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
            ->route('customer.orders.show', [$table->token, $order])
            ->with('success', 'Pesanan berhasil dikirim ke kasir/dapur.');
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
}
