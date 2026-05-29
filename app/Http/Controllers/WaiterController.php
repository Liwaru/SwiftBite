<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WaiterController extends Controller
{
    public function dashboard(Request $request): View
    {
        $status = $request->query('status') === 'selesai' ? 'selesai' : 'aktif';
        $perPage = (int) $request->query('per_page', 5);
        $perPage = in_array($perPage, [3, 5], true) ? $perPage : 5;

        $orders = Order::with(['diningTable', 'items.menuItem'])
            ->when($status === 'aktif', fn ($query) => $query->where('status', 'diproses'))
            ->when($status === 'selesai', fn ($query) => $query->where('status', 'selesai'))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('waiter.dashboard', compact('orders', 'status', 'perPage'));
    }

    public function complete(Order $order): RedirectResponse
    {
        abort_unless($order->status === 'diproses', 403);

        $order->update(['status' => 'selesai']);

        return back()->with('success', 'Pesanan ditandai selesai.');
    }
}
