<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $tables = DiningTable::latest()->get();
        $menuItems = MenuItem::orderBy('category')->orderBy('name')->get();
        $orders = Order::with(['diningTable', 'items'])
            ->latest()
            ->limit(20)
            ->get();

        return view('admin.dashboard', compact('tables', 'menuItems', 'orders'));
    }

    public function storeTable(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
        ]);

        DiningTable::create([
            'name' => $validated['name'],
            'qr_token' => Str::random(16),
            'is_active' => true,
        ]);

        return back()->with('success', 'Meja baru berhasil dibuat.');
    }

    public function storeMenuItem(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:300'],
            'price' => ['required', 'integer', 'min:0'],
        ]);

        MenuItem::create($validated + ['is_available' => true]);

        return back()->with('success', 'Menu baru berhasil ditambahkan.');
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
