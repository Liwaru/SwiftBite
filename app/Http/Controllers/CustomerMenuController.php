<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CustomerMenuController extends Controller
{
    public function show(string $token): View
    {
        $table = DiningTable::where('qr_token', $token)
            ->where('is_active', true)
            ->firstOrFail();

        $menuItems = MenuItem::where('is_available', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return view('customer.menu', compact('table', 'menuItems'));
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $table = DiningTable::where('qr_token', $token)
            ->where('is_active', true)
            ->firstOrFail();

        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:100'],
            'payment_method' => ['required', 'in:cash,qris'],
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

        $items = MenuItem::whereIn('id', $quantities->keys())
            ->where('is_available', true)
            ->get();

        $order = DB::transaction(function () use ($table, $validated, $quantities, $items) {
            $total = 0;

            $order = Order::create([
                'dining_table_id' => $table->id,
                'customer_name' => $validated['customer_name'] ?? null,
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'new',
            ]);

            foreach ($items as $item) {
                $quantity = $quantities->get($item->id, 0);
                $subtotal = $item->price * $quantity;
                $total += $subtotal;

                $order->items()->create([
                    'menu_item_id' => $item->id,
                    'menu_name' => $item->name,
                    'price' => $item->price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total_price' => $total]);

            return $order;
        });

        return redirect()
            ->route('customer.orders.show', [$table->qr_token, $order])
            ->with('success', 'Pesanan berhasil dikirim ke kasir/dapur.');
    }

    public function receipt(string $token, Order $order): View
    {
        $table = DiningTable::where('qr_token', $token)->firstOrFail();

        abort_unless($order->dining_table_id === $table->id, 404);

        $order->load('items');

        return view('customer.receipt', compact('table', 'order'));
    }
}
