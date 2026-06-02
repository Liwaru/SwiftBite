<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientUsage;
use App\Models\Order;
use App\Support\ActivityRecorder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ChefController extends Controller
{
    public function dashboard(): View
    {
        $processingOrders = Order::with(['diningTable', 'items.menuItem'])
            ->where('status', 'diproses')
            ->latest()
            ->limit(6)
            ->get();

        $ingredients = Ingredient::orderBy('nama_bahan')->get();

        $stats = [
            'pesanan_diproses' => $processingOrders->count(),
            'total_bahan' => $ingredients->count(),
            'bahan_menipis' => $ingredients->filter(fn (Ingredient $ingredient) => $ingredient->status_label === 'Menipis')->count(),
            'bahan_habis' => $ingredients->filter(fn (Ingredient $ingredient) => $ingredient->status_label === 'Habis')->count(),
        ];

        return view('chef.dashboard', compact('stats', 'processingOrders', 'ingredients'));
    }

    public function orders(): View
    {
        $orders = Order::with(['diningTable', 'items.menuItem'])
            ->where('status', 'diproses')
            ->latest()
            ->get();

        return view('chef.orders', compact('orders'));
    }

    public function ingredients(): View
    {
        $ingredients = Ingredient::query()
            ->withSum(['usages as used_today' => fn ($query) => $query->whereDate('created_at', today())], 'qty')
            ->orderBy('nama_bahan')
            ->get();

        return view('chef.ingredients', compact('ingredients'));
    }

    public function useIngredient(Request $request, Ingredient $ingredient): RedirectResponse
    {
        $validated = $request->validate([
            'qty' => ['required', 'numeric', 'min:0.01', 'max:99999'],
            'note' => ['nullable', 'string', 'max:120'],
        ]);

        $qty = (float) $validated['qty'];

        if ($qty > $ingredient->stok) {
            throw ValidationException::withMessages([
                'qty' => 'Jumlah penggunaan tidak boleh melebihi stok bahan.',
            ]);
        }

        $ingredient->decrement('stok', $qty);

        IngredientUsage::create([
            'id_bahan' => $ingredient->id_bahan,
            'qty' => $qty,
            'note' => $validated['note'] ?? null,
            'actor_name' => session('auth_name'),
        ]);

        ActivityRecorder::activity('Chef', 'Menggunakan bahan ' . $ingredient->nama_bahan . ' sebanyak ' . $qty . ' ' . $ingredient->satuan);

        return redirect()
            ->route('chef.ingredients')
            ->with('success', 'Penggunaan bahan berhasil dicatat.');
    }
}
