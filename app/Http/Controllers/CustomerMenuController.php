<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CustomerMenuController extends Controller
{
    public function show(string $token): View
    {
        $table = DiningTable::where('token', $token)
            ->firstOrFail();

        $menuItems = MenuItem::with('categoryModel')
            ->where('status', 'tersedia')
            ->orderBy('nama_menu')
            ->get()
            ->groupBy('category');

        return view('customer.menu', compact('table', 'menuItems'));
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $table = DiningTable::where('token', $token)
            ->firstOrFail();

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

        $items = MenuItem::whereIn('id_menu', $quantities->keys())
            ->where('status', 'tersedia')
            ->get();

        $order = DB::transaction(function () use ($table, $validated, $quantities, $items) {
            $total = 0;

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
            }

            $order->update(['total_harga' => $total]);

            return $order;
        });

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
}
