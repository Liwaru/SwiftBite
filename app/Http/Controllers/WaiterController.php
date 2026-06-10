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
            ->when($status === 'aktif', fn ($query) => $query->where('status', 'siap_diantar'))
            ->when($status === 'selesai', fn ($query) => $query->whereIn('status', ['menunggu_pembayaran', 'selesai']))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'aktif' => Order::where('status', 'siap_diantar')->count(),
            'selesai_today' => Order::whereIn('status', ['menunggu_pembayaran', 'selesai'])->whereDate('updated_at', today())->count(),
        ];

        // Fetch logged-in user from custom session
        $userId = $request->session()->get('auth_user_id');
        $waiterUser = \App\Models\User::find($userId);

        $todayAbsensi = null;
        if ($waiterUser) {
            $todayAbsensi = \App\Models\Absensi::where('id_user', $waiterUser->id_user)
                ->where('tanggal', today()->toDateString())
                ->first();
        }

        return view('waiter.dashboard', compact('orders', 'status', 'perPage', 'stats', 'todayAbsensi', 'waiterUser'));
    }

    public function complete(Order $order): RedirectResponse
    {
        abort_unless($order->status === 'siap_diantar', 403);

        $nextStatus = $order->payment_method === 'cash' || $order->payment_status !== 'berhasil'
            ? 'menunggu_pembayaran'
            : 'selesai';

        $order->update(['status' => $nextStatus]);

        ActivityRecorder::activity('Waiter', 'Mengantar pesanan #' . $order->kode_pesanan);

        $message = $nextStatus === 'menunggu_pembayaran'
            ? 'Pesanan sudah diantar dan menunggu pembayaran kasir.'
            : 'Pesanan sudah diantar dan selesai.';

        return back()->with('success', $message);
    }
}
