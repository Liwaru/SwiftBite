<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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
            'active_users' => User::whereIn('level', [1, 2, 3, 4])->count(),
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

        $data = ['page' => $pages[$section], 'section' => $section];

        if ($section === 'users') {
            $roleOptions = [
                1 => 'Waiter',
                2 => 'Cashier',
                3 => 'Manager',
                4 => 'Owner',
            ];

            $filters = [
                'q' => trim((string) request('q', '')),
                'role' => (string) request('role', 'semua'),
            ];

            $usersQuery = User::query()
                ->whereIn('level', [1, 2, 3, 4])
                ->when($filters['q'] !== '', function ($query) use ($filters) {
                    $query->where(function ($search) use ($filters) {
                        $search->where('name', 'like', '%' . $filters['q'] . '%')
                            ->orWhere('email', 'like', '%' . $filters['q'] . '%');
                    });
                })
                ->when($filters['role'] !== 'semua' && array_key_exists((int) $filters['role'], $roleOptions), function ($query) use ($filters) {
                    $query->where('level', (int) $filters['role']);
                })
                ->orderBy('level')
                ->orderBy('name');

            $data['users'] = $usersQuery->paginate(10)->withQueryString();
            $data['filters'] = $filters;
            $data['roleOptions'] = $roleOptions;
            $data['summary'] = [
                'total_user' => User::whereIn('level', [1, 2, 3, 4])->count(),
                'waiter' => User::where('level', 1)->count(),
                'cashier' => User::where('level', 2)->count(),
                'pengelola' => User::whereIn('level', [3, 4])->count(),
            ];
        }

        if ($section === 'menus') {
            $menuItems = MenuItem::with('categoryModel')
                ->withSum('orderItems as total_sold', 'qty')
                ->orderBy('nama_menu')
                ->get();

            $data['foodMenuItems'] = $menuItems
                ->filter(fn (MenuItem $menu) => $menu->category === 'Makanan')
                ->values();

            $data['drinkMenuItems'] = $menuItems
                ->filter(fn (MenuItem $menu) => $menu->category === 'Minuman')
                ->values();

            $data['menuSummary'] = [
                'total_menu' => MenuItem::count(),
                'makanan' => MenuItem::whereHas('categoryModel', fn ($query) => $query->where('nama_kategori', 'Makanan'))->count(),
                'minuman' => MenuItem::whereHas('categoryModel', fn ($query) => $query->where('nama_kategori', 'Minuman'))->count(),
                'aktif' => MenuItem::where('status', 'tersedia')->count(),
            ];
        }

        return view('manager.page', $data);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,name'],
            'password' => ['required', 'string', 'min:6'],
            'level' => ['required', Rule::in([1, 2])],
        ]);

        $username = strtolower($validated['username']);

        User::create([
            'name' => $username,
            'email' => $username . '@swiftbite.test',
            'password' => $validated['password'],
            'level' => (int) $validated['level'],
        ]);

        return redirect()
            ->route('manager.page', 'users')
            ->with('success', 'User baru berhasil ditambahkan.');
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'alpha_dash',
                Rule::unique('users', 'name')->ignore($user->getKey(), $user->getKeyName()),
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'level' => ['required', Rule::in([1, 2, 3, 4])],
        ]);

        $username = strtolower($validated['username']);

        $data = [
            'name' => $username,
            'email' => $username . '@swiftbite.test',
            'level' => (int) $validated['level'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        $user->update($data);

        return redirect()
            ->route('manager.page', 'users')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function storeMenu(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', Rule::in(['Makanan', 'Minuman'])],
            'name' => ['required', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0'],
            'image_data' => ['nullable', 'string'],
        ]);

        $category = Category::firstOrCreate([
            'nama_kategori' => $validated['category'],
        ]);

        $photoPath = null;

        if (! empty($validated['image_data']) && preg_match('/^data:image\/(png|jpe?g|webp);base64,/', $validated['image_data'], $matches)) {
            $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
            $imageData = substr($validated['image_data'], strpos($validated['image_data'], ',') + 1);
            $binary = base64_decode($imageData, true);

            if ($binary !== false) {
                $directory = public_path('uploads/menu');
                File::ensureDirectoryExists($directory);

                $filename = Str::slug($validated['name']) . '-' . Str::random(8) . '.' . $extension;
                File::put($directory . DIRECTORY_SEPARATOR . $filename, $binary);
                $photoPath = 'uploads/menu/' . $filename;
            }
        }

        MenuItem::create([
            'id_kategori' => $category->id_kategori,
            'nama_menu' => $validated['name'],
            'deskripsi' => null,
            'harga' => $validated['price'],
            'foto' => $photoPath,
            'stok' => 0,
            'status' => 'tersedia',
        ]);

        return redirect()
            ->route('manager.page', 'menus')
            ->with('success', $validated['category'] . ' berhasil ditambahkan.');
    }

    public function confirmDestroyMenus(Request $request): View
    {
        $validated = $request->validate([
            'menu_ids' => ['required', 'array', 'min:1'],
            'menu_ids.*' => ['integer', 'exists:menus,id_menu'],
        ]);

        $menus = MenuItem::with('categoryModel')
            ->whereIn('id_menu', $validated['menu_ids'])
            ->orderBy('nama_menu')
            ->get();

        return view('manager.confirm-delete-menus', compact('menus'));
    }

    public function destroyMenus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'menu_ids' => ['required', 'array', 'min:1'],
            'menu_ids.*' => ['integer', 'exists:menus,id_menu'],
        ]);

        $menus = MenuItem::whereIn('id_menu', $validated['menu_ids'])->get();

        foreach ($menus as $menu) {
            if ($menu->foto) {
                File::delete(public_path($menu->foto));
            }

            $menu->delete();
        }

        return redirect()
            ->route('manager.page', 'menus')
            ->with('success', count($validated['menu_ids']) . ' menu berhasil dihapus.');
    }
}
