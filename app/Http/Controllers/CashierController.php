<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashierController extends Controller
{
    public function dashboard(Request $request): View
    {
        $status = $this->normalizeStatus($request->query('status', 'semua'));
        $orders = $this->ordersForStatus($status)->get();
        $stats = $this->todayStats();

        $menuItems = MenuItem::with('categoryModel')
            ->where('status', 'tersedia')
            ->orderBy('nama_menu')
            ->limit(8)
            ->get();

        return view('cashier.dashboard', compact('orders', 'stats', 'status', 'menuItems'));
    }

    public function liveOrders(Request $request)
    {
        $status = $this->normalizeStatus($request->query('status', 'semua'));
        $orders = $this->ordersForStatus($status)->get();
        $stats = $this->todayStats();

        return response()->json([
            'orders_html' => view('cashier.partials.order-list', compact('orders'))->render(),
            'stats' => $stats,
            'latest_order_id' => (int) ($orders->max('id_order') ?? 0),
            'updated_at' => now()->format('H:i:s'),
        ]);
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

    private function normalizeStatus(?string $status): string
    {
        $allowedStatuses = ['semua', 'menunggu', 'diproses', 'selesai'];

        return in_array($status, $allowedStatuses, true) ? $status : 'semua';
    }

    private function ordersForStatus(string $status)
    {
        return Order::with(['diningTable', 'items.menuItem'])
            ->when($status === 'semua', fn ($query) => $query->whereNotIn('status', ['dibatalkan']))
            ->when($status !== 'semua', fn ($query) => $query->where('status', $status))
            ->latest()
            ->limit(30);
    }

    private function todayStats(): array
    {
        $todayOrders = Order::whereDate('created_at', today());

        return [
            'today_orders' => (clone $todayOrders)->count(),
            'pending_payment' => (clone $todayOrders)->where('status', 'menunggu')->count(),
            'processing_orders' => (clone $todayOrders)->where('status', 'diproses')->count(),
            'today_revenue' => (float) (clone $todayOrders)->where('status', 'selesai')->sum('total_harga'),
        ];
    }
}
