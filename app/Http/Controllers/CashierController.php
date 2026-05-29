<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class CashierController extends Controller
{
    public function dashboard(Request $request): View
    {
        $status = $this->normalizeStatus($request->query('status', 'aktif'));
        $orders = $this->ordersForStatus($status)
            ->paginate(5)
            ->withQueryString();
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
        $status = $this->normalizeStatus($request->query('status', 'aktif'));
        $orders = $this->ordersForStatus($status)
            ->paginate(5)
            ->withQueryString();
        $stats = $this->todayStats();

        return response()->json([
            'orders_html' => view('cashier.partials.order-list', compact('orders'))->render(),
            'stats' => $stats,
            'latest_order_id' => (int) ($orders->getCollection()->max('id_order') ?? 0),
            'updated_at' => now()->format('H:i:s'),
        ]);
    }

    public function history(Request $request): View
    {
        $filters = [
            'search' => trim((string) $request->query('search', '')),
            'status' => $this->normalizeHistoryStatus($request->query('status', 'semua')),
            'payment' => $this->normalizePaymentFilter($request->query('payment', 'semua')),
            'date' => $this->normalizeDateFilter($request->query('date', 'today')),
            'date_from' => $request->query('date_from'),
            'date_to' => $request->query('date_to'),
        ];

        $hasCustomerNameColumn = Schema::hasColumn('orders', 'customer_name');
        $hasActiveFilters = $filters['search'] !== ''
            || $filters['status'] !== 'semua'
            || $filters['payment'] !== 'semua'
            || $filters['date'] !== 'today'
            || filled($filters['date_from'])
            || filled($filters['date_to']);

        $orders = Order::with(['diningTable', 'items.menuItem'])
            ->whereIn('status', ['selesai', 'dibatalkan'])
            ->when($filters['status'] !== 'semua', fn ($query) => $query->where('status', $filters['status']))
            ->when($filters['payment'] !== 'semua', function ($query) use ($filters) {
                if ($filters['payment'] === 'ewallet') {
                    $query->whereIn('metode_pembayaran', ['gopay', 'ovo', 'dana', 'shopeepay']);

                    return;
                }

                $query->where('metode_pembayaran', $filters['payment']);
            })
            ->when($filters['search'] !== '', function ($query) use ($filters, $hasCustomerNameColumn) {
                $search = $filters['search'];

                $query->where(function ($query) use ($search, $hasCustomerNameColumn) {
                    $query->where('kode_pesanan', 'like', "%{$search}%")
                        ->orWhereHas('diningTable', fn ($tableQuery) => $tableQuery->where('nama_meja', 'like', "%{$search}%"));

                    if ($hasCustomerNameColumn) {
                        $query->orWhere('customer_name', 'like', "%{$search}%");
                    }
                });
            })
            ->when($filters['date'] === 'today', fn ($query) => $query->whereDate('created_at', today()))
            ->when($filters['date'] === 'yesterday', fn ($query) => $query->whereDate('created_at', today()->subDay()))
            ->when($filters['date'] === 'week', fn ($query) => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]))
            ->when($filters['date'] === 'month', fn ($query) => $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]))
            ->when($filters['date'] === 'custom' && filled($filters['date_from']), fn ($query) => $query->whereDate('created_at', '>=', $filters['date_from']))
            ->when($filters['date'] === 'custom' && filled($filters['date_to']), fn ($query) => $query->whereDate('created_at', '<=', $filters['date_to']))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('cashier.history', compact('orders', 'filters', 'hasActiveFilters'));
    }

    public function updateOrderStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:diproses,dibatalkan'],
        ]);

        abort_unless($order->status === 'menunggu', 403);

        $order->update($validated);

        return back()->with('success', 'Status pesanan diperbarui.');
    }

    private function normalizeStatus(?string $status): string
    {
        $allowedStatuses = ['aktif', 'menunggu', 'diproses', 'selesai'];

        return in_array($status, $allowedStatuses, true) ? $status : 'aktif';
    }

    private function normalizeHistoryStatus(?string $status): string
    {
        $allowedStatuses = ['semua', 'selesai', 'dibatalkan'];

        return in_array($status, $allowedStatuses, true) ? $status : 'semua';
    }

    private function normalizePaymentFilter(?string $payment): string
    {
        if ($payment === 'tunai') {
            return 'cash';
        }

        $allowedPayments = ['semua', 'cash', 'qris', 'ewallet', 'gopay', 'ovo', 'dana', 'shopeepay'];

        return in_array($payment, $allowedPayments, true) ? $payment : 'semua';
    }

    private function normalizeDateFilter(?string $date): string
    {
        $allowedDates = ['today', 'yesterday', 'week', 'month', 'custom'];

        return in_array($date, $allowedDates, true) ? $date : 'today';
    }

    private function ordersForStatus(string $status)
    {
        return Order::with(['diningTable', 'items.menuItem'])
            ->when($status === 'aktif', fn ($query) => $query->whereIn('status', ['menunggu', 'diproses']))
            ->when($status !== 'aktif', fn ($query) => $query->where('status', $status))
            ->latest();
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
