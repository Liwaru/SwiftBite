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
use Illuminate\Support\Facades\DB;

class ChefController extends Controller
{

    public function finishCooking(Order $order): RedirectResponse
{
    if ($order->status !== 'diproses') {
        return back()->withErrors([
            'order' => 'Pesanan ini tidak sedang diproses.',
        ]);
    }

    $order->load([
        'items.menuItem.recipes.ingredient',
    ]);

    try {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $menu = $item->menuItem;

                if (! $menu) {
                    continue;
                }

                foreach ($menu->recipes as $recipe) {
                    $ingredient = $recipe->ingredient;

                    if (! $ingredient) {
                        continue;
                    }

                    $usedQty = (float) $recipe->qty * (int) $item->qty;

                    if ($usedQty <= 0) {
                        continue;
                    }

                    if ((float) $ingredient->stok < $usedQty) {
                        throw ValidationException::withMessages([
                            'stok' => 'Stok bahan ' . $ingredient->nama_bahan . ' tidak cukup untuk pesanan ini.',
                        ]);
                    }

                    $ingredient->decrement('stok', $usedQty);

                    IngredientUsage::create([
                        'id_bahan' => $ingredient->id_bahan,
                        'qty' => $usedQty,
                        'note' => 'Pesanan ' . $order->kode_pesanan . ' - ' . $menu->nama_menu,
                        'actor_name' => session('auth_name'),
                    ]);
                }
            }

            $order->update([
                'status' => 'siap_diantar',
            ]);
        });
    } catch (ValidationException $exception) {
        throw $exception;
    }

    ActivityRecorder::activity(
        'Baker',
        'Menyelesaikan masakan untuk pesanan #' . $order->kode_pesanan . ' dan mengurangi bahan sesuai resep.'
    );

    return back()->with('success', 'Pesanan selesai dimasak. Bahan berhasil dikurangi sesuai resep.');
}


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

        $userId = request()->session()->get('auth_user_id');
        $bakerUser = \App\Models\User::find($userId);
        $todayAbsensi = null;

        if ($bakerUser) {
            $todayAbsensi = \App\Models\Absensi::where('id_user', $bakerUser->id_user)
                ->where('tanggal', today()->toDateString())
                ->first();
        }

        return view('chef.dashboard', compact('stats', 'processingOrders', 'ingredients', 'todayAbsensi', 'bakerUser'));
    }

    public function orders(): View
    {
        $orders = Order::with(['diningTable', 'items.menuItem'])
            ->where('status', 'diproses')
            ->latest()
            ->get();

        return view('chef.orders', compact('orders'));
    }

    public function markReady(Order $order): RedirectResponse
    {
        abort_unless($order->status === 'diproses', 403);

        $order->update(['status' => 'siap_diantar']);

        ActivityRecorder::activity('Baker', 'Menandai pesanan #' . $order->kode_pesanan . ' siap diantar');

        return back()->with('success', 'Tugas Baker selesai. Pesanan dikirim ke Waiter.');
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

        ActivityRecorder::activity('Baker', 'Menggunakan bahan ' . $ingredient->nama_bahan . ' sebanyak ' . $qty . ' ' . $ingredient->satuan);

        return redirect()
            ->route('baker.ingredients')
            ->with('success', 'Penggunaan bahan berhasil dicatat.');
    }
    
public function liveOrders()
{
    $processingOrders = Order::with(['diningTable', 'items.menuItem'])
        ->where('status', 'diproses')
        ->latest()
        ->limit(6)
        ->get();

    return response()->json([
        'html' => view('chef.partials.live-orders', compact('processingOrders'))->render(),
        'count' => $processingOrders->count(),
        'latest_order_id' => (int) ($processingOrders->max('id_order') ?? 0),
        'orders' => $processingOrders->map(function ($order) {
            return [
                'id' => (int) $order->id_order,
                'table' => $order->diningTable?->nama_meja ?? 'Kasir Langsung',
                'items' => $order->items
                    ->map(fn ($item) => $item->menuItem?->nama_menu ?? 'Menu')
                    ->values()
                    ->all(),
            ];
        })->values(),
    ]);
}}
