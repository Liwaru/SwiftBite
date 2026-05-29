<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\View\View;

class ManagerController extends Controller
{
    public function dashboard(): View
    {
        $todayOrders = Order::whereDate('created_at', today());

        $stats = [
            'total_menu' => MenuItem::count(),
            'total_tables' => DiningTable::count(),
            'today_orders' => (clone $todayOrders)->count(),
            'active_users' => User::count(),
            'orders_today' => [
                'menunggu' => (clone $todayOrders)->where('status', 'menunggu')->count(),
                'diproses' => (clone $todayOrders)->where('status', 'diproses')->count(),
                'selesai' => (clone $todayOrders)->where('status', 'selesai')->count(),
                'dibatalkan' => (clone $todayOrders)->where('status', 'dibatalkan')->count(),
            ],
        ];

        return view('manager.dashboard', compact('stats'));
    }

    public function page(string $section): View
    {
        $pages = [
            'users' => ['title' => 'Data User', 'description' => 'Kelola akun pengguna dan role di SwiftBite.'],
            'menus' => ['title' => 'Data Menu', 'description' => 'Kelola menu makanan dan minuman.'],
            'tables' => ['title' => 'Data Meja', 'description' => 'Kelola meja dan QR ordering.'],
            'stock' => ['title' => 'Stok Produk', 'description' => 'Pantau stok makanan dan minuman.'],
            'access' => ['title' => 'Hak Akses', 'description' => 'Kelola hak akses berdasarkan role.'],
            'database' => ['title' => 'Database', 'description' => 'Kelola backup, import, dan pemeliharaan database.'],
            'activity' => ['title' => 'Catatan Aktivitas', 'description' => 'Pantau aktivitas penting di sistem.'],
        ];

        abort_unless(isset($pages[$section]), 404);

        return view('manager.page', ['page' => $pages[$section], 'section' => $section]);
    }
}
