<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\DataChange;
use App\Models\MenuItem;
use App\Models\Ingredient;
use App\Models\IngredientUsage;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use App\Support\ActivityRecorder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ManagerController extends Controller
{
    private array $backupTables = ['users', 'tables', 'categories', 'menus', 'packages', 'package_items', 'ingredients', 'ingredient_usages', 'orders', 'order_details'];

    private array $resetTables = ['order_details', 'orders', 'ingredient_usages', 'ingredients', 'package_items', 'packages', 'menus', 'categories', 'tables'];

    public function dashboard(): View
    {
        $todayOrders = Order::whereDate('created_at', today());

        $stats = [
            'total_menu' => MenuItem::count(),
            'total_tables' => DiningTable::count(),
            'today_orders' => (clone $todayOrders)->count(),
            'active_users' => User::whereIn('level', [1, 2, 3, 4, 5])->count(),
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
            'ingredients' => ['title' => 'Data Bahan', 'description' => 'Kelola bahan baku bakery dan status stok.'],
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
                2 => 'Chef',
                3 => 'Cashier',
                4 => 'Manager',
                5 => 'Owner',
            ];

            $filters = [
                'q' => trim((string) request('q', '')),
                'role' => (string) request('role', 'semua'),
            ];

            $usersQuery = User::query()
                ->whereIn('level', [1, 2, 3, 4, 5])
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
                'total_user' => User::whereIn('level', [1, 2, 3, 4, 5])->count(),
                'waiter' => User::where('level', 1)->count(),
                'chef' => User::where('level', 2)->count(),
                'cashier' => User::where('level', 3)->count(),
                'pengelola' => User::whereIn('level', [4, 5])->count(),
            ];
        }

        if ($section === 'ingredients') {
            $ingredients = Ingredient::query()
                ->withSum(['usages as used_today' => fn ($query) => $query->whereDate('created_at', today())], 'qty')
                ->orderBy('nama_bahan')
                ->get();

            $data['ingredients'] = $ingredients;
            $data['ingredientSummary'] = [
                'total' => $ingredients->count(),
                'aman' => $ingredients->filter(fn (Ingredient $ingredient) => $ingredient->status_label === 'Aman')->count(),
                'menipis' => $ingredients->filter(fn (Ingredient $ingredient) => $ingredient->status_label === 'Menipis')->count(),
                'habis' => $ingredients->filter(fn (Ingredient $ingredient) => $ingredient->status_label === 'Habis')->count(),
                'penggunaan_hari_ini' => IngredientUsage::whereDate('created_at', today())->sum('qty'),
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

            $data['packageItems'] = Package::with(['items.menuItem.categoryModel'])
                ->orderBy('nama_paket')
                ->get();

            $data['availablePackageMenuItems'] = $menuItems
                ->filter(fn (MenuItem $menu) => in_array($menu->category, ['Makanan', 'Minuman'], true))
                ->values();

            $data['menuSummary'] = [
                'total_menu' => MenuItem::count(),
                'makanan' => MenuItem::whereHas('categoryModel', fn ($query) => $query->where('nama_kategori', 'Makanan'))->count(),
                'minuman' => MenuItem::whereHas('categoryModel', fn ($query) => $query->where('nama_kategori', 'Minuman'))->count(),
                'paket' => Package::count(),
                'aktif' => MenuItem::where('status', 'tersedia')->count() + Package::where('status', 'tersedia')->count(),
            ];
        }

        if ($section === 'stock') {
            $stockItems = MenuItem::with('categoryModel')
                ->orderBy('nama_menu')
                ->get();

            $data['foodStockItems'] = $stockItems
                ->filter(fn (MenuItem $menu) => $menu->category === 'Makanan')
                ->values();

            $data['drinkStockItems'] = $stockItems
                ->filter(fn (MenuItem $menu) => $menu->category === 'Minuman')
                ->values();

            $data['stockSummary'] = [
                'total_produk' => $stockItems->count(),
                'stok_aman' => $stockItems->where('stok', '>', 5)->count(),
                'stok_menipis' => $stockItems->filter(fn (MenuItem $menu) => (int) $menu->stok > 0 && (int) $menu->stok <= 5)->count(),
                'stok_habis' => $stockItems->where('stok', '<=', 0)->count(),
            ];
        }

        if ($section === 'tables') {
            $tables = DiningTable::orderBy('nama_meja')->get();

            $data['tables'] = $tables;
            $data['tableSummary'] = [
                'total' => $tables->count(),
                'aktif' => $tables->filter(fn (DiningTable $table) => $this->tableIsActive($table))->count(),
                'nonaktif' => $tables->filter(fn (DiningTable $table) => ! $this->tableIsActive($table))->count(),
                'today_orders' => Order::whereDate('created_at', today())->count(),
            ];
        }

        if ($section === 'activity') {
            $tab = request('tab') === 'data' ? 'data' : 'activity';
            $activityRole = (string) request('role', 'semua');
            $changeFilter = (string) request('change', 'semua');
            $activityRoleOptions = ['semua', 'Customer', 'Waiter', 'Chef', 'Cashier', 'Manager', 'Owner'];
            $dataChangeOptions = ['semua', 'Tambah', 'Edit', 'Hapus', 'Dipulihkan'];

            if (! in_array($activityRole, $activityRoleOptions, true)) {
                $activityRole = 'semua';
            }

            if (! in_array($changeFilter, $dataChangeOptions, true)) {
                $changeFilter = 'semua';
            }

            $data['tab'] = $tab;
            $data['activityRole'] = $activityRole;
            $data['changeFilter'] = $changeFilter;
            $data['activityRoleOptions'] = $activityRoleOptions;
            $data['dataChangeOptions'] = $dataChangeOptions;
            $data['activityLogs'] = ActivityLog::query()
                ->when($activityRole !== 'semua', fn ($query) => $query->where('role', $activityRole))
                ->latest()
                ->paginate(10, ['*'], 'activity_page')
                ->withQueryString();
            $data['dataChanges'] = DataChange::query()
                ->when(in_array($changeFilter, ['Tambah', 'Edit', 'Hapus'], true), fn ($query) => $query->where('action', $changeFilter))
                ->when($changeFilter === 'Dipulihkan', fn ($query) => $query->whereNotNull('restored_at'))
                ->latest()
                ->paginate(10, ['*'], 'data_page')
                ->withQueryString();
        }

        $views = [
            'users' => 'manager.data_user',
            'menus' => 'manager.data_menu',
            'ingredients' => 'manager.data_bahan',
            'tables' => 'manager.data_meja',
            'stock' => 'manager.stok_produk',
            'access' => 'manager.hak_akses',
            'database' => 'manager.database',
            'activity' => 'manager.catatan_aktivitas',
        ];

        return view($views[$section], $data);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:15', 'alpha_dash', 'unique:users,name'],
            'password' => ['required', 'string', 'min:6', 'max:20'],
            'level' => ['required', Rule::in([1, 2, 3])],
        ]);

        $username = strtolower($validated['username']);

        $user = User::create([
            'name' => $username,
            'email' => $username . '@swiftbite.test',
            'password' => $validated['password'],
            'level' => (int) $validated['level'],
        ]);

        ActivityRecorder::dataChange('Tambah', 'User', $user->name, null, $this->userSnapshot($user), $user);

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
                'max:15',
                'alpha_dash',
                Rule::unique('users', 'name')->ignore($user->getKey(), $user->getKeyName()),
            ],
            'password' => ['nullable', 'string', 'min:6', 'max:20'],
            'level' => ['required', Rule::in([1, 2, 3, 4, 5])],
        ]);

        $username = strtolower($validated['username']);
        $before = $this->userSnapshot($user);

        $data = [
            'name' => $username,
            'email' => $username . '@swiftbite.test',
            'level' => (int) $validated['level'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        $user->update($data);

        ActivityRecorder::dataChange('Edit', 'User', $user->name, $before, $this->userSnapshot($user), $user);

        return redirect()
            ->route('manager.page', 'users')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function storeTable(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:7'],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ]);

        $tableName = $this->normalizeTableName($validated['name']);

        if (DiningTable::where('nama_meja', $tableName)->exists()) {
            return back()
                ->withErrors(['name' => 'Nama meja sudah digunakan.'])
                ->withInput();
        }

        $table = DiningTable::create([
            'nama_meja' => $tableName,
            'token' => $this->generateUniqueTableToken(),
            'status' => $validated['status'] === 'nonaktif' ? 'nonaktif' : 'aktif',
        ]);

        ActivityRecorder::dataChange('Tambah', 'Meja', $table->nama_meja, null, $this->tableSnapshot($table), $table);

        return redirect()
            ->route('manager.page', 'tables')
            ->with('success', 'Meja baru berhasil ditambahkan.');
    }

    public function updateTable(Request $request, DiningTable $table): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:7'],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ]);

        $tableName = $this->normalizeTableName($validated['name']);

        if (DiningTable::where('nama_meja', $tableName)->whereKeyNot($table->getKey())->exists()) {
            return back()
                ->withErrors(['name' => 'Nama meja sudah digunakan.'])
                ->withInput();
        }

        $before = $this->tableSnapshot($table);

        $table->update([
            'nama_meja' => $tableName,
            'status' => $validated['status'] === 'nonaktif' ? 'nonaktif' : 'aktif',
        ]);

        ActivityRecorder::dataChange('Edit', 'Meja', $table->nama_meja, $before, $this->tableSnapshot($table), $table);

        return redirect()
            ->route('manager.page', 'tables')
            ->with('success', 'Data meja berhasil diperbarui.');
    }

    public function destroyTable(DiningTable $table): RedirectResponse
    {
        ActivityRecorder::dataChange('Hapus', 'Meja', $table->nama_meja, $this->tableSnapshot($table), null, $table);
        $table->delete();

        return redirect()
            ->route('manager.page', 'tables')
            ->with('success', 'Meja berhasil dihapus.');
    }

    public function storeMenu(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', Rule::in(['Makanan', 'Minuman'])],
            'name' => ['required', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:300'],
            'price' => ['required', 'integer', 'min:0', 'max:50000'],
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

        $menu = MenuItem::create([
            'id_kategori' => $category->id_kategori,
            'nama_menu' => $validated['name'],
            'deskripsi' => $validated['description'] ?? null,
            'harga' => $validated['price'],
            'foto' => $photoPath,
            'stok' => 0,
            'status' => 'tersedia',
        ]);

        ActivityRecorder::dataChange('Tambah', 'Menu', $menu->nama_menu, null, $this->menuSnapshot($menu), $menu);

        return redirect()
            ->route('manager.page', 'menus')
            ->with('success', $validated['category'] . ' berhasil ditambahkan.');
    }

    public function updateMenu(Request $request, MenuItem $menu): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:300'],
            'price' => ['required', 'integer', 'min:0', 'max:50000'],
            'status' => ['required', Rule::in(['tersedia', 'habis'])],
            'image_data' => ['nullable', 'string'],
        ]);

        $photoPath = $menu->foto;
        $before = $this->menuSnapshot($menu);

        if (! empty($validated['image_data']) && preg_match('/^data:image\/(png|jpe?g|webp);base64,/', $validated['image_data'], $matches)) {
            $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
            $imageData = substr($validated['image_data'], strpos($validated['image_data'], ',') + 1);
            $binary = base64_decode($imageData, true);

            if ($binary !== false) {
                $directory = public_path('uploads/menu');
                File::ensureDirectoryExists($directory);

                if ($menu->foto) {
                    File::delete(public_path($menu->foto));
                }

                $filename = Str::slug($validated['name']) . '-' . Str::random(8) . '.' . $extension;
                File::put($directory . DIRECTORY_SEPARATOR . $filename, $binary);
                $photoPath = 'uploads/menu/' . $filename;
            }
        }

        $menu->update([
            'nama_menu' => $validated['name'],
            'deskripsi' => $validated['description'] ?? null,
            'harga' => $validated['price'],
            'status' => $validated['status'],
            'foto' => $photoPath,
        ]);

        ActivityRecorder::dataChange('Edit', 'Menu', $menu->nama_menu, $before, $this->menuSnapshot($menu), $menu);

        return redirect()
            ->route('manager.page', 'menus')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function updateStock(Request $request, MenuItem $menu): RedirectResponse
    {
        $validated = $request->validate([
            'change_type' => ['required', Rule::in(['add', 'subtract'])],
            'amount' => ['required', 'integer', 'min:1', 'max:999'],
            'note' => ['nullable', 'string', 'max:120'],
        ]);

        $currentStock = (int) $menu->stok;
        $amount = (int) $validated['amount'];
        $newStock = $validated['change_type'] === 'add'
            ? $currentStock + $amount
            : $currentStock - $amount;

        if ($newStock < 0) {
            return back()
                ->withErrors(['amount' => 'Jumlah pengurangan tidak boleh melebihi stok saat ini.'])
                ->withInput();
        }

        $before = $this->menuSnapshot($menu);
        $menu->update(['stok' => $newStock]);

        ActivityRecorder::dataChange('Edit', 'Menu', $menu->nama_menu, $before, $this->menuSnapshot($menu), $menu);

        $activity = $validated['change_type'] === 'add'
            ? 'Menambahkan stok ' . $menu->nama_menu . ' (+' . $amount . ')'
            : 'Mengurangi stok ' . $menu->nama_menu . ' (-' . $amount . ')';

        if (! empty($validated['note'])) {
            $activity .= ' - ' . $validated['note'];
        }

        ActivityRecorder::activity('Manager', $activity . '. Stok akhir ' . $menu->stok . ' pcs');

        return redirect()
            ->route('manager.page', 'stock')
            ->with('success', 'Stok produk berhasil diperbarui.');
    }

    public function storeIngredient(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:ingredients,nama_bahan'],
            'stock' => ['required', 'numeric', 'min:0', 'max:99999'],
            'unit' => ['required', 'string', 'max:20'],
            'minimum_stock' => ['required', 'numeric', 'min:0', 'max:99999'],
        ]);

        $ingredient = Ingredient::create([
            'nama_bahan' => Str::title($validated['name']),
            'stok' => $validated['stock'],
            'satuan' => strtolower($validated['unit']),
            'stok_minimum' => $validated['minimum_stock'],
        ]);

        ActivityRecorder::dataChange('Tambah', 'Bahan', $ingredient->nama_bahan, null, $this->ingredientSnapshot($ingredient), $ingredient);

        return redirect()
            ->route('manager.page', 'ingredients')
            ->with('success', 'Data bahan berhasil ditambahkan.');
    }

    public function updateIngredient(Request $request, Ingredient $ingredient): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('ingredients', 'nama_bahan')->ignore($ingredient->getKey(), $ingredient->getKeyName()),
            ],
            'stock' => ['required', 'numeric', 'min:0', 'max:99999'],
            'unit' => ['required', 'string', 'max:20'],
            'minimum_stock' => ['required', 'numeric', 'min:0', 'max:99999'],
        ]);

        $before = $this->ingredientSnapshot($ingredient);

        $ingredient->update([
            'nama_bahan' => Str::title($validated['name']),
            'stok' => $validated['stock'],
            'satuan' => strtolower($validated['unit']),
            'stok_minimum' => $validated['minimum_stock'],
        ]);

        ActivityRecorder::dataChange('Edit', 'Bahan', $ingredient->nama_bahan, $before, $this->ingredientSnapshot($ingredient), $ingredient);

        return redirect()
            ->route('manager.page', 'ingredients')
            ->with('success', 'Data bahan berhasil diperbarui.');
    }

    public function destroyIngredient(Ingredient $ingredient): RedirectResponse
    {
        ActivityRecorder::dataChange('Hapus', 'Bahan', $ingredient->nama_bahan, $this->ingredientSnapshot($ingredient), null, $ingredient);
        $ingredient->delete();

        return redirect()
            ->route('manager.page', 'ingredients')
            ->with('success', 'Data bahan berhasil dihapus.');
    }

    public function storePackage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'description' => ['nullable', 'string', 'max:300'],
            'price' => ['required', 'integer', 'min:0', 'max:500000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'items' => ['required', 'array'],
            'items.*' => ['nullable', 'integer', 'min:0', 'max:99'],
        ]);

        $selectedItems = collect($validated['items'])
            ->map(fn ($quantity) => (int) $quantity)
            ->filter(fn ($quantity) => $quantity > 0);

        if ($selectedItems->isEmpty()) {
            return back()
                ->withErrors(['items' => 'Pilih minimal 1 makanan atau minuman untuk isi paket.'])
                ->withInput();
        }

        $validMenuCount = MenuItem::whereIn('id_menu', $selectedItems->keys())->count();

        if ($validMenuCount !== $selectedItems->count()) {
            return back()
                ->withErrors(['items' => 'Beberapa menu paket tidak valid.'])
                ->withInput();
        }

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $directory = public_path('uploads/packages');
            File::ensureDirectoryExists($directory);

            $filename = Str::slug($validated['name']) . '-' . Str::random(8) . '.' . $photo->extension();
            $photo->move($directory, $filename);
            $photoPath = 'uploads/packages/' . $filename;
        }

        $package = DB::transaction(function () use ($validated, $selectedItems, $photoPath) {
            $package = Package::create([
                'nama_paket' => $validated['name'],
                'deskripsi' => $validated['description'] ?? null,
                'foto' => $photoPath,
                'harga' => $validated['price'],
                'status' => 'tersedia',
            ]);

            foreach ($selectedItems as $menuId => $quantity) {
                $package->items()->create([
                    'id_menu' => (int) $menuId,
                    'qty' => $quantity,
                ]);
            }

            return $package->load('items.menuItem');
        });

        ActivityRecorder::dataChange('Tambah', 'Paket', $package->nama_paket, null, $this->packageSnapshot($package), $package);

        return redirect()
            ->route('manager.page', 'menus')
            ->with('success', 'Paket promo berhasil ditambahkan.');
    }

    public function updatePackage(Request $request, Package $package): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'description' => ['nullable', 'string', 'max:300'],
            'price' => ['required', 'integer', 'min:0', 'max:500000'],
            'status' => ['required', Rule::in(['tersedia', 'habis'])],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'items' => ['required', 'array'],
            'items.*' => ['nullable', 'integer', 'min:0', 'max:99'],
        ]);

        $selectedItems = collect($validated['items'])
            ->map(fn ($quantity) => (int) $quantity)
            ->filter(fn ($quantity) => $quantity > 0);

        if ($selectedItems->isEmpty()) {
            return back()
                ->withErrors(['items' => 'Pilih minimal 1 makanan atau minuman untuk isi paket.'])
                ->withInput();
        }

        $validMenuCount = MenuItem::whereIn('id_menu', $selectedItems->keys())->count();

        if ($validMenuCount !== $selectedItems->count()) {
            return back()
                ->withErrors(['items' => 'Beberapa menu paket tidak valid.'])
                ->withInput();
        }

        $package->load('items.menuItem');
        $before = $this->packageSnapshot($package);
        $photoPath = $package->foto;

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $directory = public_path('uploads/packages');
            File::ensureDirectoryExists($directory);

            if ($package->foto) {
                File::delete(public_path($package->foto));
            }

            $filename = Str::slug($validated['name']) . '-' . Str::random(8) . '.' . $photo->extension();
            $photo->move($directory, $filename);
            $photoPath = 'uploads/packages/' . $filename;
        }

        DB::transaction(function () use ($package, $validated, $selectedItems, $photoPath) {
            $package->update([
                'nama_paket' => $validated['name'],
                'deskripsi' => $validated['description'] ?? null,
                'foto' => $photoPath,
                'harga' => $validated['price'],
                'status' => $validated['status'],
            ]);

            $package->items()->delete();

            foreach ($selectedItems as $menuId => $quantity) {
                $package->items()->create([
                    'id_menu' => (int) $menuId,
                    'qty' => $quantity,
                ]);
            }
        });

        $package->load('items.menuItem');
        ActivityRecorder::dataChange('Edit', 'Paket', $package->nama_paket, $before, $this->packageSnapshot($package), $package);

        return redirect()
            ->route('manager.page', 'menus')
            ->with('success', 'Paket promo berhasil diperbarui.');
    }

    public function destroyPackage(Package $package): RedirectResponse
    {
        $package->load('items.menuItem');

        ActivityRecorder::dataChange('Hapus', 'Paket', $package->nama_paket, $this->packageSnapshot($package), null, $package);

        if ($package->foto) {
            File::delete(public_path($package->foto));
        }

        $package->delete();

        return redirect()
            ->route('manager.page', 'menus')
            ->with('success', 'Paket promo berhasil dihapus.');
    }

    public function destroyMenus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'menu_ids' => ['required', 'array', 'min:1'],
            'menu_ids.*' => ['integer', 'exists:menus,id_menu'],
        ]);

        $menus = MenuItem::whereIn('id_menu', $validated['menu_ids'])->get();

        foreach ($menus as $menu) {
            ActivityRecorder::dataChange('Hapus', 'Menu', $menu->nama_menu, $this->menuSnapshot($menu), null, $menu);

            if ($menu->foto) {
                File::delete(public_path($menu->foto));
            }

            $menu->delete();
        }

        return redirect()
            ->route('manager.page', 'menus')
            ->with('success', count($validated['menu_ids']) . ' menu berhasil dihapus.');
    }

    public function restoreDataChange(DataChange $change): RedirectResponse
    {
        abort_unless($change->restored_at === null, 403);

        if ($change->data_type === 'Menu') {
            $this->restoreMenuChange($change);
        } elseif ($change->data_type === 'User') {
            $this->restoreUserChange($change);
        } elseif ($change->data_type === 'Meja') {
            $this->restoreTableChange($change);
        } elseif ($change->data_type === 'Paket') {
            $this->restorePackageChange($change);
        } elseif ($change->data_type === 'Bahan') {
            $this->restoreIngredientChange($change);
        } else {
            return back()->withErrors(['restore' => 'Jenis data belum mendukung pemulihan.']);
        }

        $change->update(['restored_at' => now()]);

        ActivityRecorder::activity('Manager', 'Memulihkan perubahan data ' . $change->data_type . ': ' . $change->data_name);

        return back()->with('success', 'Data berhasil dikembalikan.');
    }

    public function backupDatabase(): Response
    {
        $database = DB::getDatabaseName();
        $date = now()->format('Y-m-d_H-i-s');
        $sql = "-- SwiftBite database backup\n";
        $sql .= "-- Database: {$database}\n";
        $sql .= "-- Generated at: " . now()->format('Y-m-d H:i:s') . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($this->backupTables as $table) {
            if (! $this->tableExists($table)) {
                continue;
            }

            $create = DB::select("SHOW CREATE TABLE `{$table}`")[0]->{'Create Table'};
            $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $sql .= $create . ";\n\n";

            $rows = DB::table($table)->get();

            foreach ($rows as $row) {
                $data = (array) $row;
                $columns = array_map(fn ($column) => "`{$column}`", array_keys($data));
                $values = array_map(fn ($value) => $this->sqlValue($value), array_values($data));
                $sql .= "INSERT INTO `{$table}` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
            }

            $sql .= "\n";
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        return response($sql, 200, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="swiftbite-backup-' . $date . '.sql"',
        ]);
    }

    public function importDatabase(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'database_file' => ['required', 'file', 'mimes:sql,txt', 'max:5120'],
        ]);

        $sql = File::get($validated['database_file']->getRealPath());

        DB::unprepared('SET FOREIGN_KEY_CHECKS=0;');
        DB::unprepared($sql);
        DB::unprepared('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()
            ->route('manager.page', 'database')
            ->with('success', 'Database berhasil diimport.');
    }

    public function resetDatabase(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'confirmation' => ['required', 'string'],
        ]);

        if ($validated['confirmation'] !== 'RESET DATABASE') {
            return redirect()
                ->route('manager.page', 'database')
                ->withErrors(['confirmation' => 'Ketik RESET DATABASE untuk melakukan reset.']);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($this->resetTables as $table) {
            if ($this->tableExists($table)) {
                DB::table($table)->truncate();
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()
            ->route('manager.page', 'database')
            ->with('success', 'Database operasional berhasil direset.');
    }

    private function sqlValue(mixed $value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return DB::getPdo()->quote((string) $value);
    }

    private function tableExists(string $table): bool
    {
        return DB::selectOne(
            'SELECT COUNT(*) as aggregate FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?',
            [$table],
        )->aggregate > 0;
    }

    private function menuSnapshot(MenuItem $menu): array
    {
        return [
            'id_menu' => $menu->id_menu,
            'id_kategori' => $menu->id_kategori,
            'nama_menu' => $menu->nama_menu,
            'deskripsi' => $menu->deskripsi,
            'harga' => $menu->harga,
            'foto' => $menu->foto,
            'stok' => $menu->stok,
            'status' => $menu->status,
        ];
    }

    private function packageSnapshot(Package $package): array
    {
        return [
            'id_paket' => $package->id_paket,
            'nama_paket' => $package->nama_paket,
            'deskripsi' => $package->deskripsi,
            'foto' => $package->foto,
            'harga' => $package->harga,
            'status' => $package->status,
            'items' => $package->items
                ->map(fn ($item) => [
                    'id_menu' => $item->id_menu,
                    'nama_menu' => $item->menuItem?->nama_menu,
                    'qty' => $item->qty,
                ])
                ->values()
                ->all(),
        ];
    }

    private function tableSnapshot(DiningTable $table): array
    {
        return [
            'id_meja' => $table->id_meja,
            'nama_meja' => $table->nama_meja,
            'token' => $table->token,
            'status' => $table->status,
        ];
    }

    private function ingredientSnapshot(Ingredient $ingredient): array
    {
        return [
            'id_bahan' => $ingredient->id_bahan,
            'nama_bahan' => $ingredient->nama_bahan,
            'stok' => $ingredient->stok,
            'satuan' => $ingredient->satuan,
            'stok_minimum' => $ingredient->stok_minimum,
        ];
    }

    private function tableIsActive(DiningTable $table): bool
    {
        return in_array($table->status, ['aktif', 'kosong', 'terisi'], true);
    }

    private function normalizeTableName(string $name): string
    {
        $name = trim(preg_replace('/\s+/', ' ', $name));

        if (preg_match('/^meja\s*0*(\d+)$/i', $name, $matches)) {
            return 'Meja ' . (int) $matches[1];
        }

        return Str::title($name);
    }

    private function generateUniqueTableToken(): string
    {
        do {
            $token = Str::random(32);
        } while (DiningTable::where('token', $token)->exists());

        return $token;
    }

    private function userSnapshot(User $user): array
    {
        return [
            $user->getKeyName() => $user->getKey(),
            'name' => $user->name,
            'email' => $user->email,
            'level' => $user->level,
            'password' => $user->password,
        ];
    }

    private function restoreMenuChange(DataChange $change): void
    {
        if ($change->action === 'Tambah' && $change->target_id) {
            MenuItem::whereKey($change->target_id)->delete();

            return;
        }

        $snapshot = $change->before_data;

        if (! is_array($snapshot)) {
            return;
        }

        MenuItem::updateOrCreate(
            ['id_menu' => $snapshot['id_menu']],
            [
                'id_kategori' => $snapshot['id_kategori'],
                'nama_menu' => $snapshot['nama_menu'],
                'deskripsi' => $snapshot['deskripsi'] ?? null,
                'harga' => $snapshot['harga'],
                'foto' => $snapshot['foto'] ?? null,
                'stok' => $snapshot['stok'] ?? 0,
                'status' => $snapshot['status'] ?? 'tersedia',
            ],
        );
    }

    private function restoreUserChange(DataChange $change): void
    {
        if ($change->action === 'Tambah' && $change->target_id) {
            User::whereKey($change->target_id)->delete();

            return;
        }

        $snapshot = $change->before_data;

        if (! is_array($snapshot)) {
            return;
        }

        User::updateOrCreate(
            ['id_user' => $snapshot['id_user']],
            [
                'name' => $snapshot['name'],
                'email' => $snapshot['email'],
                'level' => $snapshot['level'],
                'password' => $snapshot['password'],
            ],
        );
    }

    private function restoreTableChange(DataChange $change): void
    {
        if ($change->action === 'Tambah' && $change->target_id) {
            DiningTable::whereKey($change->target_id)->delete();

            return;
        }

        $snapshot = $change->before_data;

        if (! is_array($snapshot)) {
            return;
        }

        DiningTable::updateOrCreate(
            ['id_meja' => $snapshot['id_meja']],
            [
                'nama_meja' => $snapshot['nama_meja'],
                'token' => $snapshot['token'],
                'status' => $snapshot['status'] ?? 'aktif',
            ],
        );
    }

    private function restorePackageChange(DataChange $change): void
    {
        if ($change->action === 'Tambah' && $change->target_id) {
            Package::whereKey($change->target_id)->delete();

            return;
        }

        $snapshot = $change->before_data;

        if (! is_array($snapshot)) {
            return;
        }

        DB::transaction(function () use ($snapshot) {
            $package = Package::updateOrCreate(
                ['id_paket' => $snapshot['id_paket']],
                [
                    'nama_paket' => $snapshot['nama_paket'],
                    'deskripsi' => $snapshot['deskripsi'] ?? null,
                    'foto' => $snapshot['foto'] ?? null,
                    'harga' => $snapshot['harga'],
                    'status' => $snapshot['status'] ?? 'tersedia',
                ],
            );

            $package->items()->delete();

            foreach (($snapshot['items'] ?? []) as $item) {
                if (! isset($item['id_menu'], $item['qty'])) {
                    continue;
                }

                $package->items()->create([
                    'id_menu' => $item['id_menu'],
                    'qty' => $item['qty'],
                ]);
            }
        });
    }

    private function restoreIngredientChange(DataChange $change): void
    {
        if ($change->action === 'Tambah' && $change->target_id) {
            Ingredient::whereKey($change->target_id)->delete();

            return;
        }

        $snapshot = $change->before_data;

        if (! is_array($snapshot)) {
            return;
        }

        Ingredient::updateOrCreate(
            ['id_bahan' => $snapshot['id_bahan']],
            [
                'nama_bahan' => $snapshot['nama_bahan'],
                'stok' => $snapshot['stok'],
                'satuan' => $snapshot['satuan'],
                'stok_minimum' => $snapshot['stok_minimum'],
            ],
        );
    }
}
