<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Order;
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

        return view('customer.menu', compact('table', 'menuItems'));
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
            'quantities' => ['required', 'array'],
            'quantities.*' => ['nullable', 'integer', 'min:0', 'max:20'],
        ]);

        $quantities = collect($validated['quantities'])
            ->map(fn ($quantity) => (int) $quantity)
            ->filter(fn ($quantity) => $quantity > 0);

        if ($quantities->isEmpty()) {
            return back()
                ->withErrors(['quantities' => 'Pilih minimal 1 menu dulu.'])
                ->withInput();
        }

        $order = DB::transaction(function () use ($table, $validated, $quantities) {
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
