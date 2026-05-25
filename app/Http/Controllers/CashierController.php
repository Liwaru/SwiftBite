<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashierController extends Controller
{
    public function dashboard(): View
    {
        $orders = Order::with(['diningTable', 'items.menuItem'])
            ->whereNotIn('status', ['selesai', 'dibatalkan'])
            ->latest()
            ->limit(30)
            ->get();

        return view('cashier.dashboard', compact('orders'));
    }

    public function history(): View
    {
        $orders = Order::with(['diningTable', 'items.menuItem'])
            ->whereIn('status', ['selesai', 'dibatalkan'])
            ->latest()
            ->limit(50)
            ->get();

        return view('cashier.history', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:menunggu,diproses,selesai,dibatalkan'],
        ]);

        $order->update($validated);

        return back()->with('success', 'Status pesanan diperbarui.');
    }
}
