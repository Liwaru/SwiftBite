<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Support\ActivityRecorder;
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

        $stats = [
            'aktif' => Order::where('status', 'diproses')->count(),
            'selesai_today' => Order::where('status', 'selesai')->whereDate('updated_at', today())->count(),
        ];

        return view('waiter.dashboard', compact('orders', 'status', 'perPage', 'stats'));
    }

    public function complete(Order $order): RedirectResponse
    {
        abort_unless($order->status === 'diproses', 403);

        $order->update(['status' => 'selesai']);

        ActivityRecorder::activity('Waiter', 'Menyelesaikan pesanan #' . $order->kode_pesanan);

        return back()->with('success', 'Pesanan ditandai selesai.');
    }
}
