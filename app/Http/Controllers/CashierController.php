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
        $orders = Order::with(['diningTable', 'items'])
            ->whereNotIn('status', ['paid', 'cancelled'])
            ->latest()
            ->limit(30)
            ->get();

        return view('cashier.dashboard', compact('orders'));
    }

    public function history(): View
    {
        $orders = Order::with(['diningTable', 'items'])
            ->whereIn('status', ['paid', 'cancelled'])
            ->latest()
            ->limit(50)
            ->get();

        return view('cashier.history', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,preparing,ready,paid,cancelled'],
        ]);

        $order->update($validated);

        return back()->with('success', 'Status pesanan diperbarui.');
    }
}
