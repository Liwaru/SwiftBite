<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\CustomerMenuController;
use App\Http\Controllers\CustomerPageController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WaiterController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/midtrans/notification', [CustomerMenuController::class, 'midtransNotification'])->name('midtrans.notification');

Route::middleware('simple.auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile/name', [ProfileController::class, 'updateName'])->name('profile.name.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

Route::middleware(['simple.auth', 'user.level:1,4'])->group(function () {
    Route::get('/waiter', [WaiterController::class, 'dashboard'])->name('waiter.dashboard')->middleware('feature.access:waiter.orders');
    Route::patch('/waiter/orders/{order}/complete', [WaiterController::class, 'complete'])->name('waiter.orders.complete')->middleware('feature.access:waiter.orders');
});

Route::middleware(['simple.auth', 'user.level:2,4'])->group(function () {
    Route::get('/baker/live-orders', [ChefController::class, 'liveOrders'])
        ->name('baker.orders.live')
        ->middleware('feature.access:chef.orders');

    Route::patch('/baker/orders/{order}/finish-cooking', [ChefController::class, 'finishCooking'])
        ->name('baker.orders.finish-cooking')
        ->middleware('feature.access:chef.orders');

    Route::get('/baker', [ChefController::class, 'dashboard'])->name('baker.dashboard')->middleware('feature.access:chef.orders');
    Route::get('/baker/orders', [ChefController::class, 'orders'])->name('baker.orders')->middleware('feature.access:chef.orders');
    Route::patch('/baker/orders/{order}/ready', [ChefController::class, 'markReady'])->name('baker.orders.ready')->middleware('feature.access:chef.orders');
    Route::get('/baker/ingredients', [ChefController::class, 'ingredients'])->name('baker.ingredients')->middleware('feature.access:chef.ingredients');
    Route::post('/baker/ingredients/{ingredient}/use', [ChefController::class, 'useIngredient'])->name('baker.ingredients.use')->middleware('feature.access:chef.ingredients');

    Route::redirect('/chef', '/baker')->name('chef.dashboard');
    Route::redirect('/chef/orders', '/baker/orders')->name('chef.orders');
    Route::patch('/chef/orders/{order}/ready', [ChefController::class, 'markReady'])->name('chef.orders.ready')->middleware('feature.access:chef.orders');
    Route::redirect('/chef/ingredients', '/baker/ingredients')->name('chef.ingredients');
    Route::post('/chef/ingredients/{ingredient}/use', [ChefController::class, 'useIngredient'])->name('chef.ingredients.use')->middleware('feature.access:chef.ingredients');
});

Route::middleware(['simple.auth', 'user.level:4'])->group(function () {
    Route::get('/manager', [ManagerController::class, 'dashboard'])->name('manager.dashboard');
    Route::post('/manager/access', [ManagerController::class, 'updateAccess'])->name('manager.access.update');
    Route::post('/manager/users', [ManagerController::class, 'storeUser'])->name('manager.users.store');
    Route::patch('/manager/users/{user}', [ManagerController::class, 'updateUser'])->name('manager.users.update');
    Route::post('/manager/tables', [ManagerController::class, 'storeTable'])->name('manager.tables.store');
    Route::patch('/manager/tables/{table}', [ManagerController::class, 'updateTable'])->name('manager.tables.update');
    Route::delete('/manager/tables/{table}', [ManagerController::class, 'destroyTable'])->name('manager.tables.destroy');
    Route::post('/manager/menus', [ManagerController::class, 'storeMenu'])->name('manager.menus.store');
    Route::patch('/manager/menus/{menu}', [ManagerController::class, 'updateMenu'])->name('manager.menus.update');
    Route::delete('/manager/menus', [ManagerController::class, 'destroyMenus'])->name('manager.menus.destroy');
    Route::post('/manager/packages', [ManagerController::class, 'storePackage'])->name('manager.packages.store');
    Route::patch('/manager/packages/{package}', [ManagerController::class, 'updatePackage'])->name('manager.packages.update');
    Route::delete('/manager/packages/{package}', [ManagerController::class, 'destroyPackage'])->name('manager.packages.destroy');
    Route::post('/manager/ingredients', [ManagerController::class, 'storeIngredient'])->name('manager.ingredients.store');
    Route::patch('/manager/ingredients/{ingredient}', [ManagerController::class, 'updateIngredient'])->name('manager.ingredients.update');
    Route::delete('/manager/ingredients/{ingredient}', [ManagerController::class, 'destroyIngredient'])->name('manager.ingredients.destroy');
    Route::post('/manager/stock/scan', [ManagerController::class, 'scanStockBarcode'])->name('manager.stock.scan');
    Route::patch('/manager/stock/{menu}', [ManagerController::class, 'updateStock'])->name('manager.stock.update');
    Route::post('/manager/database/backup', [ManagerController::class, 'backupDatabase'])->name('manager.database.backup');
    Route::post('/manager/database/import', [ManagerController::class, 'importDatabase'])->name('manager.database.import');
    Route::delete('/manager/database/reset', [ManagerController::class, 'resetDatabase'])->name('manager.database.reset');
    Route::post('/manager/activity/data/{change}/restore', [ManagerController::class, 'restoreDataChange'])
        ->name('manager.activity.restore');

    Route::get('/manager/barang-masuk', [ManagerController::class, 'ingredientIn'])
        ->name('manager.ingredient-in');

    Route::post('/manager/barang-masuk', [ManagerController::class, 'storeIngredientIn'])
        ->name('manager.ingredient-in.store');

    Route::get('/manager/barang-keluar', [ManagerController::class, 'ingredientOut'])
        ->name('manager.ingredient-out');

    Route::post('/manager/barang-keluar', [ManagerController::class, 'storeIngredientOut'])
        ->name('manager.ingredient-out.store');

    Route::get('/manager/absensi', [AbsensiController::class, 'index'])
        ->name('absensi.index')
        ->middleware('feature.access:manager.absensi');

    Route::get('/manager/absensi/create', [AbsensiController::class, 'create'])
        ->name('absensi.create')
        ->middleware('feature.access:manager.absensi');

    Route::post('/manager/absensi/store', [AbsensiController::class, 'store'])
        ->name('absensi.store')
        ->middleware('feature.access:manager.absensi');

    Route::get('/manager/absensi/edit/{id}', [AbsensiController::class, 'edit'])
        ->name('absensi.edit')
        ->middleware('feature.access:manager.absensi');

    Route::put('/manager/absensi/update/{id}', [AbsensiController::class, 'update'])
        ->name('absensi.update')
        ->middleware('feature.access:manager.absensi');

    Route::delete('/manager/absensi/delete/{id}', [AbsensiController::class, 'destroy'])
        ->name('absensi.destroy')
        ->middleware('feature.access:manager.absensi');

    Route::get('/manager/absensi/{id}', [ManagerController::class, 'showAbsensi'])
        ->whereNumber('id')
        ->middleware('feature.access:manager.absensi');

    // Manager-accessible report routes (appear when manager has report permissions)
    Route::get('/manager/laporan/penjualan', [OwnerController::class, 'sales'])->name('manager.reports.sales')->middleware('feature.access:owner.sales');
    Route::get('/manager/laporan/keuangan', [OwnerController::class, 'finance'])->name('manager.reports.finance')->middleware('feature.access:owner.finance');
    Route::get('/manager/laporan/produk', [OwnerController::class, 'products'])->name('manager.reports.products')->middleware('feature.access:owner.products');
    Route::get('/manager/laporan/bahan', [OwnerController::class, 'ingredients'])->name('manager.reports.ingredients')->middleware('feature.access:owner.ingredients');

    Route::get('/manager/{section}', [ManagerController::class, 'page'])
        ->name('manager.page');
});

Route::middleware(['simple.auth', 'user.level:5'])->group(function () {
    Route::get('/owner', [OwnerController::class, 'dashboard'])->name('owner.dashboard');
    Route::get('/owner/sales', [OwnerController::class, 'sales'])->name('owner.sales');
    Route::get('/owner/finance', [OwnerController::class, 'finance'])->name('owner.finance');
    Route::get('/owner/products', [OwnerController::class, 'products'])->name('owner.products');
    Route::get('/owner/ingredients', [OwnerController::class, 'ingredients'])->name('owner.ingredients');
});

Route::middleware(['simple.auth', 'user.level:4,5'])->group(function () {
    Route::get('/admin', fn () => session('auth_level') == 5
        ? redirect()->route('owner.dashboard')
        : redirect()->route('manager.dashboard'))->name('admin.dashboard');
    Route::post('/admin/tables', fn () => abort(404))->name('admin.tables.store');
    Route::post('/admin/menu-items', fn () => abort(404))->name('admin.menu-items.store');
    Route::patch('/admin/orders/{order}/status', fn () => abort(404))->name('admin.orders.status');
});

Route::middleware(['simple.auth', 'user.level:3,4'])->group(function () {
    Route::get('/kasir', [CashierController::class, 'dashboard'])->name('cashier.dashboard');
    Route::get('/kasir/pesanan', [CashierController::class, 'orders'])->name('cashier.orders')->middleware('feature.access:cashier.orders');
    Route::post('/kasir/pesanan/scan', [CashierController::class, 'scanOrder'])->name('cashier.orders.scan')->middleware('feature.access:cashier.orders');
    Route::post('/kasir/pesanan/menu-barcode', [CashierController::class, 'scanMenuBarcode'])->name('cashier.orders.menu-barcode')->middleware('feature.access:cashier.orders');
    Route::post('/kasir/pesanan/langsung', [CashierController::class, 'storeDirectOrder'])->name('cashier.orders.direct-store')->middleware('feature.access:cashier.orders');
    Route::get('/kasir/live-orders', [CashierController::class, 'liveOrders'])->name('cashier.orders.live')->middleware('feature.access:cashier.orders');
    Route::get('/kasir/riwayat', [CashierController::class, 'history'])->name('cashier.history')->middleware('feature.access:cashier.history');
    Route::patch('/kasir/orders/{order}/status', [CashierController::class, 'updateOrderStatus'])
        ->name('cashier.orders.status')
        ->middleware('feature.access:cashier.order_status');
});

Route::middleware(['simple.auth', 'user.level:0'])->group(function () {
    Route::get('/pelanggan', [CustomerPageController::class, 'home'])->name('customer.home');
});

Route::get('/menu/{token}', [CustomerMenuController::class, 'show'])->name('customer.menu');
Route::post('/menu/{token}/orders', [CustomerMenuController::class, 'store'])->name('customer.orders.store');
Route::get('/menu/{token}/orders/{order}/payment', [CustomerMenuController::class, 'payment'])->name('customer.orders.payment');
Route::patch('/menu/{token}/orders/{order}/payment', [CustomerMenuController::class, 'confirmPayment'])->name('customer.orders.payment.confirm');
Route::post('/menu/{token}/orders/{order}/ewallet', [CustomerMenuController::class, 'createEwalletPayment'])->name('customer.orders.ewallet.create');
Route::post('/menu/{token}/orders/{order}/midtrans-sync', [CustomerMenuController::class, 'syncMidtransPayment'])->name('customer.orders.midtrans.sync');
Route::get('/menu/{token}/orders/{order}/qris', [CustomerMenuController::class, 'qris'])->name('customer.orders.qris');
Route::get('/menu/{token}/orders/{order}/qris/status', [CustomerMenuController::class, 'qrisStatus'])->name('customer.orders.qris.status');
Route::get('/menu/{token}/orders/{order}', [CustomerMenuController::class, 'receipt'])->name('customer.orders.show');

Route::get('/language/{locale}', function ($locale) {
    if (! in_array($locale, ['id', 'en'])) {
        abort(404);
    }

    session(['locale' => $locale]);

    return back();
})->name('language.switch');

Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])
    ->name('attendance.checkIn')
    ->middleware('simple.auth');

Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])
    ->name('attendance.checkOut')
    ->middleware('simple.auth');
