<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\View\View;

class OwnerController extends Controller
{
    public function dashboard(): View
    {
        $todayOrders = Order::whereDate('created_at', today());

        $stats = [
            'today_orders' => (clone $todayOrders)->count(),
            'today_revenue' => (clone $todayOrders)->where('status', 'selesai')->sum('total_harga'),
            'active_menu' => MenuItem::where('status', 'tersedia')->count(),
            'active_users' => User::whereIn('level', [1, 2, 3, 4])->count(),
            'orders_today' => [
                'menunggu' => (clone $todayOrders)->where('status', 'menunggu')->count(),
                'diproses' => (clone $todayOrders)->where('status', 'diproses')->count(),
                'selesai' => (clone $todayOrders)->where('status', 'selesai')->count(),
                'dibatalkan' => (clone $todayOrders)->where('status', 'dibatalkan')->count(),
            ],
        ];

        return view('owner.dashboard', compact('stats'));
    }
}
