<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
        $menuItems = MenuItem::with('categoryModel')
            ->orderBy('nama_menu')
            ->get()
            ->sortBy('category');
        $orders = Order::with(['diningTable', 'items.menuItem'])
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
            'nama_meja' => $validated['name'],
            'token' => Str::random(32),
            'status' => 'aktif',
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

        $category = Category::firstOrCreate([
            'nama_kategori' => $validated['category'],
        ]);

        MenuItem::create([
            'id_kategori' => $category->id_kategori,
            'nama_menu' => $validated['name'],
            'deskripsi' => $validated['description'] ?? null,
            'harga' => $validated['price'],
            'stok' => 0,
            'status' => 'tersedia',
        ]);

        return back()->with('success', 'Menu baru berhasil ditambahkan.');
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
